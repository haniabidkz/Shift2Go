<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Hamcrest\Arrays\IsArray;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class Employee extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'company_name',
        'type',
        'email',
        'email_verified_at',
        'password',
        'issue_by',
        'created_by',
        'acount_type',
        'manager_permission',
        'company_detail',
        'company_setting',
        'lang',
        'mode',
        'is_delete',
        'deleted_at',
        'deleted_by',
        'plan',
        'plan_expire_date',
        'remember_token'
    ];
     protected $hidden = [
        'password', 'pin',
    ];

    public function getwagesalary($id = 0)
    {
        $salary = '-';
        $employee_data = Profile::whereRaw('user_id ='.$id.'')->get()->toArray();
        if(!empty($employee_data[0]['default_salary']))
        {
            $def_salary = json_decode($employee_data[0]['default_salary'],true);
            $amount = ($def_salary['salary'] != '') ? $def_salary['salary'] : '0';
            $type = ($def_salary['salary_per'] == 'hourly') ? 'hour' : 'hour';
            $salary = $amount.' / '.$type;
        }
        return $salary;
    }

    public function getweeklyhours($id = 0)
    {
        $employee_data = Profile::whereRaw('user_id ='.$id.'')->get()->toArray();
        return (!empty($employee_data[0]['weekly_hour'])) ? $employee_data[0]['weekly_hour'].' '.__('hour') : '-' ;
    }

    public function getLocations($id = 0)
    {
        $location_data = [];
        $location_data1 = '-';
        $employee_data = Profile::whereRaw('user_id ='.$id.'')->get()->toArray();
        if(!empty($employee_data[0]['location_id']))
        {
            $locations = Location::whereRaw('id IN ('.$employee_data[0]['location_id'].')')->get()->toArray();
            if(!empty($locations))
            {
                foreach($locations as $location)
                {
                    $location_data[] =$location;
                }
            }
            // if(!empty($location_data))
            // {
            //     $location_data1 = implode(', ',$location_data);
            // }
        }
        return $location_data;
    }

    public function getLocatopnName($id = 0)
    {
        $location_data = [];
        $location_data1 = '-';
        $employee_data = Profile::whereRaw('user_id ='.$id.'')->get()->toArray();
        if(!empty($employee_data[0]['location_id']))
        {
            $locations = Location::whereRaw('id IN ('.$employee_data[0]['location_id'].')')->get()->toArray();
            if(!empty($locations))
            {
                foreach($locations as $location)
                {
                    $location_data[] =$location['name'];
                }
            }
            if(!empty($location_data))
            {
                $location_data1 = implode(', ',$location_data);
            }
        }
        return $location_data1;
    }

    public static function getMangaerMangeLocationUser()
    {
        $permission = Auth::user()->manager_permission;
        $where = '';
        $user_where = '';
        if(!empty($permission) && Auth::user()->acount_type == 2)
        {
            $permission = json_decode($permission,true);
            $profile_where = [];
            if(!empty($permission['manage_loaction']))
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
                $user_where = (!empty($user_ids)) ? 'id IN ('.implode(',',$user_ids).')' : ' id IN (0) ' ;
            }
            else
            {
                $user_where = '0 = 0';
            }
        }
        return (!empty($user_where) ? $user_where : ' 0 = 0 ' );
    }

    public static function getMangaerMangeLocationUserOtherTable()
    {
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
                $user_where = (!empty($user_ids)) ? implode(',',$user_ids) : 0 ;
            }
        }
        return $user_where;
    }

    public function getweeklyhour()
    {
        $weekly_hour = $this->weekly_hour;
        return (!empty($weekly_hour)) ? $weekly_hour : '0';
    }

    public function getDefaultEmployeeRole($id = 0)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $id = $this->id;
        $role_value = '';
        $role_id = '';
        $default_role_id = Profile::select('role_id')->where('user_id', $id)->get()->toArray();
        if(!empty($default_role_id))
        {
             $role_id = $default_role_id[0]['role_id'];
             if(!empty($role_id))
             {
                 $role_array = Role::select('id','color','name')->whereRaw('id in ('.$role_id.')')->get()->toArray();
                 if(!empty($role_array))
                 {
                     foreach($role_array as $role_array_val)
                     {
                        $role_value .= '<span class="badge  p-2 px-3 rounded me-1" style="background-color:'.$role_array_val['color'].'"> '.$role_array_val['name'].'</span>';
                     }
                 }
             }
        }
        return (!empty($role_value)) ? $role_value : '-';
    }

    public function employeeRoles($id = 0)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $id = $this->id;
        $role_value = '';
        $role_id = '';
        $default_role_id = Profile::select('role_id')->where('user_id', $id)->get()->toArray();
        if(!empty($default_role_id))
        {
             $role_id = $default_role_id[0]['role_id'];
             if(!empty($role_id))
             {
                 $role_array = Role::whereRaw('id in ('.$role_id.')')->get()->toArray();
                 return $role_array;
                //  if(!empty($role_array))
                //  {
                //      foreach($role_array as $role_array_val)
                //      {
                //         return 
                //         // $role_value .= $role_array_val;
                //      }
                //  }
             }
        }
        return (!empty($role_value)) ? $role_value : [];
    }

    public function getAnnualHoliday($id = 0)
    {
        $profile_data = Profile::select('annual_holiday')->where('user_id',$id)->first();
        $annual_holiday = __('SET');
        if(!empty($profile_data) && !empty($profile_data['annual_holiday']))  {
            $annual_holiday_data = json_decode($profile_data['annual_holiday'],true);
            if(!empty($annual_holiday_data) && !empty($annual_holiday_data['time']))
            {
                $leave_type = ($annual_holiday_data['type'] == 'day') ? __('Days') : __('Hours') ;
                $annual_holiday = $annual_holiday_data['time'].' '.$leave_type;
            }
        }
        $data_ajax= '';
        if(Auth::user()->type == 'company')
        {
            $data_ajax= 'data-size="md" data-ajax-popup="true" data-title="'.__('Add Annual Leave').'" data-url="'.route('holidays.annual_leave',$id).'"';
        }
        $annual_holiday = '<button type="button" class="bg-transparent rounded-circle border-0" '.$data_ajax.'><span class="btn-inner--icon action-item">'. $annual_holiday .'</span> </button>';
        return $annual_holiday;
    }

    public function getUsedHoliday($id = 0)
    {
        $comapany_setting = User::companystaticSetting();
        $leave_year_start = (!empty($comapany_setting['leave_start_month'])) ? $comapany_setting['leave_start_month'] : 01 ;
        $start_date = date('Y-'.$leave_year_start.'-01');
        $end_date =  date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d', strtotime('+1 year', strtotime($start_date))))));
        $where = 'start_date > "'.$start_date.'" and end_date < "'.$end_date.'"';
        $user_leave = 0;
        $usedleaves_SQL = LeaveRequest::whereraw('user_id ='.$id.'')->whereraw('leave_type = 1')->whereraw ('leave_approval = 1')->whereraw($where)->toSql();
        $usedleaves = LeaveRequest::where('user_id',$id)->where('leave_type',1)->where('leave_approval',1)->whereraw($where)->get();
        $leve_count = 0;
        if(!empty($usedleaves) && count($usedleaves) != 0)
        {
            foreach($usedleaves as $usedleave)
            {
                $diff = Carbon::parse( $usedleave['start_date'] )->diffInDays( $usedleave['end_date'] ) ;
                $leve_count += $diff + 1;
            }
        }
        return $leve_count.' '.__('Days');
    }

    public function getRemaingHoliday($id = 0)
    {
        $comapany_setting = User::companystaticSetting();
        $leave_year_start = (!empty($comapany_setting['leave_start_month'])) ? $comapany_setting['leave_start_month'] : 01 ;
        $start_date = date('Y-'.$leave_year_start.'-01');
        $end_date =  date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d', strtotime('+1 year', strtotime($start_date))))));
        $where = 'start_date > "'.$start_date.'" and end_date < "'.$end_date.'"';
        $confirmleaves = LeaveRequest::where('user_id',$id)->where('leave_type',1)->where('leave_approval',1)->whereraw($where)->get()->toArray();
        $profile_data = Profile::select('annual_holiday')->where('user_id',$id)->first();
        $leve_count = 0;
        if(!empty($confirmleaves) && count($confirmleaves) != 0)
        {
            foreach($confirmleaves as $confirmleave)
            {
                $diff = Carbon::parse( $confirmleave['start_date'] )->diffInDays( $confirmleave['end_date'] ) ;
                $leve_count += $diff + 1;
            }
        }

        $annual_holiday = '';

        if(!empty($profile_data) && !empty($profile_data['annual_holiday']))  {
            $annual_holiday_data = json_decode($profile_data['annual_holiday'],true);
            if(!empty($annual_holiday_data) && !empty($annual_holiday_data['time']))
            {
                $annual_holiday = $annual_holiday_data['time'];
            }
        }

        if($annual_holiday == '')
        {
            $remaing_day = '<sapn class="text-info"> ? '.__('Days').'</span>';
        } else {
            $remaing_day = $annual_holiday - $leve_count;
            if ($remaing_day <= 0)
            {
                $remaing_day = '<sapn class="text-danger">'.$remaing_day.' '.__('Days').'</span>';
            } else {
                $remaing_day = '<sapn class="text-success">'.$remaing_day.' '.__('Days').'</span>';
            }
        }
        return $remaing_day;
    }

    public static function week_day_by_setting($week = 0, $created_by = 0)
    {
        $week = $week * 7;
        $employee_data = Employee::whereRaw('id = '.$created_by.' ')->first();
        $setting = [];
        if(!empty($employee_data->company_setting))
        {
            $setting = json_decode($employee_data->company_setting,true);
        }
        $start_day = (!empty($setting['company_week_start'])) ? $setting['company_week_start'] : 'monday';
        return $week_date = Rotas::getWeekArray('Y-m-d',$week,$start_day);
    }

    public function getHasLeave($id = '',$week = 0)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employee_data = Employee::whereRaw('id = '.$created_by.' ')->first();
        $setting = [];
        if(!empty($employee_data->company_setting))
        {
            $setting = json_decode($employee_data->company_setting,true);
        }
        $start_day = (!empty($setting['company_week_start'])) ? $setting['company_week_start'] : 'monday';
        $week = $week * 7;
        $week_date = Rotas::getWeekArray('Y-m-d',$week, $start_day);

        $table_date = [];
        if(!empty($id))
        {
            $employees = Employee::select('id')->Where('id',$id)->first()->toArray();
            $emp_id = $employees['id'];
            foreach($week_date as $date)
            {
                $employees = LeaveRequest::Where('user_id',$emp_id)
                                        //    ->Where('leave_approval',1)
                                           ->Where('start_date','<=',$date)
                                           ->Where('end_date','>=',$date)
                                           ->get();
                if(count($employees) == 0)
                {
                    $table_date[] = '<span> - </span>';
                } else {
                    if(!empty($employees))
                    {
                        $className = "bg-danger";
                        if($employees[0]['leave_approval'] == 0){
                            $className = "bg-info";
                            $leaveName = "leave approval";
                        }else if($employees[0]['leave_approval'] == 1){
                            $className = "bg-success";
                            $leaveName = "leave approved";
                        }else if($employees[0]['leave_approval'] == 2 || $employees[0]['leave_approval'] == 3){
                            $className = "bg-danger";
                            $leaveName = "leave denide";
                        }

                        $leave_type = $employees[0]['leave_type'];
                        $date_formate = User::CompanyDateFormat('l d M Y');
                        $start_date = date($date_formate, strtotime($employees[0]['start_date']));;
                        $end_date = date($date_formate, strtotime($employees[0]['end_date']));;
                        $ajax_pop = '';
                        if(Auth::user()->acount_type == 1)
                        {
                            $ajax_pop = 'data-size="md" data-ajax-popup="true" data-title="'.__('Leave Request').'" data-url="'.route('holidays.view_leave',$employees[0]['id']).'"';
                        }
                        if($leave_type == 1)
                        {
                            $tooltip = __('Holiday').'   '.$leaveName.'  '.$start_date.' &nbsp; - &nbsp; '.$end_date;
                            $table_date[] = '<div class="badge '.$className.'" title="'.$tooltip.'" '.$ajax_pop.'>Y</div>';
                        }
                        if($leave_type == 2)
                        {
                            $tooltip = __('Other Leave').'   '.$leaveName.'  '.$start_date.' &nbsp; - &nbsp; '.$end_date;
                            $table_date[] = '<div class="badge '.$className.'" title="'.$tooltip.'" '.$ajax_pop.' >Y</div>';
                        }
                    }
                }
            }
        }
        return '<td>'.$table_date[0].'</td><td>'.$table_date[1].'</td><td>'.$table_date[2].'</td><td>'.$table_date[3].'</td><td>'.$table_date[4].'</td><td>'.$table_date[5].'</td><td>'.$table_date[6].'</td>';
    }

    public function getRepeatweekDate($week = 0, $week_no = 0,  $start_day ='',$week_date = '')
    {
        $week2 = $week_no;
        $week1 = $week * 7;
        $date = date("Y-m-d",strtotime($week1." day", strtotime($start_day)));
        if(in_array($date, $week_date))
        {
             return $date;
        }
        elseif ($date < $week_date[0])
        {
            if($week2 == 2) { $week = $week + 2; }
            if($week2 == 3) { $week = $week + 3; }
            if($week2 == 4) { $week = $week + 4; }
            return $this->getRepeatweekDate($week, $week2, $start_day, $week_date);
        }
        elseif ($date > $week_date[0])
        {
            return $date;
        }
        else
        {
            return false;
        }
    }

    public function getWorkSchedule($id = 0, $week = 0, $location_id = 0)
    {
        $week1 = $week;
        $week = $week * 7;
        $week_date = Rotas::getWeekArray('Y-m-d',$week);

        $tr='';
        $rotas_time = '';
        $table_date = [];
        $availabilitie_data = array('','','','','','','');
        $flg = 0;
        $employee_data = '';
        $class1 = ' ';
        $user_profile_img = '';
        $show_avatars_on_rota = 0;

        $user_type = Auth::user()->type;
        $login_userId = Auth::id();
        $user123 = Auth::user();
        $created_by = $user123->get_created_by();

        $emp_setting = Auth::user()->employee_setting;
        $day_off = 'hide';
        $leave_display = 'hide';
        $availability_display = 'hide';
        if(!empty($emp_setting))
        {
            $emp_setting = json_decode($emp_setting,true);
            $day_off = (!empty($emp_setting['day_off']) && $emp_setting['day_off'] == 'show') ? 'show' : 'hide' ;
            $leave_display = (!empty($emp_setting['leave_display']) && $emp_setting['leave_display'] == 'show') ? 'show' : 'hide' ;
            $availability_display = (!empty($emp_setting['availability_display']) && $emp_setting['availability_display'] == 'show') ? 'show' : 'hide' ;
        }

        $login_employee = Employee::Where('id',$login_userId)->first();

        $show_avatars_on_rota = 0;
        $setting_data = Employee::Where('id',$created_by)->OrWhere('created_by',$created_by)->first();

        $break_paid = 'paid';
        $emp_hide_rotas_hour = 0;
        if(!(empty($setting_data->company_setting)))
        {
            $setting = json_decode($setting_data->company_setting,true);
            $show_avatars_on_rota = (!empty($setting['emp_show_avatars_on_rota'])) ? $setting['emp_show_avatars_on_rota'] : 0 ;
            $break_paid = (!empty($setting['break_paid'])) ? $setting['break_paid'] : 'paid' ;
            $emp_hide_rotas_hour = (!empty($setting['emp_hide_rotas_hour'])) ? $setting['emp_hide_rotas_hour'] : 0 ;
        }

        $emp_show_rotas_price = 0;
        if(Auth::user()->type != 'company') {
            $company_setting =User::companystaticSetting();
            $emp_show_rotas_price = ( isset($company_setting['emp_show_rotas_price']) && $company_setting['emp_show_rotas_price'] == 1 ) ? $company_setting['emp_show_rotas_price'] : 0 ;
        } else {
            $emp_show_rotas_price = 1;
        }

        $manage_location1 = [];
        $manage_add_shift = 0;
        if($login_employee->acount_type == 1){ $manage_add_shift = 1; }
        if($login_employee->acount_type == 2 && $login_employee->manager_permission != '')
        {
            $manager_permissionn = $login_employee->manager_permission;
            $manager_permission_arrayy = json_decode($manager_permissionn,true);
            if(!empty($manager_permission_arrayy['manage_loaction']))
            {
                $manage_add_shift = $manager_permission_arrayy['manager_add_edit_delete_rotas'];
                $manage_location1 = explode(',',$manager_permission_arrayy['manage_loaction']);
            }
        }

        if($id != 0)
        {
            $employee = Employee::Where('id',$id)->first();
            $employee_data = Profile::Where('user_id',$id)->first();
            $view_setting = Employee::whereRaw('id = '.$created_by.' ')->first();
            $view_setting_array = [];
            if(!empty($view_setting->company_setting))
            {
                $view_setting_array = json_decode($view_setting->company_setting,true);
            }
            if(!empty($employee_data->profile_pic) && $show_avatars_on_rota == 1)
            {
                // $user_profile_img = '<div class="text-center"><img class="avatar rounded-circle" style="width:30px;" id="blah" src="'. $profile_pic=\App\Models\Utility::get_file(Auth::user()->getUserInfo->DefaultProfilePic()) .'"></div>';
                $user_profile_img = '<div class="text-center"><img class="avatar rounded-circle" style="width:30px;" id="blah" src="'. asset(Storage::url('/uploads/profile_pic/'.$employee_data->profile_pic)) .'"></div>';

            }

            if($login_employee->acount_type == 3 && !empty($employee['id']) && $login_userId != $employee['id'])
            {
                $class1 = 'd-none ';
            }

            if(!empty($view_setting_array['see_note']) && $view_setting_array['see_note'] == 'none' && $login_employee->acount_type == 3)
            {
                $class1 = 'd-none ';
            }
            if(!empty($view_setting_array['see_note']) && $view_setting_array['see_note'] == 'self' && $login_employee->acount_type == 3 && $login_employee->id != $id)
            {
                $class1 = 'd-none ';
            }
            if(!empty($view_setting_array['see_note']) && $view_setting_array['see_note'] == 'all' && $login_employee->acount_type == 3)
            {
                $class1 = '';
            }

            //availabilities
            $availabilities = Availability::Where('user_id',$id)->get()->toArray();
            if(!empty($availabilities))
            {
                foreach($availabilities as $availabilitie)
                {
                    if((!empty($availabilitie['start_date']) && in_array($availabilitie['start_date'], $week_date)) || (!empty($availabilitie['end_date']) && in_array($availabilitie['end_date'], $week_date)))
                    {
                        $flg = 1;
                        $repeat_week = $availabilitie['repeat_week'];
                        $availability_json =  json_decode($availabilitie['availability_json'],true);
                        if(!empty($availability_json))
                        {
                            $availabilitie_data[0] = '';
                            $availabilitie_data[1] = '';
                            $availabilitie_data[2] = '';
                            $availabilitie_data[3] = '';
                            $availabilitie_data[4] = '';
                            $availabilitie_data[5] = '';
                            $availabilitie_data[6] = '';
                            foreach($availability_json as $availability_json_data)
                            {
                                $availability_string1 = '';
                                $availability_string = [];
                                foreach($availability_json_data['periods'] as $periods)
                                {
                                    if($periods['backgroundColor'] == 'rgba(0, 200, 0, 0.5)')
                                    {
                                        $availability_string[] = '<span class="text-success">'.$periods['start'].' - '.$periods['end'].'</span>';
                                        $availability_string1 .= '<span class="text-success">'.$periods['start'].' - '.$periods['end'].'</span><br>';
                                    }
                                    if($periods['backgroundColor'] == 'rgba(200, 0, 0, 0.5)')
                                    {
                                        $availability_string[] = '<span class="text-danger">'.$periods['start'].' - '.$periods['end'].'</span>';
                                        $availability_string1 .= '<span class="text-danger">'.$periods['start'].' - '.$periods['end'].'</span><br>';
                                    }
                                }
                                $week_days = $availability_json_data['day'];
                                $availability_sts = ($availability_display == 'hide') ? 'style="display:none;"' : '';
                                $availabilitie_data[$week_days] = '<div class="availability_table_box" '.$availability_sts.'">'.$availability_string1.'</div>';
                            }
                        }
                    }
                }
            }

            //if no availabilities in week
            if($flg == 0)
            {
                $prev_availabilities = Availability::Where('user_id',$id)->where('repeat_week','!=',0)->orderBy('start_date', 'desc')->first();
                if(!empty($prev_availabilities))
                {
                    $prev_repeat_week = $prev_availabilities['repeat_week'];
                    $prev_availability_json =  json_decode($prev_availabilities['availability_json'],true);
                    if($prev_repeat_week != 0)
                    {
                        $prev_start_date = $prev_availabilities['start_date'];
                        $repet_week = 7 * $prev_repeat_week;
                        $add_week_after_date = date("Y-m-d",strtotime($repet_week." day", strtotime($prev_start_date)));
                        $prev_repeat_week1 = $prev_repeat_week;
                        $response1 = '';
                        if($prev_repeat_week == 1)
                        {  }
                        if($prev_repeat_week == 2)
                        {
                            $response1 = $this->getRepeatweekDate($prev_repeat_week1, $prev_repeat_week1, $prev_start_date, $week_date);
                        }
                        if($prev_repeat_week == 3)
                        {
                            $response1 = $this->getRepeatweekDate($prev_repeat_week1, $prev_repeat_week1, $prev_start_date, $week_date);
                        }
                        if($prev_repeat_week == 4)
                        {
                            $response1 = $this->getRepeatweekDate($prev_repeat_week1, $prev_repeat_week1, $prev_start_date, $week_date);
                        }

                        if((!empty($response1) && in_array($response1, $week_date)) || ($prev_repeat_week == 1))
                        {
                            $repeat_week = $prev_repeat_week;
                            $availability_json =  json_decode($prev_availabilities['availability_json'],true);
                            if(!empty($availability_json))
                            {
                                $availabilitie_data[0] = '';
                                $availabilitie_data[1] = '';
                                $availabilitie_data[2] = '';
                                $availabilitie_data[3] = '';
                                $availabilitie_data[4] = '';
                                $availabilitie_data[5] = '';
                                $availabilitie_data[6] = '';
                                foreach($availability_json as $availability_json_data)
                                {
                                    $availability_string1 = '';
                                    $availability_string = [];
                                    foreach($availability_json_data['periods'] as $periods)
                                    {
                                        if($periods['backgroundColor'] == 'rgba(0, 200, 0, 0.5)')
                                        {
                                            $availability_string[] = '<span class="text-success">'.$periods['start'].' - '.$periods['end'].'</span>';
                                            $availability_string1 .= '<span class="text-danger">'.$periods['start'].' - '.$periods['end'].'</span><br>';
                                        }
                                        if($periods['backgroundColor'] == 'rgba(200, 0, 0, 0.5)')
                                        {
                                            $availability_string[] = '<span class="text-danger">'.$periods['start'].' - '.$periods['end'].'</span>';
                                            $availability_string1 .= '<span class="text-danger">'.$periods['start'].' - '.$periods['end'].'</span><br>';
                                        }
                                    }
                                    $week_days = $availability_json_data['day'];
                                    $availabilitie_data[$week_days] = '<div class="availability_table_box" style="display:none;">'.$availability_string1.'</div>';
                                }
                            }
                        }



                    }
                }
            }


            $rotas_time = array('','','','','','','');
            $time_counter = 0;
            $count_shift = 0;
            $shift_hour = [];
            foreach($week_date as $key => $date)
            {
                // show Rotas(Time Schedule)
                // count weekly hour
                $rotas = Rotas::select('*',DB::raw('TIMEDIFF(end_time,start_time) as time_between'))->Where('location_id',$location_id)->Where('user_id',$id)->Where('rotas_date',$date)->get()->toArray();
                if(!empty($rotas))
                {
                    $rotas_time1 = '';
                    foreach($rotas as $rota)
                    {
                        // show Rotas(Time Schedule)
                        $manage_location = [];
                        $class2 = 0;
                        if($login_employee->acount_type == 2 && $login_employee->manager_permission != '')
                        {
                            $manager_permission = $login_employee->manager_permission;
                            $manager_permission_array = json_decode($manager_permission,true);
                            if(!empty($manager_permission_array['manage_loaction']))
                            {
                                $manage_location = explode(',',$manager_permission_array['manage_loaction']);
                                if(!in_array($rota['location_id'],$manage_location) && !empty($employee['id']) && $login_userId != $employee['id'])
                                {
                                    $class1 = 'd-none ';
                                }

                                $class2 = 0;
                                if(in_array($rota['location_id'],$manage_location))
                                {
                                    $class2 = 1;
                                }
                            }
                        }

                        $border_color = '';
                        $role_name = '';

                        if(!empty($rota['role_id']) && $rota['role_id'] != null)
                        {
                            $rotas = Role::Where('id',$rota['role_id'])->first()->toArray();
                            if(!empty($rotas) && !empty($rotas['color']))
                            {
                                $border_color = $rotas['color'];
                                $role_name = $rotas['name'];
                            }
                        }

                        $update = '';
                        $shift_unavav_request = '';
                        $delete='';
                        $notes = '';
                        if($rota['note'] != "" && $rota['note'] != null){
                            $notes = '<a href="#" class="action-item only_rotas bg-transparent p-0 '.$class1.'" title="'.str_replace('"', "'",$rota['note']).'"><i class="fas fa-comment"></i></a>';
                        }


                        if($login_employee->acount_type == 1 || $class2 ==1)
                        {
                            if(Auth::user()->acount_type == 1)
                            {
                                if($rota['shift_status'] != 'enable')
                                {
                                    $shift_status  = '';
                                    if($rota['shift_status'] == 'request')
                                    {
                                        $shift_status = 'shift_unavelble_reuqest';
                                    }
                                    if($rota['shift_status'] == 'disable')
                                    {
                                        $shift_status = 'shift_unavelble';
                                    }

                                    $shift_unavav_request = '<a href="#" class="action-item only_rotas '.$shift_status.'"  data-size="md" data-ajax-popup="true" data-title="'. __('Unavailability Requested').'" title="'. __('Shift Unavailability Requested').'"
                                                data-url="'. route('rotas.shift.response',['id'=>$rota['id']]).'"><i class="fas fa-ban"></i></a>';
                                    $update = '';
                                }
                                else
                                {
                                    $shift_unavav_request = '';
                                    $update = '<a href="#" class="action-item edit_rotas only_rotas bg-transparent p-0" data-ajax-popup="true" data-title="'.__('Edit Shift').'" data-size="md" data-url="'.route('rotas.edit', $rota['id']).'"  title="'.__('Edit Shift').'">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>';
                                }
                            }
                            $delete ='<span>
                                        <a href="#" title="'.__('Delete').'" class="delete_rotas_action delete_rotas only_rotas bg-transparent p-0 action-item" data-ajax-popup="false" id="'.$rota['id'].'" action_url="'.route('rotas.destroy',$rota['id']).'" >
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form method="POST" action="'.route('rotas.destroy',$rota['id']).'" id="delete-form-'.$rota['id'].'" class="d-none">
                                        <input name="_method" type="hidden" value="DELETE">
                                        <input name="_token" type="hidden" value="'.csrf_token().'">
                                    </form></span>';
                        }

                        // shift unavelble request
                        $cancel_shift = '';
                        if(Auth::user()->acount_type == 3 || Auth::user()->acount_type == 2)
                        {
                            if(Auth::user()->id == $id)
                            {
                                $shift_unavelble_by_emp = '';
                                $title_unavability = '';
                                $shift_unavability_ajax = 'data-size="md" data-ajax-popup="true" data-title="'. __('Shift Cancel').'"  data-url="'. route('rotas.shift.cancel',['id'=>$rota['id']]).'"' ;
                                if($rota['shift_status'] == 'request')
                                {
                                    $shift_unavelble_by_emp = 'shift_unavelble';
                                    $title_unavability =__('Shift unavailable request');
                                    $shift_unavability_ajax = 'data-size="md" data-ajax-popup="true" data-title="'. __('Shift Cancel').'"  data-url="'. route('rotas.shift.cancel',['id'=>$rota['id']]).'"' ;
                                }
                                elseif($rota['shift_status'] == 'disable')
                                {
                                    $shift_unavelble_by_emp = 'shift_unavelble_done';
                                    $title_unavability =__('Shift unavailable request approved');
                                    $shift_unavability_ajax = '';
                                }
                                $cancel_shift = '<a href="#" class="action-item only_rotas '.$shift_unavelble_by_emp.' " title="'. $title_unavability.'" '.$shift_unavability_ajax.'><i class="fas fa-ban" ></i></a>';
                            }


                        }

                        $time_format = (!empty($view_setting_array['company_time_format'])) ? $view_setting_array['company_time_format'] : 12 ;

                        $timee1 = date('h:i a', strtotime($rota['start_time'])).' - '.date('h:i a', strtotime($rota['end_time']));
                        if($time_format == 12)
                        {
                            $timee1 = date('h:i a', strtotime($rota['start_time'])).' - '.date('h:i a', strtotime($rota['end_time']));
                        }
                        if($time_format == 24)
                        {
                            $timee1 = date('H:i', strtotime($rota['start_time'])).' - '.date('H:i', strtotime($rota['end_time']));
                        }

                        $ccc = (!empty($border_color)) ? 'border-color:'.$border_color.';' : '';
                        $publish_shift = '';
                        if($rota['publish'] == 0)
                        { $publish_shift = 'opacity-50'; }

                        $rotas_time1 .= '<div class=" rounded rotas_time rotas_time1 draggable-class '.$publish_shift.'" data-rotas-id="'.$rota['id'].'" style=" '.$ccc.'">
                                            <b class="text-dark">'.$timee1.'</b><br>
                                            <span class="text-secondary"> '.$role_name.' </span>
                                            <div class="float-end d-flex">
                                                '.$notes.'
                                                '.$update.'
                                                '.$delete.'
                                                '.$cancel_shift.'
                                                '.$shift_unavav_request.'
                                            </div>
                                            <sapn class="clearfix"></span>
                                        </div>';

                        $rotas_time[$key] = $rotas_time1;

                        // count weekly hour

                        if($rota['shift_status'] != 'disable')
                        {
                            $time_mimim = $rota['time_diff_in_minut'];
                            if($break_paid != 'paid')
                            {
                                $time_mimim = $rota['time_diff_in_minut'] - $rota['break_time'];
                            }
                            $time_diff = $time_mimim / 60;
                            $time_counter = $time_counter + $time_diff;
                            $count_shift++;
                        }
                        else
                        {
                            $time_diff = 0;
                        }

                        // count weekly hour Shift wise
                        $shift_hour[$rota['role_id']][] = $time_diff;
                    }
                }
            }

            // leave
            if(!empty($employee))
            {
                foreach($week_date as $key => $date)
                {
                    $leave = LeaveRequest::Where('user_id',$id)->Where('leave_approval',1)->Where('start_date','<=',$date)->Where('end_date','>=',$date)->get();

                    $tooltip = '';
                    $badge_class = '';
                    $tooltip2 = '';
                    if(count($leave) == 0)
                    {
                    }
                    else
                    {
                        if(!empty($leave))
                        {
                            $badge_class = '';
                            $leave_type = $leave[0]['leave_type'];
                            $leave_date = date('l d M Y', strtotime($leave[0]['start_date']));
                            $leave_sts = ($leave_display == 'hide') ? 'style="display:none;"' : '';

                            if($leave[0]['start_date'] != $leave[0]['end_date'])
                            {
                                $leave_date = $leave_date .' '. date('l d M Y', strtotime($leave[0]['end_date']));
                            }
                            if($leave_type == 1)
                            {
                                $tooltip = $employee['first_name'].' '.__('has').' '.__('Holiday').' for '.$leave_date;
                                $tooltip2 = '<div class="text-center text-info holiday_leave" '.$leave_sts.' title="'.$tooltip.'">'.__('Holiday Leave').'</div>';
                            }
                            if($leave_type == 2)
                            {
                                $tooltip = $employee['first_name'].' '.__('has').' '.__('other leave').' for '.$leave_date;
                                $tooltip2 = '<div class="text-center text-success other_leave" '.$leave_sts.' title="'.$tooltip.'">'.__('Other Leave').'</div>';
                            }
                        }
                    }
                    $add = '';
                    if($login_employee->acount_type == 1 || in_array($location_id,$manage_location1))
                    {
                        if($manage_add_shift != 0)
                        {
                            $add = '<a href="#" class="action-item bg-primary rounded  add_rotas px-1" data-size="md" data-ajax-popup="true" data-title="'. __('Add New Shift').'" title="'. __('Add New Shift').'" data-url="'. route('rotas.create',['id'=>$id,'date'=>$date]).'"><i class="ti ti-plus text-white bg-transparent"></i></a>';
                        }
                    }

                    $table_date[] = '<td class="'.$badge_class.' min_width-170 droppable-class" data-date="'.$date.'" data-id="'.$id.'">
                                            '.$tooltip2.'
                                            <button type="button" class="add_shift1 availability_table_boxbtn" > <b>'.$availabilitie_data[$key].'</b> </button>
                                            '.$add.'
                                            '.$rotas_time[$key].'
                                        </td>';
                }
            }

            // day off
            $profile_data = Profile::select('work_schedule','user_id','id','custom_day_off')->Where('user_id',$id)->get();
            if(!empty($profile_data[0]['work_schedule']) || $profile_data[0]['work_schedule'] != null)
            {
                $work_schedule = json_decode($profile_data[0]['work_schedule'],true);
                $i = 0;
                foreach($work_schedule as $days)
                {
                    if($days == 'day_off')
                    {
                        $tooltip = __('This is ').''.$employee['first_name'].'  '.__('Day Off');
                        $day_off_sts = ($day_off == 'hide') ? 'style="display:none;"' : '';
                        $tooltip2 = '<div class="text-center text-danger day_off_leave ws_day_off_leave" '.$day_off_sts.' data-date="'.$week_date[$i].'"  title="'.$tooltip.'">'.__('Day Off').'</div>';

                        $add = '';
                        if($login_employee->acount_type == 1 || in_array($location_id,$manage_location1))
                        {
                            if($manage_add_shift != 0)
                            {
                                $add = '<a href="#" class="action-item mr-2 bg-primary rounded  add_rotas px-1"  data-size="md" data-ajax-popup="true" data-title="'. __('Add New Shift').'" title="'. __('Add New Shift').'" data-url="'. route('rotas.create',['id'=>$id,'date'=>$week_date[$i]]).'" ><i class="ti ti-plus text-white bg-transparent"></i></a>';
                            }
                        }

                        $table_date[$i] =   '<td class="'.$badge_class.' min_width-170 droppable-class" data-date="'.$week_date[$i].'" data-id="'.$id.'">
                                                '.$tooltip2.'
                                                <button type="button" class="add_shift1 availability_table_boxbtn" > <b>'.$availabilitie_data[$i].'</b> </button>
                                                '.$add.'
                                                '.$rotas_time[$i].'
                                            </td>';
                    }
                    $i++;
                }
            }

            // custom_day_off
            $custom_day_off = '';
            $ci = 0;
            $profile_data_df = Profile::select('custom_day_off')->Where('user_id',$id)->first();
            foreach($week_date as $key => $date)
            {
                if(!empty(($profile_data_df['custom_day_off'])))
                {
                    $custom_day_off = json_decode($profile_data_df['custom_day_off'], true);
                    if(in_array($date,$custom_day_off))
                    {
                        $tooltip = __('This is ').''.$employee['first_name'].'  '.__('Day Off');
                        $day_off_sts = ($day_off == 'hide') ? 'style="display:none;"' : '';
                        $tooltip2 = '<div class="text-center text-danger day_off_leave cus_day_off_leave" '.$day_off_sts.' data-date="'.$date.'"  title="'.$tooltip.'" data-placement="top" data-html="true" data-toggle="tooltip">'.__('Day Off').'</div>';

                        $add = '';
                        if($login_employee->acount_type == 1 || in_array($location_id,$manage_location1))
                        {
                            if($manage_add_shift != 0)
                            {
                                $add = '<a href="#" class="action-item mr-2 add_rotas"  data-size="md" data-title="'. __('Add New Shift').'" title="'. __('Add New Shift').'" data-url="'. route('rotas.create',['id'=>$id,'date'=>$week_date[$ci]]).'" ><i class="ti ti-plus text-white"></i></a>';
                            }
                        }

                        $table_date[$ci] =   '<td class=" min_width-170 droppable-class" data-date="'.$date.'" data-id="'.$id.'">
                                                '.$tooltip2.'
                                                <button type="button" class="add_shift1 availability_table_boxbtn" > <b>'.$availabilitie_data[$ci].'</b> </button>
                                                '.$add.'
                                                '.$rotas_time[$ci].'
                                            </td>';
                    }
                }
                $ci++;
            }
        }

        $floot_value = 0;
        $hours = 0;
        $minute = 0;
        $working_hour = '';
        if($emp_hide_rotas_hour == 0 || Auth::user()->type == 'company')
        {
            $working_hour = __('0 Hours ');
            if(!empty($time_counter))
            {
                $hours = floor($time_counter);
                $floot_value = $time_counter - $hours;
                $minute = 60 * $floot_value / 1;
                $working_hour = $hours.' '.__('Hours').' '.round($minute).' '.__('Minutes');
            }
            if(!empty($employee_data))
            {
                if($hours > $employee_data['weekly_hour'] && !empty(!empty($employee_data['weekly_hour'])))
                {
                    $tooltip = $employee['first_name'].' '.__('is contracted').' '.$employee_data['weekly_hour'].' '.__('hours per week');
                    $working_hour = '<span class="text-danger" data-toggle="tooltip" title="'.$tooltip.'" data-placement="top" data-html="true">'.$working_hour.'</span>, ';
                }
            }
        }

        //count user role per week
        $rotas_roles = Rotas::select('role_id',DB::raw("COUNT(role_id) as count_role"))->Where('location_id',$location_id)->WhereRaw('user_id='.$id)->WhereRaw('rotas_date BETWEEN "'.$week_date[0].'" and "'.$week_date[6].'"')->groupBy('role_id')->get()->toArray();
        $tooltip_role = '';
        if(!empty($rotas_roles))
        {
            foreach($rotas_roles as $rotas_role)
            {
                if(!empty($rotas_role['role_id']))
                {
                    $count_role = Role::WhereRaw('id = '.$rotas_role['role_id'])->first()->toArray();
                    if(!empty($count_role))
                    {
                        $tooltip_role .= $rotas_role['count_role'].' '.$count_role['name'].'&#013;';
                    }
                }
                else
                {
                    $rotas_roles = Rotas::WhereRaw('user_id='.$id)->WhereRaw('role_id IS NULL')->WhereRaw('rotas_date BETWEEN "'.$week_date[0].'" and "'.$week_date[6].'"')->count();
                    $tooltip_role .= $rotas_roles.' '._('Without Roles').' '.'&#013;';

                }
            }
        }
        $shift_role = '<span title="'.$tooltip_role.'" >'.$count_shift.''.__(' Shifts').'</span>';

        // count _weekly salary
        if($id != 0)
        {
            $employee_data = Profile::Where('user_id',$id)->first()->toArray();

            $default_salarys_array = [];
            if(!empty($employee_data) &&  !empty($employee_data['default_salary']))
            {
                $default_salarys_array = json_decode($employee_data['default_salary'],true);
            }

            $custome_salary_array = [];
            if(!empty($employee_data) &&  !empty($employee_data['custome_salary']))
            {
                $custome_salary_array = json_decode($employee_data['custome_salary'],true);
            }

            $total = 0;
            $role_wise_total1 = [];
            $role_wise_total = 0;
            $salary_tooltip = [];
            $salary_tooltip_shift = [];
            $role_wise_total123 = 0;

            $currency_symbol = (!empty($view_setting_array['company_currency_symbol'])) ? $view_setting_array['company_currency_symbol'] : '$' ;

            foreach($shift_hour as $role_key =>$shift_hours)
            {
                if(!empty($role_key))
                {
                    $role_wise_total = 0;
                    if(!empty($custome_salary_array[$role_key]['custom_salary_by_hour']))
                    {
                        $role_wise_total = $custome_salary_array[$role_key]['custom_salary_by_hour'] * array_sum($shift_hours);
                        $salary_tooltip[$role_key] = round(array_sum($shift_hours)).'  '.$currency_symbol.__(' @').' '.$custome_salary_array[$role_key]['custom_salary_by_hour'].' '.__(' per hour');

                    }
                    if(!empty($custome_salary_array[$role_key]['custom_salary_by_shift']))
                    {
                        if(is_array($shift_hours) && count($shift_hours) > 0)
                        {
                            $role_wise_total += count($shift_hours) * $custome_salary_array[$role_key]['custom_salary_by_shift'];
                        }
                        $salary_tooltip_shift[$role_key] = User::priceFormat(++$role_wise_total123).'  '.__(' @').' '.$custome_salary_array[$role_key]['custom_salary_by_shift'].' '.__(' per shift');

                    }

                    if($role_wise_total == 0)
                    {
                        if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                        {
                            $role_wise_total = array_sum($shift_hours) * $default_salarys_array['salary'];
                            $salary_tooltip[$role_key] = User::priceFormat(array_sum($shift_hours)) .' '.__(' @').' '.$default_salarys_array['salary'].''.__(' per hour');
                        }
                    }
                    $role_wise_total1[$role_key] = $role_wise_total;
                }
                else
                {
                    $role_wise_total = 0;
                    if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                    {
                         $role_wise_total = array_sum($shift_hours) * $default_salarys_array['salary'];
                         $salary_tooltip['no_role'] = User::priceFormat(array_sum($shift_hours)) .'  '.__(' @').' '.$default_salarys_array['salary'].''.__(' per hour');
                    }
                    $role_wise_total1['no_role'] = $role_wise_total;
                }
            }
        }

        $salary_tooltip1 = '';
        if(!empty($salary_tooltip))
        {
            $salary_tooltip1 = __('Hourly cost').'&#013;'.implode('&#013;',$salary_tooltip);
        }

        $salary_tooltip_shift1 = '';
        if(!empty($salary_tooltip_shift))
        {
            $salary_tooltip1 .= '&#013;'.__('Shift cost').'&#013;'.implode('&#013;',$salary_tooltip_shift);
        }

        $role_wise_total2 = 0;

        if(!empty($role_wise_total1) && count($role_wise_total1) != 0 && $emp_show_rotas_price == 1)
        {
            foreach( $role_wise_total1 as $role_wise_total11)
            {
                $role_wise_total2 = $role_wise_total2 + $role_wise_total11;
            }
            $role_wise_total2 = ' <span title="'.$salary_tooltip1.'"  >( ' .User::priceFormat($role_wise_total2).' ) </span>';
        }
        else
        {
            $role_wise_total2 = '';
        }


        $admin_idss = 0;
        if(Auth::user()->type == 'company' || Auth::user()->acount_type == 1 || Auth::user()->acount_type == 2)
        {
            $admin_idss = 1;
        }

        if($id == Auth::User()->id && Auth::user()->acount_type == 3)
        {
            $admin_idss = 1;
        }

        $time_counter = ($admin_idss == 1) ? '<span>'.$working_hour.' '. $role_wise_total2 .' '.$shift_role.'</span>' : '';
        return '<tr class="d-nowne" data-user-id="'.$id.'"><td class="text-center">'.$user_profile_img.'<div><span>'.$employee['first_name'].' '.$employee['last_name'].'</span><br>'.$time_counter.' </div></td>'.$table_date[0].' '.$table_date[1].' '.$table_date[2].' '.$table_date[3].' '.$table_date[4].' '.$table_date[5].' '.$table_date[6].'</tr>';
    }

    public static function getCompanyWeeklyUserSalary($week = 0, $create_by = '', $first_location = 0, $role_id = 0)
    {
        $week = $week * 7;
        $week_date = Rotas::getWeekArray('Y-m-d',$week);

        $tr_hour = array('-','-','-','-','-','-','-');
        $tr_cost = array(0,0,0,0,0,0,0);
        $tr_cost1 = [];
        $working_hour1 = 0;
        $profiles_datas = '';
        $weekly_money = 0;
        $user123 = Auth::user();
        $created_by = $user123->get_created_by();

        $com_setting = User::companystaticSetting();
        $break_paid = (!empty($com_setting['break_paid'])) ? $com_setting['break_paid'] : 'paid';

        $role_where = ' 0 = 0';
        if($role_id != '0')
        {
            $role_where .= ' AND  FIND_IN_SET('.$role_id.',role_id) ';
        }


        if(!empty($week_date))
        {
            foreach($week_date as $week_date_key => $date)
            {
                if(!empty($first_location) && $first_location != 0)
                {
                    $profiles_datas = Profile::select('id','user_id','default_salary','custome_salary')
                                            ->whereRaw('FIND_IN_SET('.$first_location.',location_id)')
                                            ->whereRaw($role_where)->get()->toArray();
                    if(!empty($profiles_datas))
                    {
                        $user = implode(',',array_column($profiles_datas, 'user_id'));
                        $date_rotas = Rotas::select(DB::raw('SUM(time_diff_in_minut) as time_diff_in_minut'), DB::raw('SUM(break_time) as break_time'))->WhereRaw('shift_status != "disable"')->WhereRaw('location_id = '.$first_location.'')->WhereRaw('user_id IN('.$user.')')->WhereRaw('rotas_date = "'.$date.'"')->groupBy('rotas_date')->get()->toArray();
                        $tr_hour[$week_date_key] = '-';
                        if(!empty($date_rotas))
                        {
                            $working_hour1 += $date_rotas[0]['time_diff_in_minut']  - $date_rotas[0]['break_time'];


                            $time = $date_rotas[0]['time_diff_in_minut'];
                            if($break_paid != 'paid')
                            {
                                $time = $time - $date_rotas[0]['break_time'];
                            }
                            $time = $time / 60;
                            $h1 = (int)$time;
                            $m1 = $time - (int)$time;
                            $m2 = 60 * $m1 / 1;
                            $m2 = (!empty($m2)) ? $m2 : 00 ;
                            $total_time =  $h1.''.__(' Hours ').' '.(int)$m2.__(' Minutes');
                            $tr_hour[$week_date_key] = $total_time;

                        }

                        $time_counter = 0;
                        $tr_cost1[$week_date_key]['date'] = $date;
                        $role_hour = [];
                        $hour_cost = 0;

                            $date_role_rotas = Rotas::select('*',DB::raw('TIMEDIFF(end_time,start_time) as time_between1'))->WhereRaw('shift_status != "disable"')->WhereRaw('user_id IN('.$user.')')->WhereRaw('location_id = '.$first_location.'')->WhereRaw('rotas_date = "'.$date.'"')->get()->toArray();

                            if(!empty($date_role_rotas))
                            {
                                foreach($date_role_rotas as $date_role_rota)
                                {
                                    $time_counter = $date_role_rota['time_diff_in_minut'];
                                    if($break_paid != 'paid')
                                    {
                                        $time_counter = $time_counter - $date_role_rota['break_time'];
                                    }
                                    $time_counter = $time_counter / 60;



                                    $salary_data = Profile::select('default_salary','custome_salary')->whereRaw('user_id = '.$date_role_rota['user_id'].'')->first()->toArray();

                                    $default_salarys_array = [];
                                    if(!empty($salary_data['default_salary']))
                                    {
                                        $default_salarys_array = json_decode($salary_data['default_salary'],true);
                                    }

                                    $custome_salary_array = [];
                                    if(!empty($salary_data['custome_salary']))
                                    {
                                        $custome_salary_array = json_decode($salary_data['custome_salary'],true);
                                    }


                                    if(!empty($custome_salary_array) && !empty($date_role_rota['role_id']))
                                    {
                                        if( !empty($custome_salary_array[$date_role_rota['role_id']]) && !empty($custome_salary_array[$date_role_rota['role_id']]['custom_salary_by_hour']))
                                        {
                                            $hour_cost1 = $time_counter * $custome_salary_array[$date_role_rota['role_id']]['custom_salary_by_hour'];
                                            if(!empty($custome_salary_array[$date_role_rota['role_id']]['custom_salary_by_shift']))
                                            {
                                                $hour_cost1 = $hour_cost1 + $custome_salary_array[$date_role_rota['role_id']]['custom_salary_by_shift'];
                                            }
                                            $hour_cost = $hour_cost + $hour_cost1;
                                        }
                                        elseif(!empty($default_salarys_array))
                                        {
                                            if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                                            {
                                                $hour_cost1 = $time_counter * $default_salarys_array['salary'];
                                                $hour_cost = $hour_cost + $hour_cost1;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if(!empty($default_salarys_array))
                                        {
                                            if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                                            {
                                                $hour_cost1 = $time_counter * $default_salarys_array['salary'];
                                                $hour_cost = $hour_cost + $hour_cost1;
                                            }
                                        }
                                    }
                                    $tr_cost[$week_date_key] = round($hour_cost,2);
                                }
                            }
                    }

                }
            }
        }
        $weekly_money = array_sum($tr_cost);
        if($working_hour1 > 0)
        {
            $wtime = $working_hour1 / 60;
            $wh1 = (int)$wtime;
            $wm1 = $wtime - (int)$wtime;
            $wm2 = 60 * $wm1 / 1;
            $wm2 = (!empty($m2)) ? $wm2 : 00 ;
            $working_hour1 =  $wh1.''.__(' Hours ').' '.(int)$wm2.__(' Minutes');
        }

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

        $tr1 = '<tr class="text-center"> <td><span>'.__('').'</span> <span>'.$working_hour1.'</span></td> <td class="min_width-170">'.$tr_hour[0].'</td> <td class="min_width-170">'.$tr_hour[1].'</td> <td class="min_width-170">'.$tr_hour[2].'</td> <td class="min_width-170">'.$tr_hour[3].'</td> <td class="min_width-170">'.$tr_hour[4].'</td> <td class="min_width-170">'.$tr_hour[5].'</td> <td class="min_width-170">'.$tr_hour[6].'</td> </tr>';
        $tr2 = '';
        if($emp_show_rotas_price == 1)
        {
            $tr2 = '<tr class="text-center"> <td><span>'.__('Wages').'</span> <span>'.User::priceFormat($weekly_money).'</span></td> <td class="min_width-170">'.User::priceFormat($tr_cost[0]).'</td> <td class="min_width-170">'.User::priceFormat($tr_cost[1]).'</td> <td class="min_width-170">'.User::priceFormat($tr_cost[2]).'</td> <td class="min_width-170">'.User::priceFormat($tr_cost[3]).'</td> <td class="min_width-170">'.User::priceFormat($tr_cost[4]).'</td> <td class="min_width-170">'.User::priceFormat($tr_cost[5]).'</td> <td class="min_width-170">'.User::priceFormat($tr_cost[6]).'</td> </tr>';
        }

        return $tr1.''.$tr2;
    }
}
