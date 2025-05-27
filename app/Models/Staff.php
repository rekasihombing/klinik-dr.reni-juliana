<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    public $timestamps = false; // jika tabel staff gak ada kolom timestamps

    protected $fillable = ['user_id', 'nama_lengkap'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
{
    return $this->hasOne(Staff::class);
}
}
