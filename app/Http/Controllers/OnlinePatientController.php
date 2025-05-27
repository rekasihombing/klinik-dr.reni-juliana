<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OnlinePatientController extends Controller
{
    public function create()
    {
        return Inertia::render('pasien/datapasien');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nama_lengkap' => 'required|string|max:100',
        'nik' => 'required|string|max:20|unique:patients,nik,' . (Auth::user()->patient->id ?? 'NULL'),
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:L,P',
        'golongan_darah' => 'nullable|string|max:3',
        'email' => 'nullable|email|max:100',
        'no_hp' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
    ]);

    $patient = Patient::where('user_id', Auth::id())->first();

    if ($patient) {
        // update existing patient record
        $patient->update($validated);
    } else {
        // create new patient if none found (optional, depending on logic)
        $validated['user_id'] = Auth::id();
        Patient::create($validated);
    }

    return redirect()->route('dashboard')->with('success', 'Data pasien berhasil disimpan.');
}

}
