<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use Inertia\Inertia;

class StaffDashboardController extends Controller
{
    public function index()
    {
        try {
            // Ambil pasien yang telah dikonfirmasi hari ini
                $pasienHariIni = Appointment::whereIn('status', ['dikonfirmasi', 'selesai', 'diproses'])
                    ->whereDate('tanggal', Carbon::today())
                    ->with('pasien')
                    ->get();

            // Generate nomor antrian untuk pasien yang dikonfirmasi
            $pasienHariIniWithQueue = $pasienHariIni->map(function($appointment, $index) {
                return [
                    'id' => $appointment->id,
                    'no_antrian' => 'A' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                    'nama_pasien' => $appointment->pasien->nama_lengkap ?? 'N/A',
                    'waktu' => $appointment->checked_in_at ? Carbon::parse($appointment->checked_in_at)->format('H.i') : '-',
                    'status' => $appointment->status, // Status asli dari database
                    'status_display' => $this->getStatusDisplay($appointment->status), // Untuk tampilan
                    'registrasi_number' => 'REG - ' . str_pad($appointment->id, 5, '0', STR_PAD_LEFT),
                    'nik' => $appointment->pasien->nik ?? 'N/A',
                    'tanggal_lahir' => $appointment->pasien->tanggal_lahir ? 
                        Carbon::parse($appointment->pasien->tanggal_lahir)->format('d - m - Y') : 'N/A',
                    'jenis_kelamin' => $appointment->pasien->jenis_kelamin ?? 'N/A',
                    'golongan_darah' => $appointment->pasien->golongan_darah ?? 'N/A',
                    'nomor_hp' => $appointment->pasien->nomor_hp ?? 'N/A',
                    'alamat' => $appointment->pasien->alamat ?? 'N/A',
                    'keluhan' => $appointment->keluhan ?? 'N/A'
                ];
            });

            // Hitung statistik pasien menunggu konfirmasi hari ini
            $pasienMenungguKonfirmasi = Appointment::where('status', 'menunggu')
                ->whereDate('tanggal', Carbon::today())
                ->count();

            // Data untuk chart mingguan (opsional - bisa ditambahkan nanti)
            $aktivitasMingguan = $this->getWeeklyActivity();

            return Inertia::render('staff/DashboardStaff', [
                'pasienHariIni' => $pasienHariIniWithQueue,
                'totalPasienHariIni' => $pasienHariIni->count(),
                'totalMenungguKonfirmasi' => $pasienMenungguKonfirmasi,
                'aktivitasMingguan' => $aktivitasMingguan,
                'currentDate' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                'currentTime' => Carbon::now()->format('H : i : s')
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
                'currentTime' => Carbon::now()->format('H : i : s')
            ]);
        }
    }

    private function getStatusDisplay($status)
    {
        switch ($status) {
            case 'dikonfirmasi':
                return 'Menunggu Antrian';
            case 'diproses':
                return 'Konsultasi Berlangsung';
            case 'selesai':
                return 'Selesai';
            case 'dibatalkan':
                return 'Dibatalkan';
            case 'menunggu':
                return 'Menunggu Konfirmasi';
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