<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Exports\LocationExport;
use App\Models\Location;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LocationController extends Controller
{
    public function index()
    {
        if(Auth::user()->acount_type == 1 || Auth::user()->acount_type == 2)
        {
            $created_by = Auth::user()->get_created_by();
            $locations = Location::where('created_by', $created_by)->get();
            $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();

            $employees_select = [];
            foreach($employees as $employee){
                $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
            }
            return view('location.index',compact('locations','employees_select'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        $employees_select = [];
        foreach($employees as $employee){
            $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }
        return view('location.create',compact('employees_select'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $location = new Location();
        $location->name         = $request->input('name');
        $location->address      = $request->input('address');
        $location->created_by   = $created_by;
        $location->save();

        $insert_id = $location->id;
        $employees = $request->employees;

        if(!empty($employees)) {
            foreach($employees as $employee_id) {
                $profile = Profile::where('user_id', $employee_id)->first();
                if(!empty($profile->user_id)) {
                    if(!empty($profile->location_id)) {
                        $set_location_id_string ='';
                        $set_location_id = [];
                        $set_location_id = explode(',',$profile->location_id);
                        $set_location_id[] = $insert_id;
                        $set_location_id_string = implode(',',$set_location_id);
                        $profile->location_id = $set_location_id_string;
                    } else {
                        $profile->location_id = $insert_id;
                    }
                    $profile->save();
                }
            }
        }
        return redirect()->back()->with('success', __('Location Add Successfully'));
    }

    public function show(location $location)
    {
        //
    }

    public function edit(location $location)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $employees = Employee::where('is_delete', '0')->where('created_by', $created_by)->orWhere('id', $created_by)->get();
        $employees_select = [];
        foreach($employees as $employee){
            $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }

        $location_employees_select = [];
        $location_employees = Profile::select('id','user_id')->whereRaw('FIND_IN_SET('.$location->id.',location_id)')->get();
        foreach($location_employees as $location_employee){
            $location_employees_select[] = $location_employee->user_id;
        }
        $location->employees = $location_employees_select;
        return view('location.edit',compact('location','employees_select'));
    }

    public function update(Request $request, location $location)
    {
        $old_location_detail = Profile::select('id','user_id','location_id')->whereRaw('FIND_IN_SET('.$location->id.',location_id)')->get()->toArray();

        $user = Auth::user();
        $created_by = $user->get_created_by();

        $employee = $request->employees;
        $location['name']         = $request->input('name');
        $location['address']      = $request->input('address');
        $location->save();


        $location_id = $location->id;
        $new_employees = $request->employees;
        $old_employees = [];
        if(!empty($old_location_detail))
        {
            $old_employees = array_column($old_location_detail,'user_id');
        }


        $add_locations = [];
        $remove_locations = [];
        if(!empty($new_employees)) {
            $add_locations = array_diff($new_employees,$old_employees);
            $remove_locations = array_diff($old_employees,$new_employees);
        } else {
            $remove_locations = $old_employees;
        }

        if(!empty($add_locations)) {
            foreach($add_locations as $add_location) {
                $profile = Profile::select('id','user_id','location_id')->where('user_id',$add_location)->get()->first();
                if(!empty($profile->location_id))
                {
                    $set_location_id_string ='';
                    $set_location_id = [];
                    $set_location_id = explode(',',$profile->location_id);
                    $set_location_id[] = $location_id;
                    $set_location_id_string = implode(',',$set_location_id);
                    $profile->location_id = $set_location_id_string;
                } else {
                    $profile->location_id = $location_id;
                }
                $profile->save();
            }
        }

        if(!empty($remove_locations)) {
            foreach($remove_locations as $remove_location) {
                $profile = Profile::select('id','user_id','location_id')->where('user_id',$remove_location)->get()->first();
                if(!empty($profile->location_id))
                {
                    $set_location_id_string ='';
                    $set_location_id = [];
                    $set_location_id = explode(',',$profile->location_id);

                    $set_location_id_string = array_diff($set_location_id,array($location_id));
                    if(!empty($set_location_id_string)) {
                        $profile->location_id = implode(',',$set_location_id_string);
                    } else {
                        $profile->location_id = NULL;
                    }
                } else {
                    $profile->location_id = NULL;
                }
                $profile->save();
            }
        }
        return redirect()->back()->with('success', __('Location Update Successfully'));
    }

    public function destroy(location $location)
    {
        $location->delete();
        return redirect()->back()->with('success', __('Delete Succsefully'));
    }

    public function export()
    {
        $name = 'Location' . date('Y-m-d i:h:s');
        $data = Excel::download(new LocationExport(), $name . '.xlsx'); ob_end_clean();

        return $data;
    }
}
