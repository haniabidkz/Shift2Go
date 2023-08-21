<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Location;
use App\Models\Profile;
use App\Models\Report;
use App\Models\Role;
use App\Models\Rotas;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Adapter\Local;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Report $report)
    {
        if(Auth::user()->acount_type == 3)
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $permission = Auth::user()->manager_permission;

        $com_setting = User::companystaticSetting();
        $include_unpublished_shifts = (!empty($com_setting['include_unpublished_shifts'])) ? $com_setting['include_unpublished_shifts'] : 0;

        $published_shifts = ' publish = 1 ';
        if($include_unpublished_shifts == 1)
        {
            $published_shifts = ' 0 = 0';
        }


        $date_format = User::CompanyDateFormat();

        $query = $request->all();
        $rotas_where = ' 0 = 0 ';
        $leave_where = ' 0 = 0 ';
        $leave_where = ' 0 = 0 ';
        $weere1 = ' and 0 = 0 ';
        $leave_where1 = [];
        $leaveuser_role = [];
        $leaveuser = [];
        $leave_location_where = [];
        $leave_location_where1 = '';
        $get_location_val = null;
        $get_user_val = null;
        $get_role_val = null;
        $get_date_val = null;
        if(!empty($query))
        {
            if(!empty($query['location']) && $query['location'] != 'all0')
            {
                $get_location_val = $query['location'];
                $locartion = implode(',',$query['location']);
                $rotas_where .= ' and location_id IN ('.$locartion.') ';
                if(!empty($get_location_val)) {
                    foreach ($get_location_val as $get_location_value) {
                        $leave_location_where[] = 'FIND_IN_SET('.$get_location_value.', location_id) ';
                    }
                }
                if(Auth::user()->acount_type == 2)
                {
                    if(!empty($leave_location_where)) {
                        $leave_location_where1 = '('.implode(' OR ',$leave_location_where).')';
                        $leave_location_where1 = Profile::select('user_id')->WhereRaw($leave_location_where1)->get()->toArray();
                        if(!empty($leave_location_where1)) {
                            $leaveuser_location = array_column($leave_location_where1, 'user_id');
                        }
                    }
                }
            }
            if(!empty($query['employees']) && $query['employees'] != 'all0')
            {
                $get_user_val = $query['employees'];
                $employees = implode(',',$query['employees']);
                $rotas_where .= ' and user_id IN ('.$employees.') ';
                $leaveuser = $get_user_val;
            }
            if(!empty($query['role']) && $query['role'] != 'all0')
            {
                $get_role_val = $query['role'];
                $rolexx = $query['role'];
                $role1 = implode(',',$rolexx);
                $where1 = ' and location_id IN ('.$role1.') ';
                if (($key = array_search('no_role', $rolexx)) !== FALSE)
                {
                    unset($rolexx[$key]);
                    $role1 = implode(',',$rolexx);
                    if(!empty($role1))
                    {
                        $weere1 = ' and (location_id IN ('.$role1.')  OR location_id  IS NULL) ';
                    }
                }

                if(Auth::user()->acount_type == 2)
                {
                    if(!empty($get_role_val) && $get_role_val[0] != 'no_role') {
                        foreach ($get_role_val as $get_role_value) {
                            $leave_role_where[] = 'FIND_IN_SET('.$get_role_value.', role_id) ';
                        }
                    }

                    //dd($get_role_val[0], $leave_role_where);

                    if(!empty($leave_role_where)) {
                        $leave_role_whererrr = (!empty($leave_role_where)) ? $leave_role_where[0] : ' 0=0 ';
                        $leave_role_where1 = '('.implode(' OR ',$leave_role_where).')';
                        $leave_role_where1 = Profile::select('user_id')->WhereRaw($leave_role_whererrr)->get()->toArray();
                        if(!empty($leave_role_where1)) {
                            $leaveuser_role = array_column($leave_role_where1, 'user_id');
                        }
                    }
                }
                $rotas_where .= $weere1;
            }

            if(Auth::user()->acount_type == 2)
            {
                $leave_where1 = [];
                if(!empty($leaveuser_location)) {
                    $leave_where1 = array_merge($leave_where1,$leaveuser_location);
                }
                if(!empty($leaveuser)) {
                    $leave_where1 = array_merge($leave_where1,$leaveuser);
                }
                if(!empty($leaveuser_role)) {
                    $leave_where1 = array_merge($leave_where1,$leaveuser_role);
                }
                if(!empty($leave_where1)) {
                    $leave_where1 = array_intersect($leave_where1, array_unique(array_diff_key($leave_where1, array_unique($leave_where1))));
                    $leave_where1 = array_unique($leave_where1);
                    $employees = implode(',',$leave_where1);
                }
            }
            if(!empty($query['date']))
            {
                $get_date_val = $query['date'];
            }
        }
        // dd($get_date_val);

        $manager_location = '0=0';
        $manager_location_rota = '0=0';
        if(!empty($permission) && Auth::user()->acount_type == 2)
        {
            $permission = json_decode($permission,true);
            $manager_location = (!empty($permission['manage_loaction'])) ? ' id IN ('.$permission['manage_loaction'].')'   : ' id IN (0) ' ;
            $manager_location_rota = (!empty($permission['manage_loaction'])) ?  'location_id IN ('.$permission['manage_loaction'].') ' : ' location_id IN (0) ' ;
        }

        $manger_user = Employee::getMangaerMangeLocationUserOtherTable();
        if(!empty($manger_user))
        {
            $manager_location_rota = ($manager_location_rota == '0=0') ? ' user_id IN ('.$manger_user.') ' :  $manager_location_rota.' and user_id IN ('.$manger_user.')';
        }

        $filter_locationss = Location::WhereRaw('created_by = "'.$created_by.'"')->WhereRaw($manager_location)->get()->toArray();
        $filter_locations = [];
        //$filter_locations['all0'] = __('Select All');
        if(!empty($filter_locationss))
        {
            foreach($filter_locationss as $filter_locationss_data)
            {
                $filter_locations[$filter_locationss_data['id']] = $filter_locationss_data['name'];
            }
        }

        $manager_manage_user = Employee::getMangaerMangeLocationUser();
        if(Auth::user()->acount_type == 2)
        {
            if(empty($manager_manage_user))
            {
                $manager_manage_user = '0 = 0';
            }
            $filter_employeess = Employee::WhereRaw($manager_manage_user)->get()->toArray();

        } else {
            $filter_employeess = Employee::WhereRaw('id = "'.$created_by.'"')->orWhereRaw('created_by = "'.$created_by.'"')->get()->toArray();
        }
        $filter_employees = [];
        // $filter_employees['all0'] = __('Select All');
        if(!empty($filter_employeess))
        {
            foreach($filter_employeess as $filter_employeess_data)
            {
                $filter_employees[$filter_employeess_data['id']] = $filter_employeess_data['first_name'].' '.$filter_employeess_data['last_name'];
            }
        }

        $filter_roless = Role::WhereRaw('created_by = "'.$created_by.'"')->get()->toArray();
        $filter_role = [];
        // $filter_role['all0'] = __('Select All');
        $filter_role['no_role'] = __('Without Role');
        if(!empty($filter_roless))
        {
            foreach($filter_roless as $filter_roless_data)
            {
                $filter_role[$filter_roless_data['id']] = $filter_roless_data['name'];
            }
        }

        $employees = Employee::WhereRaw('id = "'.$created_by.'"')->orWhereRaw('created_by = "'.$created_by.'"')->get()->toArray();
        $employee_id = [];
        if(!empty($employees))
        {
            foreach($employees as $employee_data)
            {
                $employee_id[] = $employee_data['id'];
            }
        }
        $employee_ids = implode(',',$employee_id);

        /* *********
        * 1  last month chart data
        ********* */
        $start = Carbon::now()->subDays(30);
        $hour = [];
        $cost = [];
        $user_salary = [];
        $user_salary1 = [];

        if(!empty($query['date'])) {
            $date = explode(' to ', $query['date']);
            $period = CarbonPeriod::create($date[0],$date[1]);
            foreach ($period as $date) {
                $dates[] = $date->format($date_format);
                $date_array[] = $date->format("Y-m-d");
            }
        } else {
            foreach (range(0, 30) as $day) {
                $date = $start->copy()->addDays($day);
                $dates[] = date($date_format, strtotime($date));
                $date_array[] = date("Y-m-d", strtotime($date));
            }
        }

        foreach ($date_array as $date)
        {

            $weere1 = ($weere1 != '0 = 0' ) ? $weere1 : '';
            $rotas = Rotas::WhereRaw($rotas_where)->whereRaw($published_shifts)
                            ->WhereRaw($manager_location_rota)
                            ->WhereRaw('user_id IN ('.$employee_ids.')')
                            ->WhereRaw('rotas_date = "'.$date.'"')
                            ->WhereRaw('create_by = "'.$created_by.'"')->get()->toArray();
            $rotas_responce = Report::rotas_chart($rotas);
            $rotas_responce = json_decode($rotas_responce,true);
            $hour[] = round($rotas_responce['time_counter'],2);
            $cost[] = round($rotas_responce['hour_cost'],2);
        }

        $dates = json_encode($dates);
        $hour = json_encode($hour);
        $cost = json_encode($cost);
        $daily_totals['date'] = $dates;
        $daily_totals['hour'] = $hour;
        $daily_totals['cost'] = $cost;

        // dd($dates,$hour,$cost);
        /* *********
        * 2  monthly rotas chart
        ********* */
        for ($i = 0; $i <= 12; $i++) {
            $last_months[] = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));
            $last_months_years[] = date("Y", strtotime( date( 'Y-m-01' )." -$i months"));
            $last_months_months[] = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
        }

        $monthly_hour = [];
        $monthly_cost = [];
        foreach ($last_months_years as $last_key => $last_months_year)
        {
            $month_rotas = Rotas::WhereRaw($rotas_where)->whereRaw($published_shifts)
                                ->WhereRaw($manager_location_rota)
                                ->WhereRaw('user_id IN ('.$employee_ids.')')
                                ->WhereRaw('MONTH(rotas_date) = '.$last_months_months[$last_key].'')
                                ->WhereRaw('YEAR(rotas_date) = '.$last_months_year.'')
                                ->WhereRaw('create_by = "'.$created_by.'"')->get()->toArray();
            $monthly_rotas_responce = Report::rotas_chart($month_rotas);
            $monthly_rotas_responce = json_decode($monthly_rotas_responce,true);
            $monthly_hour[] = round($monthly_rotas_responce['time_counter'],2);
            $monthly_cost[] = round($monthly_rotas_responce['hour_cost'],2);
        }

        $last_months = json_encode($last_months);
        $monthly_hour = json_encode($monthly_hour);
        $monthly_cost = json_encode($monthly_cost);
        $monthly['last_months'] = $last_months;
        $monthly['monthly_hour'] = $monthly_hour;
        $monthly['monthly_cost'] = $monthly_cost;


        /* *********
        * 3 Employee wise rotas chart
        ********* */
        $manager_manage_user = Employee::getMangaerMangeLocationUser();
        if(Auth::user()->acount_type == 2) {
            $manager_manage_user = (!empty($manager_manage_user)) ? $manager_manage_user : '0=0' ;
            $employees = Employee::WhereRaw($manager_manage_user)->get()->toArray();
        } else {
            $employees = Employee::WhereRaw('id = "'.$created_by.'"')->orWhereRaw('created_by = "'.$created_by.'"')->get()->toArray();
        }
        $employee = [];
        if(!empty($employees))
        {
            foreach($employees as $employee_data)
            {
                $employee[] = $employee_data['first_name'].' '.$employee_data['last_name'];
            }
        }

        $current_date = date("Y-m-d");
        $last_month_date = date('Y-m-d', strtotime('-30 days'));
        if(!empty($query['date'])) {
            $date = explode(' to ', $query['date']);
            $current_date = $date[1];
            $last_month_date = $date[0];
        }
        $user_cost = [];
        $user_hour = [];
        foreach ($employees as $employee_date)
        {
            $user_rotas = Rotas::WhereRaw($rotas_where)->whereRaw($published_shifts)
                                ->WhereRaw($manager_location_rota)
                                ->WhereRaw('user_id IN ('.$employee_ids.')')
                                ->WhereRaw('user_id = '.$employee_date['id'].'')
                                ->WhereRaw('rotas_date BETWEEN "'.$last_month_date .'" AND "'.$current_date.'"')
                                ->WhereRaw('create_by = '.$created_by.'')->get()->toArray();
            $user_rotas_responce = Report::rotas_chart($user_rotas);
            $user_rotas_responce = json_decode($user_rotas_responce,true);
            $user_hour[] = round($user_rotas_responce['time_counter'],2);
            $user_cost[] = round($user_rotas_responce['hour_cost'],2);
        }
        $employee = json_encode($employee);
        $user_hour = json_encode($user_hour);
        $user_cost = json_encode($user_cost);
        $employee_wise['employee'] = $employee;
        $employee_wise['user_hour'] = $user_hour;
        $employee_wise['user_cost'] = $user_cost;

        /* *********
        * 4 Location wise rotas chart
        ********* */
        if(Auth::user()->acount_type == 2) {
            $locationss = Location::WhereRaw('created_by = "'.$created_by.'"')->WhereRaw($manager_location)->get()->toArray();
        } else {
            $locationss = Location::WhereRaw('created_by = "'.$created_by.'"')->get()->toArray();
        }
        $locations = [];
        if(!empty($locationss))
        {
            foreach($locationss as $location)
            {
                $locations[] = $location['name'];
            }
        }

        $current_date = date("Y-m-d");
        $last_month_date = date('Y-m-d', strtotime('-30 days'));
        if(!empty($query['date'])) {
            $date = explode(' to ', $query['date']);
            $current_date = $date[1];
            $last_month_date = $date[0];
        }
        $location_cost = [];
        $location_hour = [];
        foreach($locationss as $location)
        {
            $user_rotas = Rotas::WhereRaw($rotas_where)->whereRaw($published_shifts)
                                ->WhereRaw($manager_location_rota)
                                ->WhereRaw('user_id IN ('.$employee_ids.')')
                                ->WhereRaw('location_id = '.$location['id'].'')
                                ->WhereRaw('rotas_date BETWEEN "'.$last_month_date .'" AND "'.$current_date.'"')
                                ->WhereRaw('create_by = '.$created_by.'')->get()->toArray();
            $user_rotas_responce = Report::rotas_chart($user_rotas);
            $user_rotas_responce = json_decode($user_rotas_responce,true);
            $location_hour[] = round($user_rotas_responce['time_counter'],2);
            $location_cost[] = round($user_rotas_responce['hour_cost'],2);
        }

        $locations = json_encode($locations);
        $location_hour = json_encode($location_hour);
        $location_cost = json_encode($location_cost);
        $locations_wise['location'] = $locations;
        $locations_wise['location_hour'] = $location_hour;
        $locations_wise['location_cost'] = $location_cost;

        /* *********
        * 5 Role wise rotas chart
        ********* */
        $employee_roless = Role::WhereRaw('created_by = "'.$created_by.'"')->get()->toArray();
        $employee_roles12 = [];
        if(!empty($employee_roless))
        {
            foreach($employee_roless as $employee_role)
            {
                $employee_roles12[] = $employee_role['name'];
            }
        }

        $current_date = date("Y-m-d");
        $last_month_date = date('Y-m-d', strtotime('-30 days'));
        if(!empty($query['date'])) {
            $date = explode(' to ', $query['date']);
            $current_date = $date[1];
            $last_month_date = $date[0];
        }
        $role_hour = [];
        $role_cost = [];
        foreach($employee_roless as $employee_roles)
        {
            $role_rotas = Rotas::WhereRaw($rotas_where)->whereRaw($published_shifts)
                                ->WhereRaw($manager_location_rota)
                                ->WhereRaw('user_id IN ('.$employee_ids.')')
                                ->WhereRaw('role_id = '.$employee_roles['id'].'')
                                ->WhereRaw('rotas_date BETWEEN "'.$last_month_date .'" AND "'.$current_date.'"')
                                ->WhereRaw('create_by = '.$created_by.'')->get()->toArray();
            $role_rotas_responce = Report::rotas_chart($role_rotas);
            $role_rotas_responce = json_decode($role_rotas_responce,true);
            $role_hour[] = round($role_rotas_responce['time_counter'],2);
            $role_cost[] = round($role_rotas_responce['hour_cost'],2);
        }

        $employee_roles = json_encode($employee_roles12);
        $role_hour = json_encode($role_hour);
        $role_cost = json_encode($role_cost);
        $role_wise['role'] = $employee_roles;
        $role_wise['role_hour'] = $role_hour;
        $role_wise['role_cost'] = $role_cost;

        /* *********
        * 6 leave chart
        ********* */
        $leaves_type[0] = __('Holiday');
        $leaves_type[1] = __('Other Leave');

        $current_date = date("Y-m-d");
        $last_month_date = date('Y-m-d', strtotime('-30 days'));
        if(!empty($query['date'])) {
            $date = explode(' to ', $query['date']);
            $current_date = $date[1];
            $last_month_date = $date[0];
        }
        $leave_type = [];
        $leave_type[1] = 0;
        $leave_type[2] = 0;

        $leaves = Leave::WhereRaw('user_id IN ('.$employee_ids.')')
                        ->WhereRaw($leave_where)
                        ->WhereRaw('leave_approval = 1')
                        ->WhereRaw('created_by = '.$created_by.'')
                        ->WhereRaw('start_date BETWEEN "'.$last_month_date .'" AND "'.$current_date.'"')
                        ->WhereRaw('end_date BETWEEN "'.$last_month_date .'" AND "'.$current_date.'"')
                        ->get()->toArray();

        if(!empty($leaves))
        {
            foreach($leaves as $leave)
            {
                if(empty($leave_type[$leave['leave_type']]))
                {
                    $leave_type[$leave['leave_type']] = 0;
                }
                $leave_type[$leave['leave_type']] = $leave_type[$leave['leave_type']] + 1;
            }
        }
        $leaves_wise['type'] = json_encode($leaves_type);
        $leaves_wise['leave'] = json_encode(array_values($leave_type));

        /* *********
        * 7 employee wise leave chart
        ********* */
        $employees = Employee::WhereRaw('id = "'.$created_by.'"')->orWhereRaw('created_by = "'.$created_by.'"')->get()->toArray();
        $employee = [];
        if(!empty($employees))
        {
            foreach($employees as $employee_data)
            {
                $employee[] = $employee_data['first_name'].' '.$employee_data['last_name'];
            }
        }

        $current_date = date("Y-m-d");
        $last_month_date = date('Y-m-d', strtotime('-30 days'));
        if(!empty($query['date'])) {
            $date = explode(' to ', $query['date']);
            $current_date = $date[1];
            $last_month_date = $date[0];
        }
        $user_leave[1] = [];
        $user_leave[2] = [];
        $user_leave[3] = [];
        foreach ($employees as $employee_date)
        {
            $leaves_data = Leave::select('leave_type',DB::raw('count(leave_type) as leave_type_count'))
                            ->WhereRaw($leave_where)
                            ->WhereRaw('user_id = '.$employee_date['id'].'')
                            ->WhereRaw('leave_approval = 1')
                            ->WhereRaw('created_by = '.$created_by.'')
                            ->WhereRaw('start_date BETWEEN "'.$last_month_date .'" AND "'.$current_date.'"')
                            ->WhereRaw('end_date BETWEEN "'.$last_month_date .'" AND "'.$current_date.'"')
                            ->groupBy('leave_type')->get()->toArray();

            if(!empty($leaves_data))
            {
                foreach($leaves_data as $leave_data)
                {
                    $user_leave[$leave_data['leave_type']][] = $leave_data['leave_type_count'];
                }
            }
            else
            {
                $user_leave[1][] = 0;
                $user_leave[2][] = 0;
                $user_leave[3][] = 0;
            }
        }

        $user_leave2 = [];
        foreach($user_leave as $user_leave1_key => $user_leave1)
        {
            $user_leave2[$user_leave1_key]['name'] = '';
            if($user_leave1_key == 1) {
                $user_leave2[$user_leave1_key]['name'] = __('Holiday');
            }
            if($user_leave1_key == 2) {
                $user_leave2[$user_leave1_key]['name'] = __('Other Leave');
            }
            if($user_leave1_key == 3) {
                $user_leave2[$user_leave1_key]['name'] = __('Public Holiday');
            }

            foreach($employees as $employee_data_key => $employee_data)
            {
                $user_leave2[$user_leave1_key]['data'][$employee_data_key] = (!empty($user_leave1[$employee_data_key])) ? $user_leave1[$employee_data_key] : 0 ;
            }
        }

        $user_leave2 = json_encode(array_values($user_leave2));

        $employee_wise_leave['employee'] = json_encode($employee);
        $employee_wise_leave['leave'] = $user_leave2;


        /* *********
        * 8 paid/unpaid leave chart
        ********* */
        $start = Carbon::now()->subDays(30);
        if(!empty($query['date'])) {
            $date = explode(' to ', $query['date']);
            $period = CarbonPeriod::create($date[0],$date[1]);
            foreach ($period as $date) {
                $paid_leave_dates[] = $date->format("d-M");
                $paid_date_array[] = $date->format("Y-m-d");
            }
        } else {
            foreach (range(0, 30) as $day) {
                $date = $start->copy()->addDays($day);
                $paid_leave_dates[] = date($date_format, strtotime($date));
                $paid_date_array[] = date("Y-m-d", strtotime($date));
            }
        }
        $current_date = date("Y-m-d");
        $last_month_date = date('Y-m-d', strtotime('-30 days'));
        if(!empty($query['date'])) {
            $date = explode(' to ', $query['date']);
            $current_date = $date[1];
            $last_month_date = $date[0];
        }

        foreach ($paid_date_array as $date)
        {
            $paid_leaves_data = Leave::WhereRaw('created_by = '.$created_by.'')
                                        ->WhereRaw($leave_where)
                                        ->WhereRaw('leave_approval = 1')
                                        ->WhereRaw('paid_status = "paid"')
                                        ->WhereRaw('(start_date = "'.$date.'" OR end_date = "'.$date.'")')
                                        ->count();
            $unpaid_leaves_data = Leave::WhereRaw('created_by = '.$created_by.'')
                                        ->WhereRaw($leave_where)
                                        ->WhereRaw('leave_approval = 1')
                                        ->WhereRaw('paid_status = "unpaid"')
                                        ->WhereRaw('(start_date = "'.$date.'" OR end_date = "'.$date.'")')
                                        ->count();

            $unpaid_leaves_array[] = (!empty($unpaid_leaves_data)) ? $unpaid_leaves_data : 0;
            $paid_leaves_array[] = (!empty($paid_leaves_data)) ? $paid_leaves_data : 0;
        }

        $paid_leave_data['date'] = json_encode($paid_leave_dates);
        $paid_leave_data['paid'] = json_encode($paid_leaves_array);
        $paid_leave_data['unpaid'] = json_encode($unpaid_leaves_array);
        // dd($get_date_val);
        return view('report.index',compact('get_location_val', 'get_user_val', 'get_role_val', 'get_date_val', 'filter_locations','filter_employees','filter_role','daily_totals','monthly','employee_wise','locations_wise','role_wise','leaves_wise','employee_wise_leave','paid_leave_data'));
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
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }

}
