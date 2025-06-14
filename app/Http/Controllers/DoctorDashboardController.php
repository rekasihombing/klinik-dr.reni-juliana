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

    // Fetch today's appointments
    $todayAppointments = Appointment::with(['pasien' => function($query) {
            $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
        }])
        ->where('dokter_id', $doctorId)
        ->whereDate('tanggal', Carbon::today())
        ->orderBy('jam_konsultasi', 'asc')
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
    // ... validasi dan update ...
    
    $appointment->update([
        'status' => $request->status
    ]);

    // HAPUS BARIS INI:
    // if (in_array($request->status, ['menunggu', 'dikonfirmasi', 'diproses'])) {
    //     Appointment::assignQueueNumbers($appointment->dokter_id, $appointment->tanggal);
    // }

    $appointment->queue_number = $appointment->getQueueNumber();
    
    // ... return response ...
}

public function getQueueNumbers($date = null)
{
    $targetDate = $date ? Carbon::parse($date) : Carbon::today();
    $doctorId = auth()->user()->doctor->id;

    // HAPUS BARIS INI:
    // Appointment::assignQueueNumbers($doctorId, $targetDate->format('Y-m-d'));

    $appointments = Appointment::with(['pasien' => function($query) {
            $query->select('id', 'nama_lengkap', 'nik', 'no_hp', 'email');
        }])
        ->where('dokter_id', $doctorId)
        ->whereDate('tanggal', $targetDate)
        ->orderBy('jam_konsultasi', 'asc')
        ->get()
        ->map(function($appointment) {
            $appointment->queue_number = $appointment->getQueueNumber();
            return $appointment;
        });

    return response()->json([
        'appointments' => $appointments,
        'date' => $targetDate->format('Y-m-d'),
        'total_queue' => $appointments->count()
    ]);
}
}