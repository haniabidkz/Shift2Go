<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Location;
use App\Models\Profile;
use App\Models\Role;
use App\Models\Rotas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Flysystem\Adapter\Local;

class DailyViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date_formate = User::CompanyDateFormat('d M Y');
        $today = date($date_formate);
        $today1 = date('Y-m-d');

        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $employee = Employee::where('created_by', $created_by)->orwhere('id', $created_by)->get()->toArray();
        if(Auth::user()->acount_type == 3)
        {
            $employee = Employee::where('id', $userId)->get()->toArray();
        }
        elseif(Auth::user()->acount_type == 2)
        {
            $get_emp = User::manger_employee($userId);
            $employee = Employee::whereIn('id', $get_emp)->get()->toArray();
        }

        $employee_id = '';
        $employee_data = [];
        if(!empty($employee))
        {
            $employee_id = [];
            $employee_id = implode(',',array_column($employee,'id'));
            foreach($employee as $employee_info)
            {
                $employee_data[$employee_info['id']] = $employee_info['first_name'].' '.$employee_info['last_name'];
            }
        }


        $com_setting = User::companystaticSetting();
        $break_paid = (!empty($com_setting['break_paid'])) ? $com_setting['break_paid'] : 'paid';
        $include_unpublished_shifts = (!empty($com_setting['include_unpublished_shifts'])) ? $com_setting['include_unpublished_shifts'] : 0;

        // show price
        if(Auth::user()->type != 'company')
        {
            $company_setting_data = Employee::Where('id',$created_by)->first();
            if(!(empty($company_setting_data->company_setting)))
            {
                $com_setting = json_decode($company_setting_data->company_setting, true);
                $emp_show_rotas_price = (!empty($com_setting['emp_show_rotas_price'])) ? $com_setting['emp_show_rotas_price'] : 0 ;
            }
        } else {
            $emp_show_rotas_price = 1;
        }

        $published_shifts = ' publish = 1 ';
        if($include_unpublished_shifts == 1)
        {
            $published_shifts = ' 0 = 0';
        }

        $roles = Role::where('created_by', $created_by)->get();
        $role_option = [];
        if (count($roles) != 0) {
            foreach ($roles as $key => $role) {
                $role_option[$role->id] = $role->name;
            }
        }

        $loaction_option = [];
        $locations = [];
        if(Auth::user()->acount_type == 1)
        {
            $locations = Location::where('created_by', $created_by)->get();
        }
        if(Auth::user()->acount_type == 2)
        {
            if(!empty(Auth::user()->manager_permission))
            {
                $permission_array = json_decode(Auth::user()->manager_permission, true);
                if(!empty($permission_array['manage_loaction']))
                {
                    $locations = Location::whereraw('id IN ('.$permission_array['manage_loaction'].')')->get();
                }
            }
        }
        if(Auth::user()->acount_type == 3)
        {
            $locations_id = Profile::where('user_id', $userId)->first();
            if(!empty($locations_id->location_id))
            {
                $locations = Location::whereraw('id IN ('.$locations_id->location_id.')')->get();
            }
        }

        $location_option =[];
        foreach ($locations as $location) {
            $location_option[$location->id] = $location->name;
        }

        $rotas = Rotas::whereDate('rotas_date', $today1)->whereRaw('user_id IN ('.$employee_id.')')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->get();


        return view('dashboard.dayview', compact('today', 'rotas', 'employee_data', 'role_option', 'location_option'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function dayview_filter(Request $request)
    {
        $date            = $request->date;
        $date_type       = $request->date_type;
        $emp_name        = $request->emp_name;
        $loaction_name   = $request->loaction_name;
        $role_name       = $request->role_name;

        $userId          = Auth::id();
        $user            = Auth::user();
        $created_by      = $user->get_created_by();


        if($date_type == 'add_date')
        {
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }
        else if($date_type == 'sub_date')
        {
            $date = date('Y-m-d', strtotime($date . ' -1 day'));
        }

        $date = date('Y-m-d', strtotime($date));

        $where = [];
        if(!empty($emp_name))
        {
            $employee = $emp_name;
        }
        else
        {
            if(Auth::user()->acount_type == 1)
            {
                $employee = Employee::where('created_by', $created_by)->where('is_delete', 0)->orwhere('id', $created_by)->pluck('id')->toArray();
            }
            else if(Auth::user()->acount_type == 2)
            {
                $employee = User::manger_employee($userId);
            }
            else if(Auth::user()->acount_type == 3)
            {
                $employee[] = $userId;
            }
        }

        if(empty($role_name))
        {
            $role_name = Role::where('created_by', $created_by)->pluck('id');
        }

        $loc_wh = ' 0 = 0 ';
        if(!empty($loaction_name))
        {
            $loaction_arr = implode(',', $loaction_name);
            if(!empty($loaction_arr))
            {
                $loc_wh = 'location_id IN ('.$loaction_arr.')';
            }
        }


        $com_setting = User::companystaticSetting();
        $include_unpublished_shifts = (!empty($com_setting['include_unpublished_shifts'])) ? $com_setting['include_unpublished_shifts'] : 0;

        $published_shifts = ' publish = 1 ';
        if($include_unpublished_shifts == 1)
        {
            $published_shifts = ' 0 = 0';
        }

        $rotas = Rotas::whereDate('rotas_date', $date)->whereIn('user_id', $employee)->whereIn('role_id', $role_name)->whereRaw($loc_wh)->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->get();

        $date_formate = User::CompanyDateFormat('d M Y');
        $today = date($date_formate, strtotime($date));

        $returnHTML = view('dashboard.dayview_filter', compact('rotas'))->render();
        $return['returnHTML'] = $returnHTML;
        $return['date'] = $today;
        return response()->json($return);
    }
}
