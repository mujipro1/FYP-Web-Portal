<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'identifier',
        'farm_id',
        'acres'
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function deras()
    {
        return $this->belongsToMany(Dera::class, 'crop_deras')->withPivot('acres');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function expenseConfigurations()
    {
        return $this->hasMany(ExpenseConfiguration::class);
    }

}
