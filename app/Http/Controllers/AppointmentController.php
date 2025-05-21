<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppointmentController extends Controller
{
public function create()
{

    // dd(Auth::user());
    $user = Auth::user();
    $patient = $user->patient;

    return Inertia::render('JanjiTemu', [
        'patientName' => $patient ? $patient->nama : 'Nama Tidak Ditemukan',
        'patientId' => $patient ? $patient->id : null,
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'tanggal' => 'required|date',
        'jam_konsultasi' => 'required|date_format:H:i',
        'keluhan' => 'required|string|max:1000',
    ]);

    // Ambil ID pasien dari user yang login
    $user = Auth::user();
    $patient = $user->patient; // Asumsi relasi hasOne
    if (!$patient) {
        return redirect()->back()->withErrors(['pasien_id' => 'Data pasien tidak ditemukan untuk user ini.']);
    }

    $validated['pasien_id'] = $patient->id;
    $validated['dibuat_oleh'] = 'pasien';
    $validated['dokter_id'] = 1; // ganti sesuai kebutuhan

    $appointment = Appointment::create($validated);

    return redirect()->back()->with('success', 'Janji temu berhasil dibuat.');
}
}


