<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Group;
use App\Models\LeaveRequest;
use App\Models\Location;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitation;;
use App\Models\Plan;
use App\Models\Profile;
use App\Models\Report;
use App\Models\Rotas;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeExport;
use App\Imports\EmployeeImport;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->acount_type == 2)
        {
            if(Auth::user()->getAddEmployeePermission() != 1 || Auth::user()->acount_type == 3)
            {
                return redirect('/dashboard')->with('error', __('Access denied.'));
            }
        }

        if(Auth::user()->type == 'super admin' || Auth::user()->acount_type == 3)
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $userId = Auth::id();
        $userType = Auth::user()->type;

        $user = Auth::user();
        $created_by = $user->get_created_by();


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


        if(Auth::user()->acount_type == 1) {
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        }
        if(Auth::user()->acount_type == 2 && User::managerpermission()['manager_add_employees_and_manage_basic_information'] != true) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        else
        {
            $employees = Employee::whereRaw('is_delete = 0')->where('created_by', $created_by)->whereRaw(Employee::getMangaerMangeLocationUser())->Orwhere('id', $userId)->get();
        }
        if(Auth::user()->acount_type == 3) {
            return redirect()->back()->with('error', __('Permission denied.'));
            $employees = Employee::where('is_delete', '0')->Where('id', $userId)->get();
        }

        $groups = Group::where('created_by', $created_by)->get();
        $roles = Role::where('created_by', $created_by)->get();
        $roles_select = [];
        foreach($roles as $role){
            $roles_select[$role->id][] = $role->name;
            $roles_select[$role->id][] = $role->color;
        }
        return view('employee.index',compact('employees','roles_select','box'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $permission = Auth::user()->manager_permission;

        $manager_location = ' 0 = 0 ';
        if(!empty($permission) && Auth::user()->acount_type == 2)
        {
            $permission = json_decode($permission,true);
            $manager_location =  (!empty($permission['manage_loaction'])) ? ' id IN ('.$permission['manage_loaction'].') ' : ' 0 = 0 ' ;
        }

        $roles = Role::whereRaw('created_by = '. $created_by.' ')->get();
        $role_select = [];
        foreach($roles as $role){
            $role_select[$role->id] = $role->name;
        }

        $locations = Location::whereRaw('created_by = '. $created_by.' ')->whereRaw($manager_location)->get();
        $location_select = [];
        foreach($locations as $location){
            $location_select[$location->id] = $location->name;
        }
        return view('employee.create',compact('role_select','location_select'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $userId = Auth::id();
        $has_email = 0;
        if(!empty($request->email)) {
            $has_email = User::where('email', '=', $request->email)->count();
        }

        $user_exist = 0;
        if(!empty($userId)) {
            $user_exist = Profile::where('user_id', '=', $userId)->count();
        }

        $role_id = NULL;
        if(!empty($request->role_id) || $request->role_id != 0) {
            $role_id = implode(',',$request->role_id);

        }

        $location_id = NULL;
        if(!empty($request->location_id)) {
            $location_id = implode(',',$request->location_id);
        }

        $user = Auth::user();
        
        $created_by = $user->get_created_by();
       
        $issue_by = $userId;
        

        $authUser      = \Auth::user();
        
        $creator       = User::find($created_by);
        
        $totalEmployee = $authUser->countEmployees($creator->id);
        
        $plan          = Plan::find($creator->plan);
       

        if($totalEmployee < $plan->max_employee || $plan->max_employee == -1)
        {
           
            if($has_email == 0) {
                $employee = new Employee();
                $employee->first_name = $request->first_name;
                $employee->last_name = $request->last_name;
                $employee->company_name = '';
                $employee->type = 'employee';
                $employee->email = $request->email;
                $employee->password = '';
                $employee->issue_by = $issue_by;
                $employee->created_by = $created_by;
                $employee->acount_type = 3;
                // $employee->avatar = '';
                // $employee->email_verified_at = date("Y-m-d H:i:s");
                $employee->save();
                // dd($employee);

                $insert_id = $employee->id;
                $profile = new Profile();
                $profile->user_id = $insert_id;
                $profile->role_id = $role_id;
                $profile->location_id = $location_id;

                $profile->save();
                // dd($profile);
//  dd($profile);
                return redirect()->back()->with('success', __('Employee Add Successfully'));
            } else {
                return redirect()->back()->with('error', __('Email Already Exist'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Your employee limit is over, Please upgrade plan.'));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(employee $employee)
    {
        $profile = Profile::find($employee->id);
        if(!empty($profile->profile_pic))
        {
            $file_path = 'uploads/profile_pic/'.$profile->profile_pic;
            $result = Utility::changeStorageLimit(\Auth::user()->id, $file_path);
            $profile->update([
                'profile_pic' => ''
            ]);
        }
        $userId = Auth::id();
        $employee->is_delete = 1;
        $employee->deleted_by = $userId;
        $employee->deleted_at = date('Y-m-d H:i:s');
        $employee->save();
        return redirect()->back()->with('success', __('Delete Succsefully'));
    }

    public function restore(Request $request, $employee_id) {
        $employee = Employee::find($employee_id);
        if($employee)
        {
            $employee->is_delete = 0;
            $employee->deleted_by = 0;
            $employee->deleted_at = NULL;
            $employee->save();
        }
        return redirect()->back()->with('success', __('Employee Restored Succsefully'));
    }

    public function send_invitation(Request $request, $employee_id)
    {
        $employee = Employee::find($employee_id);
        if($employee)
        {
            $user = '';
            $user = $employee;
            $send_mail = Mail::to($employee->email)->send(new UserInvitation($employee));
        }
        return redirect()->back()->with('success', __('Mail Send Successfully'));
    }

    public function changeMode()
    {
        $usr = Auth::user();
        if($usr->mode == 'light')
        {
            $usr->mode  = 'dark';
        }
        else
        {
            $usr->mode  = 'light';
        }
        $usr->save();
        return redirect()->back();
    }

    public function manage_permission(Request $request ,$id)
    {
        $userId = Auth::id();
        $userType = Auth::user()->type;
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $profile = Profile::Where('user_id', $id)->first();

        $userr = Employee::Where('id', $id)->first();
        $permission = $userr->manager_permission;
        $manage_location = null;
        $manage_location_select = [];
        $manager_option = [];
        $manager_option['wage_salary_display'] = '';

        if(!empty($permission) && $userr->acount_type == 2)
        {
            $manager_option['wage_salary_display'] = 'd-none';
            $manager_permission_array = json_decode($permission,true);
            if(!empty($manager_permission_array['manage_loaction']))
            {
                $manage_location = explode(',',$manager_permission_array['manage_loaction']);
                foreach($manage_location as $manage_location_data)
                {
                    $manage_location_select[$manage_location_data] = true;
                }
            }

            if(!empty($manager_permission_array['manager_add_edit_delete_rotas']))
            {
                $manager_option['manager_add_edit_delete_rotas'] = true;
            }
            if(!empty($manager_permission_array['manager_manage_leave_and_approve_leave_requests_for_other']))
            {
                $manager_option['manager_manage_leave_and_approve_leave_requests_for_other'] = true;
            }
            if(!empty($manager_permission_array['manager_manually_add_leave_to_themselves']))
            {
                $manager_option['manager_manually_add_leave_to_themselves'] = true;
            }
            if(!empty($manager_permission_array['manager_manage_leave_embargoes']))
            {
                $manager_option['manager_manage_leave_embargoes'] = true;
            }
            if(!empty($manager_permission_array['manager_add_employees_and_manage_basic_information']))
            {
                $manager_option['manager_add_employees_and_manage_basic_information'] = true;
            }
            if(!empty($manager_permission_array['manager_view_and_edit_employee_salary']))
            {
                $manager_option['manager_view_and_edit_employee_salary'] = true;
                $manager_option['wage_salary_display'] = '';
            }
            if(!empty($manager_permission_array['manager_manage_roles']))
            {
                $manager_option['manager_manage_roles'] = true;
            }
            if(!empty($manager_permission_array['manager_view_reports']))
            {
                $manager_option['manager_view_reports'] = true;
            }
        }

        $locations = Location::whereRaw('created_by ='.$created_by.'')->get();
        $location_option = [];
        $all_location_option = [];
        $location_option1 = [];
        if(!empty($locations)) {
            foreach($locations as $location_key => $location)
            {
                if(!empty($manage_location) && in_array($location->id,$manage_location))
                {
                    $location_option[$location->id] = $location->name;
                }
                $all_location_option[$location->id] = $location->name;
                $location_option1[$location_key]['id'] = $location->id;
                $location_option1[$location_key]['name'] = $location->name;
            }
        }
        return view('employee.permission',compact('profile','location_option1','manager_option','manage_location_select','userr'));
    }

    public function set_password(Request $request ,$id)
    {
        $employee = Employee::find($id);
        return view('employee.password',compact('employee'));
    }

    public function addpassword(Request $request ,$id)
    {


        $status = 'error';
        $msg = __('Please Try Again');

        $employee = User::find($id);
        if(!empty($employee) && !empty($request->password))
        {
            $employee->password = Hash::make($request->password);
            $employee->save();

            $company = Auth::user();

            if(!empty($employee->email))
            {
                $msg2 = '';
                try
                {
                    $send_mail = Mail::to($employee->email)->send(new UserInvitation($employee, $request->password, $company));
                }
                catch(\Exception $e)
                {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                    $msg2 = __('<br>Email send Successfully.') . (isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : '' ;
                }
            }
            $status = 'success';
            $msg = __('Password Set Succsefully').$msg2;
        }

        return redirect()->back()->with($status, $msg);
    }

    public function importFile()
    {
        return view('employee.import');
    }

    public function import(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'file' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $employee = (new EmployeeImport())->toArray(request()->file('file'))[0];

        $totalitem = count($employee) - 1;

        $errorArray    = [];
        for($i = 1; $i <= count($employee) - 1; $i++)
        {
            $members = $employee[$i];
            // dd($members);
            $projectByEmail = Employee::where('id', $members[0])->first();
            // dd($projectByEmail);
            if(!empty($projectByEmail))
            {
                $userData = $projectByEmail;
            }
            else
            {
                $userData = new Employee();
            }

            $userId = Auth::id();
            $has_email = 0;
            if(!empty($request->email)) {
                $has_email = User::where('email', '=', $request->email)->count();
            }

            $user_exist = 0;
            if(!empty($userId)) {
                $user_exist = Profile::where('user_id', '=', $userId)->count();
            }

            // dd($members);
            $user = Auth::user();
            $created_by = $user->get_created_by();
            $issue_by = $userId;

            $userData->first_name = $members[0];
            $userData->last_name = $members[1];
            $userData->company_name = '';
            $userData->type = 'employee';
            $userData->email = $members[2];
            $userData->password = '';
            $userData->issue_by = $issue_by;
            $userData->created_by = $created_by;
            $userData->acount_type = 3;

            $userData->save();

            $role = Role::where('created_by' , \Auth::user()->id)->get()->pluck('id')->implode(',');

            $location = Location::where('created_by' , \Auth::user()->id)->get()->pluck('id')->implode(',');

            $insert_id = $userData->id;
            $userData = new Profile();
            $userData->user_id = $insert_id;
            $userData->role_id =$role;
            $userData->location_id = $location;
            // dd($userData);
            $userData->save();


            if(empty($userData))
            {
                $errorArray[]      = $userData;
            }
            else
            {
                $userData->save();
            }
        }

        $errorRecord = [];
        if(empty($errorArray))
        {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        }
        else
        {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalitem . ' ' . 'record');


            foreach($errorArray as $errorData)
            {

                $errorRecord[] = implode(',', $errorData);

            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function export()
    {
        $name = 'Employee' . date('Y-m-d i:h:s');
        $data = Excel::download(new EmployeeExport(), $name . '.xlsx'); ob_end_clean();

        return $data;
    }

    public function employeePassword($id)
    {
        $eId        = \Crypt::decrypt($id);
        $user = User::find($eId);

        $employee = User::where('id', $eId)->first();

        return view('employee.reset', compact('user', 'employee'));
    }

    public function employeePasswordReset(Request $request, $id){
        $validator = \Validator::make(
            $request->all(), [
                               'password' => 'required|confirmed|same:password_confirmation',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $user                 = User::where('id', $id)->first();
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->back()->with('success', __('Employee Password successfully updated'));

        // return redirect()->route('employee.index')->with(
        //              'success', 'Employee Password successfully updated.'
        //          );

    }
}
