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
            // Ambil hanya pasien yang sudah check-in hari ini
            $pasienCheckedIn = Appointment::whereNotNull('checked_in_at')
                ->whereDate('checked_in_at', Carbon::today()) // hanya hari ini
                ->with(['patient']) 
                ->orderBy('checked_in_at', 'asc') // urutkan berdasarkan waktu check-in
                ->get();

            // Debug info
            logger()->info('Jumlah pasien checked in hari ini: ' . $pasienCheckedIn->count());
            
            if ($pasienCheckedIn->count() > 0) {
                logger()->info('Sample checked-in patient:', [
                    'id' => $pasienCheckedIn->first()->id,
                    'patient_name' => $pasienCheckedIn->first()->patient?->nama_lengkap,
                    'checked_in_at' => $pasienCheckedIn->first()->checked_in_at,
                    'status' => $pasienCheckedIn->first()->status,
                ]);
            }

            return Inertia::render('staff/KonfiirmasiPasienStaff', [
                'pasienList' => $pasienCheckedIn,
                'totalCheckedIn' => $pasienCheckedIn->count(),
                'currentDate' => Carbon::today()->format('Y-m-d')
            ]);

        } catch (\Exception $e) {
            logger()->error('Error in KonfirmasiPasienController: ' . $e->getMessage());
            logger()->error('Stack trace: ' . $e->getTraceAsString());
            
            return Inertia::render('staff/KonfiirmasiPasienStaff', [
                'pasienList' => [],
                'totalCheckedIn' => 0,
                'currentDate' => Carbon::today()->format('Y-m-d')
            ]);
        }
    }

    // Method untuk konfirmasi pasien
    public function konfirmasi(Request $request, $appointmentId)
    {
        try {
            $appointment = Appointment::findOrFail($appointmentId);
            
            // Update status menjadi confirmed
            $appointment->update([
                'status' => 'confirmed',
                'confirmed_at' => Carbon::now(),
                'confirmed_by' => auth()->id() // jika ada auth
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil dikonfirmasi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal konfirmasi pasien: ' . $e->getMessage()
            ], 500);
        }
    }
}