<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Farmer;
use Illuminate\Support\Facades\Auth;

class FarmersImport implements ToModel
{
    use Importable;

    public function model(array $row)
    {
        return new Farmer([
            'company_id' => Auth::user()->company_id, // Assuming the authenticated user has a company_id
            'grower_id' => $row[0], // Index based on the order of your data
            'first_name' => $row[1],
            'middle_name' => $row[2],
            'last_name' => $row[3],
            'dob' => $row[4],
            'gender' => $row[5],
            'phone_number' => $row[6],
            'household_size' => $row[7],
            'farm_size' => $row[8],
            'production_area' => $row[9],
            'permanent_male' => $row[10],
            'permanent_female' => $row[11],
            'temporary_male' => $row[12],
            'temporary_female' => $row[13],
        ]);
    }
}
