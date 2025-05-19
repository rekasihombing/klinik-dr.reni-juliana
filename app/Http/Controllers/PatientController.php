<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function create()
    {
        return Inertia::render('Patients/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'golongan_darah' => 'nullable|string|max:3',
            'email' => 'required|email',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = auth()->id();

        Patient::create($validated);

        return redirect('/dashboard')->with('success', 'Data pasien berhasil disimpan.');
    }
}
