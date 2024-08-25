<?php

namespace App\Exports;

use App\Models\Suppliers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UninspectedSuppliersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $inspectedFarmers = Suppliers::where('inspection_status','=', 'Incomplete')->get();

        $data = new Collection();

        foreach ($inspectedFarmers as $inspectedFarmer) {
            $inspectorName = $inspectedFarmer->inspector->name ?? 'N/A'; // Assuming the inspector's name is accessible via the 'name' attribute in the User model

            $data->push([
                "ID" => $inspectedFarmer->id,
                "User ID" => $inspectedFarmer->user_id,
                "Inspector ID" => $inspectedFarmer->inspector_id,
                "Inspector Name" => $inspectorName,
                "Inspection Date" => $inspectedFarmer->inspection_date,
                "Compliance Status" => $inspectedFarmer->compliance_status,
                "First Name" => $inspectedFarmer->first_name,
                "Last Name" => $inspectedFarmer->last_name,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "User ID",
            "Inspector ID",
            "Inspector Name",
            "Inspection Date",
            "Compliance Status",
            "First Name",
            "Last Name",
          
        ];
    }
}
