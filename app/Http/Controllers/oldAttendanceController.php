<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Role;
use App\Models\Profile;
use App\Models\Location;
use App\Models\IpRestrict;
use Carbon\Carbon;
use App\Imports\AttendanceImport;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = \Auth::user();
        $created_by = $user->get_created_by();
        $role = Role::where('created_by', $created_by)->get()->pluck('name', 'id');
        $role->prepend(__('All'), '');

        $location = Location::where('created_by', $created_by)->get()->pluck('name', 'id');
        $location->prepend(__('All'), '');

        if (\Auth::user()->type == 'employee')
        {
            $emp = !empty(\Auth::user()->employee) ? \Auth::user()->employee->id : 0;

            $attendanceEmployee = Attendance::where('created_by', $created_by)->where('user_id', Auth::user()->id);

            if ($request->type == 'monthly' && !empty($request->month))
            {
                $month = date('m', strtotime($request->month));
                $year  = date('Y', strtotime($request->month));

                $start_date = date($year . '-' . $month . '-01');
                $end_date = date('Y-m-t', strtotime('01-' . $month . '-' . $year));

                $attendanceEmployee->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
            }
            elseif ($request->type == 'daily' && !empty($request->date))
            {
                $attendanceEmployee->where('date', $request->date);
            }
            else
            {
                $month      = date('m');
                $year       = date('Y');
                $start_date = date($year . '-' . $month . '-01');
                $end_date = date('Y-m-t', strtotime('01-' . $month . '-' . $year));

                $attendanceEmployee->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
            }
            $attendanceEmployee = $attendanceEmployee->get();
        }
        else
        {
            $employee = Profile::select('id');
            if (!empty($request->branch))
            {
                // $employee->where('role_id', $request->branch);
                $employee->where('role_id', 'like', "%{$request->branch}%");
            }

            if (!empty($request->department))
            {
                // $employee->where('location_id', $request->department);
                $employee->where('location_id', 'like', "%{$request->department}%");
            }

            $employee = $employee->get()->pluck('id');

            $attendanceEmployee = Attendance::where('created_by', $created_by)->whereIn('user_id', $employee);
            if ($request->type == 'monthly' && !empty($request->month))
            {
                $month = date('m', strtotime($request->month));
                $year  = date('Y', strtotime($request->month));
                $start_date = date($year . '-' . $month . '-01');
                $end_date = date('Y-m-t', strtotime('01-' . $month . '-' . $year));

                $attendanceEmployee->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
            }
            elseif ($request->type == 'daily' && !empty($request->date))
            {
                $attendanceEmployee->where('date', $request->date);
            }
            else
            {

                $month      = date('m');
                $year       = date('Y');
                $start_date = date($year . '-' . $month . '-01');
                $end_date = date('Y-m-t', strtotime('01-' . $month . '-' . $year));

                $attendanceEmployee->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
            }
            $attendanceEmployee = $attendanceEmployee->get();
        }
        return view('attendance.index', compact('attendanceEmployee','role','location'));
    }

    public function create()
    {
        // dd("");
    }

    public function store(Request $request)
    {
        // dd("");
        // return redirect()->back()->with('success', 'setting successfully updated.');
    }

    public function show(Attendance $attendance)
    {
        //
    }

    public function edit($id)
    {
        $user = \Auth::user();
        $created_by = $user->get_created_by();
        $attendanceEmployee = Attendance::where('id', $id)->first();
        $employees          = User::where('id', '=', $attendanceEmployee->user_id)->get()->pluck('first_name', 'id');

        return view('attendance.edit', compact('attendanceEmployee', 'employees'));
    }

    public function update(Request $request,$id)
    {
        $employeeId      = \Auth::user()->id;
        $todayAttendance = Attendance::where('id', '=', $employeeId)->where('date', date('Y-m-d'))->first();
        $today = Attendance::where('user_id',$employeeId)->where('date', date('Y-m-d'))->first();

        // if (!empty($todayAttendance) && $todayAttendance->clock_out == '00:00:00')
        // {
            $startTime = Utility::getValByName('company_start_time');
            $endTime   = Utility::getValByName('company_end_time');
            if (Auth::user()->type == 'employee')
            {
                $date = date("Y-m-d");
                $time = date("H:i:s");

                //early Leaving
                if(time() < strtotime($date . $endTime))
                {
                    $totalEarlyLeavingSeconds = strtotime($date . $endTime) - time();
                    $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                    $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs                     = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                }
                else
                {
                    $earlyLeaving ="00:00:00";
                }

                if (time() > strtotime($date . $endTime))
                {
                    //Overtime
                    $totalOvertimeSeconds = time() - strtotime($date . $endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    $earlyLeaving ="00:00:00";
                }
                else
                {
                    if(($earlyLeaving != '00:00:00') && ($today->total_rest != '00:00:00'))
                    {
                        $overtime;
                    }
                    else
                    {
                        $overtime = '00:00:00';
                    }
                }
                // dd($overtime);

                $attendanceEmployee                = Attendance::find($id);
                $attendanceEmployee->clock_out     = $time;
                $attendanceEmployee->early_leaving = $earlyLeaving;
                $attendanceEmployee->overtime      = $overtime;
                $attendanceEmployee->save();

                return redirect()->route('home')->with('success', __('Employee successfully clock Out.'));
            }
            else
            {
                $date = date("Y-m-d");
                //late
                if($startTime >= $request->clock_in)
                {
                    $totalLateSeconds = strtotime($date . $startTime) - strtotime($request->clock_in);
                    $hours = floor($totalLateSeconds / 3600);
                    $mins  = floor($totalLateSeconds / 60 % 60);
                    $secs  = floor($totalLateSeconds % 60);
                    $totalrest  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    $late = "00:00:00";
                }
                else
                {
                    $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);
                    $hours = floor($totalLateSeconds / 3600);
                    $mins  = floor($totalLateSeconds / 60 % 60);
                    $secs  = floor($totalLateSeconds % 60);
                    $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    $totalrest = "00:00:00";
                    // dd("dfmhjb");
                }

                if(time() < strtotime($date . $endTime))
                {
                    //early Leaving
                    $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
                    $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                    $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs                     = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                }
                else
                {
                    $earlyLeaving ="00:00:00";
                }

                if (strtotime($request->clock_out) > strtotime($date . $endTime))
                {
                    //Overtime
                    $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    $earlyLeaving ="00:00:00";
                }
                else
                {
                    $overtime = '00:00:00';
                }

                $attendanceEmployee                = Attendance::find($id);
                $attendanceEmployee->user_id       = $request->employee_id;
                $attendanceEmployee->date          = $request->date;
                $attendanceEmployee->clock_in      = $request->clock_in;
                $attendanceEmployee->clock_out     = $request->clock_out;
                $attendanceEmployee->late          = $late;
                $attendanceEmployee->early_leaving = $earlyLeaving;
                $attendanceEmployee->overtime      = $overtime;
                $attendanceEmployee->total_rest    = '00:00:00';

                $attendanceEmployee->save();

                return redirect()->route('attendance.index')->with('success', __('Employee attendance successfully updated.'));
            }
        // } else {
        //     return redirect()->back()->with('error', __('Employee are not allow multiple time clock in & clock for every day.'));
        // }
    }

    public function destroy($id)
    {
        $attendance = Attendance::where('id',$id)->first();
        $attendance->delete();

        return redirect()->route('attendance.index')->with('success', __('Attendance successfully deleted.'));
    }

    public function inattendance(Request $request)
    {
        $user = \Auth::user();
        $created_by = $user->get_created_by();
        $settings = Utility::settings();

        if ($settings['ip_restrict'] == 'on') {
            $userIp = request()->ip();
            $ip     = IpRestrict::where('created_by', $created_by)->whereIn('ip', [$userIp])->first();

            if(empty($ip)) {
                return redirect()->back()->with('error', __('this ip is not allowed to clock in & clock out.'));
            }
        }
        $user = Auth::user();
        $todayAttendance = Attendance::where('user_id', '=', $user->id)->where('date', date('Y-m-d'))->first();
        if (empty($todayAttendance))
        {
            $startTime = Utility::getValByName('company_start_time');
            $endTime   = Utility::getValByName('company_end_time');

            $attendance = Attendance::orderBy('id', 'desc')->where('user_id', '=', $user->id)->where('clock_out', '=', '00:00:00')->first();

            if ($attendance != null)
            {
                $attendance            = Attendance::find($attendance->id);
                $attendance->clock_out = $endTime;
                $attendance->save();
            }

            $date = date("Y-m-d");
            $time = date("H:i:s");

            if($startTime >= $time)
            {
                $totalOvertimeSeconds = strtotime($startTime) -  time();
                $hours                = floor($totalOvertimeSeconds / 3600);
                $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                $secs                 = floor($totalOvertimeSeconds % 60);
                $restime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                $late = '00:00:00';
                // dd($restime);
            }
            else
            {
                $totalLateSeconds = time() - strtotime($date . $startTime);
                $hours            = floor($totalLateSeconds / 3600);
                $mins             = floor($totalLateSeconds / 60 % 60);
                $secs             = floor($totalLateSeconds % 60);
                $late             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                $restime = '00:00:00';
            }

            $checkDb = Attendance::where('user_id', '=', \Auth::user()->id)->get()->toArray();

            if (empty($checkDb))
            {
                $employeeAttendance                = new Attendance();
                $employeeAttendance->user_id       = $user->id;
                $employeeAttendance->date          = $date;
                $employeeAttendance->status        = 'Present';
                $employeeAttendance->clock_in      = $time;
                $employeeAttendance->clock_out     = '00:00:00';
                $employeeAttendance->late          = $late;
                $employeeAttendance->early_leaving = '00:00:00';
                $employeeAttendance->overtime      = '00:00:00';
                $employeeAttendance->total_rest    = $restime;
                $employeeAttendance->created_by    = \Auth::user()->id;
                $employeeAttendance->save();

                return redirect()->route('home')->with('success', __('Employee Successfully Clock In.'));
            }

            foreach ($checkDb as $check)
            {
                $employeeAttendance                = new Attendance();
                $employeeAttendance->user_id       = $user->id;
                $employeeAttendance->date          = $date;
                $employeeAttendance->status        = 'Present';
                $employeeAttendance->clock_in      = $time;
                $employeeAttendance->clock_out     = '00:00:00';
                $employeeAttendance->late          = $late;
                $employeeAttendance->early_leaving = '00:00:00';
                $employeeAttendance->overtime      = '00:00:00';
                $employeeAttendance->total_rest    = $restime;
                $employeeAttendance->created_by    = \Auth::user()->id;
                $employeeAttendance->save();

                return redirect()->route('home')->with('success', __('Employee Successfully Clock In.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Employee are not allow multiple time clock in & clock for every day.'));
        }
    }

    public function importFile()
    {
        return view('attendance.import');
    }
    public function import(Request $request)
    {
        $user = \Auth::user();
        $created_by = $user->get_created_by();

        $rules = [
            'file' => 'required|mimes:csv,txt,xlsx',
        ];
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $attendance = (new AttendanceImport())->toArray(request()->file('file'))[0];

        $email_data = [];
        foreach ($attendance as $key => $employee)
        {
            if ($key != 0)
            {
                echo "<pre>";
                if ($employee != null && User::where('email', $employee[0])->where('created_by', $created_by)->exists())
                {
                    $email = $employee[0];
                }
                else
                {
                    $email_data[] = $employee[0];
                }
            }
        }
        $totalattendance = count($attendance) - 1;
        $errorArray    = [];

        $startTime = Utility::getValByName('company_start_time');
        $endTime   = Utility::getValByName('company_end_time');

        if (!empty($attendanceData))
        {
            $errorArray[] = $attendanceData;
        }
        else
        {
            foreach ($attendance as $key => $value)
            {
                if ($key != 0)
                {
                    $employeeData = User::where('email', $value[0])->where('created_by', $created_by)->first();
                    // $employeeId = 0;
                    if (!empty($employeeData))
                    {
                        $employeeId = $employeeData->id;

                        $clockIn = $value[2];
                        $clockOut = $value[3];

                        if ($clockIn)
                        {
                            $status = "present";
                        }
                        else
                        {
                            $status = "leave";
                        }

                        $totalLateSeconds = strtotime($clockIn) - strtotime($startTime);

                        $hours = floor($totalLateSeconds / 3600);
                        $mins  = floor($totalLateSeconds / 60 % 60);
                        $secs  = floor($totalLateSeconds % 60);
                        $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($clockOut);
                        $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                        $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                        $secs                     = floor($totalEarlyLeavingSeconds % 60);
                        $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        if (strtotime($clockOut) > strtotime($endTime))
                        {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($clockOut) - strtotime($endTime);
                            $hours                = floor($totalOvertimeSeconds / 3600);
                            $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                            $secs                 = floor($totalOvertimeSeconds % 60);
                            $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        }
                        else
                        {
                            $overtime = '00:00:00';
                        }

                        $check = Attendance::where('user_id', $employeeId)->where('date', $value[1])->first();
                        if ($check)
                        {
                            $check->update([
                                'late' => $late,
                                'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                                'overtime' => $overtime,
                                'clock_in' => $value[2],
                                'clock_out' => $value[3]
                            ]);
                        }
                        else
                        {
                            $time_sheet = Attendance::create([
                                'user_id' => $employeeId,
                                'date' => $value[1],
                                'status' => $status,
                                'late' => $late,
                                'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                                'overtime' => $overtime,
                                'clock_in' => $value[2],
                                'clock_out' => $value[3],
                                'total_rest'=>'00:00:00',
                                'created_by' => \Auth::user()->id,
                            ]);
                        }
                    }
                }
                else
                {
                    $email_data = implode(' And ', $email_data);
                }
            }
            if (!empty($email_data))
            {
                return redirect()->back()->with('status', 'this record is not import. ' . '</br>' . $email_data);
            }
            else
            {
                if (empty($errorArray))
                {
                    $data['status'] = 'success';
                    $data['msg']    = __('Record successfully imported');
                }
                else
                {

                    $data['status'] = 'error';
                    $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalattendance . ' ' . 'record');


                    foreach ($errorArray as $errorData)
                    {
                        $errorRecord[] = implode(',', $errorData->toArray());
                    }

                    \Session::put('errorArray', $errorRecord);
                }
                return redirect()->back()->with($data['status'], $data['msg']);
            }
        }
    }
    public function bulkAttendance(Request $request)
    {
        $user = \Auth::user();
        $created_by = $user->get_created_by();

        $role = Role::where('created_by', $created_by)->get()->pluck('name', 'id');
        $role->prepend(__('Select Role'), '');

        $location = Location::where('created_by', $created_by)->get()->pluck('name', 'id');
        $location->prepend(__('Select Location'), '');

        $employees = [];
        if (!empty($request->branch) || !empty($request->department))
        {
            $employees = Profile::select('*');
                if (!empty($request->branch))
                {
                    // $employees->where('role_id', $request->branch);
                    $employees->where('role_id', 'like', "%{$request->branch}%");
                }

                if (!empty($request->department))
                {
                    // $employees->where('location_id', $request->department);
                    $employees->where('location_id', 'like', "%{$request->department}%" );
                }
            $employees = $employees->get();
        }

        return view('attendance.bulk', compact('employees', 'role', 'location'));
    }

    public function bulkAttendanceData(Request $request)
    {
        $user = \Auth::user();
        $created_by = $user->get_created_by();
        $startTime = Utility::getValByName('company_start_time');
        $endTime   = Utility::getValByName('company_end_time');
        $date      = $request->date;

        $employees = $request->employee_id;
        $atte      = [];
        if(!empty($employees))
        {
            foreach ($employees as $employee)
            {
                $present = 'present-' . $employee;
                $in      = 'in-' . $employee;
                $out     = 'out-' . $employee;
                $atte[]  = $present;
                if ($request->$present == 'on')
                {
                    $in  = date("H:i:s", strtotime($request->$in));
                    $out = date("H:i:s", strtotime($request->$out));

                    if($startTime >= $in)
                    {
                        $totalLateSeconds = strtotime($startTime) - strtotime($in);
                        $hours = floor($totalLateSeconds / 3600);
                        $mins  = floor($totalLateSeconds / 60 % 60);
                        $secs  = floor($totalLateSeconds % 60);
                        $totalrest  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        $late = "00:00:00";
                    }
                    else
                    {
                        $totalLateSeconds = strtotime($in) - strtotime($startTime);
                        $hours = floor($totalLateSeconds / 3600);
                        $mins  = floor($totalLateSeconds / 60 % 60);
                        $secs  = floor($totalLateSeconds % 60);
                        $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    }

                    //early Leaving
                    $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($out);
                    $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                    $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs                     = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                    if (strtotime($out) > strtotime($endTime))
                    {
                        //Overtime
                        $totalOvertimeSeconds = strtotime($out) - strtotime($endTime);
                        $hours                = floor($totalOvertimeSeconds / 3600);
                        $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                        $secs                 = floor($totalOvertimeSeconds % 60);
                        $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        $earlyLeaving = "00:00:00";
                    }
                    else
                    {
                        $overtime = '00:00:00';
                    }

                    $attendance = Attendance::where('user_id', '=', $employee)->where('date', '=', $request->date)->first();

                    if (!empty($attendance))
                    {
                        $employeeAttendance = $attendance;
                    }
                    else
                    {
                        $employeeAttendance              = new Attendance();
                        $employeeAttendance->user_id = $employee;
                        $employeeAttendance->created_by  = $created_by;
                    }

                    $employeeAttendance->status        = 'Present';
                    $employeeAttendance->date          = $request->date;
                    $employeeAttendance->clock_out     = $out;
                    $employeeAttendance->clock_in      = $in;
                    $employeeAttendance->early_leaving = ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00';
                    $employeeAttendance->late          = $late;
                    $employeeAttendance->total_rest    = '00:00:00';
                    $employeeAttendance->overtime      = $overtime;
                    $employeeAttendance->save();
                }
                else
                {
                    $attendance = Attendance::where('user_id', '=', $employee)->where('date', '=', $request->date)->first();

                    if (!empty($attendance))
                    {
                        $employeeAttendance = $attendance;
                    }
                    else
                    {
                        $employeeAttendance              = new Attendance();
                        $employeeAttendance->user_id = $employee;
                        $employeeAttendance->created_by  = $created_by;
                    }

                    $employeeAttendance->status        = 'Leave';
                    $employeeAttendance->date          = $request->date;
                    $employeeAttendance->clock_in      = '00:00:00';
                    $employeeAttendance->clock_out     = '00:00:00';
                    $employeeAttendance->late          = '00:00:00';
                    $employeeAttendance->early_leaving = '00:00:00';
                    $employeeAttendance->overtime      = '00:00:00';
                    $employeeAttendance->total_rest    = '00:00:00';
                    $employeeAttendance->save();
                }
            }
            return redirect()->back()->with('success', __('Employee attendance successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Employee attendance field required.'));
        }
    }
}
