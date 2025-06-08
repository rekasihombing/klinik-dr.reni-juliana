<?php
// app/Models/TagihanObat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagihanObat extends Model
{
    protected $table = 'tagihan_obat';

    protected $fillable = [
        'tagihan_id',
        'resep_obat_id',
        'nama_obat',
        'dosis',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'catatan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relasi ke Tagihan
    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    // Relasi ke ResepObat
    public function resepObat(): BelongsTo
    {
        return $this->belongsTo(ResepObat::class, 'resep_obat_id');
    }

    // Accessor format subtotal, misal untuk rupiah
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2, ',', '.');
    }

        public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }
}
