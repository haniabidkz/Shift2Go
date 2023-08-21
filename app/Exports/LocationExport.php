<?php

namespace App\Exports;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LocationExport implements FromCollection , WithHeadings
{

    public function collection()
    {
        $id = Auth::user()->id;
        $data = Location::where('created_by', $id)->get();
        foreach($data as $k => $location)
        {
            unset($location->id,$location->created_by,$location->created_at,$location->updated_at);
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Address",
        ];
    }
}
