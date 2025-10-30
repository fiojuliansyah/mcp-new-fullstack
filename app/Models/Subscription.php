<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'form_id',
        'schedule_id',
        'plan_id',
        'price',
        'coupon_id',
        'payment_method',
        'payment_status',
        'total',
        'status',
        'start_date',
        'end_date',
        'renewal_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'renewal_date' => 'datetime',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_subscription');
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}