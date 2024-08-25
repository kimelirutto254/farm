<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter',
        'subchapter', // Add this line
        'requirement_id',
        'requirememnt',
        'status',
    ];

}
