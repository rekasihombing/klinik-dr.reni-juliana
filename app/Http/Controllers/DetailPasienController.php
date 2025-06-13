<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use Inertia\Inertia;

class DetailPasienController extends Controller
{
    public function show($id)
    {
        // Cek apakah patient ada
        $patient = Patient::find($id);

        if (!$patient) {
            abort(404, 'Pasien tidak ditemukan');
        }

        // Ambil janji temu pasien yang aktif atau sesuai kebutuhan
        $appointments = Appointment::where('pasien_id', $id)->get();

        // Kirim data ke Inertia dan Vue
        return Inertia::render('staff/DetailPasien', [
            'patientData' => [
                ['label' => 'No Registrasi', 'value' => $patient->id],
                ['label' => 'Nama', 'value' => $patient->nama_lengkap],
                ['label' => 'NIK', 'value' => $patient->nik],
                ['label' => 'Tanggal Lahir', 'value' => $patient->tanggal_lahir],
                ['label' => 'Jenis Kelamin', 'value' => $patient->jenis_kelamin],
                ['label' => 'Golongan Darah', 'value' => $patient->golongan_darah],
                ['label' => 'Email', 'value' => $patient->email],
                ['label' => 'No HP', 'value' => $patient->no_hp],
                ['label' => 'Alamat', 'value' => $patient->alamat],
            ],
            'appointmentData' => $appointments->map(function ($a) {
                return [
                    'id' => $a->id,
                    'queue_number' => $a->antrian ?? 'Belum tersedia', // Tambahkan nomor antrian
                    'date' => $a->tanggal,
                    'time' => $a->jam_konsultasi,
                    'complaint' => $a->keluhan,
                    'status' => $a->status,
                    'notes' => $a->catatan ?? 'Tidak ada catatan',
                ];
            }),
        ]);
    }
}