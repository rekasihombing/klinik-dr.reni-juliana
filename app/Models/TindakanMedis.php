<?php

// App/Models/TindakanMedis.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakanMedis extends Model
{
    use HasFactory;

    protected $table = 'tindakan_medis';
    protected $primaryKey = 'tindakan_id';
    
    protected $fillable = [
        'nama_tindakan',
        'tarif'
    ];

    public function tindakanPasien()
    {
        return $this->hasMany(TindakanPasien::class, 'tindakan_id', 'tindakan_id');
    }
}