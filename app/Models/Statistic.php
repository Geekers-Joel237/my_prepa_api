<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'time_taken',
        'prompt',
        'result',
        'status_code'
    ];
}
