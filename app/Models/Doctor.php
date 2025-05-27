<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';
    public $timestamps = false; // jika tabel staff gak ada kolom timestamps

    protected $fillable = ['user_id', 'nama_lengkap'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


public function doctor()
{
    return $this->hasOne(Doctor::class);
}
}
