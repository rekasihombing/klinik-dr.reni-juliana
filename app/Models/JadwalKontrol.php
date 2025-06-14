<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalKontrol extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kontrol';

    protected $fillable = [
        'rekam_medis_id',
        'tanggal_kontrol',
        'catatan',
        'status',
        'is_reminded',
    ];

    protected $casts = [
        'tanggal_kontrol' => 'date',
        'status' => 'string',
        'is_reminded' => 'boolean',
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }

    public function getTanggalKontrolFormattedAttribute()
    {
        return $this->tanggal_kontrol->format('d-m-Y H:i');
    }

    public function scopeByPasien($query, $pasienId)
    {
        return $query->whereHas('rekamMedis', function ($q) use ($pasienId) {
            $q->where('pasien_id', $pasienId);
        });
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'terjadwal');
    }

    public function scopePerluPengingat($query)
    {
        $duaHariLagi = Carbon::now()->addDays(2);
        $tigaHariLagi = Carbon::now()->addDays(3);
        
        return $query->where('status', 'terjadwal')
                    ->whereBetween('tanggal_kontrol', [
                        $duaHariLagi->startOfDay(), 
                        $tigaHariLagi->startOfDay()
                    ]);
    }

    // Optional: Scope untuk jadwal yang sudah lewat
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'terjadwal')
                    ->where('tanggal_kontrol', '<', Carbon::now());
    }

    // Optional: Method untuk mengecek apakah jadwal sudah lewat
    public function isOverdue()
    {
        return $this->tanggal_kontrol < Carbon::now() && $this->status === 'terjadwal';
    }
}