<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokObat extends Model
{
    use HasFactory;

    protected $table = 'stok_obat';
    
    public $timestamps = false; // Karena hanya ada updated_at
    
    protected $fillable = [
        'obat_id',
        'jumlah',
        'tanggal_kadaluarsa'
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
        'updated_at' => 'datetime'
    ];

    // Relationship dengan Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }

    // Accessor untuk status kadaluarsa
    public function getStatusKadaluarsaAttribute()
    {
        $today = now()->toDateString();
        $expiry = $this->tanggal_kadaluarsa->toDateString();
        
        if ($expiry < $today) {
            return 'expired';
        } elseif ($expiry <= now()->addDays(30)->toDateString()) {
            return 'warning';
        } else {
            return 'safe';
        }
    }

    // Scope untuk stok yang belum kadaluarsa
    public function scopeNotExpired($query)
    {
        return $query->where('tanggal_kadaluarsa', '>=', now()->toDateString());
    }

    // Scope untuk stok yang hampir kadaluarsa (30 hari)
    public function scopeNearExpiry($query)
    {
        return $query->where('tanggal_kadaluarsa', '<=', now()->addDays(30)->toDateString())
                    ->where('tanggal_kadaluarsa', '>=', now()->toDateString());
    }
}   