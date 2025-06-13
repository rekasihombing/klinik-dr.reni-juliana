<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DaftarJanjiTemu extends Controller
{
    /**
     * Display a listing of appointments for staff
     */
    public function index(Request $request)
    {
        Log::info('Fetching appointments with filters:', $request->all());

        // Build query with filters
        $query = Appointment::with(['pasien', 'dokter']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            Log::info("Applying search filter: {$search}");
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%');
            });
        }

        // Apply date range filter
        if ($request->filled('date_from')) {
            $date_from = $request->get('date_from');
            Log::info("Applying date_from filter: {$date_from}");
            $query->whereDate('tanggal', '>=', $date_from);
        }

        if ($request->filled('date_to')) {
            $date_to = $request->get('date_to');
            Log::info("Applying date_to filter: {$date_to}");
            $query->whereDate('tanggal', '<=', $date_to);
        }

        // Apply status filter - FIXED VERSION
        if ($request->filled('status')) {
            $status = $request->get('status');
            Log::info("Applying status filter: {$status}");
            
            // Define valid statuses
            $validStatuses = ['menunggu', 'dikonfirmasi', 'diproses', 'selesai', 'dibatalkan'];
            
            if (in_array($status, $validStatuses)) {
                $query->where('status', $status);
                Log::info("Status filter applied successfully");
            } else {
                Log::warning("Invalid status filter received: {$status}");
            }
        }

        // Order by date and time
        $query->orderBy('tanggal', 'asc')->orderBy('jam_konsultasi', 'asc');

        // Get the results
        $appointments = $query->get();

        Log::info('Appointments retrieved:', [
            'count' => $appointments->count(),
            'query_sql' => $query->toSql(),
            'query_bindings' => $query->getBindings()
        ]);

        $formattedAppointments = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'nama' => $appointment->pasien->nama_lengkap ?? 'N/A',
                'antrian' => $appointment->antrian ?? '-',
                'registrasi_number' => 'REG-' . str_pad($appointment->id, 5, '0', STR_PAD_LEFT),
                'tanggal' => $appointment->tanggal ? Carbon::parse($appointment->tanggal)->format('d/m/Y') : 'N/A',
                'waktu' => $appointment->jam_konsultasi ?? 'N/A',
                'keluhan' => $appointment->keluhan ?? '-',
                'status' => $appointment->status ?? 'menunggu',
                'patient_data' => [
                    'nik' => $appointment->pasien->nik ?? 'N/A',
                    'tanggal_lahir' => $appointment->pasien->tanggal_lahir ? Carbon::parse($appointment->pasien->tanggal_lahir)->format('d/m/Y') : 'N/A',
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

        // Debug: Log the actual statuses in the database
        $statusCounts = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
        
        Log::info('Status distribution in database:', $statusCounts);

        return Inertia::render('staff/listjanjitemu', [
            'appointments' => $formattedAppointments,
            'filters' => $request->only(['search', 'date_from', 'date_to', 'status']),
            'total' => $formattedAppointments->count(),
            'stats' => $this->getStatsData(),
            'debug_info' => [
                'status_counts' => $statusCounts,
                'applied_filters' => $request->only(['search', 'date_from', 'date_to', 'status']),
                'total_before_filter' => Appointment::count()
            ]
        ]);
    }

    /**
     * Show detailed information about specific appointment
     */
    public function show($id): Response
    {
        return redirect()->route('staff.appointments.index');
    }

    /**
     * Confirm an appointment
     */
    public function confirm($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            
            if ($appointment->status !== 'menunggu') {
                Log::warning("Cannot confirm appointment {$id}: Invalid status {$appointment->status}");
                return redirect()->back()->with('error', 'Janji temu tidak dapat dikonfirmasi karena status tidak valid.');
            }

            // Update status to confirmed
            $appointment->update([
                'status' => 'dikonfirmasi',
                'confirmed_at' => now(),
            ]);

            // Reassign queue numbers for the day
            Appointment::assignQueueNumbers($appointment->dokter_id, $appointment->tanggal);

            Log::info("Appointment {$id} confirmed successfully");
            return redirect()->back()->with('success', 'Janji temu berhasil dikonfirmasi');
            
        } catch (\Exception $e) {
            Log::error("Error confirming appointment {$id}: {$e->getMessage()}");
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
            
            if ($appointment->status !== 'dikonfirmasi') {
                Log::warning("Cannot complete appointment {$id}: Invalid status {$appointment->status}");
                return redirect()->back()->with('error', 'Janji temu tidak dapat diselesaikan karena status tidak valid.');
            }

            // Update status to completed
            $appointment->update([
                'status' => 'selesai',
                'completed_at' => now(),
            ]);

            Log::info("Appointment {$id} completed successfully");
            return redirect()->back()->with('success', 'Janji temu berhasil diselesaikan');
            
        } catch (\Exception $e) {
            Log::error("Error completing appointment {$id}: {$e->getMessage()}");
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
                    'tanggal' => $appointment->tanggal ? Carbon::parse($appointment->tanggal)->format('d/m/Y') : 'N/A',
                    'waktu' => $appointment->jam_konsultasi ?? 'N/A',
                    'status' => $appointment->status ?? 'menunggu',
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
            'pending_appointments' => Appointment::where('status', 'menunggu')->count(),
            'confirmed_appointments' => Appointment::where('status', 'dikonfirmasi')->count(),
            'processed_appointments' => Appointment::where('status', 'diproses')->count(),
            'completed_appointments' => Appointment::where('status', 'selesai')->count(),
            'cancelled_appointments' => Appointment::where('status', 'dibatalkan')->count(),
        ];
    }

    /**
     * Debug method to check status values in database
     */
    public function debugStatus(Request $request)
    {
        $appointments = Appointment::select(['id', 'status'])
            ->get()
            ->groupBy('status')
            ->map(function ($group) {
                return $group->count();
            });

        return response()->json([
            'status_distribution' => $appointments,
            'all_statuses' => Appointment::distinct('status')->pluck('status'),
            'request_status' => $request->get('status'),
            'sample_appointments' => Appointment::select(['id', 'status'])->limit(10)->get()
        ]);
    }
}