<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'classroom_id',
        'schedule_id',
    ];

    public function materialFiles()
    {
        return $this->hasMany(MaterialFile::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
