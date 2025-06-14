<?php  

namespace App\Http\Controllers;  

use Illuminate\Http\Request; 
use App\Models\Appointment; 
use App\Models\User; 
use App\Models\Patient; 
use Illuminate\Support\Facades\Auth; 
use Inertia\Inertia;  
use Carbon\Carbon;

class AppointmentController extends Controller 
{ 
    public function create() 
    {      
        $user = Auth::user();     
        $patient = $user->patient;     
        
        if (!$patient) {
            return redirect()->back()->with('error', 'Data pasien tidak ditemukan.');
        }

        // VALIDASI PROFIL - WAJIB SEBELUM BUAT JANJI TEMU
        if (!$this->isProfileComplete($patient)) {
            return redirect()->route('datapasien')
                ->with('error', 'Anda harus melengkapi data profil terlebih dahulu sebelum membuat janji temu.');
        }
        
        // Ambil dokter_id yang akan digunakan (sesuai dengan logic di store method)
        $doctorId = 1; // Sesuai dengan yang ada di store method
        
        // Ambil semua appointment yang sudah ada untuk dokter ini
        // Hanya ambil yang statusnya aktif (bukan dibatalkan atau selesai)
        $existingAppointments = Appointment::where('dokter_id', $doctorId)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->where(function($query) {
                // Hanya ambil appointment yang belum lewat atau hari ini
                $query->where('tanggal', '>', now()->format('Y-m-d'))
                      ->orWhere(function($q) {
                          $q->where('tanggal', '=', now()->format('Y-m-d'))
                            ->where('jam_konsultasi', '>=', now()->format('H:i'));
                      });
            })
            ->select('tanggal', 'jam_konsultasi')
            ->get()
            ->toArray();
        
        return Inertia::render('pasien/JanjiTemu', [     
            'patientName' => $patient ? $patient->nama_lengkap : 'Nama Tidak Ditemukan',     
            'patientId' => $patient ? $patient->id : null,
            'doctorId' => $doctorId,
            'existingAppointments' => $existingAppointments, // Tambahan untuk cek availability
        ]); 
    }   
    
    // Method baru untuk menampilkan riwayat janji temu
   public function history()
{
    $user = Auth::user();
    $patient = $user->patient;

    if (!$patient) {
        // Render halaman dengan modal untuk data pasien tidak ditemukan
        return Inertia::render('pasien/RiwayatJanjiTemu', [
            'patientName' => null,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'appointments' => [],
            'showPatientDataModal' => true, // Flag untuk menampilkan modal
            'modalType' => 'missing_patient_data' // Tipe modal
        ]);
    }

    // VALIDASI PROFIL JUGA UNTUK RIWAYAT
    if (!$this->isProfileComplete($patient)) {
        // Render halaman dengan modal untuk profil tidak lengkap
        return Inertia::render('pasien/RiwayatJanjiTemu', [
            'patientName' => $patient->nama_lengkap,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'appointments' => [],
            'showPatientDataModal' => true, // Flag untuk menampilkan modal
            'modalType' => 'incomplete_profile' // Tipe modal
        ]);
    }

    // Ambil semua riwayat appointment milik pasien ini, urutkan berdasarkan tanggal terbaru
    $appointments = Appointment::where('pasien_id', $patient->id)
        ->orderBy('tanggal', 'desc')
        ->orderBy('jam_konsultasi', 'desc')
        ->get()
        ->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'date' => Carbon::parse($appointment->tanggal)->format('d-m-Y'),
                'time' => Carbon::parse($appointment->jam_konsultasi)->format('H.i'),
                'queueNumber' => $appointment->getQueueNumber(),
                'status' => $this->getStatusLabel($appointment->status),
                'keluhan' => $appointment->keluhan ?? '-',
                'originalStatus' => $appointment->status,
                'createdBy' => $appointment->dibuat_oleh,
                'checkedInAt' => $appointment->checked_in_at ? Carbon::parse($appointment->checked_in_at)->format('d-m-Y H:i') : null
            ];
        })
        ->toArray();

    return Inertia::render('pasien/RiwayatJanjiTemu', [
        'patientName' => $patient->nama_lengkap,
        'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
        'appointments' => $appointments,
        'showPatientDataModal' => false
    ]);
}

    // Helper method untuk mapping status
    private function getStatusLabel($status)
    {
        $statusMap = [
            'menunggu' => 'Menunggu',
            'dikonfirmasi' => 'Dikonfirmasi',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $statusMap[$status] ?? ucfirst($status);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jam_konsultasi' => 'required|date_format:H:i',
            'keluhan' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return redirect()->back()->withErrors(['pasien_id' => 'Data pasien tidak ditemukan untuk user ini.']);
        }

        // Check for active appointments
        $existingAppointment = Appointment::where('pasien_id', $patient->id)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->first();

        if ($existingAppointment) {
            return redirect()->back()->withErrors(['error' => 'Anda sudah memiliki janji temu yang masih aktif.']);
        }

        // Check if slot is taken
        $doctorId = 1;
        $slotTaken = Appointment::where('dokter_id', $doctorId)
            ->where('tanggal', $validated['tanggal'])
            ->where('jam_konsultasi', $validated['jam_konsultasi'])
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->exists();

        if ($slotTaken) {
            return redirect()->back()->withErrors([
                'jam_konsultasi' => 'Waktu tersebut sudah dipesan oleh pasien lain.'
            ])->withInput();
        }

        // Check if appointment time is in the past
        $appointmentDateTime = Carbon::parse($validated['tanggal'] . ' ' . $validated['jam_konsultasi']);
        if ($appointmentDateTime->isPast()) {
            return redirect()->back()->withErrors([
                'jam_konsultasi' => 'Tidak dapat membuat janji temu untuk waktu yang sudah lewat.'
            ])->withInput();
        }

        $validated['pasien_id'] = $patient->id;
        $validated['dibuat_oleh'] = 'pasien';
        $validated['dokter_id'] = $doctorId;

        // Create appointment
        $appointment = Appointment::create($validated);

        // Assign queue numbers for the day
        Appointment::assignQueueNumbers($doctorId, $validated['tanggal']);

        return redirect()->route('dashboard')->with('success', 'Janji temu berhasil dibuat!');
    }

    /**
     * Cancel appointment yang sudah lewat
     */
    public function cancelAppointment(Request $request)
    {
        try {
            $request->validate([
                'appointment_id' => 'required|integer|exists:appointments,id'
            ]);

            $appointmentId = $request->appointment_id;
            $user = Auth::user();
            $patient = $user->patient;

            if (!$patient) {
                return back()->withErrors(['error' => 'Data pasien tidak ditemukan']);
            }

            $appointment = Appointment::where('id', $appointmentId)
                ->where('pasien_id', $patient->id)
                ->first();

            if (!$appointment) {
                return back()->withErrors(['error' => 'Janji temu tidak ditemukan']);
            }

            $appointment->update([
                'status' => 'dibatalkan'
                // Tidak mengosongkan antrian: 'antrian' => null dihapus
            ]);

            // Regenerasi nomor antrian untuk hari yang sama
            Appointment::assignQueueNumbers($appointment->dokter_id, $appointment->tanggal);

            return back()->with('success', 'Janji temu berhasil dibatalkan');

        } catch (\Exception $e) {
            \Log::error('Error cancelling appointment: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membatalkan janji temu']);
        }
    }

    public function checkIn(Request $request) 
    {
        try {
            $request->validate([
                'appointment_id' => 'required|exists:appointments,id',
            ]);

            $user = Auth::user();
            $patient = $user->patient;

            if (!$patient) {
                return back()->withErrors(['error' => 'Data pasien tidak ditemukan']);
            }

            // VALIDASI PROFIL JUGA UNTUK CHECK-IN
            if (!$this->isProfileComplete($patient)) {
                return redirect()->route('datapasien')
                    ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum check-in.');
            }

            // Cari appointment berdasarkan ID dan pastikan milik pasien yang login
            $appointment = Appointment::where('id', $request->appointment_id)
                                    ->where('pasien_id', $patient->id)
                                    ->first();

            if (!$appointment) {
                return back()->withErrors(['error' => 'Janji temu tidak ditemukan']);
            }

            // Cek apakah sudah check-in sebelumnya
            if ($appointment->checked_in_at) {
                return back()->withErrors(['error' => 'Anda sudah melakukan check-in sebelumnya']);
            }

            // Cek apakah hari ini adalah hari appointment
            if (!$this->canCheckInToday($appointment->tanggal)) {
                return back()->withErrors(['error' => 'Check-in hanya dapat dilakukan pada hari appointment Anda']);
            }

            // Simpan jam check-in sekarang
            $appointment->checked_in_at = Carbon::now();
            $appointment->save();

            return redirect()->back()->with('success', 'Check-in berhasil!');

        } catch (\Exception $e) {
            \Log::error('Error during check-in: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat check-in']);
        }
    }

    public function mulaiKonsultasi($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'diproses';
        $appointment->save();

        // Regenerasi nomor antrian
        Appointment::assignQueueNumbers($appointment->dokter_id, $appointment->tanggal);

        return back()->with('success', 'Status pasien diubah menjadi Diproses.');
    }

    /**
     * Cek apakah profil sudah lengkap
     */
    private function isProfileComplete($patient)
    {
        if (!$patient) {
            return false;
        }

        // Field-field yang wajib diisi
        $requiredFields = [
            'nama_lengkap',
            'nik',
            'no_hp', 
            'alamat',
            'tanggal_lahir',
            'jenis_kelamin'
        ];

        // Cek setiap field
        foreach ($requiredFields as $field) {
            $value = $patient->{$field};
            
            // Jika kosong atau hanya whitespace
            if (empty($value) || (is_string($value) && trim($value) === '')) {
                return false;
            }
        }

        // Validasi khusus tanggal lahir (tidak boleh hari ini = default registrasi)
        if ($patient->tanggal_lahir == now()->toDateString()) {
            return false;
        }

        // Validasi khusus jenis kelamin (tidak boleh default 'L')
        if ($patient->jenis_kelamin === 'L') {
            return false;
        }

        return true;
    }

    /**
     * Cek apakah bisa check-in hari ini
     */
    private function canCheckInToday($appointmentDate)
    {
        $today = now()->toDateString();
        return $appointmentDate === $today;
    }
}