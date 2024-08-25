<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionResponse extends Model
{
    use HasFactory;

    protected $fillable = ['farmer_id','requirement', 'chapter_id', 'subchapter_id', 'response'];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}
