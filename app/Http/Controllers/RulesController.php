<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Group;
use App\Models\LeaveRequest;
use App\Models\Location;
use App\Models\RequestRule;
use App\Models\Role;
use App\Models\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RulesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $rules = Rules::where('created_by', $created_by)->get();
        $haspermission = LeaveRequest::getLeaveHasPermission();
        return view('rules.index',compact('rules','haspermission'));
    }

    public function create()
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $requestrules = RequestRule::where('created_by', $created_by)->orWhere('created_by', 0)->get();
        $requestrule_select = [];
        if(!empty($requestrules)) {
            foreach($requestrules as $requestrule){
                $requestrule_select[$requestrule->id] = $requestrule->rule_name;
            }
        }

        $employees = Employee::where('created_by', $created_by)->get();
        $employees_select = [];
        if(!empty($employees)) {
            foreach($employees as $employee){
                $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
            }
        }

        $locations = Location::where('created_by', $created_by)->get();
        $location_select = [];
        if(!empty($locations)) {
            foreach($locations as $location){
                $location_select[$location->id] = $location->name;
            }
        }

        $roles = Role::where('created_by', $created_by)->get();
        $roles_select = [];
        if(!empty($roles)) {
            foreach($roles as $role) {
                $roles_select[$role->id] = $role->name;
            }
        }

        $groups = Group::where('created_by', $created_by)->get();
        $group_select = [];
        if(!empty($groups)) {
            foreach($groups as $group) {
                $group_select[$group->id] = $group->name;
            }
        }
        return view('rules.create',compact('requestrule','employees_select','requestrule_select','location_select','roles_select','group_select'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $rule = $request->input('rule_id');

        $rule_name = [];
        $rule_json = [];
        $rule_json_main = [];
        $rule_no = $rule['role_val'];
        if(!empty($rule_no)) {
            $rule_name = $rule[$rule_no];
            $rule_json['rule_id'] = $rule['role_val'];
            $rule_json_main = array_merge($rule_json,$rule_name);
        }

        $rules = new Rules();
        $rules['name']              = $request->input('name');
        $rules['start_date']        = $request->input('start_date');
        $rules['end_date']          = $request->input('end_date');
        $rules['message']           = $request->input('message');
        $rules['leave_rules_json']  = json_encode($rule_json_main);
        $rules['created_by']        = $created_by;
        $rules->save();
        return redirect()->back()->with('success', __('Rule Add Successfully'));
    }

    public function show(rules $rules)
    {
        //
    }

    public function edit(rules $rules, Request $request, $id)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $requestrules = RequestRule::where('created_by', $created_by)->orWhere('created_by', 0)->get();
        $requestrule_select = [];
        if(!empty($requestrules)) {
            foreach($requestrules as $requestrule){
                $requestrule_select[$requestrule->id] = $requestrule->rule_name;
            }
        }

        $employees = Employee::where('created_by', $created_by)->get();
        $employees_select = [];
        if(!empty($employees)) {
            foreach($employees as $employee){
                $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
            }
        }

        $locations = Location::where('created_by', $created_by)->get();
        $location_select = [];
        if(!empty($locations)) {
            foreach($locations as $location){
                $location_select[$location->id] = $location->name;
            }
        }

        $roles = Role::where('created_by', $created_by)->get();
        $roles_select = [];
        if(!empty($roles)) {
            foreach($roles as $role) {
                $roles_select[$role->id] = $role->name;
            }
        }

        $groups = Group::where('created_by', $created_by)->get();
        $group_select = [];
        if(!empty($groups)) {
            foreach($groups as $group) {
                $group_select[$group->id] = $group->name;
            }
        }

        $leaverule = Rules::find($id);
        if($leaverule)
        {
            $rule_json = $leaverule->toArray();
            $leave_rules_json = $rule_json['leave_rules_json'];
            $leave_rules_json_decode = '';
            if(!empty($leave_rules_json)) {
                $leave_rules_array = json_decode($leave_rules_json,true);
            }
            return view('rules.edit',compact('requestrule','employees_select','requestrule_select','location_select','roles_select','group_select','leave_rules_array','leaverule'));
        }
        else
        {
            $rules = Rules::where('created_by', $created_by)->get();
            return view('rules.index',compact('rules'));

        }

    }

    public function update(Request $request, rules $rules,$id)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $leaverules = Rules::find($id);
        $rule = $request->input('rule_id');

        $rule_name = [];
        $rule_json = [];
        $rule_json_main = [];
        $rule_no = $rule['role_val'];
        if(!empty($rule_no)) {
            $rule_name = $rule[$rule_no];
            $rule_json['rule_id'] = $rule['role_val'];
            $rule_json_main = array_merge($rule_json,$rule_name);
        }

        $leaverules['name']              = $request->input('name');
        $leaverules['start_date']        = $request->input('start_date');
        $leaverules['end_date']          = $request->input('end_date');
        $leaverules['message']           = $request->input('message');
        $leaverules['leave_rules_json']  = json_encode($rule_json_main);
        $leaverules['created_by']        = $created_by;
        $leaverules->save();

        return redirect()->back()->with('success', __('Rule Update Successfully'));
    }

    public function destroy(Request $request, $id)
    {
        $leaverules = Rules::find($id);
        if($leaverules)
        {
            $leaverules->delete();
        }
        return redirect()->back()->with('success', __('Rule Delete Succsefully'));
    }
}
