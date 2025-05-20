<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = ['nama_lengkap', 'nomor_telepon', 'email', 'keluhan'];
}
