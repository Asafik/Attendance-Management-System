<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan sistem
     */
    public function index()
    {
        return view('setting.setting');
    }
}
