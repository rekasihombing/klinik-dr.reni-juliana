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
 * Generate and assign queue numbers for a specific doctor and date.
 * @param int $doctorId
 * @param string $date (Y-m-d format)
 * @return void
 */
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

        // Fetch all appointments for the doctor and date, including menunggu
        $appointments = self::where('dokter_id', $doctorId)
            ->where('tanggal', $date)
            ->whereIn('status', ['menunggu', 'dikonfirmasi', 'diproses', 'selesai'])
            ->orderByRaw('CASE 
                WHEN checked_in_at IS NOT NULL THEN checked_in_at 
                ELSE created_at 
            END ASC')
            ->orderBy('created_at', 'asc') // Ensure consistent order for menunggu
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->get();

        \Log::info('Appointments to process:', [
            'count' => $appointments->count(),
            'appointments' => $appointments->map(function ($appt) {
                return [
                    'id' => $appt->id,
                    'status' => $appt->status,
                    'checked_in_at' => $appt->checked_in_at,
                    'created_at' => $appt->created_at,
                    'antrian' => $appt->antrian,
                    'dibuat_oleh' => $appt->dibuat_oleh,
                ];
            })->toArray(),
        ]);

        // Collect existing queue numbers to determine the next available number
        $existingQueueNumbers = $appointments->pluck('antrian')
            ->filter()
            ->map(function ($antrian) {
                return (int) substr($antrian, 1); // Extract number (e.g., A01 -> 1)
            })
            ->toArray();

        $queueIndex = empty($existingQueueNumbers) ? 0 : max($existingQueueNumbers);

        foreach ($appointments as $appointment) {
            // Skip if the appointment already has a queue number
            if ($appointment->antrian) {
                \Log::info('Skipping queue number assignment (already assigned):', [
                    'appointment_id' => $appointment->id,
                    'queue_number' => $appointment->antrian,
                    'status' => $appointment->status,
                ]);
                continue;
            }

            $queueIndex++;
            $queueNumber = 'A' . str_pad($queueIndex, 2, '0', STR_PAD_LEFT);

            $appointment->update(['antrian' => $queueNumber]);

            \Log::info('Assigned queue number:', [
                'appointment_id' => $appointment->id,
                'queue_number' => $queueNumber,
                'queue_index' => $queueIndex,
                'status' => $appointment->status,
                'checked_in_at' => $appointment->checked_in_at,
                'created_at' => $appointment->created_at,
            ]);
        }

        \Log::info('Completed assignQueueNumbers:', [
            'doctor_id' => $doctorId,
            'date' => $date,
            'total_assigned' => $queueIndex,
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