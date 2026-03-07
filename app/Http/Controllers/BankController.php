<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Tampilkan halaman data bank
     */
    public function index()
    {
        $banks = Bank::all();

        return view('admin.bank', compact('banks'));
    }

    /**
     * Simpan bank baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:banks,name',
        ]);

        Bank::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Bank berhasil ditambahkan.');
    }

    /**
     * Update bank
     */
    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:banks,name,' . $bank->id,
        ]);

        $bank->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Bank berhasil diperbarui.');
    }

    /**
     * Hapus bank
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();

        return redirect()->back()->with('success', 'Bank berhasil dihapus.');
    }
}
