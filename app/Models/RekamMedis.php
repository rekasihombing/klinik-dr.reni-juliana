<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';

    protected $fillable = [
        'patient_id',
        'dokter_id',
        'appointment_id',
        'no_rekam_medis',
        'tanggal_kunjungan',
        
        // Anamnesis
        'keluhan_utama',
        'rps',
        'rpd',
        'riwayat_alergi',
        'riwayat_obat',
        
        // Pemeriksaan Fisik
        'tekanan_darah',
        'suhu_tubuh',
        'nadi',
        'pernapasan',
        'berat_badan',
        'status_gizi',
        
        // Diagnosa dan Tindakan
        'diagnosa',
        'tindakan',
        'catatan_dokter'
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    // Relasi dengan Pasien
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relasi dengan Dokter
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    // Relasi dengan Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Accessor untuk format tanggal Indonesia
    public function getTanggalKunjunganFormattedAttribute()
    {
        return $this->tanggal_kunjungan->format('d-m-Y');
    }

    // Scope untuk filter berdasarkan dokter
    public function scopeByDokter($query, $dokterId)
    {
        return $query->where('doctor_id', $dokterId);
    }

    // Scope untuk filter berdasarkan pasien
    public function scopeByPasien($query, $pasienId)
    {
        return $query->where('patient_id', $pasienId);
    }
}