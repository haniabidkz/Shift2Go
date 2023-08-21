<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlip extends Model
{
    protected $fillable = [
        'user_id',
        'role_id',
        'time_diff_in_minut',
        'net_payble',
        'salary_month',
        'status',
        'created_by',
    ];

    public function employees()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'user_id');
    }
}
