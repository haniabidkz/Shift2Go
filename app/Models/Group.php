<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    protected $fillable = [
        'name',
        'company_id'
    ];

    public function getGroupEmployeeNo() {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $id = $this->id;
        $employee = Profile::whereRaw('FIND_IN_SET('.$id.',group_id)')->count();        
        return $employee.' Employees';        
    }
}

