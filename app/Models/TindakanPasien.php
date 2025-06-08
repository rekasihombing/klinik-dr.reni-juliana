<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakanPasien extends Model
{
    use HasFactory;

    protected $table = 'tindakan_pasien';
    
    protected $fillable = [
        'rekam_medis_id',
        'tindakan_id',
        'jumlah',
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }

    public function tindakanMedis()
    {
        return $this->belongsTo(TindakanMedis::class, 'tindakan_id', 'tindakan_id');
    }
}