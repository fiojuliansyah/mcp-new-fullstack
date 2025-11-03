<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplayView extends Model
{
    use HasFactory;

    protected $fillable = [
        'replay_video_id',
        'user_id',
        'view_number',
        'started_at',
        'ended_at',
        'duration_watched',
        'last_position',
    ];
    
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function replayVideo()
    {
        return $this->belongsTo(ReplayVideo::class);
    }
}