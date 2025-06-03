<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Http\Request;

class LaporanOperasionalController extends Controller
{
    public function index(){
        $data = [
            'totalPatients' => 100,
            'newPatients' => 75,
            'visitFrequency' => [
                'once' => 60,
                'twoToThree' => 30,
                'more' => 10
            ],
            'weeklyActivity' => [
                ['day' => 'Sen', 'count' => 50],
                ['day' => 'Sel', 'count' => 40],
                ['day' => 'Rab', 'count' => 30],
                ['day' => 'Kam', 'count' => 25],
                ['day' => 'Jum', 'count' => 45],
                ['day' => 'Sab', 'count' => 40],
                ['day' => 'Min', 'count' => 5]
            ]
        ];

        // Kirim data ke komponen Vue bernama 'Report/Operational'
        return Inertia::render('Doctor/LaporanOperasional', [
            'report' => $data
        ]);
    }
}
