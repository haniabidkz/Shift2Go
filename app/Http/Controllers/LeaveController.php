<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveRequest;
use App\Models\Profile;
use App\Models\Rotas;
use App\Models\User;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $userType = Auth::user()->type;
        $leave_requests = [];
        $employees = [];

        $haspermission = LeaveRequest::getLeaveHasPermission();

        $leave_status_requests = LeaveRequest::select('id')->where('leave_approval', 0)->where('created_by', $created_by)->count();

        if(Auth::user()->acount_type == 1) {
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
            $leave_requests = LeaveRequest::where('created_by', $created_by)->get();
        }
        if(Auth::user()->acount_type == 3 || Auth::user()->acount_type == 2) {
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->Where('id', $userId)->get();
            $leave_requests = LeaveRequest::where('user_id', $userId)->get();
        }

        $employee_data = Employee::whereRaw('id = '.$created_by.' ')->first();
        $setting = [];
        if(!empty($employee_data->company_setting))
        {
            $setting = json_decode($employee_data->company_setting,true);
        }
        $datetime1 = date_create(date('Y-m-d'));
        $datetime2 = date_create(date('Y-m-d'));
        $interval = date_diff($datetime1, $datetime2);
        $temp_week = $interval->format("%r%a")/7;
        $temp_week = (int)$temp_week;
        $temp_week = 0;

        $date_formate = User::CompanyDateFormat('d M Y');
        $week = 0;
        $start_day = (!empty($setting['company_week_start'])) ? $setting['company_week_start'] : 'monday';
        // $week_date = Rotas::getWeekArray($date_formate,$week,$start_day);
        $week_date = Rotas::getWeekArray($date_formate ,$temp_week*7 ,$start_day);

        $week_date['week_start'] = __(date('Y-m-d', strtotime($week_date[0])));
        $week_date['week_end'] = __(date('Y-m-d', strtotime($week_date[6])));

        return view('leave.index',compact('leave_requests','employees','week_date','created_by','haspermission','leave_status_requests', 'temp_week'));
    }

    public function create()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        if(Auth::user()->acount_type == 3 || Auth::user()->acount_type == 2) {
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->Where('id', $userId)->get();
        }
        $employees_select = [];
        foreach($employees as $employee){
            $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }

        return view('leave.create',compact('employees_select'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        if($user->acount_type == 3){
            $start_date = new DateTime($request->start_date);
            $end_date = new DateTime($request->end_date);
            $today = new DateTime(date('Y-m-d'));
            if($start_date <= $today && $end_date <= $today){
                return redirect()->back()->with('error', __('You cannot request leave for a past date.'));
            }
        }

        if(!empty($request->input('user_id'))) {
            $user_id = $request->input('user_id');
        } else {
            $user_id = $userId;
        }
        $issue_by = $userId;
        if(!empty($request->paid_status)) {
            $paid_status=  'paid';
        } else {
            $paid_status=  'unpaid';
        }
        $leave_time = [];
        if($request->leave_time_type == 'total') {
            $leave_time['leave_time_type'] = $request->leave_time_type;
            $leave_time['leave_time1'] = $request->leave_time1;
        }
        if($request->leave_time_type == 'daily') {
            $leave_time['leave_time_type'] = $request->leave_time_type;
            if(!empty($request->leave_time2)) {
                foreach($request->leave_time2 as $key => $leave_time_by_dail_hour) {
                    $leave_time['leave_time_by_dail_hour'][$key] =  $leave_time_by_dail_hour[0];
                }
            }
        }

        if(!empty($request->user_id)) {
            foreach($request->user_id as $user) {
                $leaverequest = new LeaveRequest();
                $leaverequest->user_id          = $user;
                $leaverequest->issue_by         = $issue_by;
                $leaverequest->leave_type       = $request->leave_type;
                $leaverequest->start_date       = $request->start_date;
                $leaverequest->end_date         = $request->end_date;
                $leaverequest->message          = $request->message;
                $leaverequest->leave_approval   = ($userId = Auth::user()->acount_type != 1) ? 0 : 1 ;
                $leaverequest->approved_by      = Auth::id();
                $leaverequest->approved_date    = ($userId = Auth::user()->acount_type != 1) ? null : date('Y-m-d') ;
                $leaverequest->paid_status      = $paid_status;
                $leaverequest->response_message = '';
                $leaverequest->leave_time       = json_encode($leave_time);
                $leaverequest->created_by       = $created_by;
                $leaverequest->save();
            }
        }
        return redirect()->back()->with('success', __('Leave Add Successfully'));
    }

    public function show(leave $leave)
    {
        //
    }

    public function edit(leave $leave,$id)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        if(Auth::user()->acount_type == 3 || Auth::user()->acount_type == 2) {
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->Where('id', $userId)->get();
        }

        $employees_select = [];
        foreach($employees as $employee){
            $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }
        $leave = LeaveRequest::find($id);

        $paid_status = ($leave->paid_status == 'paid') ? true : false ;

        $datetime1 = new DateTime($leave->start_date);
        $datetime2 = new DateTime($leave->end_date);
        $difference = $datetime1->diff($datetime2);

        $leave->days = $difference->d + 1;

        $leave_time = [];
        if(!empty($leave->leave_time))
        {
            $leave_time = json_decode($leave->leave_time,true);
            $vvv = '';
            if($leave_time['leave_time_type'] == 'daily' && !empty($leave_time['leave_time_by_dail_hour']) )
            {
                $leave_time_by_dail_hour_array =  $leave_time['leave_time_by_dail_hour'];
                if(!is_array($leave_time_by_dail_hour_array))
                {
                    $leave_time_by_dail_hour_array =  json_decode($leave_time['leave_time_by_dail_hour'],true);
                }
                foreach($leave_time_by_dail_hour_array as $set_hour_key => $set_hour_data)
                {
                    $val = 'value = "'.$set_hour_data.'"';
                    $vvv .= '<div class="form-group w-20 float-left">
                                    <label class="form-control-label"> '.__('Hours for').' '.$set_hour_key.' </label>
                                    <input type="number" name="leave_time2['.$set_hour_key.']" class="form-control w-90" '.$val.'>
                                </div>';
                }
            } else {
                $start_date = $leave->start_date;
                $end_date = $leave->end_date;
                $period = CarbonPeriod::create($start_date, $end_date);
                foreach ($period as $date) {
                    $datee = $date->format('Y-m-d');
                    $vvv .= '<div class="form-group w-20 float-left">
                                <label class="form-control-label"> '.__('Hours for').' '.$datee.' </label>
                                <input type="number" name="leave_time2['.$datee.']" class="form-control w-90">
                            </div>';
                }
            }
            $leave_time['leave_time_by_dail_hour'] = $vvv;
        }

        return view('leave.edit',compact('leave','employees_select','paid_status','leave_time'));
    }

    public function update(Request $request, leave $leave, $id)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();

        if(!empty($request->input('user_id'))) {
            $user_id = $request->input('user_id');
        } else {
            $user_id = $userId;
        }
        $issue_by = $userId;

        if(!empty($request->paid_status)) {
            $paid_status=  'paid';
        } else {
            $paid_status=  'unpaid';
        }
        $leave_time = [];
        if($request->leave_time_type == 'total') {
            $leave_time['leave_time_type'] = $request->leave_time_type;
            $leave_time['leave_time1'] = $request->leave_time1;
        }
        if($request->leave_time_type == 'daily') {
            $leave_time['leave_time_type'] = 'daily';
            $leave_time['leave_time_by_dail_hour'] = '';
            if(!empty($request->leave_time2)) {
                $leave_time['leave_time_by_dail_hour'] = json_encode($request->leave_time2);
            }
        }

        $leave = Leave::find($id);
        if(!empty($request->user_id)) {
            foreach($request->user_id as $user) {
                $leave->user_id          = $user;
                $leave->issue_by         = $issue_by;
                $leave->leave_type       = $request->leave_type;
                $leave->start_date       = $request->start_date;
                $leave->end_date         = $request->end_date;
                $leave->message          = $request->message;
                $leave->leave_approval   = 1;
                $leave->approved_by      = $userId;
                $leave->approved_date    = date('Y-m-d');
                $leave->paid_status      = $paid_status;
                $leave->response_message = '';
                $leave->leave_time       = json_encode($leave_time);
                $leave->created_by       = $created_by;
                $leave->save();

            }
        }
        return redirect()->back()->with('success', __('Leave Add Successfully'));
    }

    public function destroy(leave $leave,$id)
    {
        $leave = Leave::find($id);
        $leave->delete();
        return redirect()->back()->with('success', __('Delete Succsefully'));
    }

    public function annual_leave(Request $request,$id)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $annual_holiday = Profile::select('id','user_id','annual_holiday')->where('user_id', $id)->first();
        $holiday = [];
        $holiday['time'] = '';
        $holiday['apply_to'] = '';
        $holiday['type'] = '';
        if(!empty($annual_holiday) && !empty($annual_holiday['annual_holiday']))
        {
            $annual_holiday_array = json_decode($annual_holiday['annual_holiday'],true);
            if(!empty($annual_holiday_array)) {
                $holiday['time'] = $annual_holiday_array['time'];
                $holiday['type'] = $annual_holiday_array['type'];
                $holiday['apply_to'] = $annual_holiday_array['apply_to'];
            }
        }
        return view('leave.annualleave',compact('annual_holiday','holiday'));
    }

    public function annual_leave_response(Request $request,$id)
    {
        $leave = $request->leave;
        $profile = Profile::find($id);
        $profile->annual_holiday = json_encode($leave);
        $profile->save();
        $user_data = $profile->getUserData($profile->user_id);
        return redirect()->back()->with('success', __($user_data->first_name.' default holiday allowance has been updated'));
    }

    public function leave_sheet()
    {
        $userId = Auth::id();
        $week = $_REQUEST['week'];
        $week1 = $_REQUEST['week'];
        $created_by = $_REQUEST['created_by'];
        $week = $week * 7;

        $employee_data = Employee::whereRaw('id = '.$created_by.' ')->first();
        $setting = [];
        if(!empty($employee_data->company_setting))
        {
            $setting = json_decode($employee_data->company_setting,true);
        }
        $start_day = (!empty($setting['company_week_start'])) ? $setting['company_week_start'] : 'monday';
        $date_formate = User::CompanyDateFormat('Y-m-d');
        $week_date = Rotas::getWeekArray($date_formate, $week,$start_day);

        $date_formate1 = User::CompanyDateFormat('d M Y');
        $week_date1 = Rotas::getWeekArray($date_formate1, $week,$start_day);

        $table_date = [];
        $thead = '<thead> <tr class="text-center week_go_table1"><th></th><th>'.__('Holiday Allowance').'</th><th>'.__('Holiday Used').'</th><th>'.__('Holiday Remaining').'</th><th><span>'.__('Mon').'</span><br><span>'.$week_date1[0].'</span></th><th><span>'.__('Tue').'</span><br><span>'.$week_date1[1].'</span></th><th><span>'.__('Wen').'</span><br><span>'.$week_date1[2].'</span></th><th><span>'.__('Thu').'</span><br><span>'.$week_date1[3].'</span></th><th><span>'.__('Fri').'</span><br><span>'.$week_date1[4].'</span></th><th><span>'.__('Sat').'</span><br><span>'.$week_date1[5].'</span></th><th><span>'.__('Sun').'</span><br><span>'.$week_date1[6].'</span></th></tr>
        </thead>';
        $tbody = '';

        if(Auth::user()->acount_type == 1) {
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        }
        if(Auth::user()->acount_type == 3 || Auth::user()->acount_type == 2) {
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->Where('id', $userId)->get();
        }

        if(count($employees) != 0) {
            foreach($employees as $employee) {
                $tbody .= '<tr class="text-center" data-id="'.$employee->id.'"><td>'.$employee->first_name.' '.$employee->last_name.'</td><td>'.$employee->getAnnualHoliday($employee->id).'</td><td>'.$employee->getUsedHoliday($employee->id).'</td><td>'.$employee->getRemaingHoliday($employee->id).'</td>'.$employee->getHasLeave($employee->id,$week1).'';

            }
        }
        $array = array('table' => $thead.'<tbody>'.$tbody.'</tbody>', 'title' => __($week_date1[0]).' - '.__($week_date1[6]));
        return $array;
    }

    public function view_leave(Request $request ,$id)
    {
        $userId = Auth::id();
        $userType = Auth::user()->type;
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $leaverequest = LeaveRequest::find($id);

        $leave_type = __('other eave');
        if(!empty($leaverequest->leave_type) && $leaverequest->leave_type == 1) {
            $leave_type = __('holiday');
        }
        $date1=date_create($leaverequest->start_date);
        $date2=date_create($leaverequest->end_date);
        $diff=date_diff($date1,$date2);
        $user_leave = $diff->d + 1;

        $date_formate = User::CompanyDateFormat('l d M Y');
        $date = date($date_formate, strtotime($leaverequest->start_date));
        if($date1 != $date2) {
            $date .= ' - '.date($date_formate, strtotime($leaverequest->end_date));
        }

        $leave_by = __('You');
        if($userId != $leaverequest->user_id) {
            $leave_user = Employee::find($leaverequest->user_id);
            $leave_by = $leave_user->first_name.' '.$leave_user->last_name;
        }

        $string ='<b>'.$leave_by.'</b> '.__('requested').'  <b> '.$user_leave.' '.__('days').' '.$leave_type.'</b> '.__('for').' <b>'.$date.'.</b>';
        $request_message = '<div></div>';
        if(!empty(trim($leaverequest->message))) {
            $request_message = '<div class="request_message mt-3"> '. $leaverequest->message .'</div>';
        }

        $approved_by_name = __('You');
        if($userId != $leaverequest->approved_by) {
            $leave_requests = Employee::find($leaverequest->approved_by);
            $lname = (!empty($leave_requests->last_name)) ? $leave_requests->last_name : '';
            $fname = (!empty($leave_requests->first_name)) ? $leave_requests->first_name : '';
            $approved_by_name = $fname .' '. $lname;
        }
        $approved_by_name = '<div class="mt-3"><b>'.$approved_by_name.'</b> '. __('approved this leave request') .'</div>';

        $response_message = '<div></div>';
        if(!empty(trim($leaverequest->response_message))) {
            $response_message = '<div class="request_message mt-3"> '. $leaverequest->response_message .'</div>';
        }

        $user_type ='';
        if(Auth::user()->acount_type == 3) { $user_type = 'd-none'; }

        return view('leave.leaveview',compact('string','request_message','approved_by_name','response_message','leaverequest','user_type'));
    }

    public function view_leave_response(Request $request ,$id)
    {
        # code...
    }
}
