<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MaterialFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'file_url',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function getFileFullUrlAttribute()
    {
        if (!$this->file_url) {
            return null;
        }

        return Storage::disk(config('filesystems.default'))->url($this->file_url);
    }
}
