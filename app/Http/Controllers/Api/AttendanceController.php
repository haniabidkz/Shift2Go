<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Breaks;
use App\Models\Employee;
use App\Models\IpRestrict;
use App\Models\Location;
use App\Models\Profile;
use App\Models\Rotas;
use App\Models\Utility;
use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Exception;
use Validator;
use App\Traits\CommonTrait;
use Carbon\Carbon;

class AttendanceController extends ApiController
{
    use CommonTrait;


    function calculateDistance($lat1, $lon1, $lat2, $lon2, $range = null)
    {
        $earthRadius = 6371; // Radius of the earth in kilometers

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDiff / 2) * sin($lonDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;
    //    dd($distance);
        if ($range !== null) {
            return $distance <= $range;
        }
      
    }


    public function clockIn(Request $request)
    {
        $clock_in = true;
        $validator = Validator::make($request->all(), [
            'employee_id'        => 'required',
            'clock'        => 'required',
            'location_id' => 'required',
            'role_id' => 'required'

        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->errorResponse($error, 200);
        }

        // $locations1 = [];


        $employe = Employee::where([['id', $request->employee_id], ['created_by', Auth::id()]])->first();

        if (!$employe) {
            return response()->json(['message' => "Not Allowed!! Unuthorized Access.", 'error' => false], 200);
        }

        $profile = Profile::where([['user_id', $request->employee_id], ['location_id', 'LIKE', "%{$request->location_id}%"]])->first();

        if (!$profile) {
            return response()->json(['message' => "Not Allowed!! Unuthorized Location.", 'error' => false], 200);
        }

        if ($profile) {
            $location = Location::where('id', $request->location_id)->first();


            if ($location->images_required ==  1) {

                if ($request->image ==  '') {
                    return response()->json(['message' => "Image is required.", 'error' => false], 200);
                }
            }


            if ($location->blockgeoFencing == 1) {
           

                $latitude1 = $request->lat;
                $longitude1 = $request->long;

                $latitude2 = $location->latitude;

                $longitude2 = $location->longitude;
                $range = $location->blockgeoFencingRadius;
                
                $rangeInKilometers = $range/1000; // The maximum range in kilometers
              

                $withinRange = $this->calculateDistance($latitude1, $longitude1, $latitude2, $longitude2, $rangeInKilometers);
                
                if ($withinRange == 0 ) {
                    // dd("The points are outside the specified range.");
                    return response()->json(['message' => "The points are outside the specified range.", 'error' => false], 200);

                } 
            }
        }

        $role = Profile::where([['user_id', $request->employee_id], ['role_id', 'LIKE', "%{$request->role_id}%"]])->first();

        if (!$role) {
            return response()->json(['message' => "Not Allowed!! Unuthorized Role.", 'error' => false], 200);
        }


        $settings = Utility::settings();

        $user = User::where('id', $request->employee_id)->first();

        if ($user->pin != null || $user->pin != ' ') {

            $todayAttendance = Attendance::where([['user_id', $request->employee_id], ['date', date('Y-m-d')]])->first();
            $location = Location::where('id', $request->location_id)->first();



            if (empty($todayAttendance)) {


                $startTime = Utility::getValByName('company_start_time');

                $endTime   = Utility::getValByName('company_end_time');
                $attendance = Attendance::orderBy('id', 'desc')->where([['user_id', $request->employee_id], ['date', date('Y-m-d')], ['clock_out', '00:00:00']])->first();

                if ($attendance != null) {
                    $attendance = Attendance::find($attendance->id);
                    $attendance->clock_out = $endTime;
                    $attendance->save();
                }
                $date = date("Y-m-d");
                $time = date("H:i:s");


                if ($request->clock == true) {

                    if ($startTime >= $time) {
                        $totalOvertimeSeconds = strtotime($startTime) -  time();
                        $hours                = floor($totalOvertimeSeconds / 3600);
                        $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                        $secs                 = floor($totalOvertimeSeconds % 60);
                        $restime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        $late = '00:00:00';
                        // dd($restime);
                    } else {
                        $totalLateSeconds = time() - strtotime($date . $startTime);
                        $hours            = floor($totalLateSeconds / 3600);
                        $mins             = floor($totalLateSeconds / 60 % 60);
                        $secs             = floor($totalLateSeconds % 60);
                        $late             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        $restime = '00:00:00';
                    }

                    $checkDb = Attendance::where([['user_id', $request->employee_id], ['date', $date]])->get()->toArray();

                    if (empty($checkDb)) {
                        $employeeAttendance                = new Attendance();
                        $employeeAttendance->user_id       = $request->employee_id;
                        $employeeAttendance->date          = $date;
                        $employeeAttendance->status        = 'Present';
                        $employeeAttendance->clock_in      = $time;
                        $employeeAttendance->clock_out     = '00:00:00';
                        $employeeAttendance->late          = $late;
                        $employeeAttendance->early_leaving = '00:00:00';
                        $employeeAttendance->overtime      = '00:00:00';
                        $employeeAttendance->total_rest    = $restime;
                        $employeeAttendance->created_by    = \Auth::user()->id;
                        $employeeAttendance->latitude      = $request->lat;
                        $employeeAttendance->longitude    = $request->long;
                        $employeeAttendance->location_id   = $request->location_id;
                        $employeeAttendance->role_id      = $request->role_id;
                        $employeeAttendance->role_id      = $request->role_id;
                        // $rotas = Rotas::where('user_id', $request->employee_id)->first();

                        // $minutes = $rotas->time_diff_in_minut ?: 0;
                        // $hours = floor($minutes / 60);
                        // $min = $minutes - ($hours * 60);
                        // $secs  = floor($min % 60);

                        // $company_time = sprintf('%02d:%02d:%02d', $hours, $min, $secs);



                        // if (isset($todayAttendance->total_break_time)) {
                        //     $break_time = $todayAttendance->total_break_time;


                        //     if ($break_time != null) {
                        //         $start = Carbon::parse($company_time);
                        //         $end = Carbon::parse($break_time);

                        //         $mins = $end->diffInMinutes($start);
                        //         $hours = floor($minutes / 60);
                        //         $min = $minutes - ($hours * 60);
                        //         $secs  = floor($min % 60);

                        //         $total_time = sprintf('%02d:%02d:%02d', $hours, $min, $secs);
                        //         $employeeAttendance->total_time   = $total_time;
                        //     } else {
                        //         $employeeAttendance->total_time   = $company_time;
                        //     }
                        // }

                        if ($location->images_required == 1) {

                            if ($request->hasFile('image')) {
                                if ($request->file('image')->isValid()) {
                                    $this->validate($request, [
                                        'image' => 'required|mimes:jpeg,png,jpg'
                                    ]);
                                    $file = $request->file('image');
                                    $destinationPath = public_path('/uploads');
                                    //$extension = $file->getClientOriginalExtension('logo');
                                    $thumbnail = $file->getClientOriginalName('image');
                                    $thumbnail = rand() . $thumbnail;
                                    $request->file('image')->move($destinationPath, $thumbnail);
                                    $employeeAttendance->selfie = $thumbnail;
                                }
                            }


                            // if ($request->hasFile('image')) {

                            //     $profile_image = $this->uploadImage($request->file('image'), '/public/images/');

                            //     $employeeAttendance->selfie = $profile_image;
                            // }
                        }

                        $employeeAttendance->save();
                        return response()->json(['message' => "Employee Successfully Clock In.", 'employee' => $employeeAttendance, 'clock_in' => $clock_in, 'error' => false], 200);
                    } else {
                        return response()->json(['message' => "Employee Already Clock In.", 'error' => true], 200);
                    }
                }
            } else if ($location->multiple_checks == 1) {


                $startTime = Utility::getValByName('company_start_time');
                $endTime   = Utility::getValByName('company_end_time');
                $attendance = Attendance::orderBy('id', 'desc')->where([['user_id', $request->employee_id], ['date', date('Y-m-d')], ['clock_out', '00:00:00']])->first();


                $date = date("Y-m-d");
                $time = date("H:i:s");


                if ($request->clock == true) {

                    if ($startTime >= $time) {
                        $totalOvertimeSeconds = strtotime($startTime) -  time();
                        $hours                = floor($totalOvertimeSeconds / 3600);
                        $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                        $secs                 = floor($totalOvertimeSeconds % 60);
                        $restime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        $late = '00:00:00';
                        // dd($restime);
                    } else {
                        $totalLateSeconds = time() - strtotime($date . $startTime);
                        $hours            = floor($totalLateSeconds / 3600);
                        $mins             = floor($totalLateSeconds / 60 % 60);
                        $secs             = floor($totalLateSeconds % 60);
                        $late             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        $restime = '00:00:00';
                    }




                    $employeeAttendance                = new Attendance();
                    $employeeAttendance->user_id       = $request->employee_id;
                    $employeeAttendance->date          = $date;
                    $employeeAttendance->status        = 'Present';
                    $employeeAttendance->clock_in      = $time;
                    $employeeAttendance->clock_out     = '00:00:00';
                    $employeeAttendance->late          = $late;
                    $employeeAttendance->early_leaving = '00:00:00';
                    $employeeAttendance->overtime      = '00:00:00';
                    $employeeAttendance->total_rest    = $restime;
                    $employeeAttendance->created_by    = \Auth::user()->id;
                    $employeeAttendance->latitude      = $request->lat;
                    $employeeAttendance->longitude    = $request->long;
                    $employeeAttendance->location_id   = $request->location_id;
                    $employeeAttendance->role_id      = $request->role_id;
                    // $rotas = Rotas::where('user_id', $request->employee_id)->first();

                    // $minutes = $rotas->time_diff_in_minut ?: 0;
                    // $hours = floor($minutes / 60);
                    // $min = $minutes - ($hours * 60);
                    // $secs  = floor($min % 60);

                    // $company_time = sprintf('%02d:%02d:%02d', $hours, $min, $secs);

                    // if (isset($attendance->total_break_time)) {
                    //     $break_time = $attendance->total_break_time;


                    //     if ($break_time != null) {
                    //         $start = Carbon::parse($company_time);
                    //         $end = Carbon::parse($break_time);

                    //         $mins = $end->diffInMinutes($start);
                    //         $hours = floor($minutes / 60);
                    //         $min = $minutes - ($hours * 60);
                    //         $secs  = floor($min % 60);

                    //         $total_time = sprintf('%02d:%02d:%02d', $hours, $min, $secs);
                    //         $employeeAttendance->total_time   = $total_time;
                    //     } else {
                    //         $employeeAttendance->total_time   = $company_time;
                    //     }
                    // }

                    // if (isset($break_time)) {
                    //     $start = Carbon::parse($company_time);
                    //     $end = Carbon::parse($break_time);

                    //     $mins = $end->diffInMinutes($start);
                    //     $hours = floor($minutes / 60);
                    //     $min = $minutes - ($hours * 60);
                    //     $secs  = floor($min % 60);

                    //     $company_time = sprintf('%02d:%02d:%02d', $hours, $min, $secs);
                    //     $employeeAttendance->total_time   = $company_time;
                    // }

                    if ($location->images_required == 1) {

                        if ($request->hasFile('image')) {
                            if ($request->file('image')->isValid()) {
                                $this->validate($request, [
                                    'image' => 'required|mimes:jpeg,png,jpg'
                                ]);
                                $file = $request->file('image');
                                $destinationPath = public_path('/uploads');
                                //$extension = $file->getClientOriginalExtension('logo');
                                $thumbnail = $file->getClientOriginalName('image');
                                $thumbnail = rand() . $thumbnail;
                                $request->file('image')->move($destinationPath, $thumbnail);
                                $employeeAttendance->selfie = $thumbnail;
                            }
                        }


                        // if ($request->hasFile('image')) {

                        //     $profile_image = $this->uploadImage($request->file('image'), '/public/images/');

                        //     $employeeAttendance->selfie = $profile_image;
                        // }
                    }


                    $employeeAttendance->save();
                    return response()->json(['message' => "Employee Successfully Clock In.", 'employee' => $employeeAttendance, 'clock_in' => $clock_in, 'error' => false], 200);
                }
            } else {
                return response()->json(['message' => "You are not allowed to start multiple shifts in one day.", 'error' => false], 200);
            }
        } else {
            return response()->json(['message' => "Sorry!! please verify pin.", 'error' => false], 200);
        }
    }


    public function clockOut(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'employee_id'        => 'required',


        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->errorResponse($error, 200);
        }
        $clock_out = true;
        $employeeId = $request->employee_id;

        $employe = Employee::where([['id', $request->employee_id], ['created_by', Auth::id()]])->first();


        $today = Attendance::where([['user_id', $employeeId], ['date', date('Y-m-d')]])->latest()->first();

        $profile = Profile::where([['user_id', $request->employee_id], ['location_id', 'LIKE', "%{$today->location_id}%"]])->first();

        if (!$profile) {
            return response()->json(['message' => "Not Allowed!! Unuthorized Location.", 'error' => false], 200);
        }

        if ($profile) {
            $location = Location::where('id', $today->location_id)->first();


            if ($location->images_required ==  1) {

                if ($request->image ==  '') {
                    return response()->json(['message' => "Image is required.", 'error' => false], 200);
                }
            }
        }



        // $role = Profile::where([['user_id', $request->employee_id], ['role_id', 'LIKE', "%{$request->role_id}%"]])->first();

        // if (!$role) {
        //     return response()->json(['message' => "Not Allowed!! Unuthorized Role.", 'error' => false], 200);
        // }


        $user = User::where('id', $employeeId)->first();

        if ($user->pin != '' || $user->pin == '') {

            $today = Attendance::where([['user_id', $employeeId], ['date', date('Y-m-d')]])->latest()->first();

            if (!empty($today) && $today->clock_out == '00:00:00') {
                $startTime = Utility::getValByName('company_start_time');
                $endTime   = Utility::getValByName('company_end_time');


                $time = date("H:i:s");
                $date = date("Y-m-d");
                //late
                if ($startTime >= $today->clock_in) {
                    $totalLateSeconds = strtotime($date . $startTime) - strtotime($today->clock_in);
                    $hours = floor($totalLateSeconds / 3600);
                    $mins  = floor($totalLateSeconds / 60 % 60);
                    $secs  = floor($totalLateSeconds % 60);
                    $totalrest  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    $late = "00:00:00";
                } else {
                    $totalLateSeconds = strtotime($today->clock_in) - strtotime($date . $startTime);
                    $hours = floor($totalLateSeconds / 3600);
                    $mins  = floor($totalLateSeconds / 60 % 60);
                    $secs  = floor($totalLateSeconds % 60);
                    $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    $totalrest = "00:00:00";
                    // dd("dfmhjb");
                }

                if (time() < strtotime($date . $endTime)) {
                    //early Leaving
                    $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($time);
                    $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                    $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs                     = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                } else {
                    $earlyLeaving = "00:00:00";
                }

                if (strtotime($request->clock_out) > strtotime($date . $endTime)) {
                    //Overtime
                    $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    $earlyLeaving = "00:00:00";
                } else {
                    $overtime = '00:00:00';
                }

                $attendanceEmployee                = Attendance::find($today->id);
                $attendanceEmployee->user_id       = $request->employee_id;
                $attendanceEmployee->date          = $date;
                $attendanceEmployee->clock_in      = $today->clock_in;
                $attendanceEmployee->clock_out     = $time;
                $attendanceEmployee->late          = $late;
                $attendanceEmployee->early_leaving = $earlyLeaving;
                $attendanceEmployee->overtime      = $overtime;
                $attendanceEmployee->total_rest    = '00:00:00';
               

                if ($location->images_required == 1) {

                    if ($request->hasFile('image')) {
                        if ($request->file('image')->isValid()) {
                            $this->validate($request, [
                                'image' => 'required|mimes:jpeg,png,jpg'
                            ]);
                            $file = $request->file('image');
                            $destinationPath = public_path('/uploads');
                            //$extension = $file->getClientOriginalExtension('logo');
                            $thumbnail = $file->getClientOriginalName('image');
                            $thumbnail = rand() . $thumbnail;
                            $request->file('image')->move($destinationPath, $thumbnail);
                            $attendanceEmployee->clockout_selfie = $thumbnail;
                        }
                    }
                    // if ($request->hasFile('image')) {



                    //     $profile_image = $this->uploadImage($request->file('image'), '/public/images/');

                    //     $attendanceEmployee->clockout_selfie = $profile_image;
                    // }
                }
                $attendanceEmployee->save();


                $attendance = Attendance::find($attendanceEmployee->id);
                $startTime = Carbon::parse($attendanceEmployee->clock_in);
                $finishTime = Carbon::parse($attendanceEmployee->clock_out);
                $Duration = $finishTime->diffInSeconds($startTime);
                $totalDuration =  gmdate('H:i:s', $Duration);
             
                if($attendanceEmployee->total_break_time  != NULL)
                {

                $start_time = new Carbon($totalDuration);
                $end_time = new Carbon($time);
                $mins = $end_time->diffInMinutes($start_time);
 
                // $start = Carbon::parse($totalDuration);

                // $end = Carbon::parse($time);

                // $mins = $start->diffInSeconds($end);
                

                $hours = floor($mins / 60);
                $min = $mins - ($hours * 60);
                $secs  = floor($min % 60);

                $company_time = sprintf('%02d:%02d:%02d', $hours, $min, $secs);
                $attendance->total_time   = $company_time;
                }
                else{
                 $attendance->total_time   = $totalDuration;
                // }
                $attendance->save();
                }

                return response()->json(['message' => "Employee Successfully Clock Out.", 'employee' => $attendanceEmployee,  'clock_out' => $clock_out, 'error' => false], 200);
            } else {
                return response()->json(['message' => "Employee Already clock out.", 'error' => false], 200);
            }
        } else {
            return response()->json(['message' => "Sorry!! please verify pin.", 'error' => false], 200);
        }
    }


    public function breakIn(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'employee_id'        => 'required',


        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->errorResponse($error, 200);
        }

        $time = date("H:i:s");
        $date = date("Y-m-d");
        $settings = Utility::settings();




        $todayAttendance = Attendance::where([['user_id', $request->employee_id], ['date', date('Y-m-d')], ['clock_out', '00:00:00']])->first();

        if (!$todayAttendance) {
            return response()->json(['message' => "You are not checkIn today. please clock In first", 'error' => false], 200);
        }


        $break = new Breaks();
        $break->break_in = $time;
        $break->employee_id = $request->employee_id;

        $break->date = $date;
        $break->save();



        // if($todayAttendance){



        // }

        $data = Breaks::where('employee_id', $request->employee_id)->where('date', date('Y-m-d'))->get();


        return response()->json(['message' => "Break in added.", 'breaks' => $data, 'error' => false], 200);
    }




    public function breakOut(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'employee_id'        => 'required',


        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->errorResponse($error, 200);
        }

        $time = date("H:i:s");
        $date = date("Y-m-d");
        $settings = Utility::settings();




        $todayAttendance = Attendance::where([['user_id', $request->employee_id], ['date', date('Y-m-d')], ['clock_out', '00:00:00']])->first();

        if (!$todayAttendance) {
            return response()->json(['message' => "You are not checkIn today. please clock In first", 'error' => false], 200);
        }


        $data = Breaks::where('employee_id', $request->employee_id)->where('date', date('Y-m-d'))->get();
        $total_time = Breaks::where('employee_id', '=', $request->employee_id)->where('date', $date)->sum(DB::raw("TIME_TO_SEC(break_time)"));


        $break = Breaks::where('employee_id', '=', $request->employee_id)->where('date', date('Y-m-d'))->where('break_out', '=', NULL)->first();
        if ($break) {
            $break->break_out = $time;
            $break->employee_id = $request->employee_id;
            $startTime = Carbon::parse($break->break_in);
            $finishTime = Carbon::parse($break->break_out);
            $Duration = $finishTime->diffInSeconds($startTime);
            $totalDuration =  gmdate('H:i:s', $Duration);

            $break->break_time = $totalDuration;
            $break->date = $date;
            $break->save();

            $data = Breaks::where('employee_id', $request->employee_id)->where('date', $date)->get();
            $total_time = Breaks::where('employee_id', '=', $request->employee_id)->where('date', $date)->sum(DB::raw("TIME_TO_SEC(break_time)"));
            $hours = floor($total_time / 3600);
            $minutes = floor(($total_time % 3600) / 60);
            $seconds = (($total_time % 3600) % 60);
            $sumTime = $hours . ':' . $minutes . ':' . $seconds;
            $time            = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);


            $update_Attendance = Attendance::where([['user_id', $request->employee_id], ['date', date('Y-m-d')], ['clock_out', '00:00:00']])->first();
            $update_Attendance->total_break_time = $time;

            // $rotas = Rotas::where('user_id', $request->employee_id)->first();

            // $minutes = $rotas->time_diff_in_minut ?: 0;
            // $hours = floor($minutes / 60);
            // $min = $minutes - ($hours * 60);
            // $secs  = floor($min % 60);

            // $company_time = sprintf('%02d:%02d:%02d', $hours, $min, $secs);


            $start = Carbon::parse($totalDuration);

            $end = Carbon::parse($time);

            $mins = $end->diffInMinutes($start);
            $hours = floor($minutes / 60);
            $min = $minutes - ($hours * 60);
            $secs  = floor($min % 60);

            $company_time1 = sprintf('%02d:%02d:%02d', $hours, $min, $secs);
            $update_Attendance->total_time   = $company_time1;

            $update_Attendance->save();



            return response()->json(['message' => "Break out added.", 'breaks' => $data, 'total_time' => $total_time . '', 'seconds', 'error' => false], 200);
        } else {
            return response()->json(['message' => "Break not found.", 'error' => false], 200);
        }
        // $breaks = Attendance::where('user_id',$request->employee_id)





    }
}
