<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromCollection ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $auth = Auth::user();
        $id = $auth->id;
        $data = Employee::get();
        if($auth->type == 'company') {
            $data = Employee::where('created_by', $id)->where('type', 'employee')->where('is_delete',0)->get();
        }
        if($auth->type == 'employee') {
            $data = Employee::where('id', $id)->where('type', 'employee')->where('is_delete',0)->get();
        }

        foreach($data as $k => $employee)
        {
        if($employee->acount_type == 0){
            $type = '';
        }
        else if($employee->acount_type == 1){
            $type = 'Admin';
        }
        elseif($employee->acount_type == 2){
            $type =  'Manager';
        }

        elseif($employee->acount_type == 3){
            $type =  'Employee' ;
        }

            unset($employee->id,$employee->password,$employee->email_verified_at,$employee->company_detail,$employee->company_setting,
            $employee->mode,$employee->employee_setting,$employee->issue_by,$employee->created_by,$employee->plan,$employee->plan_expire_date,$employee->requested_plan,
            $employee->manager_permission,$employee->created_at,$employee->updated_at,$employee->avatar,$employee->messenger_color);

            $data[$k]["acount_type"]     = $type;
        }


        return $data;
    }

    public function headings(): array
    {
        return [
            "First Name",
            "Middle Name",
            "Last Name",
            "Company Name",
            "Type",
            "Email ID",
            "Account Type",
            "Language"
        ];
    }
}
