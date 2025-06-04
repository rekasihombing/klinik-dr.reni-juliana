<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
