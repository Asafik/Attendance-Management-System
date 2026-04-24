<?php

namespace App\Http\Controllers;

use App\Models\OfficeLocation;
use Illuminate\Http\Request;

class OfficeLocationController extends Controller
{
    // Menampilkan semua daftar lokasi kantor
    public function index()
    {
        $locations = OfficeLocation::all();
        return view('admin.show', compact('locations'));
    }

    // Menampilkan QR Code untuk lokasi tertentu
    public function show($id)
    {
        $location = OfficeLocation::findOrFail($id);
        return view('admin.show', compact('location'));
    }
}
