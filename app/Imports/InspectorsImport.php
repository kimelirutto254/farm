<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Inspectors;
use Illuminate\Support\Facades\Auth;

class InspectorsImport implements ToModel
{
    use Importable;

    public function model(array $row)
    {
        return new Inspectors([
            'current_company' => Auth::user()->company_id, // Assuming the authenticated user has a company_id
            'username' => $row[0], // Index based on the order of your data
            'name' => $row[1],
            'email' => $row[2],
            'phone' => $row[3],
            'id_number' => $row[4]
        ]);
    }
}
