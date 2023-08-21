<?php

namespace App\Http\Controllers;

use App\Models\Embargo;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\LeaveExport;
use Maatwebsite\Excel\Facades\Excel;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\Promise\all;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $haspermission = LeaveRequest::getLeaveHasPermission();

        if(Auth::user()->acount_type == 3) {
            $leave_requests = LeaveRequest::where('user_id', $userId)->orderBy('id', 'DESC')->get();
        }
        if(Auth::user()->acount_type == 2) {
            $useer_idd =  Employee::getMangaerMangeLocationUserOtherTable();
            $leave_requests = LeaveRequest::where('user_id',$useer_idd)->orderBy('id', 'DESC')->get();
        }
        if(Auth::user()->acount_type == 1) {
            $leave_requests = LeaveRequest::where('created_by', $created_by)->orderBy('id', 'DESC')->get();
        }
        return view('leaverequest.index',compact('leave_requests','haspermission'));
    }

    public function create()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $permission = Auth::user()->manager_permission;

        if(Auth::user()->acount_type == 1) {
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        }
        if(Auth::user()->acount_type == 3 || Auth::user()->acount_type == 2) {
            $employees = Employee::where('is_delete', '0')->Where('id', $userId)->get();
        }

        $has_permission = 0;
        if(!empty($permission))
        {
            $permission = json_decode($permission,true);
            if(!empty($permission['manager_manually_add_leave_to_themselves'] == 1))
            {
                $has_permission = 1;
            }
        }
        $employee_option = [];
        foreach($employees as $employee) {
            $employee_option[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }
        return view('leaverequest.create',compact('employee_option','has_permission'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $permission = Auth::user()->manager_permission;

        $leaverequest = new LeaveRequest();
        $leaverequest['user_id']      = $request->emp_id;
        $leaverequest['issue_by']     = $userId;
        $leaverequest['leave_type']   = $request->leave_type;
        $leaverequest['start_date']   = $request->start_date;
        $leaverequest['end_date']     = $request->end_date;
        $leaverequest['message']      = $request->message;
        $leaverequest['created_by']   = $created_by;

        if(!empty($permission))
        {
            $permission = json_decode($permission,true);
            if(!empty($permission['manager_manually_add_leave_to_themselves'] == 1))
            {
                $leaverequest['leave_approval'] = 1;
                $leaverequest['approved_by'] = $userId;
                $leaverequest['approved_date'] = date('Y-m-d');
                $leaverequest['paid_status'] = (!empty($request->paid_status)) ? 'paid': 'unpaid';
                $leaverequest['leave_time'] = json_encode(array("leave_time_type" => "total", "leave_time1" => $request->leave_time1));
            }
        }

        $start_date = $request->input('start_date');
        $start_date = date('Y-m-d', strtotime($start_date));

        $end_date = $request->input('end_date');
        $end_date = date('Y-m-d', strtotime($end_date));

        $embargos = Embargo::whereRaw('FIND_IN_SET('.$userId.',to_employees)')->get();

        $embargos_date = '';
        $employee_array = [];
        $flg = true;
        if(!empty($embargos))
        {
            foreach ($embargos as $embargo)
            {
                $embargos_start_date =  date('Y-m-d', strtotime($embargo->start_date));
                $embargos_end_date =  date('Y-m-d', strtotime($embargo->end_date));

                 if (($start_date >= $embargos_start_date) && ($start_date <= $embargos_end_date)){
                    $embargos_date = $embargos_start_date .' - '.$embargos_end_date.'('.$embargo->message.')';
                    $flg = false;
                    break;
                 }
                if (($end_date >= $embargos_start_date) && ($end_date <= $embargos_end_date)){
                    $embargos_date = $embargos_start_date .' - '.$embargos_end_date.'('.$embargo->message.')';
                    $flg = false;
                    break;
                }

            }
        }

        if($flg) {
            $leaverequest->save();
            return redirect()->back()->with('success', __('Leave Request Add Successfully'));
        } else {

            return redirect()->back()->with('error', __('Leave Can Not Be Requested Over '.$embargos_date.''));
        }
    }

    public function show(LeaveRequest $leaveRequest)
    {

    }

    public function edit(LeaveRequest $leaveRequest)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $permission = Auth::user()->manager_permission;

        if(Auth::user()->acount_type == 1) {
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        }
        if(Auth::user()->acount_type == 3 || Auth::user()->acount_type == 2) {
            $employees = Employee::where('is_delete', '0')->Where('id', $userId)->get();
        }

        $has_permission = 0;
        if(!empty($permission))
        {
            $permission = json_decode($permission,true);
            if(!empty($permission['manager_manually_add_leave_to_themselves'] == 1))
            {
                $has_permission = 1;
            }
        }
        $employee_option = [];
        foreach($employees as $employee) {
            $employee_option[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }
        return view('leaverequest.edit',compact('leaveRequest','employee_option','has_permission'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $leaveRequest['leave_type']   = $request->input('leave_type');
        $leaveRequest['start_date']   = $request->input('start_date');
        $leaveRequest['end_date']     = $request->input('end_date');
        $leaveRequest['message']      = $request->input('message');
        $leaveRequest['created_by']   = $created_by;
        $leaveRequest->save();
        return redirect()->back()->with('success', __('Leave Request Add Successfully'));
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $leaveRequest->leave_approval = 3;
        $leaveRequest->approved_by = $userId;
        $leaveRequest->approved_date = date('Y-m-d');
        $leaveRequest->save();
        return redirect()->back()->with('success', __('Leave Request Cancel Succsefully'));
    }

    public function reply(Request $request,$id)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $leaverequest = LeaveRequest::where('created_by', $created_by)->where('id', $id)->first();
        $confirm_leave = LeaveRequest::where('user_id', $leaverequest->user_id)->where('leave_approval', 1)->count();

        $profile = Profile::where('user_id', $leaverequest->user_id)->first();
        $yearly_leave = $profile->toArray()['annual_holiday'];
        $yearly_leave_no = 0;
        if(!empty($yearly_leave)) {
            $yearly_leave = json_decode($yearly_leave,true);
            $yearly_leave_no = $yearly_leave['time'];
        }
        $remaing_leave = $yearly_leave_no-$confirm_leave;

        $requested_user = User::where(['id' => $leaverequest->user_id])->first();
        if(!is_null($requested_user)){
            $name = $requested_user->first_name.' '.$requested_user->last_name;
        }else{
            $name = '-';
        }

        $start_date = $leaverequest->start_date;
        $end_date = $leaverequest->end_date;
        $date1 = date_create($start_date);
        $date2 = date_create($end_date);
        $date_difference  = date_diff($date1, $date2)->days + 1;
        $date_formate = User::CompanyDateFormat('D d F Y');
        $startdayname = date($date_formate, strtotime($start_date));
        $enddayname = date($date_formate, strtotime($end_date));

        $dayyy = ''.$startdayname.' - '.$enddayname.'';
        if($startdayname == $enddayname) {
            $dayyy = ''.$startdayname.'';
        }

        $requsst_string = '<span><b>'.$name.'</b> requests <b>'.$date_difference.' days </b> for <b> '.$dayyy.' </b>.</span><br>'.$name.' has '.$remaing_leave.' days holiday remaining in this year.';

        $paid_status_value = $leaverequest->paid_status;
        $paid_status = false;
        if($paid_status_value == 'unpaid') {
            $paid_status = false;
        }
        if($paid_status_value == 'paid') {
            $paid_status = true;
        }

        return view('leaverequest.reply',compact('leaverequest','requsst_string','paid_status'));
    }

    public function reply_response(Request $request ,$id)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $leaverequest = LeaveRequest::find($id);
        $status = '';
        if($leaverequest)
        {
            if($request->input('leave_approval') == 1) {
                $status = 'Confirm';
            } else {
                $status = 'Denied';
            }

            $leaverequest['leave_approval']     = $request->input('leave_approval');
            $leaverequest['response_message']   = $request->input('response_message');
            if(!empty($request->input('paid_status'))) {
                $leaverequest['paid_status']    = 'paid';
            } else {
                $leaverequest['paid_status']    = 'unpaid';
            }
            $leaverequest->approved_date = date('Y-m-d');
            $leaverequest['approved_by']   = $userId;
            $leaverequest->save();
        }
        return redirect()->back()->with('success', __('Leave Request '.$status.' Successfully'));
    }

    public function export()
    {
        $name = 'Leave' . date('Y-m-d i:h:s');
        $data = Excel::download(new LeaveExport(), $name . '.xlsx'); ob_end_clean();

        return $data;
    }
}
