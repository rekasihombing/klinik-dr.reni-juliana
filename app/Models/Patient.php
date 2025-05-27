<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'golongan_darah',
        'email',
        'no_hp',
        'alamat',
    ];

    public $timestamps = false;

    public function user()
{
    return $this->belongsTo(User::class);  // Relasi balik ke model User
}

    
}


