<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\OfficeLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceApiController extends Controller
{
    // Sesuaikan nama fungsi dengan Route (scan)
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_content' => 'required',
            'type'       => 'required|in:in,out',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data scan tidak lengkap'
            ], 422);
        }

        // Cek QR terdaftar atau tidak
        $location = OfficeLocation::where('loc_code', $request->qr_content)->first();

        if (!$location) {
            return response()->json([
                'status'  => 'error',
                'message' => 'QR Code Tidak Dikenali!'
            ], 403);
        }

        $employee = $request->user();

        $log = AttendanceLog::create([
            'employee_id'   => $employee->id,
            'location_code' => $location->loc_code,
            'type'          => $request->type,
            'date'          => date('Y-m-d'),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Absen ' . ($request->type == 'in' ? 'Masuk' : 'Pulang') . ' Berhasil!',
            'data'    => [
                'name'     => $employee->name,
                'location' => $location->name,
                'time'     => $log->created_at->format('H:i:s'),
            ]
        ], 200);
    }
}
