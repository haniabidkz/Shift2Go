<?php

namespace App\Http\Controllers;

use App\Models\Embargo;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmbargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $embargoes = Embargo::where('created_by', $created_by)->get();
        $haspermission = LeaveRequest::getLeaveHasPermission();
        return view('embargoes.index',compact('embargoes','haspermission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $permission = Auth::user()->manager_permission;
        $where = '';
        $user_where = '';
        if(!empty($permission) && Auth::user()->acount_type == 2)
        {
            $permission = json_decode($permission,true);
            $profile_where = [];
            if($permission['manage_loaction'] != '')
            {
                $manage_loaction = explode(',',$permission['manage_loaction']);
                if(!empty($manage_loaction))
                {
                    foreach($manage_loaction as $manage_loaction_data)
                    {
                        $profile_where[] = ' FIND_IN_SET('.$manage_loaction_data.', location_id) ';
                    }
                }
                if(!empty($profile_where))
                {
                    $where .= implode(' or ',$profile_where);
                }
                $where = (!empty($where)) ? $where : ' 0 = 0 ' ;

                $profile_users = Profile::select('id','user_id')->whereRaw($where)->get();
                if(!empty($profile_users))
                {
                    foreach($profile_users as $profile_user)
                    {
                        $user_ids[] = $profile_user['user_id'];
                    }
                }
                $user_where = (!empty($user_ids)) ? 'id IN ('.implode(',',$user_ids).')' : ' id IN (0)' ;
            }
            $employees = Employee::whereRaw('is_delete = 0')->whereRaw($user_where)->whereRaw('created_by = '.$created_by.'')->get();
        } else {
            $employees = Employee::whereRaw('is_delete = 0')->whereRaw('created_by = '.$created_by.'')->orwhereRaw('id ='.$created_by.'')->get();
        }
        $employees_select = [];
        foreach($employees as $employee){
            $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }
        return view('embargoes.create',compact('employees_select'));
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

        $employee = $request->employees;
        $employee_id = '';
        if(!empty($employee)) {
            $employee_id = implode(',',$employee);
        }
        $embargoes = new Embargo();
        $embargoes['issue_by']   = $request->issue_by;
        $embargoes['start_date']   = $request->start_date;
        $embargoes['end_date']     = $request->end_date;
        $embargoes['message']      = $request->message;
        $embargoes['to_employees']    = $employee_id;
        $embargoes['created_by']   = $created_by;
        $embargoes->save();
        return redirect()->back()->with('success', __('Embargoe Add Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Embargo  $embargo
     * @return \Illuminate\Http\Response
     */
    public function show(Embargo $embargo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Embargo  $embargo
     * @return \Illuminate\Http\Response
     */
    public function edit(Embargo $embargo)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employees = Employee::where('created_by', $created_by)->where('is_delete', 0)->get();
        $employees_select = [];
        foreach($employees as $employee){
            $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }
        $embargo_employees = '';
        if(!empty($embargo->to_employees)) {
            $embargo_employees = explode(',',$embargo->to_employees);
        }
        return view('embargoes.edit',compact('employees_select','embargo','embargo_employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Embargo  $embargo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Embargo $embargo)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $employee = $request->employees;
        $employee_id = '';
        if(!empty($employee)) {
            $employee_id = implode(',',$employee);
        }
        $embargo['issue_by']   = $request->issue_by;
        $embargo['start_date']   = $request->input('start_date');
        $embargo['end_date']     = $request->input('end_date');
        $embargo['message']      = $request->input('message');
        $embargo['to_employees'] = $employee_id;
        $embargo['created_by']   = $created_by;
        $embargo->save();
        return redirect()->back()->with('success', __('Embargoe Update Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Embargo  $embargo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Embargo $embargo)
    {
        $embargo->delete();
        return redirect()->back()->with('success', __('Delete Succsefully'));
    }
}
