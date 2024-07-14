<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'address',
        'number_of_acres',
        'has_deras',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deras()
    {
        return $this->hasMany(Dera::class);
    }

    public function crops()
    {
        return $this->hasMany(Crop::class);
    }

    public function farmExpenses()
    {
        return $this->hasMany(FarmExpense::class);
    }

    public function expenseConfigurations()
    {
        return $this->hasMany(ExpenseConfiguration::class);
    }

}
