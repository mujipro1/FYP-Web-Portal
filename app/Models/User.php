<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function farms()
    {
        return $this->hasMany(Farm::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function farmExpenses()
    {
        return $this->hasMany(FarmExpense::class);
    }
    public function isManager()
{
    return $this->role === 'manager'; // Assuming you have a 'role' column
}

}
