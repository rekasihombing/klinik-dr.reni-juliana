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
    \Log::info($pasiens->toArray()); // Log the data to check its structure

    return Inertia::render('staff/PasienList', [
        'pasiens' => $pasiens
    ]);

        if ($request->routeIs('staff.pasien')) {
        return Inertia::render('staff/PasienList', [
            'pasiens' => $pasiens
        ]);
    }

    if ($request->routeIs('dokter.pasien')) {
        return Inertia::render('doctor/PasienList', [
            'pasiens' => $pasiens
        ]);
    }
}


}
