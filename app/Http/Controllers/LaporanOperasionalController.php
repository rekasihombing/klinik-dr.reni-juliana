<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanOperasionalController extends Controller
{
    public function index(Request $request)
    {
        $now = \Carbon\Carbon::now();

        // 1. Ambil data dari frontend (jika ada)
        $period = $request->input('period', 'mingguan'); // mingguan / bulanan / tahunan
        $dateFrom = $request->input('dateFrom') ? \Carbon\Carbon::parse($request->input('dateFrom')) : $now->copy()->startOfWeek();
        $dateTo = $request->input('dateTo') ? \Carbon\Carbon::parse($request->input('dateTo')) : $now->copy()->endOfWeek();

        // 2. Total dan pasien baru
        $totalPatients = DB::table('patients')->count();
        $newPatients = DB::table('patients')
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->count();

        // Hitung growth percentage
        $previousMonthPatients = DB::table('patients')
            ->where('created_at', '>=', $now->copy()->subDays(60))
            ->where('created_at', '<', $now->copy()->subDays(30))
            ->count();
        
        $patientGrowth = $previousMonthPatients > 0 ? 
            round((($newPatients - $previousMonthPatients) / $previousMonthPatients) * 100, 1) : 0;

        // 3. Frekuensi kunjungan pasien
        $appointmentCounts = DB::table('appointments')
            ->select('pasien_id', DB::raw('COUNT(*) as count'))
            ->groupBy('pasien_id')
            ->get();

        $once = 0;
        $twoToThree = 0;
        $more = 0;
        $totalAppointments = $appointmentCounts->count();

        foreach ($appointmentCounts as $item) {
            if ($item->count == 1) $once++;
            elseif ($item->count >= 2 && $item->count <= 3) $twoToThree++;
            elseif ($item->count > 3) $more++;
        }

        $visitFrequency = [
            'once' => $totalAppointments > 0 ? round(($once / $totalAppointments) * 100, 1) : 0,
            'twoToThree' => $totalAppointments > 0 ? round(($twoToThree / $totalAppointments) * 100, 1) : 0,
            'more' => $totalAppointments > 0 ? round(($more / $totalAppointments) * 100, 1) : 0,
        ];

        // 4. Aktivitas berdasarkan periode
        switch ($period) {
            case 'bulanan':
                $activity = DB::table('appointments')
                    ->selectRaw('DATE_FORMAT(tanggal, "%d %b") as label, COUNT(*) as count')
                    ->whereBetween('tanggal', [$dateFrom, $dateTo])
                    ->groupBy(DB::raw('DATE(tanggal)'))
                    ->orderBy('tanggal')
                    ->get();
                break;

            case 'tahunan':
                $activity = DB::table('appointments')
                    ->selectRaw('DATE_FORMAT(tanggal, "%b %Y") as label, COUNT(*) as count')
                    ->whereBetween('tanggal', [$dateFrom, $dateTo])
                    ->groupBy(DB::raw('YEAR(tanggal), MONTH(tanggal)'))
                    ->orderBy('tanggal')
                    ->get();
                break;

            case 'mingguan':
            default:
                $activity = DB::table('appointments')
                    ->selectRaw('DAYNAME(tanggal) as day, COUNT(*) as count')
                    ->whereBetween('tanggal', [$dateFrom, $dateTo])
                    ->groupBy('day')
                    ->get()
                    ->map(function ($item) {
                        $dayMap = [
                            'Monday' => 'Sen',
                            'Tuesday' => 'Sel',
                            'Wednesday' => 'Rab',
                            'Thursday' => 'Kam',
                            'Friday' => 'Jum',
                            'Saturday' => 'Sab',
                            'Sunday' => 'Min',
                        ];
                        return [
                            'label' => $dayMap[$item->day] ?? $item->day,
                            'patients' => $item->count,
                        ];
                    });
                break;
        }

        // 5. Hitung rata-rata pasien
        $averagePatients = $activity->avg('patients') ?? $activity->avg('count') ?? 0;

        $data = [
            'totalPatients' => $totalPatients,
            'newPatients' => $newPatients,
            'patientGrowth' => $patientGrowth,
            'visitFrequency' => $visitFrequency,
            'weeklyActivity' => $activity->map(function ($a) {
                return [
                    'label' => $a->label ?? $a['label'],
                    'patients' => $a->count ?? $a['patients'],
                ];
            })->toArray(),
            'averagePatients' => round($averagePatients),
            'period' => $period,
            'dateFrom' => $dateFrom->format('d/m/Y'),
            'dateTo' => $dateTo->format('d/m/Y'),
        ];

        if ($request->is('dokter.Laporan-operasional') || $request->routeIs('dokter.laporan-operasional')) {
            return Inertia::render('Doctor/LaporanOperasional', $data);
        }

        if ($request->is('staff.Laporan-operasional') || $request->routeIs('staff.laporan-operasional')) {
            return Inertia::render('staff/LaporanOperasional', $data);
        }
    }

    public function downloadPdf(Request $request)
    {
        $now = \Carbon\Carbon::now();

        // Ambil data yang sama dengan index
        $period = $request->input('period', 'mingguan');
        $dateFrom = $request->input('dateFrom') ? \Carbon\Carbon::parse($request->input('dateFrom')) : $now->copy()->startOfWeek();
        $dateTo = $request->input('dateTo') ? \Carbon\Carbon::parse($request->input('dateTo')) : $now->copy()->endOfWeek();

        // Total dan pasien baru
        $totalPatients = DB::table('patients')->count();
        $newPatients = DB::table('patients')
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->count();

        // Hitung growth percentage
        $previousMonthPatients = DB::table('patients')
            ->where('created_at', '>=', $now->copy()->subDays(60))
            ->where('created_at', '<', $now->copy()->subDays(30))
            ->count();
        
        $patientGrowth = $previousMonthPatients > 0 ? 
            round((($newPatients - $previousMonthPatients) / $previousMonthPatients) * 100, 1) : 0;

        // Frekuensi kunjungan pasien
        $appointmentCounts = DB::table('appointments')
            ->select('pasien_id', DB::raw('COUNT(*) as count'))
            ->groupBy('pasien_id')
            ->get();

        $once = 0;
        $twoToThree = 0;
        $more = 0;
        $totalAppointments = $appointmentCounts->count();

        foreach ($appointmentCounts as $item) {
            if ($item->count == 1) $once++;
            elseif ($item->count >= 2 && $item->count <= 3) $twoToThree++;
            elseif ($item->count > 3) $more++;
        }

        $visitFrequency = [
            'once' => $totalAppointments > 0 ? round(($once / $totalAppointments) * 100, 1) : 0,
            'twoToThree' => $totalAppointments > 0 ? round(($twoToThree / $totalAppointments) * 100, 1) : 0,
            'more' => $totalAppointments > 0 ? round(($more / $totalAppointments) * 100, 1) : 0,
        ];

        // Aktivitas berdasarkan periode
        switch ($period) {
            case 'bulanan':
                $activity = DB::table('appointments')
                    ->selectRaw('DATE_FORMAT(tanggal, "%d %b") as label, COUNT(*) as count')
                    ->whereBetween('tanggal', [$dateFrom, $dateTo])
                    ->groupBy(DB::raw('DATE(tanggal)'))
                    ->orderBy('tanggal')
                    ->get();
                break;

            case 'tahunan':
                $activity = DB::table('appointments')
                    ->selectRaw('DATE_FORMAT(tanggal, "%b %Y") as label, COUNT(*) as count')
                    ->whereBetween('tanggal', [$dateFrom, $dateTo])
                    ->groupBy(DB::raw('YEAR(tanggal), MONTH(tanggal)'))
                    ->orderBy('tanggal')
                    ->get();
                break;

            case 'mingguan':
            default:
                $activity = DB::table('appointments')
                    ->selectRaw('DAYNAME(tanggal) as day, COUNT(*) as count')
                    ->whereBetween('tanggal', [$dateFrom, $dateTo])
                    ->groupBy('day')
                    ->get()
                    ->map(function ($item) {
                        $dayMap = [
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu',
                            'Sunday' => 'Minggu',
                        ];
                        return [
                            'label' => $dayMap[$item->day] ?? $item->day,
                            'patients' => $item->count,
                        ];
                    });
                break;
        }

        $averagePatients = $activity->avg('patients') ?? $activity->avg('count') ?? 0;

        $data = [
            'totalPatients' => $totalPatients,
            'newPatients' => $newPatients,
            'patientGrowth' => $patientGrowth,
            'visitFrequency' => $visitFrequency,
            'chartData' => $activity->map(function ($a) {
                return [
                    'label' => $a->label ?? $a['label'],
                    'patients' => $a->count ?? $a['patients'],
                ];
            })->toArray(),
            'averagePatients' => round($averagePatients),
            'period' => $period,
            'dateFrom' => $dateFrom->format('d/m/Y'),
            'dateTo' => $dateTo->format('d/m/Y'),
            'reportDate' => $now->format('d F Y'),
            'reportTime' => $now->format('H:i:s'),
            // Info klinik (sesuaikan dengan data klinik Anda)
            'clinicName' => 'Klinik Sehat Bersama',
            'clinicAddress' => 'Jl. Kesehatan No. 123, Medan',
            'clinicPhone' => '(061) 123-4567',
        ];

        $pdf = Pdf::loadView('reports.laporan-operasional', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Laporan-Operasional-' . $period . '-' . $now->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function printReport(Request $request)
    {
        // Sama seperti downloadPdf tapi dengan response yang berbeda
        $pdf = $this->generatePdf($request);
        
        return $pdf->stream('Laporan-Operasional.pdf');
    }

    private function generatePdf(Request $request)
    {
        // Helper method untuk generate PDF (bisa digunakan untuk download dan print)
        $now = \Carbon\Carbon::now();
        $period = $request->input('period', 'mingguan');
        $dateFrom = $request->input('dateFrom') ? \Carbon\Carbon::parse($request->input('dateFrom')) : $now->copy()->startOfWeek();
        $dateTo = $request->input('dateTo') ? \Carbon\Carbon::parse($request->input('dateTo')) : $now->copy()->endOfWeek();

        // ... (sama seperti method downloadPdf untuk mengambil data)
        // Kode ini bisa di-refactor untuk menghindari duplikasi

        $data = [
            // ... data yang sama
        ];

        return Pdf::loadView('reports.laporan-operasional', $data)->setPaper('A4', 'portrait');
    }
}