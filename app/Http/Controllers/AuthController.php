<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginPage(Request $request)
    {
        // Simpan URL yang ingin dituju setelah login (dari query param)
        if ($request->has('redirect_to') && $request->get('redirect_to') !== '') {
            session()->put('url.intended', $request->get('redirect_to'));
        }

        return view('auth.login');
    }

    /**
     * Sanitasi url.intended yang mungkin berisi POST-only endpoint
     * yang disimpan oleh middleware auth saat mencegat request POST.
     */
    protected function sanitizeIntendedUrl(): void
    {
        $intended = session()->get('url.intended');

        if (empty($intended)) {
            return;
        }

        // Mapping POST-only endpoints → GET fallback
        $postToGetMap = [
            '/booking/store' => '/booking',
            '/payment/bill/create-snap' => '/payment/bill',
        ];

        $parsedUrl = parse_url($intended);
        $intendedPath = $parsedUrl['path'] ?? '/';

        // Urai query string kalau ada
        $queryString = $parsedUrl['query'] ?? '';

        foreach ($postToGetMap as $postPath => $getPath) {
            if (str_contains($intendedPath, $postPath)) {
                $intendedPath = $getPath;
                $queryString = ''; // Hapus query string dari POST endpoint
                break;
            }
        }

        // Jika intended adalah root (/) atau kosong, hapus saja
        if (empty($intendedPath) || $intendedPath === '/' || str_contains($intendedPath, '//')) {
            session()->forget('url.intended');
            return;
        }

        // Rekonstruksi intended URL yang aman (GET)
        $sanitized = $intendedPath;
        if (!empty($queryString)) {
            $sanitized .= '?' . $queryString;
        }

        session()->put('url.intended', $sanitized);
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput();
        }

        $request->session()->regenerate();

        // Cek apakah ada redirect_to dari form (post) — prioritas utama
        if ($request->filled('redirect_to')) {
            $intendedUrl = $request->post('redirect_to');
            $parsedUrl = parse_url($intendedUrl);
            $intendedPath = ($parsedUrl['path'] ?? '/');

            // Sanitasi: jangan izinkan POST endpoint sebagai intended
            if (str_contains($intendedPath, '/booking/store') || str_contains($intendedPath, '/payment/bill/create-snap')) {
                $intendedPath = '/booking';
            }

            if (!empty($intendedPath) && $intendedPath !== '/' && !str_contains($intendedPath, '//')) {
                session()->put('url.intended', $intendedPath);
            }
        } else {
            // Tidak ada redirect_to — sanitasi url.intended yang mungkin
            // diset oleh middleware auth saat mencegat POST request
            $this->sanitizeIntendedUrl();
        }

        // Gunakan intended() — jika ada intended URL, redirect ke sana
        // Jika tidak ada, fallback ke dashboard sesuai role
        $fallbackRoute = Auth::user()->role === 'admin' ? 'dashboard.admin' : 'dashboard.users';

        return redirect()
            ->intended(route($fallbackRoute))
            ->with('success', 'Anda berhasil login.');
    }

    public function registerPage()
    {
        return view('auth.register');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.page')->with('success', 'Anda telah berhasil logout.');
    }

    public function registerProcess(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'email.unique' => 'Email sudah terdaftar',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return redirect()
                ->route('login.page')
                ->with('success', 'Akun berhasil dibuat. Silakan login.');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function forgotPasswordPage()
    {
        return view('auth.forgot-password');
    }

    /**
     * Forgot password verify now handled by PasswordResetController@sendOtp.
     * This method kept for backward compatibility but redirects to OTP flow.
     */
    public function forgotPasswordVerify(Request $request)
    {
        // Forward to PasswordResetController@sendOtp
        return app(PasswordResetController::class)->sendOtp($request);
    }

    public function resetPasswordPage(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('forgot-password.page')->withErrors(['email' => 'Silakan masukkan email Anda terlebih dahulu.']);
        }

        if (!$request->session()->has('otp_verified')) {
            return redirect()->route('otp.page')->withErrors(['otp' => 'Silakan verifikasi kode OTP terlebih dahulu.']);
        }

        return view('auth.reset-password');
    }

    public function resetPasswordProcess(Request $request)
    {
        if (!$request->session()->has('reset_email') || !$request->session()->has('otp_verified')) {
            return redirect()->route('forgot-password.page')->withErrors(['email' => 'Sesi reset password kedaluwarsa. Silakan ulangi proses.']);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        $request->session()->forget('reset_email');
        $request->session()->forget('otp_verified');

        return redirect()->route('login.page')->with('success', 'Password Anda berhasil diperbarui. Silakan login.');
    }
}
