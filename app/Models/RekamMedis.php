<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';

    protected $fillable = [
        'patient_id',      // Make sure this matches your database column name
        'dokter_id',
        'appointment_id',
        'no_rekam_medis',
        'tanggal_kunjungan',
        'status',          // Add status field
        
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

    // IMPORTANT: Fix the relationship to match your database structure
    // If your foreign key column is 'patient_id', use this:
    public function pasien()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    
    // If your foreign key column is 'pasien_id', use this instead:
    // public function pasien()
    // {
    //     return $this->belongsTo(Patient::class, 'pasien_id');
    // }

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
        return $query->where('dokter_id', $dokterId);
    }

    // Scope untuk filter berdasarkan pasien
    public function scopeByPasien($query, $pasienId)
    {
        return $query->where('patient_id', $pasienId);
    }

    public function tindakanMedis()
    {
        return $this->belongsToMany(TindakanMedis::class, 'tindakan_pasien', 'rekam_medis_id', 'tindakan_id')
                    ->withTimestamps();
    }

    public function resepObat()
    {
        return $this->hasMany(ResepObat::class, 'rekam_medis_id');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'rekam_medis_id');
    }

    public function tagihanObat()
    {
        return $this->hasMany(TagihanObat::class, 'rekam_medis_id');
    }

    public function tagihanTindakan()
    {
        return $this->hasMany(TagihanTindakan::class, 'rekam_medis_id');
    }
}