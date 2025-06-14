<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OfflineBookingController extends Controller
{
    public function create()
    {
        return Inertia::render('staff/PendaftaranPasienStaff');
    }

    public function checkNik($nik)
    {
        Log::info("Checking NIK: {$nik}");
        $patient = Patient::where('nik', $nik)->first();

        if ($patient) {
            Log::info("Patient found for NIK: {$nik}");
            return response()->json([
                'exists' => true,
                'patient' => [
                    'nama_lengkap' => $patient->nama_lengkap,
                    'nik' => $patient->nik,
                    'tanggal_lahir' => $patient->tanggal_lahir,
                    'golongan_darah' => $patient->golongan_darah,
                    'jenis_kelamin' => $patient->jenis_kelamin,
                    'alamat' => $patient->alamat,
                    'no_hp' => $patient->no_hp,
                ]
            ]);
        }

        Log::info("No patient found for NIK: {$nik}");
        return response()->json(['exists' => false]);
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

        $tanggal = Carbon::now()->toDateString();
        $jam = Carbon::now()->format('H:i');
        $checkedInAt = Carbon::now()->format('Y-m-d H:i:s');
        $doctorId = 1;

        // Check if patient already exists by NIK
        $patient = Patient::where('nik', $validated['nik'])->first();

        if ($patient) {
            Log::info("Using existing patient with NIK: {$validated['nik']}, ID: {$patient->id}");
            // Update patient data if necessary
            $patient->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'golongan_darah' => $validated['golongan_darah'] ?? $patient->golongan_darah,
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'] ?? $patient->alamat,
                'no_hp' => $validated['no_hp'] ?? $patient->no_hp,
            ]);
        } else {
            Log::info("Creating new patient with NIK: {$validated['nik']}");
            // Create new patient
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
        }

        // Create appointment
        $appointment = Appointment::create([
            'pasien_id' => $patient->id,
            'dokter_id' => $doctorId,
            'tanggal' => $tanggal,
            'jam_konsultasi' => $jam,
            'keluhan' => $validated['keluhan'],
            'status' => 'dikonfirmasi',
            'dibuat_oleh' => 'staff',
            'checked_in_at' => $checkedInAt,
        ]);

        // Assign queue numbers for the day
        Appointment::assignQueueNumbers($doctorId, $tanggal);

        return redirect()->route('dashboardstaff')->with('success', 'Pendaftaran berhasil.');
    }
}