<?php

namespace App\Exports;

use App\Models\Availabilities;
use App\Models\Leave;
use App\Models\Availability;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AvailabilitiesExport implements FromCollection ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $id = Auth::user()->id;
       $data = Availability::where('created_by', $id)->get();
       foreach($data as $k => $availailty)
        {
            if($availailty->repeat_week == 0){
                $repeat_week = "No Repeat Week";
            }
            else if($availailty->repeat_week == 1){
                $repeat_week = "Every Week";
            }
            else if($availailty->repeat_week == 2){
                $repeat_week = "Repeat 2nd Week";
             }
            $username   = Leave::username($availailty->user_id);
            unset($availailty->id,$availailty->created_by,$availailty->created_at,$availailty->updated_at,$availailty->availability_json);
            $data[$k]["user_id"]     = $username;
            $data[$k]["repeat_week"]     = $repeat_week;
            
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            "User Name",
            "Name",
            "Start Date",
            "End Date",
            "Repeat Week",
        ];
    }
}