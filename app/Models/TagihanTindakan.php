<?php
// app/Models/TagihanTindakan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagihanTindakan extends Model
{
    protected $table = 'tagihan_tindakan';

    protected $fillable = [
        'tagihan_id',
        'tindakan_pasien_id',
        'nama_tindakan',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'catatan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    // Relasi ke Tagihan
    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    // Relasi ke TindakanPasien
    public function tindakanPasien(): BelongsTo
    {
        return $this->belongsTo(TindakanPasien::class, 'tindakan_pasien_id');
    }

    // Contoh accessor untuk subtotal format rupiah
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2, ',', '.');
    }

        public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }
}
