<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Menampilkan halaman data jabatan
     */
    public function index()
    {
        return view('admin.position');
    }
}
