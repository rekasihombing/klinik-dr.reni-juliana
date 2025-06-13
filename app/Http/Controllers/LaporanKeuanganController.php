<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Tagihan;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->get('filter_type', 'bulanan');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Validasi tanggal
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Ambil data berdasarkan filter
        $chartData = $this->getChartData($filterType, $startDate, $endDate);
        
        // Hitung statistik
        $totalIncome = $chartData->sum('income');
        $rataRata = $chartData->avg('income') ?? 0;
        $incomeTertinggi = $chartData->max('income') ?? 0;
        $incomeTerendah = $chartData->min('income') ?? 0;

        return Inertia::render('Doctor/LaporanKeuangan', [
            'totalIncome' => $totalIncome,
            'rataRataBulanan' => $rataRata,
            'incomeTertinggi' => $incomeTertinggi,
            'incomeTerendah' => $incomeTerendah,
            'chartData' => $chartData,
            'filterType' => $filterType,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),

            'csrf_token' => csrf_token(),
        ]);
    }

    private function getChartData($filterType, $startDate, $endDate)
    {
        $query = DB::table('tagihan')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()]);

        switch ($filterType) {
            case 'harian':
                return $query
                    ->selectRaw('DATE(created_at) as tanggal, SUM(subtotal) as income')
                    ->groupByRaw('DATE(created_at)')
                    ->orderByRaw('DATE(created_at)')
                    ->get()
                    ->map(function($item) {
                        $item->periode = $item->tanggal;
                        $item->label = Carbon::parse($item->tanggal)->format('d M Y');
                        return $item;
                    });

            case 'mingguan':
                return $query
                    ->selectRaw('YEARWEEK(created_at, 1) as minggu, YEAR(created_at) as tahun, WEEK(created_at, 1) as week_num, SUM(subtotal) as income')
                    ->groupByRaw('YEARWEEK(created_at, 1)')
                    ->orderByRaw('YEARWEEK(created_at, 1)')
                    ->get()
                    ->map(function($item) {
                        $item->periode = $item->minggu;
                        $item->label = "Minggu {$item->week_num} - {$item->tahun}";
                        return $item;
                    });

            case 'bulanan':
                return $query
                    ->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, SUM(subtotal) as income')
                    ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                    ->orderByRaw('YEAR(created_at), MONTH(created_at)')
                    ->get()
                    ->map(function($item) {
                        $date = Carbon::createFromDate($item->tahun, $item->bulan, 1);
                        $item->periode = $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
                        $item->label = $date->format('F Y'); // Format: January 2025
                        return $item;
                    });

            case 'tahunan':
                return $query
                    ->selectRaw('YEAR(created_at) as tahun, SUM(subtotal) as income')
                    ->groupByRaw('YEAR(created_at)')
                    ->orderByRaw('YEAR(created_at)')
                    ->get()
                    ->map(function($item) {
                        $item->periode = $item->tahun;
                        $item->label = $item->tahun;
                        return $item;
                    });

            default:
                return collect([]);
        }
    }

    public function downloadPDF(Request $request)
    {
        $filterType = $request->input('filter_type', 'bulanan');
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        // Ambil data untuk PDF
        $chartData = $this->getChartData($filterType, $startDate, $endDate);
        
        // Hitung statistik
        $totalIncome = $chartData->sum('income');
        $rataRata = $chartData->avg('income') ?? 0;
        $incomeTertinggi = $chartData->max('income') ?? 0;
        $incomeTerendah = $chartData->min('income') ?? 0;

        // Ambil detail transaksi untuk tabel

$detailTransaksi = Tagihan::with('pasien')
    ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
    ->orderBy('created_at', 'desc')
    ->get();


        $data = [
            'filterType' => $filterType,
            'startDate' => $startDate->format('d/m/Y'),
            'endDate' => $endDate->format('d/m/Y'),
            'totalIncome' => $totalIncome,
            'rataRata' => $rataRata,
            'incomeTertinggi' => $incomeTertinggi,
            'incomeTerendah' => $incomeTerendah,
            'chartData' => $chartData,
            'detailTransaksi' => $detailTransaksi,
            'generatedAt' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('pdf.laporan-keuangan', $data);
        
        $filename = 'laporan-keuangan-' . $filterType . '-' . 
                   $startDate->format('Y-m-d') . '-to-' . 
                   $endDate->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function getDetailTransaksi(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $transaksi = DB::table('tagihan')
            ->join('patients', 'tagihan.pasien_id', '=', 'patients.id')
            ->select('tagihan.*', 'patients.nama_lengkap as nama_lengkap_patients')
            ->whereBetween('tagihan.created_at', [$startDate, $endDate->endOfDay()])
            ->orderBy('tagihan.created_at', 'desc')
            ->paginate(10);

        return response()->json($transaksi);
    }
}