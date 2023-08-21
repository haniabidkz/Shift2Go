<?php

namespace App\Http\Controllers;

use App\Models\PaySlip;
use App\Models\User;
Use App\Models\Profile;
use App\Models\Rotas;
use App\Models\Role;
use App\Models\Utility;
use App\Exports\PayslipExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class PaySlipController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employees = User::where(
            [
                'created_by' => $created_by,
            ])->first();

        $month = [
            '01' => __('JAN'),
            '02' => __('FEB'),
            '03' => __('MAR'),
            '04' => __('APR'),
            '05' => __('MAY'),
            '06' => __('JUN'),
            '07' => __('JUL'),
            '08' => __('AUG'),
            '09' => __('SEP'),
            '10' => __('OCT'),
            '11' => __('NOV'),
            '12' => __('DEC'),
        ];

        $year = [
            '2023' => '2023',
            '2024' => '2024',
            '2025' => '2025',
            '2026' => '2026',
            '2027' => '2027',
            '2028' => '2028',
            '2029' => '2029',
            '2030' => '2030',
            '2031' => '2031',
            '2032' => '2032',
        ];

        return view('payslip.index', compact('employees', 'month', 'year'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'month' => 'required',
                'year' => 'required',
            ]
        );

        if ($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $user = Auth::user();
        $created_by = $user->get_created_by();

        $month = $request->month;
        $year  = $request->year;

        $formate_month_year = $year . '-' . $month;
        $validatePaysilp    = PaySlip::where('salary_month', '=', $formate_month_year)->where('created_by', $created_by)->pluck('user_id');


        $payslip_employee   = Profile::where('start_date', '<=', date($year . '-' . $month . '-t'))->count();

        if ($payslip_employee > count($validatePaysilp))
        {
            if($user->type == 'company' || Auth::user()->acount_type == 2)
            {
                $users = User::where('created_by',$created_by)->whereNotIn('id', $validatePaysilp)->get();
            }
            else
            {
                $users = User::where('created_by',$created_by)->whereNotIn('id', $validatePaysilp)->get();
            }

            if (!empty($employeesSalary)) {
                return redirect()->route('payslip.index')->with('error', __('Please set employee salary.'));
            }

            $weekly_money = 0;
            foreach($users as $user)
            {
                $salary_data = Profile::select('default_salary','custome_salary','start_date')->where('user_id',$user->id)->first()->toArray();
                // if(!empty($salary_data['default_salary']) || !empty($salary_data['custome_salary']) && !empty($salary_data['start_date']))
                // {
                    $employees = Profile::where('user_id',$user->id)->where('start_date', '<=', date($year . '-' . $month . '-t'))->get();
                    foreach ($employees as $employee)
                    {
                        $time_counter = 0;
                        $role_hour = [];
                        $weekly_money = 0;
                        $tr_cost =[];
                        $hour_cost = 0;
                        $sum = 0;
                        $date_role_rotas = Rotas::select('*',DB::raw('TIMEDIFF(end_time,start_time) as time_between1'))
                        ->whereMonth('rotas_date', date($month))->whereYear('rotas_date', date($year))->where('user_id',$employee->user_id)->get();

                        foreach($date_role_rotas as $date_role_rota)
                        {
                            $time_counter = $date_role_rota['time_diff_in_minut'];
                            $time_counter = $time_counter/60;
                            $h1 = (int)$time_counter;
                            $m1 = $time_counter - (int)$time_counter;
                            $m2 = 60 * $m1 / 1;
                            $m2 = (!empty($m2)) ? $m2 : 00 ;
                            $total_time =  $h1.''.__('Hours ').' '.(int)$m2.__('Minutes');

                            $salary_data = Profile::select('default_salary','custome_salary')->whereRaw('user_id = '.$date_role_rota['user_id'].'')->first()->toArray();

                            $default_salarys_array = [];
                            if(!empty($salary_data['default_salary']))
                            {
                                $default_salarys_array = json_decode($salary_data['default_salary'],true);
                            }

                            $custome_salary_array = [];
                            if(!empty($salary_data['custome_salary']))
                            {
                                $custome_salary_array = json_decode($salary_data['custome_salary'],true);
                            }
                            if(!empty($custome_salary_array) && !empty($date_role_rota['role_id']))
                            {
                                if( !empty($custome_salary_array[$date_role_rota['role_id']]) && !empty($custome_salary_array[$date_role_rota['role_id']]['custom_salary_by_hour']))
                                {
                                    $hour_cost1 = $time_counter * $custome_salary_array[$date_role_rota['role_id']]['custom_salary_by_hour'];

                                    if(!empty($custome_salary_array[$date_role_rota['role_id']]['custom_salary_by_shift']))
                                    {
                                        $hour_cost1 = $hour_cost1 + $custome_salary_array[$date_role_rota['role_id']]['custom_salary_by_shift'];
                                    }
                                    $hour_cost = $hour_cost + $hour_cost1;
                                }
                            }
                            else
                            {
                                if(!empty($default_salarys_array))
                                {
                                    if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                                    {
                                        $hour_cost1 = $time_counter * $default_salarys_array['salary'];
                                        $hour_cost = $hour_cost + $hour_cost1;
                                    }
                                }
                            }
                            $time_counter = $date_role_rota['time_diff_in_minut'];
                            $sum += $time_counter;
                        }
                        $tr_cost[] = round($hour_cost,2);
                        $weekly_money = array_sum($tr_cost);

                        $check = Rotas::where(['user_id' => $employee->user_id,'role_id' => 0])->first();

                        if($check){
                            $employee->role_id = $employee->role_id.',0';
                        }

                        $payslipEmployee                       = new PaySlip();
                        $payslipEmployee->user_id              = $employee->user_id;
                        $payslipEmployee->role_id              = $employee->role_id;
                        $payslipEmployee->net_payble           = $weekly_money;
                        $payslipEmployee->time_diff_in_minut   = $sum /60;
                        $payslipEmployee->salary_month         = $formate_month_year;
                        $payslipEmployee->status               = 0;
                        $payslipEmployee->created_by           = $created_by;
                        $payslipEmployee->save();
                    }
                // }
                // else
                // {
                //     return redirect()->route('payslip.index')->with('error', __('Please set employee joining date & salary.'));
                // }
            }
            return redirect()->route('payslip.index')->with('success', __('Payslip successfully created.'));
        }
        else
        {
            return redirect()->route('payslip.index')->with('error', __('Payslip Already created.'));
        }
    }

    public function destroy($id)
    {
        return redirect()->back()->with('error', __('This operation is not perform due to demo mode.'));
        $payslip = PaySlip::find($id);
        $payslip->delete();
        return true;
    }

    public function search_json(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $formate_month_year = $request->datePicker;
        if($user->type == 'company' || Auth::user()->acount_type == 2)
        {
            $validatePaysilp = PaySlip::where('salary_month', '=', $formate_month_year)->where('created_by', $created_by)->get()->toarray();
        }
        else
        {
            $validatePaysilp = PaySlip::where('user_id',$user->id)->where('salary_month', '=', $formate_month_year)->where('created_by', $created_by)->get()->toarray();
        }

        $data = [];
        if (empty($validatePaysilp))
        {
            $data = [];
            return;
        }
        else
        {
            foreach ($validatePaysilp as $employee) {
                $users = User::where('id',$employee['user_id'])->get()->toarray();
                foreach($users as $user)
                {
                    if (Auth::user()->acount_type == 3) {
                        if (Auth::user()->id == $employee['user_id'])
                        {
                            $tmp   = [];
                            $tmp[] = $user['id'];
                            $tmp[] = $employee['user_id'];
                            $tmp[] = $user['first_name'].' '.$user['last_name'];
                            $tmp[] =  round($employee['time_diff_in_minut'],2);
                            $tmp[] = !empty($employee['net_payble']) ? $employee['net_payble'] : '-';
                            $tmp[] = !empty($employee['net_payble']) ? $employee['net_payble'] : '-';
                            if ($employee['status'] == 1) {
                                $tmp[] = 'Paid';
                            } else {
                                $tmp[] = 'UnPaid';
                            }
                            $tmp[]  = !empty($employee['id']) ? $employee['id'] : 0;
                            $tmp['url']  =$employee['id'];
                            $data[] = $tmp;
                        }
                    }
                    else
                    {
                        $tmp   = [];
                        $tmp[] = $user['id'];
                        $tmp[] = $employee['user_id'];
                        $tmp[] = $user['first_name'].' '.$user['last_name'];
                        $tmp[] =  round($employee['time_diff_in_minut'],2);
                        $tmp[] = !empty($employee['net_payble']) ? $employee['net_payble'] : '-';
                        $tmp[] = !empty($employee['net_payble']) ? $employee['net_payble'] : '-';
                        if ($employee['status'] == 1) {
                            $tmp[] = 'Paid';
                        } else {
                            $tmp[] = 'UnPaid';
                        }
                        $tmp[]  = !empty($employee['id']) ? $employee['id'] : 0;
                        $tmp['url']  =$employee['id'];
                        $data[] = $tmp;
                    }
                }
            }
            return $data;
        }
    }
    public function pdf($id, $month)
    {
        $rotas = 0;
        $user_salary=[];
        $user_salaries=[];
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $payslip  = PaySlip::where('user_id', $id)->where('salary_month', date($month))->where('created_by', $created_by)->first();

        $roles_id = explode(',',$payslip->role_id);
        $profile = Profile::where('user_id',$id)->first();

        $default_salary_array = json_decode($profile->default_salary,true);
        $custome_salary_array = json_decode($profile->custome_salary,true);

        foreach($custome_salary_array as $custome_salary)
        {
            $user_salary[] =  $custome_salary['custom_salary_by_hour'];
            $user_salary1[] =  $custome_salary['custom_salary_by_shift'];
        }

        foreach($roles_id as $a => $r_id)
        {
            $roles = Role::where('id',$r_id)->pluck('name');
            foreach($roles as $role)
            {
                $user_salaries[$a]['name'] = $role;
            }

            foreach($user_salary as $r => $s)
            {
                $user_salaries[$r]['salary'] = $s;
            }
            foreach($user_salary1 as $y => $d)
            {
                $data = !empty($d)? $d : 0;
                $user_salaries[$y]['shift_salary'] = $data;
            }
            $user_rotas = Rotas::where('user_id',$id)->where('rotas_date','like', "%{$month}%")->get()->toArray();

            $sum=0;
            $sum1=0;
            $hour_cost1 =0;
            $hour_cost =0;
            $default_shift = 0;
            $default_salary = 0;
            foreach($user_rotas as $k => $user_rota)
            {
                if($user_rota['role_id']==$r_id)
                {
                    $minit_counter= $user_rota['time_diff_in_minut'];
                    $sum += $minit_counter;
                }
                if($user_rota['role_id']==0)
                {
                    $default_shift = 0;
                    $default_salary = $default_salary_array['salary'];
                    $minit_counter= $user_rota['time_diff_in_minut'];
                    $sum1 += $minit_counter;
                }
            }
            if($r_id == 0)
            {
                $user_salaries[$a]['shift_salary'] = $default_shift;
                $user_salaries[$a]['salary'] = $default_salary;
            }
            $user_salaries[$a]['time'] =$sum1 / 60;
            $user_salaries[$a]['time'] = $sum / 60;
        }
        $employee = User::find($payslip->user_id);
        return view('payslip.pdf', compact('payslip','employee','user_salaries'));
    }

    public function paysalary($id, $date)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employeePayslip = PaySlip::where('user_id', '=', $id)->where('created_by', $created_by)->where('salary_month', '=', $date)->first();
        if (!empty($employeePayslip)) {
            $employeePayslip->status = 1;
            $employeePayslip->save();

            return redirect()->route('payslip.index')->with('success', __('Payslip Payment successfully.'));
        } else {
            return redirect()->route('payslip.index')->with('error', __('Payslip Payment failed.'));
        }
    }

    public function send($id, $month)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $payslip  = PaySlip::where('user_id', $id)->where('salary_month', $month)->where('created_by', $created_by)->first();
        $employee = User::find($payslip->user_id);

        $payslip->name  = $employee->first_name;
        $payslip->email = $employee->email;
        $payslipId    = Crypt::encrypt($payslip->id);
        $payslip->url = route('payslip.payslipPdf', $payslipId);
        $setings = Utility::settings();

        return redirect()->back()->with('success', __('Payslip successfully sent.'));
    }

    public function payslipPdf($id)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $payslipId = Crypt::decrypt($id);

        $payslip  = PaySlip::where('id', $payslipId)->where('created_by', $created_by)->first();
        $employee = User::find($payslip->user_id);

        $payslipDetail = Utility::employeePayslipDetail($payslip->user_id);

        return view('payslip.payslipPdf', compact('payslip', 'employee', 'payslipDetail'));
    }

    public function PayslipExport(Request $request)
    {
        $name = 'payslip_' . date('Y-m-d i:h:s');
        $data = \Excel::download(new PayslipExport($request), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    public function bulk_pay_create($date)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $Employees       = PaySlip::where('salary_month', $date)->where('created_by', $created_by)->get();
        $unpaidEmployees = PaySlip::where('salary_month', $date)->where('created_by', $created_by)->where('status', '=', 0)->get();

        return view('payslip.bulkcreate', compact('Employees', 'unpaidEmployees', 'date'));
    }

    public function bulkpayment(Request $request, $date)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $unpaidEmployees = PaySlip::where('salary_month', $date)->where('created_by',$created_by)->where('status', '=', 0)->get();
        foreach ($unpaidEmployees as $employee) {
            $employee->status = 1;
            $employee->save();
        }
        return redirect()->route('payslip.index')->with('success', __('Payslip Bulk Payment successfully.'));
    }
}
