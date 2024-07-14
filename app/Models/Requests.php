<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;
    protected $table = 'requests';
    protected $fillable = [
        'user_id',
        'user_info',
        'farm_info',
        'status',
        'details',
    ];

    protected $casts = [
        'user_info' => 'array',
        'farm_info' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
