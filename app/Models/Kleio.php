<?php

namespace App\Models;

use App\Models\Farm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kleio extends Model
{
    use HasFactory;

    protected $table = 'kleio'; // Specify the table name

    protected $fillable = [
        'recommendation',
        'fun_fact',
        'record_date',
        'farm_id'
    ];

    // farm id from farms table as foriegn key
 

    public $timestamps = false; // Enable created_at & updated_at
}
