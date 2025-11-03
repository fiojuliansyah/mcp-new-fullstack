<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplayVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'replay_id',
        'video_url',
    ];

    public function replay()
    {
        return $this->belongsTo(Replay::class);
    }

    public function views()
    {
        return $this->hasMany(ReplayView::class);
    }

    public function totalWatchedByUser($userId)
    {
        return $this->views()
            ->where('user_id', $userId)
            ->sum('duration_watched');
    }

}
