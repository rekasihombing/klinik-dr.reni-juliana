<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // 3. Frekuensi kunjungan pasien
    $appointmentCounts = DB::table('appointments')
        ->select('pasien_id', DB::raw('COUNT(*) as count'))
        ->groupBy('pasien_id')
        ->get();

    $once = 0;
    $twoToThree = 0;
    $more = 0;
    foreach ($appointmentCounts as $item) {
        if ($item->count == 1) $once++;
        elseif ($item->count >= 2 && $item->count <= 3) $twoToThree++;
        elseif ($item->count > 3) $more++;
    }

    $visitFrequency = [
        'once' => $once,
        'twoToThree' => $twoToThree,
        'more' => $more,
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
    $averagePatients = $activity->avg('patients' ?? 'count');

    return Inertia::render('Doctor/LaporanOperasional', [
        'totalPatients' => $totalPatients,
        'newPatients' => $newPatients,
        'visitFrequency' => $visitFrequency,
        'weeklyActivity' => $activity->map(function ($a) {
            return [
                'label' => $a->label ?? $a['label'],
                'patients' => $a->count ?? $a['patients'],
            ];
        })->toArray(),
        'averagePatients' => round($averagePatients),
    ]);
}

}
