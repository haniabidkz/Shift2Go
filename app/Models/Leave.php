<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    //
    protected $table = 'leave_requests';

    protected $fillable =[
        'user_id', 'issue_by', 'leave_type', 'start_date', 'end_date', 'message', 'leave_approval', 'approved_by', 'approved_date', 'paid_status', 'response_message', 'leave_time', 'created_by'
    ];

    public function getUserleave($id = 0) {
        $profile_data = Profile::where('user_id',$id)->get()->count();
        return $profile_data;        
    }

    public static function username($username)
    {
        $categoryArr  = explode(',', $username);
        $unitRate = 0;
        foreach($categoryArr as $username)
        {
            $username     = Employee::find($username);
            $unitRate = $username->first_name;
        }

        return $unitRate;
    }
}
