<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\TenantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'payment_method' => 'required|string|max:100',
        ]);

        $payment = Payment::where('id', $request->payment_id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $payment->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'paid_date' => now(),
        ]);

        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Pembayaran Berhasil',
            'message' => "Pembayaran Anda sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " untuk {$payment->period_label} telah diterima",
            'type' => 'success',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Pembayaran',
            'description' => "Pembayaran {$payment->period_label} (Rp " . number_format($payment->amount, 0, ',', '.') . ")",
            'status' => 'Berhasil',
            'activity_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran sedang diproses. Silakan tunggu konfirmasi...',
        ]);
    }

    /**
     * Admin: Ambil daftar tagihan penyewa.
     */
    public function tenantPayments(TenantProfile $tenant)
    {
        $payments = Payment::where('user_id', $tenant->user_id)
            ->orderByDesc('due_date')
            ->get();

        $tenantData = [
            'id' => $tenant->id,
            'user_id' => $tenant->user_id,
            'user_name' => $tenant->user?->name ?? '-',
            'room_number' => $tenant->room?->number ?? '-',
        ];

        return response()->json([
            'success' => true,
            'tenant' => $tenantData,
            'payments' => $payments->map(fn (Payment $p) => [
                'id' => $p->id,
                'period_label' => $p->period_label,
                'amount' => (float) $p->amount,
                'due_date' => $p->due_date?->format('d M Y'),
                'paid_date' => $p->paid_date?->format('d M Y'),
                'status' => $p->status,
                'payment_method' => $p->payment_method,
            ]),
        ]);
    }

    /**
     * Admin: Buat tagihan baru untuk penyewa.
     */
    public function storeBill(Request $request, TenantProfile $tenant)
    {
        $validated = $request->validate([
            'period_label' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        Payment::create([
            'user_id' => $tenant->user_id,
            'period_label' => $validated['period_label'],
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
            'status' => 'pending',
        ]);

        // Notifikasi ke user
        Notification::create([
            'user_id' => $tenant->user_id,
            'title' => 'Tagihan Baru',
            'message' => "Tagihan baru untuk periode {$validated['period_label']} sebesar Rp " . number_format($validated['amount'], 0, ',', '.') . " telah dibuat.",
            'type' => 'warning',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil dibuat.',
        ]);
    }

    /**
     * User: Buat Snap Token Midtrans untuk tagihan.
     */
    public function createBillSnap($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Tagihan ini sudah lunas.'], 422);
        }

        $snapToken = app(\App\Services\MidtransService::class)->createSnapForPayment($payment);

        if (!$snapToken) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat transaksi Midtrans.'], 500);
        }

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'order_id' => $payment->midtrans_order_id,
        ]);
    }

    /**
     * Halaman finish setelah pembayaran tagihan (redirect dari Midtrans).
     */
    public function billFinish($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->midtrans_order_id) {
            $status = app(\App\Services\MidtransService::class)->getTransactionStatus($payment->midtrans_order_id);

            if ($status) {
                $transactionStatus = $status['transaction_status'] ?? null;

                if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                    $payment->update([
                        'status' => 'paid',
                        'payment_method' => 'midtrans',
                        'paid_date' => now(),
                        'midtrans_transaction_status' => $transactionStatus,
                        'midtrans_transaction_id' => $status['transaction_id'] ?? null,
                        'midtrans_response' => json_encode($status),
                    ]);

                    \App\Models\Notification::create([
                        'user_id' => $payment->user_id,
                        'title' => 'Pembayaran Berhasil',
                        'message' => "Pembayaran tagihan {$payment->period_label} sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " telah berhasil.",
                        'type' => 'success',
                    ]);
                } elseif ($transactionStatus === 'pending') {
                    $payment->update(['midtrans_transaction_status' => $transactionStatus, 'midtrans_response' => json_encode($status)]);
                }
            }
        }

        return view('payment.bill-finish', compact('payment'));
    }

    /**
     * Admin: Tandai tagihan sebagai lunas (cash).
     */
    public function markPaid(Request $request, Payment $payment)
    {
        if ($payment->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan ini sudah lunas.',
            ], 422);
        }

        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:100',
        ]);

        $payment->update([
            'status' => 'paid',
            'payment_method' => $validated['payment_method'] ?? 'cash',
            'paid_date' => now(),
        ]);

        Notification::create([
            'user_id' => $payment->user_id,
            'title' => 'Pembayaran Berhasil',
            'message' => "Pembayaran Anda sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " untuk {$payment->period_label} telah diterima (cash).",
            'type' => 'success',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil ditandai lunas.',
        ]);
    }
}