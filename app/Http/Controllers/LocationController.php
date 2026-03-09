<?php
// app/Http/Controllers/LocationController.php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Simpan permission lokasi pertama kali
     * Endpoint: POST /save-location-permission
     */
    public function savePermission(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'permission' => 'required|in:allowed,blocked',
            'latitude' => 'required_if:permission,allowed|numeric|between:-90,90',
            'longitude' => 'required_if:permission,allowed|numeric|between:-180,180',
            'accuracy' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Update permission di tabel users
        $user->location_permission = $request->permission;

        // Kalau ALLOW, simpan juga lokasinya
        if ($request->permission === 'allowed' && $request->has('latitude') && $request->has('longitude')) {
            $user->last_latitude = $request->latitude;
            $user->last_longitude = $request->longitude;
            $user->last_accuracy = $request->accuracy;
            $user->last_location_at = now();
        }

        $user->save();

        // Simpan juga ke activity_logs untuk history
        $activityLog = ActivityLog::where('user_id', $user->id)
            ->where('action', 'login')
            ->latest()
            ->first();

        if ($activityLog) {
            $activityLog->latitude = $request->latitude ?? null;
            $activityLog->longitude = $request->longitude ?? null;
            $activityLog->accuracy = $request->accuracy ?? null;
            $activityLog->location_source = $request->permission === 'allowed' ? 'gps' : null;
            $activityLog->save();
        }

        return response()->json([
            'success' => true,
            'message' => $request->permission === 'allowed' ? 'Lokasi tersimpan' : 'Preferensi tersimpan',
            'data' => [
                'permission' => $user->location_permission,
                'location' => $request->permission === 'allowed' ? [
                    'lat' => $request->latitude,
                    'lng' => $request->longitude,
                    'accuracy' => $request->accuracy
                ] : null
            ]
        ]);
    }

    /**
     * Update lokasi untuk user yang sudah ALLOW
     * Endpoint: POST /update-location
     */
    public function updateLocation(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Cek apakah user sudah ALLOW
        if ($user->location_permission !== 'allowed') {
            return response()->json([
                'success' => false,
                'message' => 'User tidak memiliki izin lokasi'
            ], 403);
        }

        // Update lokasi terakhir di tabel users
        $user->last_latitude = $request->latitude;
        $user->last_longitude = $request->longitude;
        $user->last_accuracy = $request->accuracy;
        $user->last_location_at = now();
        $user->save();

        // Simpan ke activity_logs (untuk login kali ini)
        $activityLog = ActivityLog::where('user_id', $user->id)
            ->where('action', 'login')
            ->latest()
            ->first();

        if ($activityLog) {
            $activityLog->latitude = $request->latitude;
            $activityLog->longitude = $request->longitude;
            $activityLog->accuracy = $request->accuracy;
            $activityLog->location_source = 'gps';
            $activityLog->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil diperbarui',
            'data' => [
                'lat' => $request->latitude,
                'lng' => $request->longitude,
                'accuracy' => $request->accuracy,
                'timestamp' => now()
            ]
        ]);
    }

    /**
     * Get lokasi terakhir user
     * Endpoint: GET /get-location
     */
    public function getLocation()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'permission' => $user->location_permission,
                'location' => $user->last_location,
                'permission_text' => $user->location_permission_text,
            ]
        ]);
    }

    /**
     * Reverse geocoding (opsional - pake API eksternal)
     * Endpoint: POST /reverse-geocode
     */
    public function reverseGeocode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Contoh pake Nominatim (OpenStreetMap) - gratis
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$request->latitude}&lon={$request->longitude}&zoom=18&addressdetails=1";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Your App Name/1.0');
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['display_name'])) {
                // Update activity_logs dengan alamat lengkap
                $activityLog = ActivityLog::where('user_id', Auth::id())
                    ->where('action', 'login')
                    ->latest()
                    ->first();

                if ($activityLog) {
                    $activityLog->full_address = $data['display_name'];
                    $activityLog->city = $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? null;
                    $activityLog->region = $data['address']['state'] ?? null;
                    $activityLog->country = $data['address']['country'] ?? null;
                    $activityLog->country_code = strtoupper($data['address']['country_code'] ?? null);
                    $activityLog->save();
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'full_address' => $data['display_name'],
                        'city' => $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? null,
                        'region' => $data['address']['state'] ?? null,
                        'country' => $data['address']['country'] ?? null,
                        'country_code' => $data['address']['country_code'] ?? null,
                    ]
                ]);
            }
        } catch (\Exception $e) {
            // Log error tapi jangan gagalin response
            \Log::error('Reverse geocoding failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mendapatkan alamat'
        ], 500);
    }
}
