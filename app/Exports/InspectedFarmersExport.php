<?php

namespace App\Exports;

use App\Models\Farmer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InspectedFarmersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $inspectedFarmers = Farmer::where('inspection_status','=', 'Complete')->get();

        $data = new Collection();

        foreach ($inspectedFarmers as $inspectedFarmer) {
            $inspectorName = $inspectedFarmer->inspector->name ?? 'N/A'; // Assuming the inspector's name is accessible via the 'name' attribute in the User model
            $productionArea = $inspectedFarmer->productionArea->name ?? 'N/A'; // Assuming the production area name is accessible via the 'name' attribute in the ProductionArea model

            $data->push([
                "Grower ID" => $inspectedFarmer->grower_id,
                "First Name" => $inspectedFarmer->first_name,
                "Last Name" => $inspectedFarmer->last_name,
                "Production Area" => $productionArea,
                "Total Area" => $inspectedFarmer->total_area,
                "Inspector Name" => $inspectorName,
                "Inspection Date" => $inspectedFarmer->inspection_date,
                "Inspection Status" => $inspectedFarmer->inspection_status,
    
           // Assuming total area is a field in the Farmer model
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            "Grower ID",
            "First Name",
            "Last Name",
            "Production Area",
            "Total Area",
            "Inspector Name",
            "Inspection Date",
            "Inspection Status",
         
         
        ];
    }
}
