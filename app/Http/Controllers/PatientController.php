<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function create()
    {
        $patient = Auth::user()->patient;

        return inertia('patients/Form', [  // Sesuaikan dengan komponen Vue kamu
            'patientName' => $patient->nama_lengkap,
            'clinicName' => 'Nama Klinik Kamu',
            'patientData' => $patient,
        ]);
    }

    public function edit()
{
    $patient = Patient::where('user_id', Auth::id())->firstOrFail();

    return Inertia::render('Patients/Edit', [
        'patient' => $patient,
    ]);
}

public function update(Request $request)
{
    $patient = Patient::where('user_id', Auth::id())->firstOrFail();

    $request->validate([
        'nik' => 'required|string|size:16',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|string|in:L,Perempuan,Laki-laki',
        'golongan_darah' => 'nullable|string|in:A,B,AB,O',
        'no_hp' => 'nullable|string',
        'alamat' => 'nullable|string',
    ]);

    $patient->update($request->only([
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'golongan_darah',
        'no_hp',
        'alamat',
    ]));

        return redirect()->route('dashboard')->with('success', 'Data pasien berhasil diperbarui');
    }
}
