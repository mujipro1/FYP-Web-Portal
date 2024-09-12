<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropMap extends Model
{
    use HasFactory;
    protected $table = 'cropmap';

    protected $fillable = [
        'farm_id',
        'crop_id',
        'coords'
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
