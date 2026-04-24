<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Menampilkan halaman data jabatan
     */
    public function index()
    {
        // sementara tanpa withCount karena relasi dimatikan
        $positions = Position::all();

        return view('admin.position', compact('positions'));
    }

    /**
     * Menyimpan jabatan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name',
        ]);

        Position::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Jabatan berhasil ditambahkan.');
    }

    /**
     * Update jabatan
     */
    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,' . $position->id,
        ]);

        $position->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Hapus jabatan
     */
    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
