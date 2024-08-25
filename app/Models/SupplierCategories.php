<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierCategories extends Model
{
    protected $table = 'supplier_categories';
    protected $fillable = [
        'name',
        'created_by',
    ];
    
}
