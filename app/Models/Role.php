<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Role extends Model
{
    protected $table = 'employee_roles';

    protected $fillable = [
        'name',
        'color',
        'default_break',
        'created_by',
        'employees'
    ];

    public function getDefaultBreack() {
        $default_break = $this->default_break;
        return (!empty($default_break)) ? $default_break : '0';
    }

    public function hasshift() {
        $data = Rotas::where('role_id',$this->id)->exists();        
        return $data;
    }

    public function getCountEmployees() {
        $id = $this->id;
        $role_employee_count = Profile::whereRaw('FIND_IN_SET('.$id.',role_id)')->get()->count();
        return $role_employee_count.' '.__('Employees');
    }
}
