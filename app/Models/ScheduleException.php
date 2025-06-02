<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleException extends Model
{
    use HasFactory;

    protected $fillable = [
        'exception_date',
        'is_open',
        'open_time',
        'close_time',
        'reason',
    ];
}

