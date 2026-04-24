<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Division;
use App\Models\Bank;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Tampilkan halaman data karyawan
     */
    public function index()
    {
        // HAPUS paginate(10), ganti dengan get() ambil semua data
        $employees = Employee::with(['division', 'position', 'bank'])->latest()->get();

        $divisions = Division::all();
        $positions = Position::all();
        $banks     = Bank::all();

        // Statistik
        $totalAktif     = Employee::where('status', 'Aktif')->count();
        $totalNonaktif  = Employee::where('status', 'Nonaktif')->count();
        $totalKaryawan  = Employee::count();

        return view('admin.employee', compact(
            'employees',
            'divisions',
            'positions',
            'banks',
            'totalAktif',
            'totalNonaktif',
            'totalKaryawan'
        ));
    }

    /**
     * Simpan karyawan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'division_id'      => 'required|exists:divisions,id',
            'position_id'      => 'nullable|exists:positions,id',
            'bank_id'          => 'required|exists:banks,id',
            'username'         => 'required|string|max:255|unique:employees,username',
            'password'         => 'required|string|min:8',
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'account_number'   => 'nullable|string|max:50',
            'status'           => 'required|in:Aktif,Nonaktif',
            'regular_off_day'  => 'nullable|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu,Tidak Libur',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['photo', 'password']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $data['password'] = Hash::make($request->password);

        Employee::create($data);

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Detail (AJAX)
     */
    public function show(Employee $employee)
    {
        return response()->json($employee->load(['division', 'position', 'bank']));
    }

    /**
     * Update karyawan
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'division_id'      => 'required|exists:divisions,id',
            'position_id'      => 'nullable|exists:positions,id',
            'bank_id'          => 'required|exists:banks,id',
            'username'         => 'required|string|max:255|unique:employees,username,' . $employee->id,
            'password'         => 'nullable|string|min:8',
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'account_number'   => 'nullable|string|max:50',
            'status'           => 'required|in:Aktif,Nonaktif',
            'regular_off_day'  => 'nullable|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu,Tidak Libur',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['photo', 'password']);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->back()->with('success', 'Karyawan berhasil diperbarui.');
    }

    /**
     * Hapus karyawan
     */
    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->back()->with('success', 'Karyawan berhasil dihapus.');
    }
}
