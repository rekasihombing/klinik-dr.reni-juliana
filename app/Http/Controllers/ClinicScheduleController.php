<?php

namespace App\Http\Controllers;

use App\Models\ClinicSchedule;
use App\Models\ScheduleException;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ClinicScheduleController extends Controller
{
    public function index()
    {
        // Fetch all clinic schedules and exceptions
        $schedules = ClinicSchedule::all();
        $exceptions = ScheduleException::all();

        // Return the Inertia response with the schedules and exceptions
        return Inertia::render('staff/Jadwal', [
            'schedules' => $schedules,
            'exceptions' => $exceptions,
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|string',
            'schedules.*.is_open' => 'required|boolean',
            'schedules.*.open_time' => 'nullable|date_format:H:i',
            'schedules.*.close_time' => 'nullable|date_format:H:i',
        ]);

        // Update each schedule
        foreach ($request->schedules as $scheduleData) {
            $schedule = ClinicSchedule::where('day_of_week', $scheduleData['day_of_week'])->first();
            if ($schedule) {
                $schedule->is_open = $scheduleData['is_open'];
                $schedule->open_time = $scheduleData['is_open'] ? $scheduleData['open_time'] : null;
                $schedule->close_time = $scheduleData['is_open'] ? $scheduleData['close_time'] : null;
                $schedule->save();
            }
        }

        return redirect()->back()->with('success', 'Schedules updated successfully');
    }
}
