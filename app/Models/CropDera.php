<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropDera extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'dera_id',
        'acres',
    ];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function dera()
    {
        return $this->belongsTo(Dera::class);
    }
}
