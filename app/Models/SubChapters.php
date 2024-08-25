<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubChapters extends Model
{   
    protected $table='sub_chapter';
    protected $fillable = [
        'id',
        'code',
        'chapter_id',

        'name',

    ];


    public function chapter()
    {
        return $this->hasOne('App\Models\Chapters', 'id', 'chapter_id');
    }

}
