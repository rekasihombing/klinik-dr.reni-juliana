<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment; 
use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    public function index()
    {
        // Mendapatkan pengguna yang sedang login
        $user = Auth::user();

        // Mengambil nama pasien dari tabel patients menggunakan relasi
        $patient = $user->patient;
        $patientName = $patient ? $patient->nama_lengkap : 'Pasien Tidak Ditemukan';

        // Ambil jadwal konsultasi berikutnya dari tabel appointments
        $nextAppointment = null;

if ($patient) {
    $nextAppointment = Appointment::where('pasien_id', $patient->id)
        ->whereIn('status', ['menunggu', 'dikonfirmasi']) // hanya status aktif
        ->where('tanggal', '>=', now()->toDateString())
        ->orderBy('tanggal')
        ->orderBy('jam_konsultasi')
        ->first();
}

        return Inertia::render('pasien/Dashboard', [
            'patientName' => $patientName,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'nextAppointment' => $nextAppointment, // Kirim data jadwal konsultasi
        ]);
    }
}
