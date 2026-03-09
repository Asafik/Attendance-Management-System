<?php
// app/Http/Controllers/ActivityLogController.php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan halaman log aktivitas
     */
    public function index(Request $request)
    {
        // Query dasar
        $query = ActivityLog::with('user.role')
            ->where('action', 'login');

        // FILTER BERDASARKAN ROLE
        if ($request->has('role') && $request->role !== 'all') {
            $query->whereHas('user.role', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // FILTER BERDASARKAN STATUS
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // FITUR SEARCH
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ip_address', 'LIKE', "%{$search}%")
                  ->orWhere('browser', 'LIKE', "%{$search}%")
                  ->orWhere('os', 'LIKE', "%{$search}%")
                  ->orWhere('device', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // PERPAGE
        $perPage = $request->get('per_page', 10);

        // Ambil data dengan pagination
        $activityLogs = $query->latest()->paginate($perPage);

        // Tambahkan query string ke pagination links
        $activityLogs->appends($request->query());

        // Hitung statistik untuk card
        $loginHariIni = ActivityLog::where('action', 'login')
            ->whereDate('created_at', today())
            ->count();

        $loginBulanIni = ActivityLog::where('action', 'login')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Hitung device
        $totalMobile = ActivityLog::where('action', 'login')
            ->where('device', 'mobile')
            ->count();

        $totalDesktop = ActivityLog::where('action', 'login')
            ->where('device', 'desktop')
            ->count();

        $totalLogin = ActivityLog::where('action', 'login')->count();

        $persentaseMobile = $totalLogin > 0
            ? round(($totalMobile / $totalLogin) * 100)
            : 0;

        $persentaseDesktop = $totalLogin > 0
            ? round(($totalDesktop / $totalLogin) * 100)
            : 0;

        // Hitung login kemarin (untuk card)
        $loginKemarin = ActivityLog::where('action', 'login')
            ->whereDate('created_at', today()->subDay())
            ->count();

        // Ambil semua role untuk dropdown filter
        $roles = Role::all();

        return view('admin.activity-log', compact(
            'activityLogs',
            'loginHariIni',
            'loginBulanIni',
            'persentaseMobile',
            'persentaseDesktop',
            'totalMobile',
            'totalDesktop',
            'loginKemarin',
            'roles'
        ));
    }
}
