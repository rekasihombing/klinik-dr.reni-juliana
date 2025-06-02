<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Inertia\Inertia;
use Carbon\Carbon;

class KonfirmasiPasienStaffController extends Controller
{
    public function index()
    {
        try {
            // Ambil pasien hari ini yang sudah check-in dan statusnya menunggu
            $pasienCheckedIn = Appointment::whereNotNull('checked_in_at')
                ->whereDate('checked_in_at', Carbon::today())
                ->where('dibuat_oleh', 'pasien')
                ->where('status', 'menunggu')  // hanya status menunggu
                ->with('pasien')
                ->orderBy('checked_in_at', 'asc')
                ->get();

            return Inertia::render('staff/KonfiirmasiPasienStaff', [
                'pasienList' => $pasienCheckedIn,
                'totalCheckedIn' => $pasienCheckedIn->count(),
                'currentDate' => Carbon::today()->format('Y-m-d')
            ]);
        } catch (\Exception $e) {
            logger()->error('Error in KonfirmasiPasienStaffController: ' . $e->getMessage());
            logger()->error('Stack trace: ' . $e->getTraceAsString());
            return Inertia::render('staff/KonfiirmasiPasienStaff', [
                'pasienList' => [],
                'totalCheckedIn' => 0,
                'currentDate' => Carbon::today()->format('Y-m-d')
            ]);
        }
    }


public function konfirmasi(Request $request, $appointmentId)
{
    try {
        $appointment = Appointment::findOrFail($appointmentId);

        $appointment->update([
            'status' => 'dikonfirmasi',
            'confirmed_at' => Carbon::now(),
            'confirmed_by' => auth()->id() ?? null
        ]);

        // redirect via Inertia ke dashboard atau halaman pasien hari ini
        return Inertia::location('/dashboardstaff');  // redirect
        // atau kalau mau render langsung page
        // return Inertia::render('staff/DashboardStaff');

    } catch (\Exception $e) {
        return Inertia::render('ErrorPage', [
            'message' => 'Gagal konfirmasi pasien: ' . $e->getMessage()
        ]);
    }
}

}
