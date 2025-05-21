<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class SidebarController extends Controller
{
   public function sidebar()
{
    // Mendapatkan pengguna yang sedang login
    $user = Auth::user();

    // Mengambil nama pasien dari tabel patients menggunakan relasi
    $patientName = $user->patient ? $user->patient->nama_lengkap : 'Pasien Tidak Ditemukan';  // Mengambil nama pasien dari tabel patients

    // Mengirimkan data ke frontend menggunakan Inertia
    return Inertia::render('Sidebar', [
        'patientName' => $patientName,  // Kirimkan nama pasien
        'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',  // Nama klinik
    ]);
}
}
