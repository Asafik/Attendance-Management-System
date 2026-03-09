<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil data pertama (karena biasanya hanya 1 record)
        $company = CompanyProfile::first() ?? new CompanyProfile();

        return view('setting.setting', compact('company'));
    }

    // Ubah nama method dari 'save' ke 'update'
    public function update(Request $request)  // ← diubah dari save ke update
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        $data = $request->except(['_token', 'logo', 'favicon']);
        $data['updated_by'] = auth()->id();

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('company', 'public');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('company', 'public');
        }

        // Update atau create
        CompanyProfile::updateOrCreate(
            ['id' => 1],
            $data
        );

        return redirect()->route('setting.index')
            ->with('success', 'Company profile updated successfully.');
    }
}
