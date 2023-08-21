<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pastemployees extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'gender',
        'date_of_birth',
        'address',
        'city',
        'county',
        'postcode',
        'phone',
        'location',
        'default_role_id',
        'role_id',
        'group_id',
        'weekly_hour',
        'annual_holiday',
        'start_date',
        'final_working_date',
        'note',
        'is_delete',
        'deleted_at',
        'create_by'
    ];
}
