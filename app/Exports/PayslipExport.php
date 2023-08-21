<?php

namespace App\Exports;


use App\Models\Employee;
use App\Models\PaySlip;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayslipExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;

    function __construct($data) {
        $this->data = $data;
    }

    public function collection()
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $request=$this->data;

        $data = PaySlip::where('created_by', $created_by);

        if(isset($request->filter_month) && !empty($request->filter_month)){
            $month=$request->filter_month;
        }else{
            $month=date('m', strtotime('last month'));
        }

        if(isset($request->filter_year) && !empty($request->filter_year)){
            $year=$request->filter_year;
        }else{
            $year=date('Y');
        }
        $formate_month_year = $year . '-' . $month;
        $data->where('salary_month', '=', $formate_month_year);
        $data=$data->get();
        $result = array();
        foreach($data as $k => $payslip)
        {
            $result[] = array(
                'employee_id'=> !empty($payslip->employees) ? $payslip->employees->id : '',
                'employee_name' => (!empty($payslip->employees)) ? $payslip->employees->first_name : '',
                'net_salary' =>  \Auth::user()->priceFormat($payslip->net_payble),
                'status' =>  $payslip->status == 0 ? 'UnPaid' :  'Paid',
            );
        }

        return collect($result);
    }

    public function headings(): array
    {
        return [
            "EMP ID",
            "Name",
            "Net Salary",
            "Status",


        ];
    }
}
