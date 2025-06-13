<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $patient = Patient::where('user_id', $user->id)->first();
        
        // Format data untuk frontend
        $patientData = null;
        if ($patient) {
            $patientData = [
                'id' => $patient->id,
                'fullName' => $patient->nama_lengkap,
                'nik' => $patient->nik,
                'birthDate' => $patient->tanggal_lahir ? \Carbon\Carbon::parse($patient->tanggal_lahir)->format('d F Y') : null,
                'gender' => $patient->jenis_kelamin === 'L' ? 'Laki-laki' : ($patient->jenis_kelamin === 'P' ? 'Perempuan' : null),
                'bloodType' => $patient->golongan_darah,
                'email' => $patient->email,
                'phoneNumber' => $patient->no_hp,
                'address' => $patient->alamat,
                // Raw data for editing
                'raw' => [
                    'nama_lengkap' => $patient->nama_lengkap,
                    'nik' => $patient->nik,
                    'tanggal_lahir' => $patient->tanggal_lahir,
                    'jenis_kelamin' => $patient->jenis_kelamin,
                    'golongan_darah' => $patient->golongan_darah,
                    'email' => $patient->email,
                    'no_hp' => $patient->no_hp,
                    'alamat' => $patient->alamat,
                ]
            ];
        }

        return Inertia::render('pasien/ProfilPasien', [
            'patientName' => $user->name,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung', // Sesuaikan dengan klinik Anda
            'patientData' => $patientData
        ]);
    }
}