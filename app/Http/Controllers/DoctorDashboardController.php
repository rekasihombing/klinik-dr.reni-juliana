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
        $doctorId = auth()->user()->doctor->id;

        // Fetch all appointments for the doctor
        $appointments = Appointment::with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
            ->where('dokter_id', $doctorId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_konsultasi', 'asc')
            ->get()
            ->map(function($appointment) {
                $appointment->queue_number = $appointment->getQueueNumber();
                return $appointment;
            });

        // Fetch today's appointments, sorted by antrian
        $todayAppointments = Appointment::with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
            ->where('dokter_id', $doctorId)
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('antrian', 'asc') // Sort by queue number
            ->get()
            ->map(function($appointment) {
                $appointment->queue_number = $appointment->getQueueNumber();
                return $appointment;
            });

        // Fetch next appointment
        $nextAppointment = Appointment::with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
            ->where('dokter_id', $doctorId)
            ->where('tanggal', '>=', Carbon::now())
            ->where('status', '!=', 'selesai')
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_konsultasi', 'asc')
            ->first();

        if ($nextAppointment) {
            $nextAppointment->queue_number = $nextAppointment->getQueueNumber();
        }

        \Log::info('Doctor Dashboard Data:', [
            'doctor_id' => $doctorId,
            'today_date' => Carbon::today()->format('Y-m-d'),
            'today_appointments_count' => $todayAppointments->count(),
            'antrian_values' => $todayAppointments->pluck('antrian')->toArray(),
        ]);

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
                'antrianValues' => $todayAppointments->pluck('antrian')->toArray(),
            ]
        ]);
    }

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

        $appointment->queue_number = $appointment->getQueueNumber();

        return Inertia::render('Doctor/AppointmentDetail', [
            'appointment' => $appointment
        ]);
    }

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:menunggu,dikonfirmasi,diproses,selesai,dibatalkan'
    ]);

    $appointment = Appointment::findOrFail($id);

    if ($appointment->dokter_id !== auth()->user()->doctor->id) {
        abort(403, 'Unauthorized');
    }

    $appointment->update([
        'status' => $request->status,
        'updated_at' => Carbon::now(),
    ]);

    // Only reassign queue numbers for new or active appointments without antrian
    if (in_array($request->status, ['menunggu', 'dikonfirmasi', 'diproses']) && !$appointment->antrian) {
        Appointment::assignQueueNumbers($appointment->dokter_id, $appointment->tanggal);
    }

    $appointment->queue_number = $appointment->getQueueNumber();

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully',
        'appointment' => $appointment
    ]);
}

    public function getQueueNumbers($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        $doctorId = auth()->user()->doctor->id;

        $appointments = Appointment::with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
            }])
            ->where('dokter_id', $doctorId)
            ->whereDate('tanggal', $targetDate)
            ->orderBy('antrian', 'asc') // Sort by queue number
            ->get()
            ->map(function($appointment) {
                $appointment->queue_number = $appointment->getQueueNumber();
                return $appointment;
            });

        \Log::info('Get Queue Numbers:', [
            'doctor_id' => $doctorId,
            'date' => $targetDate->format('Y-m-d'),
            'appointments_count' => $appointments->count(),
            'antrian_values' => $appointments->pluck('antrian')->toArray(),
        ]);

        return response()->json([
            'appointments' => $appointments,
            'date' => $targetDate->format('Y-m-d'),
            'total_queue' => $appointments->count()
        ]);
    }
}