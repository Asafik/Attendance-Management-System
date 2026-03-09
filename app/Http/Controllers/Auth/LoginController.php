<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // CEK APAKAH USER AKTIF
            if (!$user->is_active) {
                Auth::logout();

                // CATAT LOG LOGIN GAGAL (AKUN TIDAK AKTIF)
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'login',
                    'status' => 'failed',
                    'description' => 'Akun tidak aktif',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'browser' => $this->getBrowser($request->userAgent()),
                    'os' => $this->getOS($request->userAgent()),
                    'device' => $this->getDevice($request->userAgent()),
                ]);

                return back()->with('error', 'Akun Anda tidak aktif.');
            }

            // UPDATE SESSION INFO (1 AKUN 1 DEVICE)
            $user->session_id = Session::getId();
            $user->last_login_at = now();
            $user->last_ip = $request->ip();
            $user->last_user_agent = $request->userAgent();
            $user->save();

            // ===== CATAT LOG LOGIN SUKSES =====
            $log = ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'status' => 'success',
                'description' => 'Login berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'browser' => $this->getBrowser($request->userAgent()),
                'os' => $this->getOS($request->userAgent()),
                'device' => $this->getDevice($request->userAgent()),
                // Lokasi GPS akan diupdate nanti lewat AJAX
                'latitude' => null,
                'longitude' => null,
                'accuracy' => null,
                'location_source' => null,
            ]);
            // ==================================

            // ===== SET SESSION UNTUK DASHBOARD =====
            Session::put('location_permission', $user->location_permission ?? 'not_set');
            Session::put('user_id', $user->id);
            Session::put('last_activity_log_id', $log->id);
            // ========================================

            return redirect()->intended('dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        // LOGIN GAGAL (SALAH PASSWORD)
        $user = User::where('email', $request->email)->first();

        ActivityLog::create([
            'user_id' => $user->id ?? null,
            'action' => 'login',
            'status' => 'failed',
            'description' => 'Email atau password salah',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser' => $this->getBrowser($request->userAgent()),
            'os' => $this->getOS($request->userAgent()),
            'device' => $this->getDevice($request->userAgent()),
        ]);

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // CATAT LOG LOGOUT
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'logout',
                'status' => 'success',
                'description' => 'Logout berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'browser' => $this->getBrowser($request->userAgent()),
                'os' => $this->getOS($request->userAgent()),
                'device' => $this->getDevice($request->userAgent()),
            ]);

            // HAPUS SESSION ID SAAT LOGOUT
            $user->session_id = null;
            $user->save();
        }

        Auth::logout();
        Session::flush();

        return redirect('/')
            ->with('success', 'Anda telah logout.');
    }

    // ===== FUNGSI BANTU UNTUK DETEKSI BROWSER =====
    private function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) return 'Internet Explorer';
        return 'Unknown';
    }

    private function getOS($userAgent)
    {
        if (strpos($userAgent, 'Windows NT 10.0') !== false) return 'Windows 10';
        if (strpos($userAgent, 'Windows NT 11.0') !== false) return 'Windows 11';
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac OS X') !== false) {
            if (preg_match('/Mac OS X (\d+[._]\d+)/', $userAgent, $matches)) {
                return 'macOS ' . str_replace('_', '.', $matches[1]);
            }
            return 'macOS';
        }
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) {
            if (preg_match('/Android (\d+(\.\d+)?)/', $userAgent, $matches)) {
                return 'Android ' . $matches[1];
            }
            return 'Android';
        }
        if (preg_match('/(iPhone|iPad).*OS (\d+[._]\d+)/', $userAgent, $matches)) {
            return 'iOS ' . str_replace('_', '.', $matches[2]);
        }
        if (strpos($userAgent, 'iOS') !== false) return 'iOS';
        return 'Unknown';
    }

    private function getDevice($userAgent)
    {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $userAgent)) {
            return 'tablet';
        }
        if (preg_match('/(mobile|iphone|ipod|android|blackberry)/i', $userAgent)) {
            return 'mobile';
        }
        return 'desktop';
    }
}
