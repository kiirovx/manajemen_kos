<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput();
        }

        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route('dashboard.admin')
                ->with('success', 'Anda berhasil login.');
        }

        return redirect()
            ->route('dashboard.users')
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
}
