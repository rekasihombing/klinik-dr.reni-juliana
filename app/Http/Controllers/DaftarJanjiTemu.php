<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class DaftarJanjiTemu extends Controller
{
    /**
     * Display a listing of appointments for staff
     */
    public function index(Request $request)
    {
        // Build query with filters
        $query = Appointment::with(['pasien', 'dokter']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%');
            });
        }

        // Apply date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->get('date_to'));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Order by date and time
        $query->orderBy('tanggal', 'asc')->orderBy('jam_konsultasi', 'asc');

        $appointments = $query->get();

        $formattedAppointments = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'nama' => $appointment->pasien->nama_lengkap ?? 'N/A',
                'antrian' => 'A' . str_pad($appointment->id, 3, '0', STR_PAD_LEFT),
                'registrasi_number' => 'REG-' . str_pad($appointment->id, 5, '0', STR_PAD_LEFT),
                'tanggal' => $appointment->tanggal ?? 'N/A',
                'waktu' => $appointment->jam_konsultasi ?? 'N/A',
                'keluhan' => $appointment->keluhan ?? '-',
                'status' => $appointment->status ?? 'pending',
                // Include patient data for detail modal
                'patient_data' => [
                    'nik' => $appointment->pasien->nik ?? 'N/A',
                    'tanggal_lahir' => $appointment->pasien->tanggal_lahir ?? 'N/A',
                    'jenis_kelamin' => $appointment->pasien->jenis_kelamin ?? 'N/A',
                    'golongan_darah' => $appointment->pasien->golongan_darah ?? 'N/A',
                    'email' => $appointment->pasien->email ?? 'N/A',
                    'no_hp' => $appointment->pasien->no_hp ?? 'N/A',
                    'alamat' => $appointment->pasien->alamat ?? 'N/A',
                    'dokter_nama' => $appointment->dokter->nama ?? 'N/A',
                ],
                'dibuat_tanggal' => $appointment->created_at ? $appointment->created_at->format('d/m/Y H:i') : 'N/A',
                'checked_in_at' => $appointment->checked_in_at ? Carbon::parse($appointment->checked_in_at)->format('d/m/Y H:i') : null,
            ];
        });

        return Inertia::render('staff/listjanjitemu', [
            'appointments' => $formattedAppointments,
            'filters' => $request->only(['search', 'date_from', 'date_to', 'status']),
            'total' => $formattedAppointments->count(),
            'stats' => $this->getStatsData(),
        ]);
    }

    /**
     * Show detailed information about specific appointment
     * This method is not needed anymore since detail data is included in index
     */
    public function show($id): Response
    {
        // Redirect to index page - detail will be handled by frontend
        return redirect()->route('staff.appointments.index');
    }

    /**
     * Confirm an appointment
     */
    public function confirm($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            
            // Update status to confirmed
            $appointment->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Janji temu berhasil dikonfirmasi');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengkonfirmasi janji temu');
        }
    }

    /**
     * Complete an appointment
     */
    public function complete($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            
            // Update status to completed
            $appointment->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Janji temu berhasil diselesaikan');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyelesaikan janji temu');
        }
    }

    /**
     * Get appointment statistics for dashboard
     */
    public function dashboard(): Response
    {
        $stats = $this->getStatsData();
        
        // Get recent appointments for dashboard
        $recentAppointments = Appointment::with(['pasien', 'dokter'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'nama' => $appointment->pasien->nama_lengkap ?? 'N/A',
                    'tanggal' => $appointment->tanggal ? $appointment->tanggal->format('d/m/Y') : 'N/A',
                    'waktu' => $appointment->jam_konsultasi ?? 'N/A',
                    'status' => $appointment->status ?? 'pending',
                    'dokter_nama' => $appointment->dokter->nama ?? 'N/A',
                ];
            });

        return Inertia::render('staff/Dashboard', [
            'stats' => $stats,
            'recentAppointments' => $recentAppointments
        ]);
    }

    /**
     * Get statistics data
     */
    private function getStatsData(): array
    {
        $today = Carbon::today();
        
        return [
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::whereDate('tanggal', $today)->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
            'cancelled_appointments' => Appointment::where('status', 'cancelled')->count(),
        ];
    }
}