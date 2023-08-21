<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Employee;
use App\Models\Utility;
use App\Models\User;
use Hamcrest\Type\IsArray;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AvailabilitiesExport;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $availabilitys = Availability::where('created_by', $created_by)->where('user_id', $userId)->get();
        if(Auth::user()->acount_type == 1) {
            $availabilitys = Availability::where('created_by', $created_by)->get();
        }

        $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        $filter_employees = [];
        $filter_employees['all0'] = __('Select all');
        if(!empty($employees))
        {
            foreach($employees as $employee)
            {
                $filter_employees[$employee['id']] = $employee['first_name'].' '.$employee['last_name'];
            }
        }
        return view('availability.index',compact('availabilitys','filter_employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        $filter_employees = [];
        if(!empty($employees))
        {
            foreach($employees as $employee)
            {
                $filter_employees[$employee['id']] = $employee['first_name'].' '.$employee['last_name'];
            }
        }
        return view('availability.create',compact('filter_employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $userId = Auth::id();

        $availability_json = [];
        $availability_json2 = [];
        $availability_json3 = [];
        $availability_json_impload = '';
        if(!empty($request->timetable) && is_array($request->timetable)) {
            foreach($request->timetable as $key => $timetable) {
                $availability_json2 = [];
                $availability_json[$key]['day'] = $key;
                $availability_json2['day'] = $key;

                if(!empty($timetable) && is_array($timetable)) {
                    foreach($timetable as $key2 => $timetable_time) {
                        $start = explode(' - ',$timetable_time['time'])[0];
                        $end = explode(' - ',$timetable_time['time'])[1];
                        $backgroundColor =  ($timetable_time['availability'] == 'availability') ? 'rgba(0, 200, 0, 0.5)' : 'rgba(200, 0, 0, 0.5)';
                        $availability_json[$key]['periods'][] = array('start' => $start,'end' => $end, 'backgroundColor' => $backgroundColor);
                        $availability_json2['periods'][] = array('start' => $start,'end' => $end, 'backgroundColor' => $backgroundColor);
                    }
                }
                $availability_json3[] = json_encode($availability_json2);
            }
        }
        $availability_json_impload = '['. implode(',',$availability_json3) .']';

        $availability = new Availability();
        $availability->user_id = $request->user_id;
        $availability->name = $request->name;
        $availability->start_date = $request->start_date;
        $availability->end_date = (!empty($request->end_date)) ? $request->end_date : NULL ;
        $availability->repeat_week = (!empty($request->end_date)) ? 0 : $request->repeat_week ;
        $availability->availability_json = $availability_json_impload;
        $availability->created_by = $created_by;

        $availability->save();

        $settings  = Utility::settings(Auth::user()->ownerId());

        if(isset($settings['availability_create_notificaation']) && $settings['availability_create_notificaation'] ==1){
            $availability    = Crypt::encrypt($availability->id);
            $employee = User::find($request->user_id);
            $uArr = [
                'employee_name' => $employee->first_name,
                'email'         => $employee->email,
                'company_name'  => \Auth::user()->first_name,
            ];
            Utility::send_slack_msg('new_availability',$uArr);
            // $msg = __('Availability pattern has been added by the ').\Auth::user()->first_name.'.';
            // Utility::send_slack_msg($msg);
        }
        if(isset($settings['telegram_availability_create_notificaation']) && $settings['telegram_availability_create_notificaation'] ==1){
            $RotaId    = Crypt::encrypt($rotas->id);
            $employee = User::find($request->user_id);
            $uArr = [
                'employee_name' => $employee->first_name,
                'email'         => $employee->email,
                'company_name'  => \Auth::user()->first_name,
            ];
            Utility::send_telegram_msg('new_availability',$uArr);
                // $resp = __('Availability pattern has been added by the ').\Auth::user()->first_name.'.';
                // Utility::send_telegram_msg($resp);
        }

        $module ='New Availability';
        $webhook=  Utility::webhookSetting($module);
        if($webhook)
        {
            $parameter = json_encode($availability);
            // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
            $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);
        }

        return redirect()->back()->with('success', __('Your availability pattern has been added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function show(availability $availability)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function edit(availability $availability)
    {
        $user_id = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        $filter_employees = [];
        if(!empty($employees))
        {
            foreach($employees as $employee)
            {
                $filter_employees[$employee['id']] = $employee['first_name'].' '.$employee['last_name'];
            }
        }
        return view('availability.edit',compact('availability','user_id','filter_employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, availability $availability)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $userId = Auth::id();

        $availability_json = [];
        $availability_json2 = [];
        $availability_json3 = [];
        $availability_json_impload = '';
        if(!empty($request->timetable) && is_array($request->timetable)) {
            foreach($request->timetable as $key => $timetable) {
                $availability_json2 = [];
                $availability_json[$key]['day'] = $key;
                $availability_json2['day'] = $key;

                if(!empty($timetable) && is_array($timetable)) {
                    foreach($timetable as $key2 => $timetable_time) {
                        $start = explode(' - ',$timetable_time['time'])[0];
                        $end = explode(' - ',$timetable_time['time'])[1];
                        $backgroundColor =  ($timetable_time['availability'] == 'availability') ? 'rgba(0, 200, 0, 0.5)' : 'rgba(200, 0, 0, 0.5)';
                        $availability_json[$key]['periods'][] = array('start' => $start,'end' => $end, 'backgroundColor' => $backgroundColor);
                        $availability_json2['periods'][] = array('start' => $start,'end' => $end, 'backgroundColor' => $backgroundColor);
                    }
                }
                $availability_json3[] = json_encode($availability_json2);
            }
        }
        $availability_json_impload = '['. implode(',',$availability_json3) .']';

        $availability->user_id = $request->user_id;
        $availability->name = $request->name;
        $availability->start_date = $request->start_date;
        $availability->end_date = (!empty($request->end_date)) ? $request->end_date : NULL ;
        $availability->repeat_week = (!empty($request->end_date)) ? 0 : $request->repeat_week ;
        $availability->availability_json = $availability_json_impload;
        $availability->created_by = $created_by;
        $availability->save();
        return redirect()->back()->with('success', __('Your availability pattern has been added'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function destroy(availability $availability)
    {
        //
        $availability->delete();
        return redirect()->back()->with('success', __('Delete Succsefully'));
    }

    public function export()
    {
        $name = 'Availabity' . date('Y-m-d i:h:s');
        $data = Excel::download(new AvailabilitiesExport(), $name . '.xlsx'); ob_end_clean();

        return $data;
    }
}
