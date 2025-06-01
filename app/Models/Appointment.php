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
    ];
        protected $casts = [
        'checked_in_at' => 'datetime',  // supaya otomatis ke Carbon
    ];

    // Relasi ke pasien (jika model Patient ada)
  public function patient() // ubah dari pasien() ke patient()
{
    return $this->belongsTo(Patient::class, 'pasien_id');
}

    // Relasi ke dokter (jika model Doctor ada)
    public function dokter()
    {
        return $this->belongsTo(Doctor::class, 'dokter_id');
    }

    
}
