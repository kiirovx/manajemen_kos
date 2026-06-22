<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    /**
     * Generate Snap Token untuk pembayaran Booking.
     */
    public function createSnapTransaction(Booking $booking): ?string
    {
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            $orderId = $this->generateOrderId($booking);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $booking->room_price,
                ],
                'customer_details' => [
                    'first_name' => $booking->customer_name,
                    'email' => $booking->customer_email,
                    'phone' => $booking->customer_phone,
                ],
                'item_details' => [
                    [
                        'id' => 'ROOM-' . ($booking->room_id ?? '0'),
                        'price' => (int) $booking->room_price,
                        'quantity' => 1,
                        'name' => 'Booking: ' . $booking->room_name,
                    ],
                ],
                'callbacks' => [
                    'finish' => route('booking.payment.finish', ['id' => $booking->id]),
                ],
            ];

            Log::info('Midtrans Snap Request', [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->room_price,
                'server_key_set' => !empty(config('midtrans.server_key')),
                'is_production' => config('midtrans.is_production'),
            ]);

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $booking->update([
                'midtrans_order_id' => $orderId,
                'midtrans_snap_token' => $snapToken,
            ]);

            Log::info('Midtrans Snap Token created', [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
            ]);

            return $snapToken;

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token ERROR', [
                'booking_id' => $booking->id ?? 'unknown',
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e),
                'server_key_empty' => empty(config('midtrans.server_key')),
                'client_key_empty' => empty(config('midtrans.client_key')),
                'is_production' => config('midtrans.is_production'),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Generate Snap Token untuk pembayaran Tagihan (Payment).
     */
    public function createSnapForPayment(Payment $payment): ?string
    {
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            $orderId = 'BILL-' . $payment->id . '-' . time() . '-' . strtoupper(substr(uniqid(), -4));
            $user = $payment->user;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $payment->amount,
                ],
                'customer_details' => [
                    'first_name' => $user?->name ?? 'User',
                    'email' => $user?->email ?? 'user@koskita.com',
                    'phone' => '-',
                ],
                'item_details' => [
                    [
                        'id' => 'BILL-' . $payment->id,
                        'price' => (int) $payment->amount,
                        'quantity' => 1,
                        'name' => 'Tagihan: ' . $payment->period_label,
                    ],
                ],
                'callbacks' => [
                    'finish' => route('payment.bill.finish', ['id' => $payment->id]),
                ],
            ];

            Log::info('Midtrans Snap Bill Request', [
                'payment_id' => $payment->id,
                'order_id' => $orderId,
                'gross_amount' => (int) $payment->amount,
                'server_key_set' => !empty(config('midtrans.server_key')),
                'is_production' => config('midtrans.is_production'),
            ]);

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $payment->update([
                'midtrans_order_id' => $orderId,
                'midtrans_snap_token' => $snapToken,
            ]);

            Log::info('Midtrans Snap for Bill created', [
                'payment_id' => $payment->id,
                'order_id' => $orderId,
            ]);

            return $snapToken;

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Bill ERROR', [
                'payment_id' => $payment->id ?? 'unknown',
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e),
                'server_key_empty' => empty(config('midtrans.server_key')),
                'amount' => $payment->amount ?? '?',
                'is_production' => config('midtrans.is_production'),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Verifikasi signature key dari callback Midtrans.
     */
    public function verifySignature(array $payload): bool
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $computedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        $valid = $computedSignature === $signatureKey;

        if (!$valid) {
            Log::warning('Midtrans Signature Key Mismatch', [
                'computed' => $computedSignature,
                'received' => $signatureKey,
                'order_id' => $orderId,
            ]);
        }

        return $valid;
    }

    /**
     * Dapatkan status transaksi dari Midtrans API.
     */
    public function getTransactionStatus(string $orderId): ?array
    {
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');

            $status = \Midtrans\Transaction::status($orderId);

            Log::info('Midtrans Transaction Status', [
                'order_id' => $orderId,
                'status' => $status,
            ]);

            return $status;

        } catch (\Exception $e) {
            Log::error('Midtrans Status Check error: ' . $e->getMessage(), [
                'order_id' => $orderId,
            ]);
            return null;
        }
    }

    /**
     * Generate unique order ID untuk Booking.
     */
    private function generateOrderId(Booking $booking): string
    {
        return 'BOOK-' . $booking->id . '-' . time() . '-' . strtoupper(substr(uniqid(), -4));
    }
}