<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'form_id',
        'topic',
        'agenda',
        'type',
        'duration',
        'time',
        'timezone',
        'password',
        'status',
        'settings',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
    ];

    protected $casts = [
        'settings' => 'array',
        'time' => 'datetime',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function replays()
    {
        return $this->hasMany(Replay::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function latestReplay()
    {
        return $this->hasOne(Replay::class)->latestOfMany();
    }

    public function latestQuiz()
    {
        return $this->hasOne(Quiz::class)->latestOfMany();
    }

    public function latestMaterial()
    {
        return $this->hasOne(Material::class)->latestOfMany();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function replayViews()
    {
        return $this->hasMany(ReplayView::class);
    }

}
