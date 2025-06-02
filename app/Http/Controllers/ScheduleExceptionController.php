<?php

namespace App\Http\Controllers;

use App\Models\ScheduleException;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ScheduleExceptionController extends Controller
{
    public function index()
    {
        // Fetch all schedule exceptions
        $exceptions = ScheduleException::all();

        // Return the Inertia response with the exceptions
        return Inertia::render('ScheduleExceptions', [
            'exceptions' => $exceptions,
        ]);
    }
}

