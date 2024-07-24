<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FarmWorker;
use App\Models\Expense;
use App\Models\FarmExpense;

class Reconciliation extends Model
{
    use HasFactory;
    // tabel
    protected $table = 'reconciliation';
    protected $fillable = [
        'user_id',
        'expense_id',
        'amount',
        'date',
        'spent',
        'farm_expense_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function farmExpense()
    {
        return $this->belongsTo(FarmExpense::class);
    }
}
