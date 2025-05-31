<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            // PERBAIKAN: Cari appointment yang akan datang terlebih dahulu
            $nextAppointment = Appointment::where('pasien_id', $patient->id)
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->whereRaw('CONCAT(tanggal, " ", jam_konsultasi) > NOW()') // Appointment masa depan
                ->orderBy('tanggal')
                ->orderBy('jam_konsultasi')
                ->first();

            // Jika tidak ada appointment masa depan, cari yang terlewat (dalam 24 jam terakhir)
            if (!$nextAppointment) {
                $nextAppointment = Appointment::where('pasien_id', $patient->id)
                    ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                    ->whereRaw('CONCAT(tanggal, " ", jam_konsultasi) < NOW()') // Appointment yang sudah lewat
                    ->orderBy('tanggal', 'desc') // Ambil yang paling baru lewat
                    ->orderBy('jam_konsultasi', 'desc')
                    ->first();
            }
        }

        // Cek kelengkapan data pasien
        $isProfileComplete = $this->checkProfileCompletion($patient);

        return Inertia::render('pasien/Dashboard', [
            'patientName' => $patientName,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'nextAppointment' => $nextAppointment ? [
                ...$nextAppointment->toArray(),
                // Hilangkan field isAppointmentPassed karena sudah dihitung di frontend
            ] : null,
            'isProfileComplete' => $isProfileComplete,
            'patientProfile' => $patient ? $patient->toArray() : [],
        ]);
    }

    /**
     * ALTERNATIF: Jika ingin lebih explicit dengan Carbon
     */
    public function indexAlternative()
    {
        $user = Auth::user();
        $patient = $user->patient;
        $patientName = $patient ? $patient->nama_lengkap : 'Pasien Tidak Ditemukan';

        $nextAppointment = null;

        if ($patient) {
            $now = Carbon::now();
            
            // Cari appointment yang akan datang
            $nextAppointment = Appointment::where('pasien_id', $patient->id)
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->where(function($query) use ($now) {
                    // Tanggal di masa depan
                    $query->where('tanggal', '>', $now->toDateString())
                          // Atau tanggal hari ini tapi jam belum lewat
                          ->orWhere(function($q) use ($now) {
                              $q->where('tanggal', '=', $now->toDateString())
                                ->where('jam_konsultasi', '>', $now->toTimeString());
                          });
                })
                ->orderBy('tanggal')
                ->orderBy('jam_konsultasi')
                ->first();

            // Jika tidak ada appointment masa depan, cari yang terlewat
            if (!$nextAppointment) {
                $yesterday = $now->copy()->subDay();
                
                $nextAppointment = Appointment::where('pasien_id', $patient->id)
                    ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                    ->where(function($query) use ($now, $yesterday) {
                        // Tanggal kemarin atau hari ini tapi jam sudah lewat
                        $query->where(function($q) use ($yesterday, $now) {
                                  $q->where('tanggal', '>=', $yesterday->toDateString())
                                    ->where('tanggal', '<', $now->toDateString());
                              })
                              ->orWhere(function($q) use ($now) {
                                  $q->where('tanggal', '=', $now->toDateString())
                                    ->where('jam_konsultasi', '<', $now->toTimeString());
                              });
                    })
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('jam_konsultasi', 'desc')
                    ->first();
            }
        }

        $isProfileComplete = $this->checkProfileCompletion($patient);

        return Inertia::render('pasien/Dashboard', [
            'patientName' => $patientName,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'nextAppointment' => $nextAppointment,
            'isProfileComplete' => $isProfileComplete,
            'patientProfile' => $patient ? $patient->toArray() : [],
        ]);
    }

    /**
     * VERSI DENGAN DEBUGGING - Gunakan ini untuk troubleshooting
     */
    public function indexDebug()
    {
        $user = Auth::user();
        $patient = $user->patient;
        $patientName = $patient ? $patient->nama_lengkap : 'Pasien Tidak Ditemukan';

        $nextAppointment = null;

        if ($patient) {
            // Log untuk debugging
            \Log::info('Looking for appointments for patient ID: ' . $patient->id);
            \Log::info('Current time: ' . now());

            // Cek semua appointment untuk patient ini
            $allAppointments = Appointment::where('pasien_id', $patient->id)
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->get();
            
            \Log::info('All appointments found: ', $allAppointments->toArray());

            // Appointment masa depan
            $futureAppointment = Appointment::where('pasien_id', $patient->id)
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->whereRaw('CONCAT(tanggal, " ", jam_konsultasi) > NOW()')
                ->orderBy('tanggal')
                ->orderBy('jam_konsultasi')
                ->first();

            \Log::info('Future appointment: ', $futureAppointment ? $futureAppointment->toArray() : 'null');

            if ($futureAppointment) {
                $nextAppointment = $futureAppointment; 
            } else {
                // Appointment yang terlewat
                $passedAppointment = Appointment::where('pasien_id', $patient->id)
                    ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                    ->whereRaw('CONCAT(tanggal, " ", jam_konsultasi) < NOW()')
                    ->whereRaw('CONCAT(tanggal, " ", jam_konsultasi) >= DATE_SUB(NOW()')
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('jam_konsultasi', 'desc')
                    ->first();

                \Log::info('Passed appointment: ', $passedAppointment ? $passedAppointment->toArray() : 'null');
                
                $nextAppointment = $passedAppointment;
            }

            \Log::info('Final next appointment: ', $nextAppointment ? $nextAppointment->toArray() : 'null');
        }

        $isProfileComplete = $this->checkProfileCompletion($patient);

        return Inertia::render('pasien/Dashboard', [
            'patientName' => $patientName,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'nextAppointment' => $nextAppointment,
            'isProfileComplete' => $isProfileComplete,
            'patientProfile' => $patient ? $patient->toArray() : [],
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
        $gender = $patient->jenis_kelamin;
        if (!$gender || $gender === 'L') {
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