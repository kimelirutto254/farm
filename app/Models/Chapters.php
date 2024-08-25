<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapters extends Model
{
    protected $table = 'chapters';
    protected $fillable = [
        'id',
        'code',
        'name',

    ];
    public function subchapters()
    {
        return $this->hasMany(Subchapter::class);
    }
   
}
