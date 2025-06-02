<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use Inertia\Inertia;
use Carbon\Carbon;

class OfflineBookingController extends Controller
{
    public function create()
    {
        return Inertia::render('staff/PendaftaranPasienStaff'); // ganti sesuai view form booking offline
    }


public function store(Request $request)
{
    $validated = $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'nik' => 'required|string|max:20',
        'tanggal_lahir' => 'required|date',
        'golongan_darah' => 'nullable|string|max:5',
        'jenis_kelamin' => 'required|in:L,P',
        'alamat' => 'nullable|string',
        'no_hp' => 'nullable|string|max:20',
        'keluhan' => 'required|string',
    ]);

    $tanggal = Carbon::now()->toDateString();       // yyyy-mm-dd
    $jam = Carbon::now()->format('H:i');            // hh:mm
    $checkedInAt = Carbon::now()->format('Y-m-d H:i:s');

    // Buat data pasien dulu
    $patient = Patient::create([
        'user_id' => null,
        'nama_lengkap' => $validated['nama_lengkap'],
        'nik' => $validated['nik'],
        'tanggal_lahir' => $validated['tanggal_lahir'],
        'golongan_darah' => $validated['golongan_darah'] ?? null,
        'jenis_kelamin' => $validated['jenis_kelamin'],
        'alamat' => $validated['alamat'] ?? null,
        'no_hp' => $validated['no_hp'] ?? null,
    ]);

    // Buat appointment dengan 'pasien_id' sesuai model
Appointment::create([
    'pasien_id' => $patient->id,
    'dokter_id' => 1,
    'tanggal' => $tanggal,
    'jam_konsultasi' => $jam,
    'keluhan' => $validated['keluhan'],
    'status' => 'dikonfirmasi',
    'dibuat_oleh' => 'staff',
    'checked_in_at' => $checkedInAt,
]);



    return redirect()->route('dashboardstaff')->with('success', 'Pendaftaran berhasil.');
}

}
