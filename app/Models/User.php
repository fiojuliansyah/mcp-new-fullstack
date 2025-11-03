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

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }


}
