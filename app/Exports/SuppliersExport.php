<?php

namespace App\Exports;

use App\Models\Suppliers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuppliersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $suppliers = Suppliers::all();

        $data = new Collection();

        foreach ($suppliers as $supplier) {
            $data->push([
                "Supplier No." => $supplier->id,
                "Name" => $supplier->name,
                "Phone Number" => $supplier->phone,
                "Contact Email" => $supplier->email,

                "Location" => $supplier->location,
                "Registered Date" => $supplier->created_at,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            "Supplier No.",
            "Name",
            "Phone",
            "Contact Email",

            "Location",
            "Registered Date",

        
        ];
    }
}
