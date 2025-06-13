<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $pasiens = Patient::all();

        // Debugging: Log the retrieved patients
        \Log::info('Route name: ' . $request->route()->getName());
        \Log::info('URL: ' . $request->url());
        \Log::info($pasiens->toArray());

        // PENTING: Hapus return yang salah ini dan pindahkan ke bawah
        // return Inertia::render('staff/PasienList', [
        //     'pasiens' => $pasiens
        // ]);

        // Check route berdasarkan URL atau route name
        if ($request->is('dokter.Pasien') || $request->routeIs('dokter.pasien')) {
            return Inertia::render('Doctor/PasienList', [
                'pasiens' => $pasiens
            ]);
        }

        if ($request->is('staff.Pasien') || $request->routeIs('staff.pasien')) {
            return Inertia::render('staff/PasienList', [
                'pasiens' => $pasiens
            ]);
        }

        // Default fallback ke staff
        return Inertia::render('staff/PasienList', [
            'pasiens' => $pasiens
        ]);
    }
}