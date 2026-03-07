<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportMonthlyController extends Controller
{
    /**
     * Menampilkan halaman rekap bulanan
     */
    public function index()
    {
        return view('admin.reportMonthly');
    }
}
