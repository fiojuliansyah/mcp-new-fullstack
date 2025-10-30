<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplayView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'replay_id',
        'schedule_id',
        'watch_duration',
        'is_completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replay()
    {
        return $this->belongsTo(Replay::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
