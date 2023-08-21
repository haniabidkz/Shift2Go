<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSheet extends Model
{
    protected $fillable = [
        'user_id',
        'location_id',
        'date',
        'hours',
        'remark',
    ];

    public function employee()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }
}
