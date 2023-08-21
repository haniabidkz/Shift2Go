<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use phpDocumentor\Reflection\Types\Null_;

class Profile extends Model
{
    //
    protected $table = 'profiles';

    protected $fillable = [
        'user_id', 'gender', 'date_of_birth', 'address', 'profile_pic', 'city', 'county', 'postcode', 'phone', 'emergency_contact_name', 'relationship_to_employee', 'emergency_contact_no', 'default_role_id', 'group_id', 'weekly_hour', 'annual_holiday', 'employee_type', 'start_date', 'final_working_date', 'note', 'location_id', 'role_id', 'default_salary', 'custome_salary', 'work_schedule'
    ];

    public function getUserName()
    {
        return $this->hasOne('App\Models\Employee','id','user_id');
    }

    public function getUserData($id = 0)
    {
        $employee_data = Employee::where('id',$id)->first();
        return $employee_data;
    }

    public static function getUserprofileData($id = 0)
    {
        $employee_data = Profile::where('id',$id)->first();
        return $employee_data;
    }

    public function getEmailVerify()
    {
        $password = $this->getUserName->password;
        $disable = false;
        if(!empty($password)) {
            $disable = true;
        }
        return $disable;
    }

    public function getAnnualHolidayTime()
    {
        $annual_holiday = $this->annual_holiday;
        $annual_holiday_time = NULL;
        if(!empty($annual_holiday))
        {
            $annual_holiday_array = json_decode($annual_holiday,true);
            $annual_holiday_time = $annual_holiday_array['time'];
        }
        return $annual_holiday_time;

    }

    public function getAnnualHolidayTimeType()
    {
        $annual_holiday = $this->annual_holiday;
        $annual_holiday_time_type = NULL;
        if(!empty($annual_holiday))
        {
            $annual_holiday_array = json_decode($annual_holiday,true);
            $annual_holiday_time_type = $annual_holiday_array['type'];
        }
        return $annual_holiday_time_type;
    }

    public function getSalaryData()
    {
        $salary = $this->salary;
        $sallry_array = NULL;
        if(!empty($salary))
        {
            $sallry_array = json_decode($salary,true);
            if(!empty($sallry_array)) {
                unset($sallry_array['default_salary']);
                unset($sallry_array['default_salary_per']);
            }
        }
        return $sallry_array;
    }

    public function getSalaryDatta()
    {
        $salary = $this->custome_salary;
        $sallry_array = NULL;
        if(!empty($salary))
        {
            $sallry_array = json_decode($salary,true);
        }
        return $sallry_array;
    }

    public function CustomDefaultSalary()
    {
        $default_salary = $this->default_salary;
        $sallry_array = NULL;
        if(!empty($default_salary))
        {
            $sallry_array = json_decode($default_salary,true);
        }
        return $sallry_array;
    }

    public function CustomSalary()
    {
        $custome_salary = $this->custome_salary;
        $sallry_array = NULL;
        if(!empty($custome_salary))
        {
            $sallry_array = json_decode($default_salary,true);
        }
        return $sallry_array;
    }

    public function CustomRoleName($key = 0)
    {
        if($key == 0) {
            $role_name = NULL;
        } else {
            $role_row = Role::select('name')->where('id',$key)->first();
            if(!empty($role_row->name)) {
                $role_name = $role_row->name;
            }
        }
        return $role_name;
    }

    public function WorkSchedule()
    {
        $work_schedule = $this->work_schedule;
        $work_schedule_array = NULL;
        if(!empty($work_schedule)) {
            $work_schedule_array = json_decode($work_schedule,true);
        }
        return $work_schedule_array;
    }

    public function DefaultProfilePic()
    {
        $profile_pic = $this->profile_pic;
        $default_profile_pic = 'uploads/profile_pic/avatar.png';
        if(!empty($profile_pic)) {
            $default_profile_pic = 'uploads/profile_pic/'.$profile_pic;
        }
        return $default_profile_pic;
    }

    public function present_status($employee_id, $data)
    {
        return Attendance::where('user_id', $employee_id)->where('date', $data)->first();
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
                    $location_data[] = $location['name'];
                }
            }
            if(!empty($location_data))
            {
                $location_data1 = implode(', ',$location_data);
            }
        }
        return $location_data1;
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
}
