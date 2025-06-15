<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Inertia\Inertia;

class JanjiTemuController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('pasien')->get();

        // Transform the data to include patient name directly in the appointment object
        $appointmentsWithPatientName = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'pasien_id' => $appointment->pasien_id,
                'dokter_id' => $appointment->dokter_id,
                'tanggal' => $appointment->tanggal,
                'jam_konsultasi' => $appointment->jam_konsultasi,
                'keluhan' => $appointment->keluhan,
                'status' => $appointment->status,
                'antrian' => $appointment->antrian,
                'checked_in_at' => $appointment->checked_in_at,
                'nama_lengkap' => $appointment->pasien ? $appointment->pasien->nama_lengkap : 'Tidak diketahui',
                'created_at' => $appointment->created_at,
                'updated_at' => $appointment->updated_at,
            ];
        });

        return Inertia::render('Doctor/JanjiTemuPasien', [
            'appointments' => $appointmentsWithPatientName,
        ]);
    }
}