<?php

namespace App\Exports;

use App\Models\Rotas;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RotasExport implements FromCollection,WithHeadings
{
    public function collection()
    {
        $id = Auth::user()->id;
        $data = Rotas::where('create_by', $id)->get();
        foreach($data as $k => $location)
        {
            $username   = Rotas::username($location->user_id);
            $data[$k]["user_id"]     = $username;
            unset($location->created_by,$location->created_at,$location->updated_at,$location->location_id,$location->issued_by,$location->role_id,$location->publish,$location->create_by);
        }
        return $data;
    }
    public function headings(): array
    {
        return [
            "Rota",
            "User",
            "Date",
            "Start time",
            "End time",
            "break time",
            "income",
            "notes",
            "shift_status",
        ];
    }
}
