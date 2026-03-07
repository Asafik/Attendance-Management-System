<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1])) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleName = $user->role ? $user->role->display_name : 'User';

            // Notifikasi sukses dengan nama dan role
            return redirect()->intended('/dashboard')->with(
                'success',
                'Selamat datang, ' . $user->name . '! Anda login sebagai ' . $roleName . '.'
            );
        }

        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user && !$user->is_active) {
            return back()->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.');
        }

        return back()->with('error', 'Email atau password salah.');
    }
}
