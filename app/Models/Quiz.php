<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'form_id',
        'schedule_id',
        'start_date',
        'end_date',
        'estimated_time',
        'attempts_time',
        'max_score',
        'total_question',
        'auto_mark',
        'publish_date',
        'status',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function isActive(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }
}
