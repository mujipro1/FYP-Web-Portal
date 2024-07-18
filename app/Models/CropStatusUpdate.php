<?php
 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropStatusUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id', 
        'status', 
        'remarks', 
    ];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}

