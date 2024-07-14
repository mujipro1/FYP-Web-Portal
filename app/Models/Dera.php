<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dera extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'farm_id',
        'number_of_acres',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function crops()
    {
        return $this->belongsToMany(Crop::class, 'crop_deras')->withPivot('acres');
    }
}
