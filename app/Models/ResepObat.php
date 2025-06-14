<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ResepObat extends Model
{
    use HasFactory;

    protected $table = 'resep_obat'; // atau 'resep_obats' jika kamu pakai default Laravel

    protected $fillable = [
        'rekam_medis_id',
        'obat_id',
        'nama_obat',
        'dosis',
        'jumlah',
        'catatan',
        'tanggal_mulai',
        'tanggal_terakhir',
        'dari_klinik',
    ];

    protected $casts = [
        'dari_klinik' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_terakhir' => 'date',
    ];

    // Relasi ke rekam medis
    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class);
    }

    // Relasi ke obat
    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }

    /**
     * Scope untuk resep yang sedang aktif
     */
    public function scopeAktif($query)
    {
        return $query->whereDate('tanggal_mulai', '<=', Carbon::now())
                    ->whereDate('tanggal_terakhir', '>=', Carbon::now());
    }

    /**
     * Scope untuk resep yang sudah berakhir
     */
    public function scopeBerakhir($query)
    {
        return $query->whereDate('tanggal_terakhir', '<', Carbon::now());
    }

    /**
     * Scope untuk resep yang belum dimulai
     */
    public function scopeBelumDimulai($query)
    {
        return $query->whereDate('tanggal_mulai', '>', Carbon::now());
    }

    /**
     * Accessor untuk mengetahui apakah resep sedang aktif
     */
    public function getStatusAktifAttribute()
    {
        return Carbon::now()->between($this->tanggal_mulai, $this->tanggal_terakhir);
    }

    /**
     * Accessor untuk menghitung sisa hari
     */
    public function getSisaHariAttribute()
    {
        return Carbon::now()->diffInDays($this->tanggal_terakhir, false);
    }

    /**
     * Accessor untuk menghitung durasi hari
     */
    public function getDurasiHariAttribute()
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_terakhir) + 1;
    }

    /**
     * Accessor untuk progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        if (!$this->status_aktif) {
            return $this->tanggal_terakhir < Carbon::now() ? 100 : 0;
        }

        $totalDays = $this->durasi_hari;
        $daysPassed = $this->tanggal_mulai->diffInDays(Carbon::now()) + 1;
        
        return min(100, max(0, ($daysPassed / $totalDays) * 100));
    }

    /**
     * Scope untuk resep berdasarkan pasien
     */
    public function scopeByPasien($query, $patientId)
    {
        return $query->whereHas('rekamMedis.appointment', function($q) use ($patientId) {
            $q->where('patient_id', $patientId);
        });
    }

    /**
     * Scope untuk resep dari klinik
     */
    public function scopeDariKlinik($query)
    {
        return $query->where('dari_klinik', true);
    }

    /**
     * Scope untuk resep dari luar klinik
     */
    public function scopeDariLuar($query)
    {
        return $query->where('dari_klinik', false);
    }
}