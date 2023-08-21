<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimesheetExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = \Auth::user();
        $created_by = $user->get_created_by();

        $data=Attendance::where('created_by', $created_by)->get();

        foreach($data as $k=>$timesheet)
        {
            $data[$k]["user_id"]=!empty($timesheet->employee) ? $timesheet->employee->first_name : '';
            $data[$k]["location_id"]=!empty($timesheet->location) ? $timesheet->location->name : '';
            // $data[$k]["created_by"]=User::employee($timesheet->created_by);
            unset($timesheet->created_at,$timesheet->updated_at);
        }
        return $data;
    }
    public function headings(): array
    {
        return [
            "ID",
            "Employee Name",
            "Location",
            "Date",
            "Hour",
            "Remark",
            "Created By"
        ];
    }
}
