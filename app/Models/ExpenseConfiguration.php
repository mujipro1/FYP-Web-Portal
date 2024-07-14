<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id', 
        'crop_id', 
        'expense_head', 
        'include'
    ];

    protected $casts = [
        'include' => 'boolean',
        'crop_id' => 'boolean',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

}
