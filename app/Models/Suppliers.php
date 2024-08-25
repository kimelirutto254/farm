<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Percentage;

class Suppliers extends Model
{
    protected $table = 'suppliers';
    protected $fillable = [
    

        'name',
        'email',
        'phone',
        'location',
        'category',
        'created_by',
        'inspection_status',
        'compliance_status',
        'sanction_status',


    ];

  
    public function category()
    {
        return $this->hasOne('App\Models\SupplierCategories', 'id', 'category');
    }
    

}
