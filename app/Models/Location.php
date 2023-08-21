<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Location extends Model
{
    //
    protected $fillable = [
        'name',
        'address',
        'employees',
        'created_by'
    ];

    public function getCountEmployees() {
        $id = $this->id;
        $role_employee_count = Profile::whereRaw('FIND_IN_SET('.$id.',location_id)')->get()->count();
        return $role_employee_count.' Employees';
    }
    
    public function countmanager($id =0)
    {
        $create_by = Auth::User()->id;
        $managers = User::where("acount_type", 2)->where("manager_permission",'!=', '')->get()->toArray();                

        $count = __('0 Manager');
        if(!empty($managers))
        {
            $counter = 0;
            foreach ($managers as $key => $manager)
            {
                $manager_array = json_decode($manager['manager_permission'], true);
                if(!empty($manager_array['manage_loaction']))
                {
                    $loaction_array = explode(',', $manager_array['manage_loaction']);                    
                    if( !empty($loaction_array)  &&  in_array($id,$loaction_array) )
                    {
                        $counter++;
                    }                    
                }
            }

            $count = $counter.__(' Managers');;
        }
        else
        {
            $count = __('No Manager');
        }
        return $count;
    }
}
