<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsCoordinate extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'certified_crop_longitude', // Add new longitude columns
        'other_crop_longitude',
        'residential_longitude',
        'conservation_longitude',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}
