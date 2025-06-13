<?php
// app/Models/Tagihan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    protected $table = 'tagihan';

    protected $fillable = [
        'nomor_tagihan',
        'rekam_medis_id',
        'pasien_id',
        'tanggal_tagihan',
        'subtotal',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_tagihan' => 'date',
        'subtotal' => 'decimal:2',
    ];

   protected $attributes = [
        'subtotal' => 0
    ];

    // Relasi ke RekamMedis
 public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }


    public function tagihanObat()
    {
        return $this->hasMany(TagihanObat::class, 'tagihan_id');
    }

    public function tagihanTindakan()
    {
        return $this->hasMany(TagihanTindakan::class, 'tagihan_id');
    }

    // Relasi ke Pasien
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'pasien_id');
    }

    // Accessor contoh format subtotal
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2, ',', '.');
    }
}
