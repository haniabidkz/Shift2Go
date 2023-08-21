<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Profile;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        if(Auth::user()->acount_type == 1 || Auth::user()->acount_type == 2)
        {
            $user = Auth::user();
            $created_by = $user->get_created_by();
            $roles = Role::where('created_by', $created_by)->get();

            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
            $employees_select = [];
            foreach($employees as $employee){
                $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
            }

            return view('role.index',compact('roles','employees_select'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        $employees_select = [];
        foreach($employees as $employee){
            $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }
        return view('role.create',compact('employees_select'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $role = new Role();
        $role['name']           = $request->input('name');
        $role['color']          = $request->input('color');
        $role['default_break']  = $request->input('default_break');
        $role['created_by']     = $created_by;
        $role->save();

        $insert_id = $role->id;
        $employees = $request->employees;
        if(!empty($employees)) {
            foreach($employees as $employee_id) {
                $profile = Profile::where('user_id', $employee_id)->first();
                if(!empty($profile->user_id)) {
                    if(!empty($profile->role_id)) {
                        $set_role_id_string ='';
                        $set_role_id = [];
                        $set_role_id = explode(',',$profile->role_id);
                        $set_role_id[] = $insert_id;
                        $set_role_id_string = implode(',',$set_role_id);
                        $profile->role_id = $set_role_id_string;
                    } else {
                        $profile->role_id = $insert_id;
                    }
                    $profile->save();
                }
            }
        }
        return redirect()->back()->with('success', __('Role Add Successfully'));
    }

    public function show(role $role)
    {
        //
    }

    public function edit(role $role)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        $employees_select = [];
        foreach($employees as $employee){
            $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }

        $role_employees_select = [];
        $role_employees = Profile::select('id','user_id')->whereRaw('FIND_IN_SET('.$role->id.',role_id)')->get();
        foreach($role_employees as $role_employee){
            $role_employees_select[] = $role_employee->user_id;
        }
        $role->employees = $role_employees_select;
        return view('role.edit',compact('role','employees_select'));
    }

    public function update(Request $request, role $role)
    {
        $old_role_detail = Profile::select('id','user_id','role_id')->whereRaw('FIND_IN_SET('.$role->id.',role_id)')->get()->toArray();

        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employee = $request->employees;
        $role->name           = $request->name;
        $role->color          = $request->color;
        $role->default_break  = $request->default_break;
        $role->created_by    = $created_by;
        $role->save();

        $role_id = $role->id;
        $new_employees = $request->employees;
        $old_employees = [];
        if(!empty($old_role_detail))
        {
            $old_employees = array_column($old_role_detail,'user_id');
        }

        $add_roles = [];
        $remove_roles = [];
        if(!empty($new_employees)) {
            $add_roles = array_diff($new_employees,$old_employees);
            $remove_roles = array_diff($old_employees,$new_employees);
        } else {
            $remove_roles = $old_employees;
        }

        if(!empty($add_roles)) {
            foreach($add_roles as $add_role) {
                $profile = Profile::select('id','user_id','role_id')->where('user_id',$add_role)->get()->first();
                if(!empty($profile->role_id))
                {
                    $set_role_id_string ='';
                    $set_role_id = [];
                    $set_role_id = explode(',',$profile->role_id);
                    $set_role_id[] = $role_id;
                    $set_role_id_string = implode(',',$set_role_id);
                    $profile->role_id = $set_role_id_string;
                } else {
                    $profile->role_id = $role_id;
                }
                $profile->save();
            }
        }

        if(!empty($remove_roles)) {
            foreach($remove_roles as $remove_role) {
                $profile = Profile::select('id','user_id','role_id')->where('user_id',$remove_role)->get()->first();
                if(!empty($profile->role_id))
                {
                    $set_role_id_string ='';
                    $set_role_id = [];
                    $set_role_id = explode(',',$profile->role_id);

                    $set_role_id_string = array_diff($set_role_id,array($role_id));
                    if(!empty($set_role_id_string)) {
                        $profile->role_id = implode(',',$set_role_id_string);
                    } else {
                        $profile->role_id = NULL;
                    }
                } else {
                    $profile->role_id = NULL;
                }
                $profile->save();
            }
        }
        return redirect()->back()->with('success', __('Role Update Successfully'));
    }

    public function destroy(role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', __('Delete Succsefully'));
    }
}
