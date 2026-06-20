<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Payment;
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
}
