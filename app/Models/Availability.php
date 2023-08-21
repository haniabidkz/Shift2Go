<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    //
    protected $fillable = [ 'user_id', 'name', 'start_date', 'end_date', 'repeat_week', 'availability_json', 'created_by' ];

    public function getUserInfo()
    {
        return $this->hasOne('App\Models\Employee','id','user_id');
    }


    public function getAvailabilityDate()
    {
        $start_date = '';
        $end_date = '';
        $date = '';
        $date_formate = User::CompanyDateFormat(' d M Y ');
        if(!empty($this->start_date)) {
            $start_date = date ($date_formate , strtotime($this->start_date) );
            $date = $start_date;
        }
        if(!empty($this->end_date)) {
            $end_date = date ($date_formate , strtotime($this->end_date) );
            $date .= ' - ' .$end_date.' ';
        }

        if(!empty($this->repeat_week)) {
            if($this->repeat_week == 1) {
                $date .= ''.__(' ( Repeating every week ) ').'';
            } else {
                $date .= '('.__(' Repeating every ').$this->repeat_week.' '.__('week').')';
            }
        }
        return $date;
    }
}
