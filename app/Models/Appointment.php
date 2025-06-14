<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id',
        'dokter_id',
        'tanggal',
        'jam_konsultasi',
        'keluhan',
        'status',
        'dibuat_oleh',
        'checked_in_at',
        'antrian', // Add antrian to fillable
    ];

    public function pasien()
    {
        return $this->belongsTo(Patient::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Doctor::class, 'dokter_id');
    }

    /**
     * Generate and assign queue numbers for a specific doctor and date.
     * @param int $doctorId
     * @param string $date (Y-m-d format)
     * @return void
     */
public static function assignQueueNumbers($doctorId, $date)
{
    return \DB::transaction(function () use ($doctorId, $date) {
        \Log::info('Starting assignQueueNumbers:', [
            'doctor_id' => $doctorId,
            'date' => $date,
        ]);

        // Fetch all confirmed appointments for the doctor and date with locking
        $appointments = self::where('dokter_id', $doctorId)
            ->where('tanggal', $date)
            ->where('status', 'dikonfirmasi')
            ->whereNotNull('checked_in_at')
            ->orderBy('checked_in_at', 'asc')
            ->orderBy('id', 'asc')
            ->lockForUpdate() // Prevent concurrent modifications
            ->get();

        \Log::info('Appointments to process:', [
            'count' => $appointments->count(),
            'appointments' => $appointments->map(function ($appt) {
                return [
                    'id' => $appt->id,
                    'checked_in_at' => $appt->checked_in_at,
                    'antrian' => $appt->antrian,
                    'dibuat_oleh' => $appt->dibuat_oleh,
                ];
            })->toArray(),
        ]);

        // Fetch existing queue numbers
        $existingQueueNumbers = self::where('dokter_id', $doctorId)
            ->where('tanggal', $date)
            ->where('status', 'dikonfirmasi')
            ->whereNotNull('antrian')
            ->pluck('antrian')
            ->map(function ($queueNumber) {
                return (int) substr($queueNumber, 1);
            })->toArray();

        $startingIndex = empty($existingQueueNumbers) ? 0 : max($existingQueueNumbers);

        \Log::info('Queue number calculation:', [
            'existing_queue_numbers' => $existingQueueNumbers,
            'starting_index' => $startingIndex,
        ]);

        // Assign queue numbers
        foreach ($appointments as $appointment) {
            // Skip if the appointment already has a valid queue number
            if ($appointment->antrian && in_array((int) substr($appointment->antrian, 1), $existingQueueNumbers)) {
                \Log::info('Skipping appointment with existing queue number:', [
                    'appointment_id' => $appointment->id,
                    'queue_number' => $appointment->antrian,
                    'checked_in_at' => $appointment->checked_in_at,
                ]);
                continue;
            }

            $startingIndex++;
            $queueNumber = 'A' . str_pad($startingIndex, 2, '0', STR_PAD_LEFT);

            // Check for duplicate queue number
            if (in_array($startingIndex, $existingQueueNumbers)) {
                \Log::warning('Queue number already exists:', [
                    'queue_number' => $queueNumber,
                    'appointment_id' => $appointment->id,
                ]);
                continue;
            }

            $appointment->update(['antrian' => $queueNumber]);
            \Log::info('Assigned queue number:', [
                'appointment_id' => $appointment->id,
                'queue_number' => $queueNumber,
                'starting_index' => $startingIndex,
                'checked_in_at' => $appointment->checked_in_at,
            ]);

            $existingQueueNumbers[] = $startingIndex;
        }

        \Log::info('Completed assignQueueNumbers:', [
            'doctor_id' => $doctorId,
            'date' => $date,
            'total_assigned' => count($existingQueueNumbers),
        ]);
    });
}

    /**
     * Get queue number for a single appointment.
     * @return string|null
     */
    public function getQueueNumber()
    {
        return $this->antrian ?? '-';
    }
}