<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Room;
use App\Models\TenantProfile;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle Midtrans payment callback (webhook).
     */
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Callback Received', $payload);

        // Validasi signature key
        if (!$this->midtransService->verifySignature($payload)) {
            Log::warning('Midtrans Callback: Invalid Signature', [
                'ip' => $request->ip(),
                'order_id' => $payload['order_id'] ?? 'unknown',
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature',
            ], 403);
        }

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        if (!$orderId || !$transactionStatus) {
            Log::error('Midtrans Callback: Missing order_id or transaction_status', $payload);

            return response()->json([
                'status' => 'error',
                'message' => 'Missing order_id or transaction_status',
            ], 400);
        }

        // Cari booking berdasarkan midtrans_order_id
        $booking = Booking::where('midtrans_order_id', $orderId)->first();

        // Cari payment (tagihan) jika bukan booking
        $payment = null;
        if (!$booking) {
            $payment = \App\Models\Payment::where('midtrans_order_id', $orderId)->first();
        }

        if (!$booking && !$payment) {
            Log::error('Midtrans Callback: Booking/Payment not found', [
                'order_id' => $orderId,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Booking or Payment not found',
            ], 404);
        }

        // Update status booking atau payment berdasarkan transaction_status
        if ($booking) {
            $this->updateBookingStatus($booking, $transactionStatus, $payload);
            Log::info('Midtrans Callback: Booking updated', [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
            ]);
        }

        if ($payment) {
            $this->updatePaymentStatus($payment, $transactionStatus, $payload);
            Log::info('Midtrans Callback: Payment updated', [
                'payment_id' => $payment->id,
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Callback processed successfully',
        ]);
    }

    /**
     * Update booking status berdasarkan transaction_status dari Midtrans.
     */
    private function updateBookingStatus(Booking $booking, string $transactionStatus, array $payload): void
    {
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentMethod = $payload['payment_type'] ?? ($payload['payment_channel'] ?? null);
        $transactionId = $payload['transaction_id'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;

        $statusMap = [
            'capture' => function () use ($booking, $fraudStatus, $payload, $paymentMethod, $transactionId, $grossAmount) {
                if ($fraudStatus === 'accept') {
                    $this->markAsPaid($booking, $payload, $paymentMethod, $transactionId, $grossAmount);
                } elseif ($fraudStatus === 'challenge') {
                    $this->markAsPending($booking, $payload);
                }
            },
            'settlement' => function () use ($booking, $payload, $paymentMethod, $transactionId, $grossAmount) {
                $this->markAsPaid($booking, $payload, $paymentMethod, $transactionId, $grossAmount);
            },
            'pending' => function () use ($booking, $payload) {
                $this->markAsPending($booking, $payload);
            },
            'deny' => function () use ($booking, $payload) {
                $this->markAsFailed($booking, $payload, 'Ditolak');
            },
            'expire' => function () use ($booking, $payload) {
                $this->markAsFailed($booking, $payload, 'Kadaluarsa');
            },
            'cancel' => function () use ($booking, $payload) {
                $this->markAsFailed($booking, $payload, 'Dibatalkan');
            },
            'refund' => function () use ($booking, $payload) {
                $this->markAsRefund($booking, $payload);
            },
        ];

        if (isset($statusMap[$transactionStatus])) {
            $statusMap[$transactionStatus]();

            Log::info('Booking status updated via callback', [
                'booking_id' => $booking->id,
                'status' => $booking->fresh()->status,
                'midtrans_status' => $transactionStatus,
            ]);
        } else {
            Log::warning('Unknown transaction status received', [
                'booking_id' => $booking->id,
                'status' => $transactionStatus,
            ]);
        }
    }

    /**
     * Tandai booking sebagai Dibayar.
     * Jika user punya akun, auto-create TenantProfile dengan status "Menunggu Persetujuan".
     */
    private function markAsPaid(Booking $booking, array $payload, ?string $paymentMethod, ?string $transactionId, $grossAmount): void
    {
        $previousStatus = $booking->status;

        $booking->update([
            'status' => 'Dibayar',
            'midtrans_transaction_status' => $payload['transaction_status'],
            'midtrans_response' => json_encode($payload),
            'payment_method' => $paymentMethod ?? $booking->payment_method,
            'midtrans_transaction_id' => $transactionId,
            'gross_amount' => $grossAmount ?? $booking->room_price,
            'paid_at' => now(),
        ]);

        // Auto-create TenantProfile untuk user yang login (jika belum ada)
        if ($booking->user_id && $booking->room_id) {
            $existingTenant = TenantProfile::where('user_id', $booking->user_id)->first();
            if (!$existingTenant) {
                $room = Room::find($booking->room_id);
                if ($room) {
                    TenantProfile::create([
                        'user_id' => $booking->user_id,
                        'room_id' => $booking->room_id,
                        'booking_id' => $booking->id,
                        'phone' => $booking->customer_phone ?? '-',
                        'lease_start' => now(),
                        'status' => 'Menunggu Persetujuan',
                    ]);

                    Log::info('TenantProfile auto-created after payment', [
                        'booking_id' => $booking->id,
                        'user_id' => $booking->user_id,
                        'room_id' => $booking->room_id,
                        'tenant_status' => 'Menunggu Persetujuan',
                    ]);
                }
            }
        }

        // Kurangi slot kamar (hanya jika sebelumnya bukan Dibayar)
        if ($booking->room_id && $previousStatus !== 'Dibayar') {
            $room = Room::find($booking->room_id);
            if ($room) {
                $room->decreaseSlot();

                Log::info('Room slot decreased after payment', [
                    'room_id' => $room->id,
                    'room_number' => $room->number,
                    'remaining_slots' => $room->slots,
                ]);
            }
        }

        // Buat notifikasi untuk admin
        $this->createPaymentNotification($booking);

        Log::info('Booking marked as paid', [
            'booking_id' => $booking->id,
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
        ]);
    }

    /**
     * Tandai booking sebagai Pending.
     */
    private function markAsPending(Booking $booking, array $payload): void
    {
        $booking->update([
            'status' => 'Pending',
            'midtrans_transaction_status' => $payload['transaction_status'],
            'midtrans_response' => json_encode($payload),
        ]);
    }

    /**
     * Tandai booking sebagai Gagal/Dibatalkan/Kadaluarsa.
     */
    private function markAsFailed(Booking $booking, array $payload, string $status): void
    {
        $previousStatus = $booking->status;

        $booking->update([
            'status' => $status,
            'midtrans_transaction_status' => $payload['transaction_status'],
            'midtrans_response' => json_encode($payload),
        ]);

        // Jika sebelumnya sudah Dibayar, kembalikan slot
        if ($previousStatus === 'Dibayar' && $booking->room_id) {
            $room = Room::find($booking->room_id);
            if ($room) {
                $room->increaseSlot();

                Log::info('Room slot returned after failed/refund booking', [
                    'room_id' => $room->id,
                    'room_number' => $room->number,
                    'restored_slots' => $room->slots,
                ]);
            }
        }
    }

    /**
     * Tandai booking sebagai Refund.
     */
    private function markAsRefund(Booking $booking, array $payload): void
    {
        $this->markAsFailed($booking, $payload, 'Refund');
    }

    /**
     * Update Payment (tagihan bulanan) status berdasarkan callback Midtrans.
     */
    private function updatePaymentStatus(\App\Models\Payment $payment, string $transactionStatus, array $payload): void
    {
        $paymentMethod = $payload['payment_type'] ?? ($payload['payment_channel'] ?? null);
        $transactionId = $payload['transaction_id'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;

        $statusMap = [
            'settlement' => 'paid',
            'capture' => 'paid',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'cancelled',
            'refund' => 'refund',
        ];

        $paymentStatus = $statusMap[$transactionStatus] ?? $transactionStatus;

        $updateData = [
            'status' => $paymentStatus,
            'midtrans_transaction_status' => $transactionStatus,
            'midtrans_response' => json_encode($payload),
            'midtrans_transaction_id' => $transactionId,
        ];

        // Jika pembayaran berhasil, catat paid_date dan payment_method
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $updateData['paid_date'] = now();
            $updateData['payment_method'] = $paymentMethod ?? $payment->payment_method ?? 'midtrans';

            // Buat notifikasi untuk user
            \App\Models\Notification::create([
                'user_id' => $payment->user_id,
                'title' => 'Pembayaran Tagihan Berhasil',
                'message' => "Pembayaran tagihan {$payment->period_label} sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " telah berhasil.",
                'type' => 'success',
            ]);

            Log::info('Payment bill marked as paid via callback', [
                'payment_id' => $payment->id,
                'order_id' => $payload['order_id'] ?? null,
                'transaction_status' => $transactionStatus,
                'paid_date' => now(),
            ]);
        }

        $payment->update($updateData);

        Log::info('Payment status updated via callback', [
            'payment_id' => $payment->id,
            'old_status' => $payment->getOriginal('status') ?? '?',
            'new_status' => $paymentStatus,
            'midtrans_status' => $transactionStatus,
        ]);
    }

    /**
     * Buat notifikasi untuk admin ketika pembayaran berhasil.
     */
    private function createPaymentNotification(Booking $booking): void
    {
        try {
            $adminUsers = \App\Models\User::where('role', 'admin')->get();
            $formattedAmount = 'Rp ' . number_format($booking->room_price, 0, ',', '.');

            foreach ($adminUsers as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Pembayaran Baru',
                    'message' => "{$booking->customer_name} telah melakukan pembayaran sebesar {$formattedAmount} untuk {$booking->room_name}.",
                    'type' => 'payment_success',
                    'is_read' => false,
                ]);
            }

            Log::info('Payment notification created for admin', [
                'booking_id' => $booking->id,
                'customer_name' => $booking->customer_name,
                'amount' => $booking->room_price,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create payment notification: ' . $e->getMessage());
        }
    }
}