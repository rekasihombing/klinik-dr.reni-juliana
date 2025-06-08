<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'telepon',
    ];

     public function getInitialAttribute()
    {
        return strtoupper(substr($this->nama_lengkap, 0, 1));
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
{
    return $this->hasOne(Staff::class);
}

public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('user_id', 'like', "%{$search}%")
              ->orWhere('posisi', 'like', "%{$search}%")
              ->orWhere('spesialisasi', 'like', "%{$search}%");
        });
    }
}
