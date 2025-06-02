<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';
    
    protected $fillable = [
        'nama_obat',
        'jenis_obat',
        'deskripsi',
        'harga',
        'satuan'
    ];

    // Relationship dengan StokObat
    public function stokObat()
    {
        return $this->hasMany(StokObat::class);
    }

    // Mendapatkan total stok yang belum kadaluarsa
    public function getTotalStokAttribute()
    {
        return $this->stokObat()
            ->where('tanggal_kadaluarsa', '>=', now()->toDateString())
            ->sum('jumlah');
    }

    // Mendapatkan stok yang hampir kadaluarsa
    public function getStokHampirKadaluarsaAttribute()
    {
        return $this->stokObat()
            ->where('tanggal_kadaluarsa', '<=', now()->addDays(30)->toDateString())
            ->where('tanggal_kadaluarsa', '>=', now()->toDateString())
            ->sum('jumlah');
    }
}