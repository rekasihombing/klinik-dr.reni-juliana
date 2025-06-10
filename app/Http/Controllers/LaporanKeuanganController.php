<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganController extends Controller
{
public function index()
{
    // Ambil semua data tagihan (misalnya per bulan)
    $tagihan = DB::table('tagihan')
        ->selectRaw('MONTH(created_at) as bulan, SUM(subtotal) as income')
        ->groupByRaw('MONTH(created_at)')
        ->get();

    // Hitung total income keseluruhan
    $totalIncome = DB::table('tagihan')->sum('subtotal');

    // Hitung rata-rata income per bulan
    $rataRataBulanan = $tagihan->avg('income');

    // Ambil income tertinggi dan terendah dari data bulanan
    $incomeTertinggi = $tagihan->max('income');
    $incomeTerendah = $tagihan->min('income');

    // Kirim data ke frontend
    return Inertia::render('Doctor/LaporanKeuangan', [
        'totalIncome' => $totalIncome,
        'rataRataBulanan' => $rataRataBulanan,
        'incomeTertinggi' => $incomeTertinggi,
        'incomeTerendah' => $incomeTerendah,
        'chartData' => $tagihan,
    ]);
}

}
