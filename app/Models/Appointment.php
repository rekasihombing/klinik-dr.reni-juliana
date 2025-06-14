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
    // Fetch all appointments for the doctor on the given date
    $appointments = self::where('dokter_id', $doctorId)
        ->where('tanggal', $date)
        ->whereIn('status', ['menunggu', 'dikonfirmasi', 'diproses'])
        ->orderBy('jam_konsultasi')
        ->orderBy('created_at')
        ->get();

    // Find the highest existing queue number for the day (including 'selesai')
    $existingQueueNumbers = self::where('dokter_id', $doctorId)
        ->where('tanggal', $date)
        ->whereNotNull('antrian')
        ->pluck('antrian')
        ->map(function ($queueNumber) {
            return (int) substr($queueNumber, 1); // Extract number from 'A01', 'A02', etc.
        })
        ->max();

    $startingIndex = $existingQueueNumbers ? $existingQueueNumbers : 0;

    // Assign queue numbers only to appointments without a queue number
    foreach ($appointments as $index => $appointment) {
        if (!$appointment->antrian) { // Only assign if no queue number exists
            $queueNumber = 'A' . str_pad($startingIndex + $index + 1, 2, '0', STR_PAD_LEFT);
            $appointment->update(['antrian' => $queueNumber]);
        }
    }
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