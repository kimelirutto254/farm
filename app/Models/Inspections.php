<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Percentage;

class Inspections extends Model
{
    protected $table = 'farmers';
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'id_number',
        'phone_number',


        'gender',
        'phone',
        'address',
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
    ];

    public function documents()
    {
        return $this->hasMany('App\Models\EmployeeDocument', 'employee_id', 'employee_id')->get();
    }

  

   
    public static function employee_id()
    {
        $employee = Employee::latest()->first();

        return !empty($employee) ? $employee->id + 1 : 1;
    }

  
   
   
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    

  
    public static function employee_name($name)
    {

        $employee = Employee::where('id', $name)->first();
        if (!empty($employee)) {
            return $employee->name;
        }
    }


    public static function login_user($name)
    {
        $user = User::where('id', $name)->first();
        return $user->name;
    }

}
