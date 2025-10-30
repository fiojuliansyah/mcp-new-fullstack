<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'type',
        'value',
        'expired_at',
        'limit',
        'times_used',
        'max_uses_per_user',
        'min_purchase_amount',
        'is_active',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getFormattedValueAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }
        return 'RM' . number_format($this->value, 0, ',', '.');
    }
}