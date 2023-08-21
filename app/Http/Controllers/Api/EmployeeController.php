<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Attendance;
use App\Models\Breaks;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Location;
use App\Models\Profile;
use App\Models\Report;
use App\Models\Rotas;
use Hash;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Exception;
use Validator;


class EmployeeController extends ApiController
{
    public function companyEmployees()
    {
        $clock ='';
        $date = date("Y-m-d");
        $user = Auth::user();
        if (Auth::user()->type == 'company') {
            

            $employees = Employee::where('created_by', '=', Auth::id())->where([['type', 'employee'],['is_delete',0]])->get();
            foreach ($employees as $employee) {
                $locations = $employee->getLocations($employee->id);
                $employee['location'] = json_encode($locations);
                $role =   $employee->employeeRoles($employee->id);
                $employee['role'] = json_encode($role);
                $wadge = $employee->getwagesalary($employee->id);
                $employee['wadge'] = $wadge;
                $weekly_hours = $employee->getweeklyhours($employee->id);
                $employee['weekly_hours'] = $weekly_hours;
                $profile = Profile::Where('user_id', $employee['id'])->first();

                $profile_pic = Utility::get_file($profile->DefaultProfilePic());
                $employee['profile_pic'] = $profile_pic;
                $attendance = Attendance::where([['user_id',$employee['id']],['date', $date]])->first();
               

                if(isset($attendance))
                {
                    if((isset($attendance['clock_in'])) && $attendance['clock_out'] == '00:00:00'){
                        $employee['check_in_status'] = true ;
                    }
                    else if(isset($attendance['clock_out']) ){
                        $employee['check_in_status'] =false;
                    }
                   
                }
                else{
                    $employee['check_in_status'] =  false;

                }

                $breaks = Breaks::where([['employee_id', $employee['id']],['date', $date]])->first();


                if(isset($breaks))
                {
                    if((isset($breaks['break_in'])) && $breaks['break_out'] == NULL){
                        $employee['break_status'] = true ;
                    }
                    else if(isset($breaks['break_out']) ){
                        $employee['break_status'] = false;
                    }
                }
                else{
                    $employee['break_status'] = false;

                }
            }
            return response()->json(['message' => "Employee list fetched succcessfully", 'data' => $employees, 'error' => false], 200);

            // return view('employee.index',compact('employees','roles_select','box'));
        }



        //  $user = Auth::user();
        if (Auth::user()->type == 'employee') {

            $company = User::where('id', '=', Auth::id())->where('type', '=', 'employee')->first();


            $employees = Employee::where([['created_by', $company->created_by],['is_delete',0]])->get();

          
            foreach ($employees as $employee) {
                $locations = $employee->getLocations($employee->id);
                $employee['location'] = json_encode($locations);
                $role =   $employee->employeeRoles($employee->id);
                $employee['role'] = json_encode($role);
                $wadge = $employee->getwagesalary($employee->id);
                $employee['wadge'] = $wadge;
                $weekly_hours = $employee->getweeklyhours($employee->id);
                $employee['weekly_hours'] = $weekly_hours;
                $profile = Profile::Where('user_id', $employee['id'])->first();

                $profile_pic = Utility::get_file($profile->DefaultProfilePic());
                $employee['profile_pic'] = $profile_pic;

                $attendance = Attendance::where([['user_id',$employee['id']],['date', $date]])->latest()->first();
               

                if(isset($attendance))
                {
                    if((isset($attendance['clock_in'])) && $attendance['clock_out'] == '00:00:00'){
                        $employee['check_in_status'] =true ;
                    }
                    else if(isset($attendance['clock_out']) ){
                        $employee['check_in_status'] =false;
                    }
                   
                }
                else{
                    $employee['check_in_status'] =  false;

                }

                $breaks = Breaks::where([['employee_id', $employee['id']],['date', $date]])->first();


                if(isset($breaks))
                {
                    if((isset($breaks['break_in'])) && $breaks['break_out'] == NULL){
                        $employee['break_status'] = true;
                    }
                    else if(isset($breaks['break_out']) ){
                        $employee['break_status'] = false;
                    }
                }
                else{
                    $employee['break_status'] =  false;

                }
            }


            return response()->json(['message' => "Employee list fetched succcessfully", 'data' => $employees, 'error' => false], 200);
        }
    }



    public function verifyEmployee(Request $request)
    {
        try {
            if (!auth()->user()) {
                return response()->json(['message' => 'Unauthenticated', 'error' => true], 200);
            }
            $validator = Validator::make($request->all(), [
                'employee_id'        => 'required',
                'pin'        => 'required|digits:4'


            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $employee  = User::where('id', $request->employee_id)->first();
            if ($employee->pin != $request->pin) {

                return response()->json(['message' => "Sorry!! Pin not matched", 'error' => false], 200);
            } else if ($employee->pin == 0) {
                return response()->json(['message' => "Employee set pin first", 'error' => true], 200);
            }
            return response()->json(['message' => "Pin verified successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }


    public function employeeStatus(Request $request)
    {
        try {

            $date = date("Y-m-d");

            if (!auth()->user()) {
                return response()->json(['message' => 'Unauthenticated', 'error' => true], 200);
            }
            $validator = Validator::make($request->all(), [
                'employee_id'        => 'required',



            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $employee  = Employee::where('id', $request->employee_id)->first();
           
            if(!$employee){
           
                return response()->json(['message' => "Employee not find", 'error' => true], 200);
            }
            $attendance = Attendance::where([['user_id',$employee->id],['date', $date]])->latest()->first();
            // dd($attendance);
           
            $checkIn_status = false; // Initialize with a default value
                $break_status=false;

                if($attendance)
                {
                    if((isset($attendance->clock_in)) && $attendance->clock_out == '00:00:00'){
                       $checkIn_status = true ;
                    }
                    else if(isset($attendance->clock_out) ){
                        $checkIn_status  = false;
                    }
                    else{
                        $checkIn_status =  false;

                    }
                }

                $breaks = Breaks::where([['employee_id', $employee->id],['date', $date]])->first();


                if($breaks)
                {
                    if((isset($breaks->break_in)) && $breaks->break_out == NULL){
                        $break_status = true ;
                    }
                    else if(isset($breaks->break_out) ){
                        $break_status = false;
                    }
                }

                return response()->json(['message' => 'Employee status', 'checkIn_status' => $checkIn_status, 'break_status' => $break_status,  'error' => false], 200);


        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
}
