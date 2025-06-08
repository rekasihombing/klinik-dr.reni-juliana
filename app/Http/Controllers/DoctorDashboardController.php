<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use Inertia\Inertia;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        // Ambil ID dokter yang sedang login (sesuaikan dengan sistem autentikasi Anda)
        $doctorId = auth()->user()->doctor->id;
        // atau auth()->user()->doctor_id tergantung struktur Anda
        
        // Debug: Log doctor ID
        \Log::info('Doctor ID: ' . $doctorId);
        
        // Ambil semua appointment untuk dokter yang sedang login dengan relasi pasien
        $appointments = Appointment::with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
            ->where('dokter_id', $doctorId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_konsultasi', 'asc')
            ->get();

        // Debug: Log all appointments
        \Log::info('All appointments count: ' . $appointments->count());
        \Log::info('Today date: ' . Carbon::today()->format('Y-m-d'));

        // Ambil appointment hari ini - langsung dari database dengan nomor antrian
        $todayAppointments = Appointment::with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
            ->where('dokter_id', $doctorId)
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('jam_konsultasi', 'asc')
            ->get();

        // Tambahkan nomor antrian untuk appointment hari ini
        $todayAppointments = $todayAppointments->map(function($appointment, $index) {
            $appointment->queue_number = $index + 1; // Nomor antrian dimulai dari 1
            return $appointment;
        });

        // Debug: Log today appointments
        \Log::info('Today appointments count: ' . $todayAppointments->count());
        foreach($todayAppointments as $apt) {
            \Log::info('Appointment date: ' . $apt->tanggal . ', Patient: ' . ($apt->pasien ? $apt->pasien->nama_lengkap : 'No patient') . ', Queue: ' . $apt->queue_number);
        }

        // Ambil appointment berikutnya
        $nextAppointment = Appointment::with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
            ->where('dokter_id', $doctorId)
            ->where('tanggal', '>=', Carbon::now())
            ->where('status', '!=', 'selesai')
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_konsultasi', 'asc')
            ->first();

        // Tambahkan nomor antrian untuk next appointment jika ada
        if ($nextAppointment) {
            // Cari posisi appointment ini dalam antrian hari ini
            $todayAppointmentIds = $todayAppointments->pluck('id')->toArray();
            $queuePosition = array_search($nextAppointment->id, $todayAppointmentIds);
            
            if ($queuePosition !== false) {
                $nextAppointment->queue_number = $queuePosition + 1;
            } else {
                // Jika appointment berikutnya bukan hari ini, set nomor antrian 1
                $nextAppointment->queue_number = 1;
            }
        }

        // Tambahkan nomor antrian untuk semua appointments
        $appointments = $appointments->groupBy(function($appointment) {
            return Carbon::parse($appointment->tanggal)->format('Y-m-d');
        })->map(function($dayAppointments) {
            return $dayAppointments->sortBy('jam_konsultasi')->values()->map(function($appointment, $index) {
                $appointment->queue_number = $index + 1;
                return $appointment;
            });
        })->flatten();

        return Inertia::render('Doctor/Dashboard', [
            'patientName' => auth()->user()->name, 
            'clinicName' => 'Klinik praktek Dr. reni', 
            'appointments' => $appointments,
            'todayAppointments' => $todayAppointments, 
            'nextAppointment' => $nextAppointment,
            'debugInfo' => [
                'doctorId' => $doctorId,
                'todayDate' => Carbon::today()->format('Y-m-d'),
                'appointmentsCount' => $appointments->count(),
                'todayAppointmentsCount' => $todayAppointments->count(),
            ]
        ]);
    }

    // Method untuk melihat detail appointment
    public function showAppointmentDetail($id)
    {
        $appointment = Appointment::with([
                'pasien' => function($query) {
                    $query->select('id', 'nama_lengkap', 'nik', 'tanggal_lahir', 'jenis_kelamin', 'golongan_darah', 'email', 'no_hp', 'alamat');
                },
                'dokter'
            ])
            ->findOrFail($id);

        if ($appointment->dokter_id !== auth()->user()->doctor->id) {
            abort(403, 'Unauthorized');
        }

        // Tambahkan nomor antrian untuk appointment detail
        $appointmentDate = Carbon::parse($appointment->tanggal)->format('Y-m-d');
        $dayAppointments = Appointment::where('dokter_id', auth()->user()->doctor->id)
            ->whereDate('tanggal', $appointmentDate)
            ->orderBy('jam_konsultasi', 'asc')
            ->pluck('id')
            ->toArray();
        
        $queuePosition = array_search($appointment->id, $dayAppointments);
        $appointment->queue_number = $queuePosition !== false ? $queuePosition + 1 : 1;

        return Inertia::render('Doctor/AppointmentDetail', [
            'appointment' => $appointment
        ]);
    }

    // Method untuk update status appointment
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,berlangsung,selesai,dibatalkan'
        ]);

        $appointment = Appointment::findOrFail($id);

        // Pastikan appointment ini milik dokter yang login
        if ($appointment->dokter_id !== auth()->user()->doctor->id) {
            abort(403, 'Unauthorized');
        }

        $appointment->update([
            'status' => $request->status
        ]);

        // Load appointment dengan nomor antrian
        $appointmentDate = Carbon::parse($appointment->tanggal)->format('Y-m-d');
        $dayAppointments = Appointment::where('dokter_id', auth()->user()->doctor->id)
            ->whereDate('tanggal', $appointmentDate)
            ->orderBy('jam_konsultasi', 'asc')
            ->pluck('id')
            ->toArray();
        
        $queuePosition = array_search($appointment->id, $dayAppointments);
        $appointment->queue_number = $queuePosition !== false ? $queuePosition + 1 : 1;

        return response()->json([
            'message' => 'Status appointment berhasil diupdate',
            'appointment' => $appointment->load(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
        ]);
    }

    // Method tambahan untuk mendapatkan nomor antrian real-time
    public function getQueueNumbers($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        $doctorId = auth()->user()->doctor->id;

        $appointments = Appointment::with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
            ->where('dokter_id', $doctorId)
            ->whereDate('tanggal', $targetDate)
            ->orderBy('jam_konsultasi', 'asc')
            ->get()
            ->map(function($appointment, $index) {
                $appointment->queue_number = $index + 1;
                return $appointment;
            });

        return response()->json([
            'appointments' => $appointments,
            'date' => $targetDate->format('Y-m-d'),
            'total_queue' => $appointments->count()
        ]);
    }
}