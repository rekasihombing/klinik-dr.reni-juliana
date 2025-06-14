<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KonfirmasiPasienStaffController extends Controller
{
public function index()
{
    try {
        $today = Carbon::today();

        // Clear queue numbers only for menunggu appointments
        Appointment::whereDate('tanggal', $today)
            ->where('status', 'menunggu')
            ->update(['antrian' => null]);

        // Fetch checked-in patients
        $pasienCheckedIn = Appointment::whereNotNull('checked_in_at')
            ->whereDate('checked_in_at', $today)
            ->where('dibuat_oleh', 'pasien')
            ->where('status', 'menunggu')
            ->with('pasien')
            ->orderBy('checked_in_at', 'asc')
            ->get();

        \Log::info('Pasien Checked In for Confirmation:', [
            'date' => $today->toDateString(),
            'count' => $pasienCheckedIn->count(),
            'appointments' => $pasienCheckedIn->toArray(),
        ]);

        return Inertia::render('staff/KonfiirmasiPasienStaff', [
            'pasienList' => $pasienCheckedIn,
            'totalCheckedIn' => $pasienCheckedIn->count(),
            'currentDate' => $today->format('Y-m-d')
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in KonfirmasiPasienStaffController: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return Inertia::render('staff/KonfiirmasiPasienStaff', [
            'pasienList' => [],
            'totalCheckedIn' => 0,
            'currentDate' => Carbon::today()->format('Y-m-d')
        ]);
    }
}

    public function konfirmasi(Request $request, $appointmentId)
    {
        return DB::transaction(function () use ($appointmentId) {
            try {
                $appointment = Appointment::findOrFail($appointmentId);

                $appointment->update([
                    'status' => 'dikonfirmasi',
                    'checked_in_at' => Carbon::now(),
                    'confirmed_by' => auth()->id() ?? null
                ]);

                // Assign queue number after confirmation
                Appointment::assignQueueNumbers($appointment->dokter_id, $appointment->tanggal);

                \Log::info('Appointment confirmed:', [
                    'appointment_id' => $appointmentId,
                    'queue_number' => $appointment->getQueueNumber(),
                    'checked_in_at' => $appointment->checked_in_at,
                    'today' => Carbon::today()->toDateString(),
                    'confirmed_count' => Appointment::whereDate('tanggal', Carbon::today())
                        ->where('status', 'dikonfirmasi')
                        ->whereNotNull('checked_in_at')
                        ->count(),
                    'confirmed_appointments' => Appointment::whereDate('tanggal', Carbon::today())
                        ->where('status', 'dikonfirmasi')
                        ->whereNotNull('checked_in_at')
                        ->orderBy('checked_in_at', 'asc')
                        ->get()
                        ->toArray(),
                ]);

                return Inertia::location('/dashboardstaff');
            } catch (\Exception $e) {
                \Log::error('Error confirming appointment:', [
                    'appointment_id' => $appointmentId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return Inertia::render('ErrorPage', [
                    'message' => 'Gagal konfirmasi pasien: ' . $e->getMessage()
                ]);
            }
        });
    }
}