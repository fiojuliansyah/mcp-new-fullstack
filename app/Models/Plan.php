<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'duration',
        'duration_value',
        'device_limit',
        'is_weekly_live_classes',
        'is_materials',
        'is_quizzes',
        'replay_day',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
