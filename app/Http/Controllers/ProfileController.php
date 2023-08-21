<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Group;
use App\Models\Location;
use App\Models\Profile;
use App\Models\Role;
use App\Models\Utility;
use App\Models\User;
use File;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Profiler\Profile as ProfilerProfile;

class ProfileController extends Controller
{
    public function index($id = 0)
    {
        if(Auth::user()->type != 'super admin')
        {
            $ID = $id;
            $id = $id;
            // dd($ID);
            $ID = Crypt::decrypt($id);
            $id = Crypt::decrypt($id);


            $userId = Auth::id();
            $userType = Auth::user()->type;
            $user = Auth::user();

            $created_by = $user->get_created_by();
            $manager_permission_disallow = (Auth::user()->acount_type == 2) ? 'd-none' : '' ;

            $profile = Profile::Where('user_id', $id)->first();

            $user_name = $profile->getUserName->first_name .' '. $profile->getUserName->last_name;

            $custom_role_rates = $user_name.' '.__('is not assigned to any roles. You must assign some roles before you can create custom rates.');

            $userr = Employee::Where('id', $id)->first();

            $permission = $userr->manager_permission;
            $manage_location = null;
            $manage_location_select = [];
            $manager_option = [];
            $manager_option['wage_salary_display'] = '';
            $manager_option['manager_add_employees_and_manage_basic_information'] = false;

            $permission = Auth::user()->manager_permission;

            if(!empty($permission) && Auth::user()->acount_type == 2)
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

            if(Auth::user()->type == 'company' || Auth::user()->acount_type == 1) { }
            if(Auth::user()->acount_type == 2 && $manager_option['manager_add_employees_and_manage_basic_information'] != true)
            {
                if(empty($manage_location))
                {
                    return redirect()->back()->with('error', __('Location not assigned to manager.'));
                }
            }
            if(Auth::user()->acount_type == 3 && Auth::user()->id != $id)
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }

            $company_detail = [];
            if(Auth::user()->acount_type == 1 || Auth::user()->acount_type == 2 || Auth::user()->acount_type == 3  && (!empty($userr->company_detail)))
            {
                $company_detail = json_decode($userr->company_detail,true);
            }

            $role_value[''] = __('Select Role');
            if(!empty($profile->role_id)) {
                $role_ids = explode(',',$profile->role_id);
                $custom_role_rates = [];
                foreach($role_ids as $role_id) {
                    $role = Role::select('id','name')->Where('id', $role_id)->first();
                    if(!empty($role)) {
                        $role_value[$role->id] = $role->name;
                        $custom_role_rates[$role->id] = $role->name;
                    }
                }
            }

            $roles = Role::where('created_by',$created_by)->get();
            $role_options = [];
            if(!empty($roles)) {
                foreach($roles as $role) {
                    $role_options[$role->id] = $role->name;
                }
            }

            $role_selected_option = [];
            if(!empty($profile->role_id)) {
                $role_selected_option = explode(',',$profile->role_id);
            }
            $profile->role_id = $role_selected_option;

            $groups = Group::where('created_by',$created_by)->get();
            $group_option = [];
            if(!empty($groups)) {
                foreach($groups as $group) {
                    $group_option[$group->id] = $group->name;
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

            $location_selected_option = [];
            if(!empty($profile->location_id)) {
                $location_selected_option = explode(',',$profile->location_id);
            }
            $profile->location_id = $location_selected_option;

            $salary_data = $profile->getSalaryDatta();

            return view('profile.index',compact('userr','profile','company_detail','role_value','group_option','all_location_option','location_option','location_option1','locations','role_options','custom_role_rates','salary_data','manage_location','manage_location_select','manager_option','manager_permission_disallow'));
        }
        else
        {

            $userId = Auth::id();
            $profile = Profile::Where('user_id', $userId)->first();
            return view('profile.superadminprofile',compact('profile','id'));
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(profile $profile)
    {
        //
    }

    public function edit(profile $profile)
    {
        //
    }

    public function update(Request $request, profile $profile)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        if($request->form_type == 'company_info')
        {
            $company_name = (!empty($request->company_name)) ? $request->company_name : null ;
            $company_info['company_telephone_number'] = (!empty($request->company_telephone_number)) ? $request->company_telephone_number : null ;
            $company_info['comapany_address'] = (!empty($request->comapany_address)) ? $request->comapany_address : null ;
            $company_info['company_city'] = (!empty($request->company_city)) ? $request->company_city : null ;
            $company_info['comapany_county'] = (!empty($request->comapany_county)) ? $request->comapany_county : null ;
            $company_info['company_postcode'] = (!empty($request->company_postcode)) ? $request->company_postcode : null ;

            $employee = Employee::find($request->employee_id);
            $employee->company_name = $company_name;
            $employee->company_detail = json_encode($company_info);
            $employee->save();
        }

        if($request->form_type == 'manage_permission')
        {
            $manager_permission = [];
            $manager_permission['manage_loaction'] = '';
            if(!empty($request->manage_location_id))
            {
                $manager_permission['manage_loaction'] = implode(',',$request->manage_location_id);
            }
            $manager_permission['manager_add_edit_delete_rotas'] = (!empty($request->manager_add_edit_rotas)) ? $request->manager_add_edit_rotas : 0 ;
            $manager_permission['manager_manage_leave_and_approve_leave_requests_for_other'] = (!empty($request->manager_manage_leave_and_approve_leave_requests_for_other)) ? $request->manager_manage_leave_and_approve_leave_requests_for_other : 0 ;
            $manager_permission['manager_manually_add_leave_to_themselves'] = (!empty($request->manager_manually_add_leave_to_themselves)) ? $request->manager_manually_add_leave_to_themselves : 0 ;
            $manager_permission['manager_manage_leave_embargoes'] = (!empty($request->manager_manage_leave_embargoes)) ? $request->manager_manage_leave_embargoes : 0 ;
            $manager_permission['manager_add_employees_and_manage_basic_information'] = (!empty($request->manager_add_employees_and_manage_basic_information)) ? $request->manager_add_employees_and_manage_basic_information : 0 ;
            $manager_permission['manager_view_and_edit_employee_salary'] = (!empty($request->manager_view_and_edit_employee_salary)) ? $request->manager_view_and_edit_employee_salary : 0 ;
            $manager_permission['manager_manage_roles'] = (!empty($request->manager_manage_roles)) ? $request->manager_manage_roles : 0 ;
            $manager_permission['manager_view_reports'] = (!empty($request->manager_view_reports)) ? $request->manager_view_reports : 0 ;

            $employee = Employee::find($request->employee_id);
            $employee->acount_type = $request->acount_type;
            $employee->manager_permission = ($request->acount_type == 2) ? json_encode($manager_permission) : null;
            $employee->save();
        }

        if($request->form_type == 'work_table')
        {
            $profile->work_schedule = json_encode($request->work_schedule);
            $settings  = Utility::settings(Auth::user()->ownerId());
            if(isset($settings['days_off_notificaation']) && $settings['days_off_notificaation'] ==1){

                $mesg = __('Work Schedule Updated by the  ').\Auth::user()->first_name.'.';
                Utility::send_slack_msg($mesg);

            }
            if(isset($settings['telegram_days_off_notificaation']) && $settings['telegram_days_off_notificaation'] ==1){
                $resp = __('Work Schedule Updated by the  ').\Auth::user()->first_name.'.';
                Utility::send_telegram_msg($resp);
            }
        }

        if($request->form_type == 'salary')
        {
            $profile->default_salary = json_encode($request->default_salary);
            $profile->custome_salary = json_encode($request->salary);
        }

        if($request->form_type == 'role')
        {
            $role_id = NULL;
            if(!empty($request->role_id)) {
                $role_id  = implode(',',$request->role_id);
            }
            $profile->role_id = $role_id;
        }

        if($request->form_type == 'loaction')
        {
            $location_id = NULL;
            if(!empty($request->location_id)) {
                $location_id  = implode(',',$request->location_id);
            }
            $profile->location_id = $location_id;
        }

        if($request->form_type == 'employee')
        {
            $profile->weekly_hour           = (!empty($request->weekly_hour)) ? $request->weekly_hour : NULL;

            if(!empty($request->annual_holiday['time'])) {
                $annual_holiday = $request->annual_holiday;
                $annual_holiday['apply_to'] = 'all_year';
                $profile->annual_holiday    = json_encode($annual_holiday);
            }

            $profile->employee_type         = $request->employee_type;
            $profile->start_date            = (!empty($request->start_date)) ? $request->start_date : NULL;
            $profile->final_working_date    = (!empty($request->final_working_date)) ? $request->final_working_date : NULL;
            $profile->note                  = $request->note;
        }

        if($request->form_type == 'personal')
        {
            $has_email = User::whereRaw('email = "'.$request->email.'" ')->whereRaw('id != '.$request->employee_id.' ')->count();
            if($has_email == 0)
            {
                if($request->hasFile('profile_pic'))
                {
                    $this->validate($request, [
                        'profile_pic' => 'image',
                    ]);
                    $file_path = 'uploads/profile_pic/'.$profile->profile_pic;
                    $image_size = $request->file('profile_pic')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->id, $image_size);
                    if($result==1)
                    {
                        Utility::changeStorageLimit(\Auth::user()->id, $file_path);
                        $filenameWithExt = $request->file('profile_pic')->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('profile_pic')->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        $settings = Utility::getStorageSetting();
                        $dir        = 'uploads/profile_pic/';
                        $image_path = $dir . $fileNameToStore;
                        $path = Utility::upload_file($request,'profile_pic',$fileNameToStore,$dir,[]);
                        $url = '';
                        if($path['flag'] == 1){
                            $url = $path['url'];
                        }else{
                            return redirect()->back()->with('error', __($path['msg']));
                        }
                        $profile->profile_pic                = $fileNameToStore;
                    }
                }
                    $profile->gender                     = $request->gender;
                    $profile->date_of_birth              = (!empty($request->date_of_birth)) ? $request->date_of_birth : NULL ;
                    $profile->emergency_contact_name     = $request->emergency_contact_name;
                    $profile->emergency_contact_no       = $request->emergency_contact_no;
                    $profile->relationship_to_employee   = $request->relationship_to_employee;
                    $profile->city                       = $request->city;
                    $profile->county                     = $request->county;
                    $profile->postcode                   = (!empty($request->postcode)) ? $request->postcode : NULL;
                    $profile->phone                      = (!empty($request->phone)) ? $request->phone : NULL ;
                    $profile->address                    = $request->address;

                $employee = Employee::find($request->employee_id);
                $employee->first_name = $request->first_name;
                $employee->middle_name = $request->middle_name;
                $employee->last_name = $request->last_name;
                if(!empty($request->email)) {
                    $employee->email = $request->email;
                } else {
                    return redirect()->back()->with('error', __('Enter Email Address'));
                }
                $employee->save();

            }
            else
            {
                return redirect()->back()->with('error', __('Email already exists'));
            }
        }

        if($request->form_type == 'superadmininfo')
        {
            if($request->hasFile('profile_pic'))
            {
                $this->validate($request, [
                    'profile_pic' => 'image',
                ]);
                $filenameWithExt = $request->file('profile_pic')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('profile_pic')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $settings = Utility::getStorageSetting();

                $dir        = 'uploads/profile_pic/';

                $image_path = $dir . $fileNameToStore;

                if(File::exists($image_path))
                {
                    File::delete($image_path);
                }

                $url = '';
                $path = Utility::upload_file($request,'profile_pic',$fileNameToStore,$dir,[]);
                if($path['flag'] == 1){
                    $url = $path['url'];

                }else{
                    return redirect()->back()->with('error', __($path['msg']));

                }
                $profile->profile_pic = $fileNameToStore;
            }

            $employee = Employee::find($request->employee_id);
            $employee->company_name = $request->company_name;

            if(!empty($request->email)) {
                $employee->email = $request->email;
            }
            $employee->save();
            $profile->save();
        }

        $profile->save();
        // return redirect()->back()->with('success', __('Information Update Successfully'));
        return redirect()->back()->with('success', __('Information Update Successfully'). ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
    }

    public function destroy(profile $profile)
    {
        //
    }

    public function updatePassword(Request $request)
    {
        if($request->form_type == 'set_other_password' && Auth::user()->type == 'company')
        {

            $request->validate( ['new_password' => 'required|min:6'] );
            $obj_user           = User::find($request->employee_id);
            $obj_user->password = Hash::make($request->new_password);
            $obj_user->save();
            return redirect()->route('profile', $obj_user->id)->with('success', __('Password successfully updated.'));
        }
        // dd( $request->form_type);
        if(\Auth::Check() && $request->form_type == 'set_own_password')
        {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:4',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            // dd(\Auth::user());
            $objUser          = \Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;

            if(Hash::check($request_data['current_password'], $current_password))
            {
                $user_id            = \Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return redirect()->route('profile', Crypt::encrypt($objUser->id))->with('success', __('Password successfully updated.'));
            }
            else
            {
                return redirect()->route('profile', Crypt::encrypt($objUser->id))->with('error', __('Please enter correct current password.'));
            }
        }
        else
        {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }
}
