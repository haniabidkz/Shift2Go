<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\pastemployees;
use App\Models\Report;
use App\Models\Rotas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PastemployeesController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $past_employees = Employee::where('created_by', $created_by)
                             ->where('is_delete', '1')
                             ->get();

        $this_month = date('m');
        $this_year = date('Y');
        $first_day_this_month = date('Y-m-01');
        $last_day_this_month  = date('Y-m-t');
        $box['month_employee'] = Employee::whereRaw('MONTH(created_at) = '.$this_month.'')->whereRaw('YEAR(created_at) = '.$this_year.'')->whereRaw('type = "employee"')
        ->whereRaw('is_delete = 0')->whereRaw('created_by = '.$userId.'')->count();
        $box['total_employee'] = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->count();
        $box['month_rotas'] = Rotas::whereRaw(' rotas_date BETWEEN "'.$first_day_this_month.'" AND "'.$last_day_this_month.'" ')->whereRaw('create_by ='.$created_by.'')->count();

        $date_rotas = Rotas::select(DB::raw('SUM(time_diff_in_minut) as time_diff_in_minut'))
                        ->whereRaw(' rotas_date BETWEEN "'.$first_day_this_month.'" AND "'.$last_day_this_month.'" ')->whereRaw('create_by ='.$created_by.'')->first()->toArray();
        $box['month_rotas_time'] = __('0 Hour');
        if(!empty($date_rotas))
        {
            $time = $date_rotas['time_diff_in_minut'] / 60;
            $h1 = (int)$time;
            $m1 = $time - (int)$time;
            $m2 = 60 * $m1 / 1;
            $m2 = (!empty($m2)) ? $m2 : 00 ;
            $total_time =  $h1.''.__(' Hour ').' '.(int)$m2.__(' Minute');
            $box['month_rotas_time'] = $total_time;
        }

        $month_rotas = Rotas::whereRaw(' rotas_date BETWEEN "'.$first_day_this_month.'" AND "'.$last_day_this_month.'" ')->whereRaw('create_by ='.$created_by.'')->get()->toArray();
        $rotas_responce = Report::rotas_chart($month_rotas);
        $month_cost = json_decode($rotas_responce,true);
        $box['month_rotas_cost'] = round($month_cost['hour_cost'],2);

        $box['month_leave'] = LeaveRequest::whereRaw('start_date BETWEEN "'.$first_day_this_month.'" AND "'.$last_day_this_month.'" ')->whereRaw('end_date BETWEEN "'.$first_day_this_month.'" AND "'.$last_day_this_month.'" ')->whereRaw('created_by ='.$created_by.'')->WhereRaw('leave_approval = 1')->count();
        $box['month_comapany_leave_use'] = LeaveRequest::whereRaw('start_date BETWEEN "'.$first_day_this_month.'" AND "'.$last_day_this_month.'" ')->whereRaw('end_date BETWEEN "'.$first_day_this_month.'" AND "'.$last_day_this_month.'" ')->whereRaw('leave_type = 1')->whereRaw('created_by ='.$created_by.'')->WhereRaw('leave_approval = 1')->count();
        $box['month_other_leave_use'] = LeaveRequest::whereRaw('start_date BETWEEN "'.$first_day_this_month.'" AND "'.$last_day_this_month.'" ')->whereRaw('end_date BETWEEN "'.$first_day_this_month.'" AND "'.$last_day_this_month.'" ')->whereRaw('leave_type = 2')->whereRaw('created_by ='.$created_by.'')->WhereRaw('leave_approval = 1')->count();

        return view('pastemployees.index',compact('past_employees','box'));
    }

    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }

    public function show(pastemployees $pastemployees)
    {
        //
    }

    public function edit(pastemployees $pastemployees)
    {
        //
    }

    public function update(Request $request, pastemployees $pastemployees)
    {
        //
    }

    public function destroy(pastemployees $pastemployees, Request $request)
    {
        //
    }
}
