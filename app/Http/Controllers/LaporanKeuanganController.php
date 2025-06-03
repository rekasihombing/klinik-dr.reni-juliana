<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function index()
    {
        $data = [
            'totalIncome' => 500000000,
            'rataRataBulanan' => 183000000,
            'incomeTertinggi' => 200000000,
            'incomeTerendah' => 185000000,
            'chartData' => [
                ['bulan' => 'Jan', 'income' => 130000000],
                ['bulan' => 'Feb', 'income' => 200000000],
                ['bulan' => 'Mar', 'income' => 185000000],
            ],
        ];

        return Inertia::render('Doctor/LaporanKeuangan', $data);
    }
}
