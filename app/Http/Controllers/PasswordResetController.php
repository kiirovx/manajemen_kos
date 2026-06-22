<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    /**
     * Step 1: Generate OTP & send to user email.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak terdaftar di sistem kami.',
        ]);

        $email = $request->email;
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in password_reset_tokens table (reuse existing table)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $otp,
                'created_at' => now(),
            ]
        );

        // Send OTP email
        Mail::to($email)->send(new OtpMail($otp));

        // Store session: email + expiry (10 menit)
        session([
            'reset_email' => $email,
            'otp_expiry' => now()->addMinutes(10)->timestamp,
        ]);

        return redirect()->route('otp.page')
            ->with('success', 'Kode OTP telah dikirim ke ' . $email);
    }

    /**
     * Step 2: Show OTP verification page.
     */
    public function showOtpPage(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('forgot-password.page')
                ->withErrors(['email' => 'Silakan masukkan email Anda terlebih dahulu.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Step 3: Verify OTP code.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.size' => 'Kode OTP harus 6 digit.',
        ]);

        $email = session('reset_email');
        $otpExpiry = session('otp_expiry');

        if (!$email) {
            return redirect()->route('forgot-password.page')
                ->withErrors(['email' => 'Sesi telah berakhir. Silakan ulangi proses.']);
        }

        // Check OTP expiry
        if (now()->timestamp > $otpExpiry) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan kirim ulang.']);
        }

        // Check OTP in database
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->withInput();
        }

        // OTP valid — delete the token so it can't be reused
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Mark OTP as verified in session
        session(['otp_verified' => true]);
        session()->forget('otp_expiry');

        return redirect()->route('reset-password.page')
            ->with('success', 'OTP berhasil diverifikasi. Silakan buat password baru.');
    }

    /**
     * Step 3b: Resend OTP.
     */
    public function resendOtp(Request $request)
    {
        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('forgot-password.page')
                ->withErrors(['email' => 'Sesi telah berakhir. Silakan ulangi proses.']);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $otp,
                'created_at' => now(),
            ]
        );

        Mail::to($email)->send(new OtpMail($otp));

        session(['otp_expiry' => now()->addMinutes(10)->timestamp]);

        return back()->with('success', 'Kode OTP baru telah dikirim ke ' . $email);
    }
}