<?php
// app/Http/Controllers/RoleController.php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Tampilkan halaman role
     */
    public function index()
    {
        $roles = Role::withCount('users')->latest()->get();
        return view('user.role', compact('roles'));
    }

    /**
     * Simpan role baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'display_name' => 'required|string|max:255',
        ]);

        Role::create([
            'name' => strtolower($request->name),
            'display_name' => $request->display_name,
        ]);

        return redirect()->back()->with('success', 'Role berhasil ditambahkan.');
    }

    /**
     * Update role
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
        ]);

        $role->update([
            'name' => strtolower($request->name),
            'display_name' => $request->display_name,
        ]);

        return redirect()->back()->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Hapus role
     */
    public function destroy(Role $role)
    {
        // Cek apakah role ini digunakan oleh user
        $userCount = User::where('role_id', $role->id)->count();

        if ($userCount > 0) {
            return redirect()->back()->with('error', "Role {$role->display_name} tidak dapat dihapus karena masih digunakan oleh {$userCount} user.");
        }

        $role->delete();

        return redirect()->back()->with('success', 'Role berhasil dihapus.');
    }
}
