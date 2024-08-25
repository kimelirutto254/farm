<?php

namespace App\Exports;

use App\Models\Farmer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NonCompliantFarmersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $compliantFarmers = Farmer::where('compliance_status', 'noncompliant')->get();

        $data = new Collection();

        foreach ($compliantFarmers as $farmer) {
            $data->push([
                "Grower ID" => $farmer->grower_id,
                "First Name" => $farmer->first_name,
                "Last Name" => $farmer->last_name,
                "Compliance Status" => $farmer->compliance_status,

                "Production Area" => $farmer->production_area,
                "Total Area" => $farmer->total_area,
                "Inspection Date" => $farmer->inspection_date,

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
            "Compliance Status",
            "Inspection Date",


        ];
    }
}

