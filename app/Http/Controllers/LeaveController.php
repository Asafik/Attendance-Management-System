<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Menampilkan halaman data izin & cuti
     */
    public function index()
    {
        return view('admin.leave');
    }
}
