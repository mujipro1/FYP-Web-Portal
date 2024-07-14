<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'user_id',
        'date',
        'amount',
        'details',
    ];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
