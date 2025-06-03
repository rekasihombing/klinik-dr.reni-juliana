<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    ];

protected $casts = [
    'tanggal' => 'date', // FIX: gunakan 'date' bukan 'datetime'
];

    public function pasien()
    {
        return $this->belongsTo(Patient::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Doctor::class, 'dokter_id');
    }
}
