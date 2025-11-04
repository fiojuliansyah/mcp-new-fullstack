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
        'classroom_id',
        'schedule_day',
        'plan_id',
        'price',
        'coupon_id',
        'plusian_coupon_id',
        'payment_method',
        'payment_status',
        'total',
        'subtotal',
        'status',
        'payment_gateway_bill_id',
        'start_date',
        'end_date',
        'renewal_date',
        'cancel_reason',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'renewal_date' => 'datetime',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'classroom_id' => 'array',
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

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function plusianCoupon()
    {
        return $this->belongsTo(Coupon::class, 'plusian_coupon_id', 'id');
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}