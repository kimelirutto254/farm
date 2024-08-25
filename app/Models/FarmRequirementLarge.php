<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmRequirementLarge extends Model
{
    use HasFactory;
    protected $table = 'farm_requirements_large';

    protected $fillable = [
        'chapter',
        'subchapter', 
        'requirement_id',
        'requirement',
        'status',
    ];

}
