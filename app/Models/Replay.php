<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replay extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'form_id',
        'schedule_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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

    public function replayVideos()
    {
        return $this->hasMany(ReplayVideo::class);
    }

}
