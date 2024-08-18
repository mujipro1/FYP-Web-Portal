<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $table = 'map';

    protected $fillable = [
        'farm_id',
        'coords'
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
