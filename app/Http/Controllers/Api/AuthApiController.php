<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee; // Pastikan Model Employee yang di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    /**
     * LOGIN KHUSUS KARYAWAN (Flutter App)
     * Menggunakan tabel 'employees' & field 'username'
     */
    public function loginEmployee(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Username dan password jangan dikosongin ya, Mas!'
            ], 422);
        }

        // 2. Cari Employee berdasarkan Username + Load Relasi
        $employee = Employee::with(['position', 'division'])
                    ->where('username', $request->username)
                    ->first();

        // 3. Cek apakah ada datanya & password cocok
        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Username atau Password salah!'
            ], 401);
        }

        // 4. Cek apakah statusnya Aktif
        if ($employee->status !== 'Aktif') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun Mas sedang dinonaktifkan oleh Admin.'
            ], 403);
        }

        // 5. Buat Token Baru (Hapus token lama biar 1 akun 1 login)
        $employee->tokens()->delete();
        $token = $employee->createToken('token-aleena-employee')->plainTextToken;

        // 6. Response Sukses (Data untuk disimpan di Flutter)
        return response()->json([
            'status'       => 'success',
            'message'      => 'Login Berhasil!',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'              => $employee->id,
                'name'            => $employee->name,
                'username'        => $employee->username,
                'position'        => $employee->position ? $employee->position->name : '-',
                'division'        => $employee->division ? $employee->division->name : '-',
                'photo_url'       => $employee->photo_url,
                'regular_off_day' => $employee->regular_off_day,
            ]
        ], 200);
    }

    /**
     * AMBIL DATA PROFIL (Dipakai Flutter buat refresh dashboard)
     */
    public function me(Request $request)
    {
        $employee = $request->user()->load(['position', 'division']);

        return response()->json([
            'status' => 'success',
            'user'   => $employee
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Berhasil logout, sampai jumpa lagi!'
        ]);
    }
}
