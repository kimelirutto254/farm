<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $table = 'farmers';
    
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'id_number',
        'phone_number',
        'dob',
        'permanent_male',
        'permanent_female',
        'temporary_male',
        'temporary_female',
        'company_id',
        'gender',
        'phone',
        'address',
        'status',
        'inspector_id',
        'email',
        'password',
        'grower_id',
        'documents',
        'created_by',
        'region',
        'town',
        'route',
        'collection_center',
        'nearest_center',
        'leased_land',
        'inspection_status',
        'compliance_status',
        'sanction_status',
        'inspection_date',
        'household_size', // New field
        'farm_size', // New field
        'production_area', // New field
        'permanent_male_workers', // New field
        'permanent_female_workers', // New field
        'temporary_male_workers', // New field
        'temporary_female_workers', // New field
    ];

    public function inspector()
    {
        return $this->belongsTo(Inspectors::class, 'inspector_id');
    }

    // Accessor to get inspector name
    public function getInspectorNameAttribute()
    {
        return $this->inspector ? $this->inspector->name : null;
    }
}
