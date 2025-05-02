<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chatting extends Model
{
    use HasFactory;

    protected $table = 'chatting';

    protected $fillable = [
        'message',
        'to',
        'from',
        'created_at',
    ];

    public $timestamps = false; // if you're only using `created_at` manually

    // Optional: relationships to User model
    public function sender()
    {
        return $this->belongsTo(User::class, 'from');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to');
    }
}
