<?php
// app/Http/Middleware/CheckSingleSession.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckSingleSession
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // CEK TAMBAHAN: Pastikan user aktif (redundan, tapi aman)
            if (!$user->is_active) {
                Auth::logout();
                Session::flush();
                return redirect('/login')->with('error', 'Akun Anda telah dinonaktifkan.');
            }

            $currentSessionId = Session::getId();

            // Jika user punya session_id dan berbeda dengan session sekarang
            if ($user->session_id && $user->session_id !== $currentSessionId) {
                // Paksa logout
                Auth::logout();
                Session::flush();

                // Set flash message
                session()->flash('error', 'Akun Anda telah login di perangkat lain.');
                session()->flash('error_type', 'multiple_login');

                // Redirect ke halaman 419 custom
                return redirect()->route('error.419');
            }

            // Update last activity
            if ($user->session_id === $currentSessionId) {
                $user->last_login_at = now();
                $user->save();
            }
        }

        return $next($request);
    }
}
