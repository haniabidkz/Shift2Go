<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Report extends Model
{
    public static function rotas_chart($rotas = [])
    {
        $com_setting = User::companystaticSetting();
        $break_paid = (!empty($com_setting['break_paid'])) ? $com_setting['break_paid'] : 'paid';

        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $hour = [];
        $cost = [];
        $user_salary = [];
        $user_salary1 = [];
        $hour_cost = 0;
        $time_counter = 0;
        if(!empty($rotas))
        {
            foreach($rotas as $rota)
            {
                $time_diff_bre = $rota['time_diff_in_minut'];
                $rr_break_time = (!empty($rota['break_time'])) ? $rota['break_time'] : 0;
                if($break_paid != 'paid')
                {
                    $time_diff_bre = $rota['time_diff_in_minut'] - $rr_break_time;
                }
                $time_diff = $time_diff_bre /60;
                $time_counter = $time_counter + $time_diff;


                if(!in_array($rota['user_id'], $user_salary))
                {
                    $user_salary[] = $rota['user_id'];
                    $user_salary1[$rota['user_id']]['default_salary']='';
                    $user_salary1[$rota['user_id']]['custome_salary']='';
                    $prifile = Profile::WhereRaw('user_id = '.$rota['user_id'].'')->first();
                    if(!empty($prifile))
                    {
                        $user_salary1[$rota['user_id']]['default_salary'] = $prifile->default_salary;
                        $user_salary1[$rota['user_id']]['custome_salary'] = $prifile->custome_salary;
                    }
                }

                $default_salarys_array = [];
                if(!empty($user_salary1[$rota['user_id']]['default_salary']))
                {
                    $default_salarys_array = json_decode($user_salary1[$rota['user_id']]['default_salary'],true);
                }

                $custome_salary_array = [];
                if(!empty($user_salary1[$rota['user_id']]['custome_salary']))
                {
                    $custome_salary_array = json_decode($user_salary1[$rota['user_id']]['custome_salary'],true);
                }

                if(!empty($custome_salary_array) && !empty($rota['role_id']))
                {
                    if(
                        !empty($custome_salary_array[$rota['role_id']]) &&
                        !empty($custome_salary_array[$rota['role_id']]['custom_salary_by_hour'])
                        )
                    {
                        $hour_cost1 = $time_diff * $custome_salary_array[$rota['role_id']]['custom_salary_by_hour'];
                        if(!empty($custome_salary_array[$rota['role_id']]['custom_salary_by_shift']))
                        {
                            $hour_cost1 = $hour_cost1 + $custome_salary_array[$rota['role_id']]['custom_salary_by_shift'];
                        }
                        $hour_cost = $hour_cost + $hour_cost1;
                    }
                    elseif(!empty($default_salarys_array))
                    {
                        if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                        {
                            $hour_cost1 = $time_diff * $default_salarys_array['salary'];
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
                            $hour_cost1 = $time_diff * $default_salarys_array['salary'];
                            $hour_cost = $hour_cost + $hour_cost1;
                        }
                    }
                }
            }
        }
        $array['time_counter'] =  $time_counter;
        $array['hour_cost'] =  $hour_cost;
        return json_encode($array);
    }

    public static function rota_chart($rota = [])
    {
        $com_setting = User::companystaticSetting();
        $break_paid = (!empty($com_setting['break_paid'])) ? $com_setting['break_paid'] : 'paid';

        $userId = Auth::id();
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $hour = [];
        $cost = [];
        $user_salary = [];
        $user_salary1 = [];
        $hour_cost = 0;
        $time_counter = 0;
        if(!empty($rota))
        {
            $time_diff_bre = $rota['time_diff_in_minut'];
            $rr_break_time = (!empty($rota['break_time'])) ? $rota['break_time'] : 0;
            if($break_paid != 'paid')
            {
                $time_diff_bre = $rota['time_diff_in_minut'] - $rr_break_time;
            }
            $time_diff =  $time_diff_bre /60;
            $time_counter = $time_counter + $time_diff;

            if(!in_array($rota['user_id'], $user_salary))
            {
                $user_salary[] = $rota['user_id'];
                $user_salary1[$rota['user_id']]['default_salary']='';
                $user_salary1[$rota['user_id']]['custome_salary']='';
                $prifile = Profile::WhereRaw('user_id = '.$rota['user_id'].'')->first();
                if(!empty($prifile))
                {
                    $user_salary1[$rota['user_id']]['default_salary'] = $prifile->default_salary;
                    $user_salary1[$rota['user_id']]['custome_salary'] = $prifile->custome_salary;
                }
            }

            $default_salarys_array = [];
            if(!empty($user_salary1[$rota['user_id']]['default_salary']))
            {
                $default_salarys_array = json_decode($user_salary1[$rota['user_id']]['default_salary'],true);
            }

            $custome_salary_array = [];
            if(!empty($user_salary1[$rota['user_id']]['custome_salary']))
            {
                $custome_salary_array = json_decode($user_salary1[$rota['user_id']]['custome_salary'],true);
            }

            if(!empty($custome_salary_array) && !empty($rota['role_id']))
            {
                if(
                    !empty($custome_salary_array[$rota['role_id']]) &&
                    !empty($custome_salary_array[$rota['role_id']]['custom_salary_by_hour'])
                    )
                {
                    $hour_cost1 = $time_diff * $custome_salary_array[$rota['role_id']]['custom_salary_by_hour'];
                    if(!empty($custome_salary_array[$rota['role_id']]['custom_salary_by_shift']))
                    {
                        $hour_cost1 = $hour_cost1 + $custome_salary_array[$rota['role_id']]['custom_salary_by_shift'];
                    }
                    $hour_cost = $hour_cost + $hour_cost1;
                }
                elseif(!empty($default_salarys_array))
                {
                    if(!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly')
                    {
                        $hour_cost1 = $time_diff * $default_salarys_array['salary'];
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
                        $hour_cost1 = $time_diff * $default_salarys_array['salary'];
                        $hour_cost = $hour_cost + $hour_cost1;
                    }
                }
            }
        }
        $array['time_counter'] =  $time_counter;
        $array['hour_cost'] =  $hour_cost;
        return $array;
    }
}
