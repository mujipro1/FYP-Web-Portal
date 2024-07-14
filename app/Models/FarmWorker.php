<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmWorker extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'user_id',
        'access'
    ];

    /**
     * Get the farm that owns the worker.
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * Get the user that is the worker.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
