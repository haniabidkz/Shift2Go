<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomMeeting extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'meeting_id',
        'user_id',
        'password',
        'start_date',
        'duration',
        'start_url',
        'join_url',
        'status',
        'created_by'
    ];

    public function getUserInfo()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
    public function checkDateTime()
    {
        $m = $this;
        if (\Carbon\Carbon::parse($m->start_date)->addMinutes($m->duration)->gt(\Carbon\Carbon::now())) {
            return 1;
        }else{
            return 0;
        }

    }
}
