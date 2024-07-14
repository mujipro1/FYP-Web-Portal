<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'user_id',
        'expense_type',
        'expense_subtype',
        'date',
        'details',
        'total'
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
