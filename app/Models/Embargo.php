<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Embargo extends Model
{
    protected $table = 'embargoes';

    protected $fillable = [
        'start_date',
        'end_date',
        'message',
        'to_employees',
        'created_by'
    ];

    public function getCountEmployee()
    {
        $to_employees = $this->to_employees;
        $no_of_employee = '0 Employees';
        if(!empty($to_employees)) {
            $no_of_employee = count(explode(',',$to_employees)).' Employees';
        }
        return $no_of_employee;
    }

    public function getDateBetween()
    {
        $date_formate = User::CompanyDateFormat('D d F Y');
        $start_date =  date('<b>'.$date_formate.'</b>', strtotime($this->start_date));
        $end_date =  date('<b>'.$date_formate.'</b>', strtotime($this->end_date));
        if($start_date == $end_date)
        {
            $datebetween = $start_date;
        }
        else
        {
            $datebetween = $start_date .' - '. $end_date;
        }
        return $datebetween;
    }
}
