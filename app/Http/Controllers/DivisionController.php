<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Menampilkan halaman data divisi
     */
    public function index()
    {
        // sementara tanpa withCount karena relasi dimatikan
        $divisions = Division::all();

        return view('admin.devision', compact('divisions'));
    }

    /**
     * Menyimpan divisi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
        ]);

        Division::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Update divisi
     */
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
        ]);

        $division->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Hapus divisi
     */
    public function destroy(Division $division)
    {
        $division->delete();

        return redirect()->back()->with('success', 'Divisi berhasil dihapus.');
    }
}
