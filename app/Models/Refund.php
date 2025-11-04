<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'amount',
        'bank',
        'reason',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
