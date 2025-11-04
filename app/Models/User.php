<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'avatar_url',
        'password',
        'account_type',
        'ic_number',
        'gender',
        'phone',
        'postal_code',
        'level',
        'google_id',
        'parent_id',
        'language',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAvatarUrlAttribute($value)
    {
        if ($value) {
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            return Storage::disk($disk)->url($value);
        }

        return asset('frontend/assets/images/student-profile.png'); 
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id', 'id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function replayViews()
    {
        return $this->hasMany(ReplayView::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }

    protected function avgQuizScore(): Attribute
    {
        return Attribute::get(function () {
            $attempts = $this->attempts;
            if ($attempts->isEmpty()) {
                return 0;
            }
            return round($attempts->avg('score'), 2);
        });
    }

    protected function attendanceRate(): Attribute
    {
        return Attribute::get(function () {
            $attendances = $this->attendances;
            if ($attendances->isEmpty()) {
                return 0;
            }

            $total = $attendances->count();
            $present = $attendances->where('status', 'present')->count();

            return round(($present / $total) * 100, 2);
        });
    }


}
