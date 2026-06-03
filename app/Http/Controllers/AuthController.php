<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function loginProcess(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
            $credentials = $request->only('email', 'password');
        // $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect berdasarkan role
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->role === 'admin') {
                    return redirect()->route('dashboard.admin');
                } else {
                    return redirect()->route('dashboard.users');
                }
            } else {
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ]);
            }
        } else {
            return back()->withInput()->with('error', 'Login gagal. Pastikan email dan password benar.');
        }
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($request->password !== $request->password_confirmation) {
            return back()->withErrors(['password' => 'Password confirmation does not match.']);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('login.page')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }
}
