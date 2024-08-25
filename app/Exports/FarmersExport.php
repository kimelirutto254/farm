<?php

namespace App\Exports;

use App\Models\Farmer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FarmersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = Farmer::all();
        foreach ($data as $k => $farmer) {
            // Mapping relevant fields for farmers
            $data[$k] = [
                $farmer->grower_id,
                $farmer->id_number,
                $farmer->phone_number,
                $farmer->first_name,
                $farmer->middle_name,
                $farmer->last_name,
    
                $farmer->gender,
             
              
      
                $farmer->region,
                $farmer->production_area,
                $farmer->farmer_type,
                $farmer->town,
                $farmer->route,
                $farmer->collection_center,
                $farmer->nearest_center,
          
                $farmer->leased_land,
                $farmer->inspection_status,
                $farmer->inspection_date,
                $farmer->compliance_status,
                $farmer->sanctioned_status,
                $farmer->created_at,
                $farmer->updated_at,
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            "Grower ID",
            "ID Number",
            "Phone Number",
            "First Name",
            "Middle Name",
           "Last Name",
          
            "Gender",
          
  
            "Region",
            "Production Area",
            "Farmer Type",
            "Town",
            "Route",
            "Collection Center",
            "Nearest Center",
    
            "Leased Land",
            "Inspection Status",
            "Inspection Date",
            "Compliance Status",
            "Sanctioned Status",
                
            "Created At",
            "Updated At",
        ];
    }
}
