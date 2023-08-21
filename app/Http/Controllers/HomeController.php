<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LandingPageSections;
use App\Models\Location;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Profile;
use App\Models\Role;
use App\Models\Rotas;
use App\Models\User;
use App\Models\Utility;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\Plan as ApiPlan;
use Spatie\GoogleCalendar\Event;
use Stripe;

class HomeController extends Controller
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    public function index()
    {
        if(Auth::check())
        {
            if(Auth::user()->type == 'super admin')
            {
                $user                       = Auth::user();
                $user['total_user']         = $user->countCompany();
                $user['total_paid_user']    = $user->countPaidCompany();
                $user['total_orders']       = Order::total_orders();
                $user['total_orders_price'] = Order::total_orders_price();
                $user['total_plan']         = Plan::total_plan();
                $user['most_purchese_plan'] = (!empty(Plan::most_purchese_plan()) ? Plan::most_purchese_plan()->name : '');
                $chartData                  = $this->getOrderChart(['duration' => 'week']);
                $profile = Profile::where('user_id',$user->id)->first()->toArray();

                $user = Auth::user();
                $created_by = $user->get_created_by();
                $users = User::where('created_by',$created_by)->get();

                return view('super_admin', compact('user', 'chartData','profile','users'));
            }
            else
            {
                $com_setting = User::companystaticSetting();
                $break_paid = (!empty($com_setting['break_paid'])) ? $com_setting['break_paid'] : 'paid';
                $include_unpublished_shifts = (!empty($com_setting['include_unpublished_shifts'])) ? $com_setting['include_unpublished_shifts'] : 0;

                $userId = Auth::id();
                $user = Auth::user();
                $created_by = $user->get_created_by();
                $userType = Auth::user()->type;

                $employee = Employee::where('created_by', $created_by)->orwhere('id', $created_by)->get()->toArray();
                if(Auth::user()->acount_type == 3)
                {
                    $employee = Employee::where('id', $userId)->get()->toArray();
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

                $roless = Role::where('created_by', $created_by)->get()->toArray();
                $roles = [];
                if(!empty($roless))
                {
                    foreach($roless as $keyy => $role)
                    {

                        $color = "color:".$role['color']."";
                        $roles[$keyy]['id'] = $role['id'];
                        $roles[$keyy]['name'] = '<i class="fas fa-dot-circle" aria-hidden="true" style="'.$color.'"></i>'.$role['name'];
                    }
                }
                $locations = Location::where('created_by', $created_by)->get()->toArray();

                // show price
                if(Auth::user()->type != 'company')
                {
                    $company_setting_data = Employee::Where('id',$created_by)->first();
                    if(!(empty($company_setting_data->company_setting)))
                    {
                        $com_setting = json_decode($company_setting_data->company_setting,true);
                        $emp_show_rotas_price = (!empty($com_setting['emp_show_rotas_price'])) ? $com_setting['emp_show_rotas_price'] : 0 ;
                    }
                }
                else
                {
                    $emp_show_rotas_price = 1;
                }

                $published_shifts = ' publish = 1 ';
                if($include_unpublished_shifts == 1)
                {
                    $published_shifts = ' 0 = 0';
                }

                // feed calender
                $rotas_dates = Rotas::select('rotas_date')->whereRaw('user_id IN ('.$employee_id.')')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->groupBy('rotas_date')->get()->toArray();
                $rotas_date = [];
                if(!empty($rotas_dates))
                {
                    $rotas_date = [];
                    $rotas_date = array_column($rotas_dates,'rotas_date');
                }

                $count_role_id = [];
                foreach($rotas_date as $date)
                {
                    $rotas = Rotas::whereRaw('user_id IN ('.$employee_id.')')->whereRaw('rotas_date = "'.$date.'" ')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->get()->toArray();
                    if(!empty($rotas))
                    {
                        $role_id = [];
                        foreach($rotas as $rota)
                        {
                            $profile_data = Profile::whereRaw('user_id = '.$rota['user_id'].' ')->first();
                            $color = '#8492a6';
                            $nameee = '-';
                            $roll_ids = '-';
                            if(!empty($rota['role_id']))
                            {
                                $role_data = Role::whereRaw('id = '.$rota['role_id'].' ')->first();
                                $color = $role_data['color'];
                                $nameee = $role_data['name'];
                                $roll_ids = $role_data['id'];
                            }

                            $location = Location::whereRaw('id = '.$rota['location_id'].' ')->first();

                            $role_id[] = (!empty($rota['role_id'])) ? $rota['role_id'] : '';
                            $count_role_id[$date][$rota['role_id']][$rota['location_id']][] = array(
                                'id'                => $rota['id'],
                                'roll_id_color'     => $color,
                                'roll_id'           => $roll_ids,
                                'role_id'           => $roll_ids,
                                'roll_name'         => $nameee,
                                'custome_salary'    => $profile_data->custome_salary,
                                'default_salary'    => $profile_data->default_salary,
                                'location_id'       => $rota['location_id'],
                                'time_diff_in_minut'=> $rota['time_diff_in_minut'],
                                'break_time'        => $rota['break_time'],
                                'location_name'     => $location->name,
                                'start_time'        => $rota['start_time'],
                                'end_time'          => $rota['end_time'],
                                'data'              => $employee_data[$rota['user_id']].' ('.$rota['start_time'].' - '.$rota['end_time'].') '.$location->name,
                            );
                        }
                    }
                }

                $feed_calender = [];
                $i=-1;
                foreach($count_role_id as $feed_key => $count_role_ids)
                {
                    $i++;
                    $html = '';
                    $roll_cnt = '';
                    $cnt_employee1 = 0;
                    $daily_expence = 0;
                    if(!empty($count_role_ids))
                    {
                        foreach($count_role_ids as $feed_role_id => $feed_role_data)
                        {
                            $tooltip = '';
                            $location = '';
                            $css='';
                            $emp_time = '';
                            $cnt_employee = 0;

                            foreach($feed_role_data as $feed_location_id => $feed_location_data)
                            {
                                $css = 'background-color: '.$feed_location_data[0]['roll_id_color'].';';
                                $cnt_employee += count($feed_location_data);
                                $roll_id = $feed_location_data[0]['roll_id'];
                                $roll_id_name = $feed_location_data[0]['roll_name'];
                                $user_data = '';
                                if(!empty($feed_location_data))
                                {
                                    $time_counter = 0;
                                    foreach($feed_location_data as $feed_user_id => $feed_user_data)
                                    {
                                        $user_data .= $feed_user_data['data'].'&#013;';
                                        $time_counter = $feed_user_data['time_diff_in_minut'];
                                        if($break_paid != 'paid')
                                        {
                                            $time_counter = $feed_user_data['time_diff_in_minut'] - $feed_user_data['break_time'];
                                        }
                                        $time_counter = $time_counter / 60;

                                        $daily_expence1 = 0;

                                        $default_salarys_array = [];
                                        if(!empty($feed_user_data['default_salary']))
                                        {
                                            $default_salarys_array = json_decode($feed_user_data['default_salary'],true);
                                        }

                                        $custome_salary_array = [];
                                        if(!empty($feed_user_data['custome_salary']))
                                        {
                                            $custome_salary_array = json_decode($feed_user_data['custome_salary'],true);
                                        }

                                        if(!empty($custome_salary_array) && !empty($feed_user_data['role_id']))
                                        {

                                            if( !empty($custome_salary_array[$feed_user_data['role_id']]) &&
                                                !empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'])
                                            )
                                            {
                                                $daily_expence1 = $time_counter * $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'];
                                                if(!empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift']))
                                                {
                                                    $daily_expence1 = $daily_expence1 + $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift'];
                                                }
                                            }
                                            elseif(!empty($default_salarys_array))
                                            {
                                                if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                                                {
                                                    $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                                }
                                            }
                                        }
                                        else
                                        {
                                            if(!empty($default_salarys_array))
                                            {
                                                if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                                                {
                                                    $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                                }
                                            }
                                        }
                                        $daily_expence += $daily_expence1;
                                    }
                                }

                                $tooltip .= $user_data;
                            }
                            $feed_role_id = (!empty($feed_role_id)) ? $feed_role_id : 'no_role';
                            $roll_cnt .= '<div class="badge1" data_role_id="'.$feed_role_id.'" style="'.$css.' " title="'.$tooltip.'">'.$cnt_employee.'</div> ';
                            $cnt_employee1 += $cnt_employee;
                        }

                        $text_color = (Auth::user()->mode != 'dark') ? 'text-dark' : 'text-white';
                        $priceee  = ($emp_show_rotas_price == 1) ? ' <span><span>'. User::priceFormat($daily_expence) .'</span></span>' : '';

                        $feed_calender[$i] = array(
                            'start' => $feed_key,
                            'end' => $feed_key,
                            'className' => 'bg-transparent',
                            'html' => '<div>'.$roll_cnt.'<div class=" '.$text_color.' opacity-50 mt-2" style=" font-size: 12px; "> <span title="'.__('Employees').' '.$cnt_employee1.'"><i class="fas fa-user" aria-hidden="true"></i> <span>'.$cnt_employee1.'</span></span> &nbsp;&nbsp;  '.$priceee.'  </div></div>',
                        );
                    }
                }

                if(Auth::user()->type == 'company')
                {
                    $current_month_rotas = Rotas::whereMonth('rotas_date', date('m'))
                                            ->whereYear('rotas_date', date('Y'))
                                            ->where('publish', 1)
                                            ->where('shift_status', 'enable')
                                            ->where('create_by', $created_by)
                                            ->OrderBy('rotas_date', 'ASC')
                                            ->OrderBy('start_time', 'ASC')
                                            ->get();
                    // dd($current_month_rotas);
                    $feed_calender = json_encode($feed_calender);
                    $users = User::find($created_by);
                    $plan = Plan::find($users->plan);
                    if($plan->company_storage > 0)
                    {
                        $storage_limit = number_format($users->company_storage / $plan->company_storage, 2) * 100;
                    }
                    else
                    {
                        $storage_limit = 0;
                    }
                    return view('home',compact('feed_calender','roles','locations','current_month_rotas','users','plan','storage_limit'));
                }
                else
                {
                    $employeeAttendance = Attendance::orderBy('id', 'desc')->where('user_id', '=' ,Auth::user()->id)->first();
                    $current_month_rotas = Rotas::whereMonth('rotas_date', date('m'))
                                            ->whereYear('rotas_date', date('Y'))
                                            ->where('publish', 1)
                                            ->where('shift_status', 'enable')
                                            ->where('create_by', $created_by)
                                            ->where('user_id' , Auth::user()->id)
                                            ->OrderBy('rotas_date', 'ASC')
                                            ->OrderBy('start_time', 'ASC')
                                            ->get();
                    // dd($current_month_rotas);
                    $feed_calender = json_encode($feed_calender);
                    return view('home',compact('feed_calender','roles','locations','current_month_rotas','employeeAttendance'));
                }
            }
        }
        else
        {
            if(!file_exists(storage_path() . "/installed"))
            {
                header('location:install');
                die;
            }
            else
            {
                $settings = Utility::settings();
                if($settings['display_landing_page'] == 'on')
                {
                    return view('layouts.theme_landing');
                }
                else
                {
                    return redirect('login');
                }
            }
        }
    }

    public function get_rota_data(Request $request)
    {
        if($request->get('is_live')=='1')
        {
            Utility::googleCalendarConfig();
            $data= Event::get();
            $type ='rotas';
            $type=Utility::colorCodeData($type);
            $feed_calender = [];
            foreach($data as $val)
            {
                $text_color = (Auth::user()->mode != 'dark') ? 'text-dark' : 'text-white';
                $end_date=date_create($val->endDateTime);
                date_add($end_date,date_interval_create_from_date_string("1 days"));

                if($val->colorId=="$type"){
                    $feed_calender[] = array(
                        'start' => $val->startDateTime,
                        'end' => $end_date,
                        'className' => 'bg-transparent',
                        'html' => '<div>'.$val->summary.'<div class=" '.$text_color.' opacity-50 mt-2" style=" font-size: 12px; "> <span title="'.__('Employees').' '.'1'.'"><i class="fas fa-user" aria-hidden="true"></i> <span>'.'1'.'</span></span> &nbsp;&nbsp;  '.'0'.'  </div></div>',
                    );
                }
            }
        }
        else
        {
            $com_setting = User::companystaticSetting();
            $break_paid = (!empty($com_setting['break_paid'])) ? $com_setting['break_paid'] : 'paid';
            $include_unpublished_shifts = (!empty($com_setting['include_unpublished_shifts'])) ? $com_setting['include_unpublished_shifts'] : 0;

            $userId = Auth::id();
            $user = Auth::user();
            $created_by = $user->get_created_by();
            $userType = Auth::user()->type;

            $employee = Employee::where('created_by', $created_by)->orwhere('id', $created_by)->get()->toArray();
            if(Auth::user()->acount_type == 3)
            {
                $employee = Employee::where('id', $userId)->get()->toArray();
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
            $roless = Role::where('created_by', $created_by)->get()->toArray();
            $roles = [];
            if(!empty($roless))
            {
                foreach($roless as $keyy => $role)
                {
                    $color = "color:".$role['color']."";
                    $roles[$keyy]['id'] = $role['id'];
                    $roles[$keyy]['name'] = '<i class="fas fa-dot-circle" aria-hidden="true" style="'.$color.'"></i>'.$role['name'];
                }
            }
            $locations = Location::where('created_by', $created_by)->get()->toArray();
                    // show price
            if(Auth::user()->type != 'company')
            {
                $company_setting_data = Employee::Where('id',$created_by)->first();
                if(!(empty($company_setting_data->company_setting)))
                {
                    $com_setting = json_decode($company_setting_data->company_setting,true);
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

            $rotas_dates = Rotas::select('rotas_date')->whereRaw('user_id IN ('.$employee_id.')')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->groupBy('rotas_date')->get()->toArray();
            $rotas_date = [];
            if(!empty($rotas_dates))
            {
                $rotas_date = [];
                $rotas_date = array_column($rotas_dates,'rotas_date');
            }

            $count_role_id = [];
            foreach($rotas_date as $date)
            {
                $rotas = Rotas::whereRaw('user_id IN ('.$employee_id.')')->whereRaw('rotas_date = "'.$date.'" ')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->get()->toArray();
                if(!empty($rotas))
                {
                    $role_id = [];
                    foreach($rotas as $rota)
                    {
                        $profile_data = Profile::whereRaw('user_id = '.$rota['user_id'].' ')->first();
                        $color = '#8492a6';
                        $nameee = '-';
                        $roll_ids = '-';
                        if(!empty($rota['role_id']))
                        {
                            $role_data = Role::whereRaw('id = '.$rota['role_id'].' ')->first();
                            $color = $role_data['color'];
                            $nameee = $role_data['name'];
                            $roll_ids = $role_data['id'];
                        }

                        $location = Location::whereRaw('id = '.$rota['location_id'].' ')->first();

                        $role_id[] = (!empty($rota['role_id'])) ? $rota['role_id'] : '';
                        $count_role_id[$date][$rota['role_id']][$rota['location_id']][] = array(
                            'id'                => $rota['id'],
                            'roll_id_color'     => $color,
                            'roll_id'           => $roll_ids,
                            'role_id'           => $roll_ids,
                            'roll_name'         => $nameee,
                            'custome_salary'    => $profile_data->custome_salary,
                            'default_salary'    => $profile_data->default_salary,
                            'location_id'       => $rota['location_id'],
                            'time_diff_in_minut'=> $rota['time_diff_in_minut'],
                            'break_time'        => $rota['break_time'],
                            'location_name'     => $location->name,
                            'start_time'        => $rota['start_time'],
                            'end_time'          => $rota['end_time'],
                            'data'              => $employee_data[$rota['user_id']].' ('.$rota['start_time'].' - '.$rota['end_time'].') '.$location->name,
                        );
                    }
                }
            }

            $feed_calender = [];
            $i=-1;
            foreach($count_role_id as $feed_key => $count_role_ids)
            {
                $i++;
                $html = '';
                $roll_cnt = '';
                $cnt_employee1 = 0;
                $daily_expence = 0;
                if(!empty($count_role_ids))
                {
                    foreach($count_role_ids as $feed_role_id => $feed_role_data)
                    {
                        $tooltip = '';
                        $location = '';
                        $css='';
                        $emp_time = '';
                        $cnt_employee = 0;

                        foreach($feed_role_data as $feed_location_id => $feed_location_data)
                        {
                            $css = 'background-color: '.$feed_location_data[0]['roll_id_color'].';';
                            $cnt_employee += count($feed_location_data);
                            $roll_id = $feed_location_data[0]['roll_id'];
                            $roll_id_name = $feed_location_data[0]['roll_name'];
                            $user_data = '';
                            $user_time1 ='';
                            $user_time2 ='';
                            if(!empty($feed_location_data))
                            {
                                $time_counter = 0;
                                foreach($feed_location_data as $feed_user_id => $feed_user_data)
                                {
                                    $user_data .= $feed_user_data['data'].'&#013;';
                                    $time_counter = $feed_user_data['time_diff_in_minut'];
                                    $user_time1 .= $feed_user_data['start_time'];
                                    $user_time2 .= $feed_user_data['end_time'];

                                    if($break_paid != 'paid')
                                    {
                                        $time_counter = $feed_user_data['time_diff_in_minut'] - $feed_user_data['break_time'];
                                    }
                                    $time_counter = $time_counter / 60;
                                    $daily_expence1 = 0;

                                    $default_salarys_array = [];
                                    if(!empty($feed_user_data['default_salary']))
                                    {
                                        $default_salarys_array = json_decode($feed_user_data['default_salary'],true);
                                    }

                                    $custome_salary_array = [];
                                    if(!empty($feed_user_data['custome_salary']))
                                    {
                                        $custome_salary_array = json_decode($feed_user_data['custome_salary'],true);
                                    }

                                    if(!empty($custome_salary_array) && !empty($feed_user_data['role_id']))
                                    {
                                        if( !empty($custome_salary_array[$feed_user_data['role_id']]) &&
                                            !empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'])
                                        )
                                        {
                                            $daily_expence1 = $time_counter * $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'];
                                            if(!empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift']))
                                            {
                                                $daily_expence1 = $daily_expence1 + $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift'];
                                            }
                                        }
                                        elseif(!empty($default_salarys_array))
                                        {
                                            if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                                            {
                                                $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if(!empty($default_salarys_array))
                                        {
                                            if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                                            {
                                                $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                            }
                                        }
                                    }
                                    $daily_expence += $daily_expence1;
                                }
                            }
                            $tooltip .= $user_data;
                        }
                        $feed_role_id = (!empty($feed_role_id)) ? $feed_role_id : 'no_role';
                        $roll_cnt .= '<div class="badge1" data_role_id="'.$feed_role_id.'" style="'.$css.' " title="'.$tooltip.'">'.$cnt_employee.'</div> ';
                        $cnt_employee1 += $cnt_employee;
                    }
                    $text_color = (Auth::user()->mode != 'dark') ? 'text-dark' : 'text-white';
                    $priceee  = ($emp_show_rotas_price == 1) ? ' <span><span>'. User::priceFormat($daily_expence) .'</span></span>' : '';
                    $feed_calender[$i] = array(
                        'start' => $feed_key,
                        'end' => $feed_key,
                        'className' => 'bg-transparent',
                        'html' => '<div>'.$roll_cnt.'<div class=" '.$text_color.' opacity-50 mt-2" style=" font-size: 12px; "> <span title="'.__('Employees').' '.$cnt_employee1.'"><i class="fas fa-user" aria-hidden="true"></i> <span>'.$cnt_employee1.'</span></span> &nbsp;&nbsp;  '.$priceee.'  </div></div>',
                        'allDay' => false,
                    );
                }
            }
        }
        return  $feed_calender;
    }

    public function check()
    {

    }

    public function location_filter()
    {
        $location_id = $_REQUEST['location_id'];
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $userType = Auth::user()->type;

        $com_setting = User::companystaticSetting();
        // dd($com_setting);
        $break_paid = (!empty($com_setting['break_paid'])) ? $com_setting['break_paid'] : 'paid';
        $include_unpublished_shifts = (!empty($com_setting['include_unpublished_shifts'])) ? $com_setting['include_unpublished_shifts'] : 0;

        // show price
        if(Auth::user()->type != 'company')
        {
            $company_setting_data = Employee::Where('id',$created_by)->first();
            if(!(empty($company_setting_data->company_setting)))
            {
                $com_setting = json_decode($company_setting_data->company_setting,true);
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

        $employee = Employee::where('created_by', $created_by)->orwhere('id', $created_by)->get()->toArray();
        if(Auth::user()->acount_type == 3)
        {
            $employee = Employee::where('id', $userId)->get()->toArray();
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

        $rotas_dates = Rotas::select('rotas_date')->whereRaw('user_id IN ('.$employee_id.')')->whereRaw('shift_status != "disable"')->whereRaw($published_shifts)->groupBy('rotas_date')->get()->toArray();
        if(!empty($location_id))
        {
            $rotas_dates = Rotas::select('rotas_date')->whereRaw('user_id IN ('.$employee_id.')')->whereRaw('location_id = '.$location_id.' ')->whereRaw('shift_status != "disable"')->whereRaw($published_shifts)->groupBy('rotas_date')->get()->toArray();
        }
        $rotas_date = [];
        if(!empty($rotas_dates))
        {
            $rotas_date = [];
            $rotas_date = array_column($rotas_dates,'rotas_date');
        }

        $count_role_id = [];
        foreach($rotas_date as $date)
        {
            $rotas = Rotas::whereRaw('user_id IN ('.$employee_id.')')->whereRaw('rotas_date = "'.$date.'" ')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->get()->toArray();
            if(!empty($location_id))
            {
                $rotas = Rotas::whereRaw('user_id IN ('.$employee_id.')')->whereRaw('rotas_date = "'.$date.'" ')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->whereRaw('location_id = '.$location_id.' ')->get()->toArray();
            }
            if(!empty($rotas))
            {
                $role_id = [];
                foreach($rotas as $rota)
                {
                    $profile_data = Profile::whereRaw('user_id = '.$rota['user_id'].' ')->first();
                    $color = '#8492a6';
                    $nameee = '-';
                    $roll_ids = '-';
                    if(!empty($rota['role_id']))
                    {
                        $role_data = Role::whereRaw('id = '.$rota['role_id'].' ')->first();
                        $color = $role_data['color'];
                        $nameee = $role_data['name'];
                        $roll_ids = $role_data['id'];
                    }

                    $location = Location::whereRaw('id = '.$rota['location_id'].' ')->first();

                    $role_id[] = (!empty($rota['role_id'])) ? $rota['role_id'] : '';
                    $count_role_id[$date][$rota['role_id']][$rota['location_id']][] = array(
                        'id'                => $rota['id'],
                        'roll_id_color'     => $color,
                        'roll_id'           => $roll_ids,
                        'role_id'           => $roll_ids,
                        'roll_name'         => $nameee,
                        'custome_salary'    => $profile_data->custome_salary,
                        'default_salary'    => $profile_data->default_salary,
                        'location_id'       => $rota['location_id'],
                        'time_diff_in_minut'=> $rota['time_diff_in_minut'],
                        'break_time'        => $rota['break_time'],
                        'location_name'     => $location->name,
                        'start_time'        => $rota['start_time'],
                        'end_time'          => $rota['end_time'],
                        'data'              => $employee_data[$rota['user_id']].' ('.$rota['start_time'].' - '.$rota['end_time'].') '.$location->name,
                    );
                }
            }
        }

        $feed_calender = [];
        $i=-1;
        // date
        foreach($count_role_id as $feed_key => $count_role_ids)
        {
            $i++;
            $html = '';
            $roll_cnt = '';
            $cnt_employee1 = 0;
            $daily_expence = 0;
            if(!empty($count_role_ids))
            {
                // role_id
                foreach($count_role_ids as $feed_role_id => $feed_role_data)
                {
                    $tooltip = '';
                    $location = '';
                    $css='';
                    $emp_time = '';
                    $cnt_employee = 0;
                    //locatio_id
                    foreach($feed_role_data as $feed_location_id => $feed_location_data)
                    {
                        $css = 'background-color: '.$feed_location_data[0]['roll_id_color'].';';
                        $cnt_employee += count($feed_location_data);
                        $roll_id = $feed_location_data[0]['roll_id'];
                        $roll_id_name = $feed_location_data[0]['roll_name'];
                        $user_data = '';
                        if(!empty($feed_location_data))
                        {
                            $time_counter = 0;
                            foreach($feed_location_data as $feed_user_id => $feed_user_data)
                            {
                                $user_data .= $feed_user_data['data'].'&#013;';
                                $time_counter = $feed_user_data['time_diff_in_minut'];
                                if($break_paid != 'paid')
                                {
                                    $time_counter = $feed_user_data['time_diff_in_minut'] - $feed_user_data['break_time'];
                                }

                                $time_counter = $time_counter / 60;

                                $daily_expence1 = 0;

                                $default_salarys_array = [];
                                if(!empty($feed_user_data['default_salary']))
                                {
                                    $default_salarys_array = json_decode($feed_user_data['default_salary'],true);
                                }

                                $custome_salary_array = [];
                                if(!empty($feed_user_data['custome_salary']))
                                {
                                    $custome_salary_array = json_decode($feed_user_data['custome_salary'],true);
                                }

                                if(!empty($custome_salary_array) && !empty($feed_user_data['role_id']))
                                {
                                    if( !empty($custome_salary_array[$feed_user_data['role_id']]) &&
                                        !empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'])
                                      )
                                    {
                                        $daily_expence1 = $time_counter * $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'];
                                        if(!empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift']))
                                        {
                                            $daily_expence1 = $daily_expence1 + $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift'];
                                        }
                                    }
                                    elseif(!empty($default_salarys_array))
                                    {
                                        if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                                        {
                                            $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                        }
                                    }
                                }
                                else
                                {
                                    if(!empty($default_salarys_array))
                                    {
                                        if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                                        {
                                            $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                        }
                                    }
                                }
                                $daily_expence += $daily_expence1;
                            }
                        }
                        $tooltip .= $user_data;
                    }
                    $feed_role_id = (!empty($feed_role_id)) ? $feed_role_id : 'no_role';
                    $roll_cnt .= '<div class="badge1" data_role_id="'.$feed_role_id.'" style="'.$css.' color: #FFF" data-html="true" data-toggle="tooltip" title="'.$tooltip.'">'.$cnt_employee.'</div> ';
                    $cnt_employee1 += $cnt_employee;
                }

                $text_color = (Auth::user()->mode != 'dark') ? 'text-dark' : 'text-white';
                $priceee  = ($emp_show_rotas_price == 1) ? ' <span><span>'.User::priceFormat($daily_expence).'</span></span>' : '';
                $feed_calender[$i] = array(
                    'start' => $feed_key,
                    'end' => $feed_key,
                    'className' => 'bg-transparent',
                    'html' => '<div>'.$roll_cnt.'<div class="'.$text_color.' opacity-50 mt-2" style=" font-size: 13px; "> <span data-toggle="tooltip" title="'.__('Employees').' '.$cnt_employee1.'"><i class="fas fa-user" aria-hidden="true"></i> <span>'.$cnt_employee1.'</span></span> &nbsp;&nbsp;  '.$priceee.'  </div></div>',
                );
            }

        }
        return $feed_calender;
    }
    public function getOrderChart($arrParam)
    {
        $arrDuration = [];
        if($arrParam['duration'])
        {
            if($arrParam['duration'] == 'week')
            {
                $previous_week = strtotime("-2 week +1 day");
                for($i = 0; $i < 7; $i++)
                {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }

        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];
        foreach($arrDuration as $date => $label)
        {

            $data               = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $data->total;
        }

        return $arrTask;
    }
}
