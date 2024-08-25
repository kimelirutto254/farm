<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerPolygon extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'farmer_polygons';

    // Specify the primary key
    protected $primaryKey = 'id';

    // Indicate if the IDs are auto-incrementing
    public $incrementing = true;

    // Indicate if the model should be timestamped
    public $timestamps = true;

    // Specify which attributes should be mass-assignable
    protected $fillable = [
        'farmer_id',
        'farm_area_polygons',
        'crop_area_polygons',
        'conservation_area_polygons',
        'other_crops_area_polygons',
        'residential_area_polygons',
        'location',
    ];

  
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }
}
