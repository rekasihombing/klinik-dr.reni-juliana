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

        // Cek kelengkapan data pasien
        $isProfileComplete = $this->checkProfileCompletion($patient);

        return Inertia::render('pasien/Dashboard', [
            'patientName' => $patientName,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'nextAppointment' => $nextAppointment, // Kirim data jadwal konsultasi
            'isProfileComplete' => $isProfileComplete, // Status kelengkapan profil
            'patientProfile' => $patient ? $patient->toArray() : [], // Data profil lengkap
        ]);
    }

    /**
     * Mengecek kelengkapan data profil pasien
     */
    private function checkProfileCompletion($patient)
    {
        if (!$patient) {
            return false;
        }

        // Daftar field yang wajib diisi berdasarkan struktur tabel patients
        $requiredFields = [
            'nama_lengkap',
            'nik',
            'no_hp',
            'alamat',
        ];

        // Cek apakah semua field wajib sudah diisi
        foreach ($requiredFields as $field) {
            $value = $patient->{$field};
            
            // Cek jika field kosong, null, atau hanya whitespace
            if (empty($value) || (is_string($value) && trim($value) === '')) {
                return false;
            }
        }

        // Cek khusus untuk tanggal_lahir - tidak boleh default value dari registrasi
        $birthDate = $patient->tanggal_lahir;
        if (!$birthDate || $birthDate == now()->toDateString()) {
            return false;
        }

        // Cek khusus untuk jenis_kelamin - tidak boleh default 'L' dari registrasi
        // Asumsi user harus memilih sendiri jenis kelaminnya
        $gender = $patient->jenis_kelamin;
        if (!$gender || $gender === 'L') {
            // Jika ingin membolehkan 'L' sebagai pilihan valid, hapus kondisi ini
            // dan hanya cek if (!$gender)
            return false;
        }

        return true;
    }

    /**
     * Alternative method - jika ingin pengecekan yang lebih spesifik
     */
    private function checkProfileCompletionDetailed($patient)
    {
        if (!$patient) {
            return [
                'complete' => false,
                'missing_fields' => ['Semua data profil']
            ];
        }

        $missingFields = [];

        // Cek setiap field dan catat yang kosong
        if (empty($patient->nama_lengkap)) {
            $missingFields[] = 'Nama Lengkap';
        }
        
        if (empty($patient->nik)) {
            $missingFields[] = 'NIK';
        }
        
        if (empty($patient->no_hp)) {
            $missingFields[] = 'Nomor HP';
        }
        
        if (empty($patient->alamat)) {
            $missingFields[] = 'Alamat';
        }

        // Cek tanggal lahir (tidak boleh default dari registrasi)
        $birthDate = $patient->tanggal_lahir;
        if (!$birthDate || $birthDate == now()->toDateString()) {
            $missingFields[] = 'Tanggal Lahir';
        }

        // Cek jenis kelamin (jika ingin memaksa user memilih ulang)
        $gender = $patient->jenis_kelamin;
        if (!$gender || $gender === 'L') {
            $missingFields[] = 'Jenis Kelamin';
        }

        return [
            'complete' => empty($missingFields),
            'missing_fields' => $missingFields
        ];
    }
}