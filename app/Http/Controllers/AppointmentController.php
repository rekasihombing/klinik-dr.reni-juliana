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
        // dd(Auth::user());     
        $user = Auth::user();     
        $patient = $user->patient;     
        
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
            return redirect()->back()->withErrors(['error' => 'Data pasien tidak ditemukan']);
        }

        // Ambil semua riwayat appointment milik pasien ini, urutkan berdasarkan tanggal terbaru
        $appointments = Appointment::where('pasien_id', $patient->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_konsultasi', 'desc')
            ->get()
            ->map(function ($appointment, $index) {
                // Generate nomor antrian berdasarkan urutan pada hari yang sama
                $queueNumber = $this->generateQueueNumber($appointment);
                
                return [
                    'id' => $appointment->id,
                    'date' => Carbon::parse($appointment->tanggal)->format('d-m-Y'),
                    'time' => Carbon::parse($appointment->jam_konsultasi)->format('H.i'),
                    'queueNumber' => $queueNumber,
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
            'clinicName' => 'Klinik Kesehatan', // Sesuaikan dengan nama klinik Anda
            'appointments' => $appointments
        ]);
    }

    // Helper method untuk generate nomor antrian
    private function generateQueueNumber($appointment)
    {
        // Cari posisi appointment ini berdasarkan waktu pada tanggal yang sama
        $sameDataAppointments = Appointment::where('dokter_id', $appointment->dokter_id)
            ->where('tanggal', $appointment->tanggal)
            ->whereIn('status', ['menunggu', 'dikonfirmasi', 'selesai']) // Tidak termasuk yang dibatalkan
            ->orderBy('jam_konsultasi')
            ->orderBy('created_at')
            ->get();

        $position = $sameDataAppointments->search(function ($item) use ($appointment) {
            return $item->id === $appointment->id;
        });

        // Jika tidak ditemukan atau dibatalkan, return "-"
        if ($position === false || $appointment->status === 'dibatalkan') {
            return '-';
        }

        // Generate format antrian: A01, A02, dst
        return 'A' . str_pad($position + 1, 2, '0', STR_PAD_LEFT);
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

        // Cek jika sudah ada janji temu aktif
        $existingAppointment = Appointment::where('pasien_id', $patient->id)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->first();

        if ($existingAppointment) {
            return redirect()->back()->withErrors(['error' => 'Anda sudah memiliki janji temu yang masih aktif.']);
        }

        // Tambahan validasi: Cek apakah slot waktu masih tersedia
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

        // Tambahan validasi: Cek apakah waktu yang dipilih sudah lewat
        $appointmentDateTime = Carbon::parse($validated['tanggal'] . ' ' . $validated['jam_konsultasi']);
        if ($appointmentDateTime->isPast()) {
            return redirect()->back()->withErrors([
                'jam_konsultasi' => 'Tidak dapat membuat janji temu untuk waktu yang sudah lewat.'
            ])->withInput();
        }

        $validated['pasien_id'] = $patient->id;
        $validated['dibuat_oleh'] = 'pasien';
        $validated['dokter_id'] = $doctorId;

        $appointment = Appointment::create($validated);

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

            // Cari appointment berdasarkan ID dan pasien_id
            $appointment = Appointment::where('id', $appointmentId)
                                    ->where('pasien_id', $patient->id)
                                    ->first();

            if (!$appointment) {
                return back()->withErrors(['error' => 'Janji temu tidak ditemukan']);
            }

            // Cek apakah appointment memang sudah lewat
            $appointmentDateTime = \Carbon\Carbon::parse($appointment->tanggal . ' ' . $appointment->jam_konsultasi);
            $now = \Carbon\Carbon::now();

            // Update status menjadi 'dibatalkan' (sesuai enum yang sudah ada)
            $appointment->update([
                'status' => 'dibatalkan'
            ]);

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

            // Simpan jam check-in sekarang
            $appointment->checked_in_at = Carbon::now();
            $appointment->save();

            return redirect()->back()->with('success', 'Check-in berhasil!');

        } catch (\Exception $e) {
            \Log::error('Error during check-in: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat check-in']);
        }
    }
}