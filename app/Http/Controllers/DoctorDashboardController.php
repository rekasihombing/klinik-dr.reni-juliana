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

        // Ambil appointment hari ini - langsung dari database
        $todayAppointments = Appointment::with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
            ->where('dokter_id', $doctorId)
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('jam_konsultasi', 'asc')
            ->get();

        // Debug: Log today appointments
        \Log::info('Today appointments count: ' . $todayAppointments->count());
        foreach($todayAppointments as $apt) {
            \Log::info('Appointment date: ' . $apt->tanggal . ', Patient: ' . ($apt->pasien ? $apt->pasien->nama_lengkap : 'No patient'));
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
        if ($appointment->dokter_id !== auth()->user()->id) {
            abort(403, 'Unauthorized');
        }

        $appointment->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Status appointment berhasil diupdate',
            'appointment' => $appointment->load(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
        ]);
    }
}