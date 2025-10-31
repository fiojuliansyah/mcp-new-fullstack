<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'user_id',
        'status',
        'date',
        'time',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function latestSchedule()
    {
        return $this->hasOne(Schedule::class)->latestOfMany();
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'classroom_subscription');
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
