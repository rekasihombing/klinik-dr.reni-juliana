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

    // **Tambahkan ini supaya tanggal_lahir jadi objek Carbon**
    protected $casts = [
        'tanggal_lahir' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'patient_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'pasien_id');
    }
}


