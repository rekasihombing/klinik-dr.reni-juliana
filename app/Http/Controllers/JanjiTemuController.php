<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Inertia\Inertia;

class JanjiTemuController extends Controller
{
public function index()
    {
        $appointments = Appointment::all();

        return Inertia::render('Doctor/JanjiTemuPasien', [
            'appointments' => $appointments,
        ]);
    }
}
