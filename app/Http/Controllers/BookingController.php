<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Halaman booking publik dengan data kamar tersedia.
     */
    public function index()
    {
        $rooms = Room::where('status', '!=', 'maintenance')
            ->where('slots', '>', 0)
            ->orderBy('number')
            ->get();

        return view('booking.booking', compact('rooms'));
    }

    /**
     * Simpan booking dari frontend.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_message' => 'nullable|string',
            'room_name' => 'required|string',
            'room_price' => 'required|numeric',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'payment_method' => 'nullable|string',
        ]);

        // Validasi ketersediaan kamar
        if ($request->room_id) {
            $room = Room::find($request->room_id);
            if (!$room || !$room->hasAvailableSlots()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, kamar sudah penuh atau tidak tersedia.',
                ], 422);
            }
        }

        $isCash = $request->payment_method === 'cash';

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_message' => $request->customer_message,
            'room_name' => $request->room_name,
            'room_price' => $request->room_price,
            'status' => $isCash ? 'Dibayar' : 'Menunggu Pembayaran',
            'payment_method' => $request->payment_method ?? 'midtrans',
            'paid_at' => $isCash ? now() : null,
        ]);

        // Jika cash, langsung buat TenantProfile untuk review Admin
        if ($isCash) {
            if ($booking->user_id && $booking->room_id) {
                $existingTenant = \App\Models\TenantProfile::where('user_id', $booking->user_id)->first();
                if (!$existingTenant) {
                    \App\Models\TenantProfile::create([
                        'user_id' => $booking->user_id,
                        'room_id' => $booking->room_id,
                        'booking_id' => $booking->id,
                        'phone' => $booking->customer_phone ?? '-',
                        'lease_start' => now(),
                        'status' => 'Menunggu Persetujuan',
                    ]);

                    // Kurangi slot kamar
                    $room = Room::find($booking->room_id);
                    if ($room) {
                        $room->decreaseSlot();
                    }
                }
            }
        }

        // Jika metode midtrans, generate Snap Token
        $snapToken = null;
        if ($request->payment_method === 'midtrans') {
            $snapToken = $this->midtransService->createSnapTransaction($booking);

            if (!$snapToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat transaksi pembayaran. Silakan coba lagi.',
                ], 500);
            }
        }

        Log::info('Booking created', [
            'booking_id' => $booking->id,
            'room_id' => $booking->room_id,
            'customer' => $booking->customer_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat. Melanjutkan ke pembayaran...',
            'booking_id' => $booking->id,
            'snap_token' => $snapToken,
            'redirect_url' => route('booking.payment.page', ['id' => $booking->id]),
        ]);
    }

    /**
     * Halaman pembayaran (Midtrans Snap).
     */
    public function paymentPage($id)
    {
        $booking = Booking::findOrFail($id);

        if (!in_array($booking->status, ['Menunggu Pembayaran', 'Pending'])) {
            return redirect()->route('booking')->with('error', 'Booking tidak dalam status pembayaran.');
        }

        return view('booking.payment', compact('booking'));
    }

    /**
     * Cek status pembayaran via API.
     */
    public function paymentStatus($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->midtrans_order_id) {
            $status = $this->midtransService->getTransactionStatus($booking->midtrans_order_id);

            if ($status) {
                return response()->json([
                    'success' => true,
                    'booking_status' => $booking->status,
                    'midtrans_status' => $status['transaction_status'] ?? null,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'booking_status' => $booking->status,
            'midtrans_status' => null,
        ]);
    }

    /**
     * Halaman finish setelah pembayaran (redirect dari Midtrans).
     */
    public function paymentFinish($id)
    {
        $booking = Booking::findOrFail($id);

        // Cek status terbaru dari Midtrans
        if ($booking->midtrans_order_id) {
            $status = $this->midtransService->getTransactionStatus($booking->midtrans_order_id);

            if ($status) {
                $transactionStatus = $status['transaction_status'] ?? null;
                $paymentMethod = $status['payment_type'] ?? null;
                $transactionId = $status['transaction_id'] ?? null;
                $grossAmount = $status['gross_amount'] ?? null;

                if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                    $previousStatus = $booking->status;
                    $booking->update([
                        'status' => 'Dibayar',
                        'midtrans_transaction_status' => $transactionStatus,
                        'midtrans_transaction_id' => $transactionId,
                        'gross_amount' => $grossAmount ?? $booking->room_price,
                        'payment_method' => $paymentMethod ?? $booking->payment_method,
                        'paid_at' => now(),
                    ]);

                    // Kurangi slot kamar
                    if ($booking->room_id && $previousStatus !== 'Dibayar') {
                        $room = Room::find($booking->room_id);
                        if ($room) {
                            $room->decreaseSlot();

                            Log::info('Room slot decreased', [
                                'room_id' => $room->id,
                                'remaining_slots' => $room->slots,
                            ]);
                        }
                    }
                } elseif ($transactionStatus === 'pending') {
                    $booking->update([
                        'status' => 'Pending',
                        'midtrans_transaction_status' => $transactionStatus,
                    ]);
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $booking->update([
                        'status' => match($transactionStatus) {
                            'deny' => 'Ditolak',
                            'expire' => 'Kadaluarsa',
                            'cancel' => 'Dibatalkan',
                            default => 'Gagal',
                        },
                        'midtrans_transaction_status' => $transactionStatus,
                    ]);
                }
            }
        }

        return view('booking.finish', compact('booking'));
    }

    /**
     * Admin: Batalkan booking.
     */
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if (!in_array($booking->status, ['Menunggu Pembayaran', 'Pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya booking dengan status Menunggu Pembayaran atau Pending yang dapat dibatalkan.',
            ], 422);
        }

        $previousStatus = $booking->status;
        $booking->update([
            'status' => 'Dibatalkan',
            'midtrans_transaction_status' => 'cancel',
        ]);

        // Kembalikan slot kamar jika sebelumnya Dibayar
        if ($previousStatus === 'Dibayar' && $booking->room_id) {
            $room = Room::find($booking->room_id);
            if ($room) {
                $room->increaseSlot();
            }
        }

        Log::info('Booking cancelled by admin', [
            'booking_id' => $booking->id,
            'previous_status' => $previousStatus,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibatalkan.',
        ]);
    }

    /**
     * Admin: Hapus booking.
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Booking yang sudah Dibayar tidak dapat dihapus
        if ($booking->status === 'Dibayar') {
            return response()->json([
                'success' => false,
                'message' => 'Booking dengan status Dibayar tidak dapat dihapus.',
            ], 422);
        }

        $bookingId = $booking->id;
        $booking->delete();

        Log::info('Booking deleted by admin', [
            'booking_id' => $bookingId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dihapus.',
        ]);
    }

    /**
     * Admin: Terima booking cash (buat TenantProfile + set Aktif).
     */
    public function acceptCash($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->payment_method !== 'cash') {
            return redirect(route('dashboard.admin') . '#booking-review')
                ->with('error', 'Hanya booking cash yang dapat diterima melalui metode ini.');
        }

        if ($booking->tenantProfile) {
            return redirect(route('dashboard.admin') . '#booking-review')
                ->with('error', 'Booking ini sudah memiliki profil penyewa.');
        }

        // Buat TenantProfile
        \App\Models\TenantProfile::create([
            'user_id' => $booking->user_id,
            'room_id' => $booking->room_id,
            'booking_id' => $booking->id,
            'phone' => $booking->customer_phone ?? '-',
            'lease_start' => now(),
            'status' => 'Aktif',
            'approved_at' => now(),
        ]);

        // Update status kamar
        if ($booking->room_id) {
            $room = \App\Models\Room::find($booking->room_id);
            if ($room && $room->status !== 'occupied') {
                $room->update(['status' => 'occupied']);
            }
        }

        Log::info('Cash booking accepted by admin', [
            'booking_id' => $booking->id,
            'customer' => $booking->customer_name,
            'room_id' => $booking->room_id,
        ]);

        return redirect(route('dashboard.admin') . '#booking-review')
            ->with('success', 'Booking cash untuk ' . $booking->customer_name . ' berhasil diterima. Penyewa sekarang Aktif.');
    }

    /**
     * Cetak bukti pembayaran (PDF view).
     */
    public function invoice($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'Dibayar') {
            return redirect()->back()->with('error', 'Pembayaran belum lunas.');
        }

        return view('booking.invoice', compact('booking'));
    }
}