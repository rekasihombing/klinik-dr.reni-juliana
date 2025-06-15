<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LaporanOperasionalController extends Controller
{
    public function index(Request $request)
    {
        return $this->generateReportData($request, true);
    }

    public function downloadPdf(Request $request)
    {
        $data = $this->generateReportData($request, false);
        $pdf = Pdf::loadView('reports.laporan-operasional', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $now = \Carbon\Carbon::now();
        $filename = 'Laporan-Operasional-' . $data['period'] . '-' . $now->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function printReport(Request $request)
    {
        $data = $this->generateReportData($request, false);
        $pdf = Pdf::loadView('reports.laporan-operasional', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('Laporan-Operasional.pdf');
    }

    private function generateReportData(Request $request, $forInertia = false)
    {
        $now = \Carbon\Carbon::now();

        // 1. Ambil data dari frontend
        $period = $request->input('period', 'mingguan');
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

        // 5. Hitung rata-rata pasien
        $averagePatients = $activity->avg('patients') ?? $activity->avg('count') ?? 0;

        // 6. Format chartData
        $chartData = $activity->map(function ($a) {
            return [
                'label' => $a->label ?? $a['label'],
                'patients' => $a->count ?? $a['patients'],
            ];
        })->toArray();

        // Logging untuk debugging
        Log::info('Chart Data Generated:', ['chartData' => $chartData]);

        // Data default jika chartData kosong
        if (empty($chartData)) {
            $chartData = [
                ['label' => 'Senin', 'patients' => 50],
                ['label' => 'Selasa', 'patients' => 60],
                ['label' => 'Rabu', 'patients' => 45],
                ['label' => 'Kamis', 'patients' => 55],
                ['label' => 'Jumat', 'patients' => 40],
            ];
            Log::info('Using default chart data due to empty activity');
        }

        $data = [
            'totalPatients' => $totalPatients,
            'newPatients' => $newPatients,
            'patientGrowth' => $patientGrowth,
            'visitFrequency' => $visitFrequency,
            'chartData' => $chartData,
            'averagePatients' => round($averagePatients),
            'period' => $period,
            'dateFrom' => $dateFrom->format('d F Y'),
            'dateTo' => $dateTo->format('d F Y'),
            'reportDate' => $now->format('d F Y'),
            'reportTime' => $now->format('H:i'),
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'clinicAddress' => 'Jl. Sutomo No.33, Brandan Tim. Baru, Kec. Babalan, Kabupaten Langkat, Sumatera Utara 20881',
            'clinicPhone' => '0822-7484-9745',
        ];

        if ($forInertia) {
            if ($request->is('dokter.Laporan-operasional') || $request->routeIs('dokter.laporan-operasional')) {
                return Inertia::render('Doctor/LaporanOperasional', $data);
            }
            if ($request->is('staff.Laporan-operasional') || $request->routeIs('staff.laporan-operasional')) {
                return Inertia::render('staff/LaporanOperasional', $data);
            }
        }

        return $data;
    }
}