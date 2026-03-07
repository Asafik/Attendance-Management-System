<?php
// app/Http/Middleware/CheckActiveUser.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActiveUser
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            return redirect('/login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
