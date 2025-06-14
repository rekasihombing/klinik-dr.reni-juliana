<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\RekamMedis;
use Carbon\Carbon;
use Inertia\Inertia;

class StaffDashboardController extends Controller
{
public function index()
{
    try {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        $today = Carbon::today();

        \Log::info('Today date:', ['today' => $today->toDateString()]);
        $totalAppointments = Appointment::count();
        \Log::info('Total appointments in database:', ['total' => $totalAppointments]);
        $allAppointmentsToday = Appointment::whereDate('tanggal', $today)->get();
        \Log::info('All appointments today (no status filter):', [
            'count' => $allAppointmentsToday->count(),
            'appointments' => $allAppointmentsToday->toArray()
        ]);

        $pasienHariIni = Appointment::whereIn('status', ['menunggu', 'dikonfirmasi', 'diproses', 'selesai'])
            ->whereDate('tanggal', $today)
            ->whereNotNull('checked_in_at') // Filter for checked-in patients
            ->with(['pasien' => function($query) {
                $query->select('id', 'nama_lengkap', 'nik', 'tanggal_lahir', 'jenis_kelamin', 'golongan_darah', 'no_hp', 'alamat');
            }])
            ->orderBy('jam_konsultasi', 'asc') // Sort by check-in time
            ->orderBy('created_at', 'asc')
            ->get();

        \Log::info('Query for today appointments:', [
            'date' => $today->toDateString(),
            'statuses' => ['menunggu', 'dikonfirmasi', 'diproses', 'selesai'],
            'count' => $pasienHariIni->count(),
            'raw_query' => Appointment::whereIn('status', ['menunggu', 'dikonfirmasi', 'diproses', 'selesai'])
                ->whereDate('tanggal', $today)
                ->toSql()
        ]);

        $otherStatusAppointments = Appointment::whereDate('tanggal', $today)
            ->whereNotIn('status', ['menunggu', 'dikonfirmasi', 'diproses', 'selesai'])
            ->get();
        \Log::info('Appointments with other status today:', [
            'count' => $otherStatusAppointments->count(),
            'appointments' => $otherStatusAppointments->pluck('status')->toArray()
        ]);

        $pasienHariIniWithQueue = $pasienHariIni->map(function($appointment) {
            $rekamMedis = RekamMedis::where('appointment_id', $appointment->id)->first();
            
            return [
                'id' => $appointment->id,
                'appointment_id' => $appointment->id,
                'rekam_medis_id' => $rekamMedis ? $rekamMedis->id : null,
                'has_rekam_medis' => $rekamMedis ? true : false,
                'no_antrian' => $appointment->getQueueNumber(),
                'nama_pasien' => $appointment->pasien->nama_lengkap ?? 'N/A',
                'waktu' => $appointment->checked_in_at ? Carbon::parse($appointment->checked_in_at)->format('H.i') : 
                           ($appointment->jam_konsultasi ? Carbon::parse($appointment->jam_konsultasi)->format('H.i') : '-'),
                'checked_in_at' => $appointment->checked_in_at ? Carbon::parse($appointment->checked_in_at)->format('H.i') : 'Belum Check-in',
                'status' => $appointment->status,
                'status_display' => $this->getStatusDisplay($appointment->status),
                'registrasi_number' => 'REG - ' . str_pad($appointment->id, 5, '0', STR_PAD_LEFT),
                'nik' => $appointment->pasien->nik ?? 'N/A',
                'tanggal_lahir' => $appointment->pasien->tanggal_lahir ? 
                    Carbon::parse($appointment->pasien->tanggal_lahir)->format('d - m - Y') : 'N/A',
                'jenis_kelamin' => $appointment->pasien->jenis_kelamin ?? 'N/A',
                'golongan_darah' => $appointment->pasien->golongan_darah ?? 'N/A',
                'nomor_hp' => $appointment->pasien->no_hp ?? 'N/A',
                'alamat' => $appointment->pasien->alamat ?? 'N/A',
                'keluhan' => $appointment->keluhan ?? 'N/A',
                'tanggal_appointment' => $appointment->tanggal,
                'waktu_appointment' => Carbon::parse($appointment->jam_konsultasi)->format('H.i'),
                'pasien_id' => $appointment->pasien->id ?? null,
            ];
        });

        $pasienMenungguKonfirmasi = Appointment::where('status', 'menunggu')
            ->whereDate('tanggal', $today)
            ->count();

        $aktivitasMingguan = $this->getWeeklyActivity();

        \Log::info('Final data sent to frontend:', [
            'pasienHariIni_count' => $pasienHariIniWithQueue->count(),
            'totalPasienHariIni' => $pasienHariIni->count(),
            'totalMenungguKonfirmasi' => $pasienMenungguKonfirmasi,
            'today_string' => $today->toDateString(),
            'current_time' => Carbon::now()->toDateTimeString()
        ]);

        return Inertia::render('staff/DashboardStaff', [
            'pasienHariIni' => $pasienHariIniWithQueue,
            'totalPasienHariIni' => $pasienHariIni->count(),
            'totalMenungguKonfirmasi' => $pasienMenungguKonfirmasi,
            'aktivitasMingguan' => $aktivitasMingguan,
            'currentDate' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'currentTime' => Carbon::now()->format('H:i:s'),
            'debug' => [
                'today' => $today->toDateString(),
                'total_appointments' => $totalAppointments,
                'all_today_count' => $allAppointmentsToday->count(),
                'filtered_today_count' => $pasienHariIni->count()
            ]
        ]);
    } catch (\Exception $e) {
        logger()->error('Error in DashboardStaffController: ' . $e->getMessage());
        logger()->error('Stack trace: ' . $e->getTraceAsString());

        return Inertia::render('staff/DashboardStaff', [
            'pasienHariIni' => [],
            'totalPasienHariIni' => 0,
            'totalMenungguKonfirmasi' => 0,
            'aktivitasMingguan' => [],
            'currentDate' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'currentTime' => Carbon::now()->format('H:i:s'),
            'debug' => [
                'error' => $e->getMessage()
            ]
        ]);
    }
}

    // Method untuk debugging - tambahkan route untuk mengecek data
    public function debugAppointments()
    {
        try {
            $today = Carbon::today();
            
            // Cek semua appointment
            $allAppointments = Appointment::with('pasien')->get();
            
            // Cek appointment hari ini
            $todayAppointments = Appointment::whereDate('tanggal', $today)
                ->with('pasien')
                ->get();
            
            // Cek format tanggal di database
            $sampleAppointments = Appointment::take(5)->get(['id', 'tanggal', 'status']);
            
            return response()->json([
                'today' => $today->toDateString(),
                'total_appointments' => $allAppointments->count(),
                'today_appointments' => $todayAppointments->count(),
                'sample_appointments' => $sampleAppointments->toArray(),
                'today_appointments_detail' => $todayAppointments->map(function($app) {
                    return [
                        'id' => $app->id,
                        'tanggal' => $app->tanggal,
                        'status' => $app->status,
                        'pasien' => $app->pasien->nama_lengkap ?? 'No name'
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    private function getStatusDisplay($status)
    {
        switch ($status) {
            case 'menunggu':
                return 'Menunggu Konfirmasi';
            case 'dikonfirmasi':
                return 'Menunggu Antrian';
            case 'diproses':
                return 'Konsultasi Berlangsung';
            case 'selesai':
                return 'Selesai';
            case 'dibatalkan':
                return 'Dibatalkan';
            default:
                return ucfirst($status);
        }
    }

    private function getWeeklyActivity()
    {
        $weeklyData = [];
        $startOfWeek = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $count = Appointment::whereDate('tanggal', $date)
                ->where('status', '!=', 'dibatalkan')
                ->count();

            $weeklyData[] = [
                'day' => $date->isoFormat('dddd'),
                'date' => $date->format('Y-m-d'),
                'count' => $count
            ];
        }

        return $weeklyData;
    }

    public function updateStatus(Request $request, $appointmentId)
    {
        try {
            $appointment = Appointment::findOrFail($appointmentId);

            $appointment->update([
                'status' => $request->status,
                'updated_at' => Carbon::now()
            ]);

            // Regenerasi nomor antrian jika status masih aktif
            if (in_array($request->status, ['menunggu', 'dikonfirmasi', 'diproses'])) {
                Appointment::assignQueueNumbers($appointment->dokter_id, $appointment->tanggal);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status pasien berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage()
            ], 500);
        }
    }
}