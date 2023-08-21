<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Breaks;
use App\Models\Rotas;
use App\Models\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BreakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function break($id)
    {
        $time = date("H:i:s");
        $date = date("Y-m-d");
        $settings = Utility::settings();
        $todayAttendance = Attendance::where([['user_id', $id], ['date', date('Y-m-d')], ['clock_out', '00:00:00']])->first();

        if (!$todayAttendance) {
            return redirect()->route('attendance.index')->with('success', __('You are not checkIn today. please clock In first.'));

        }
        $break = Breaks::where('employee_id', '=', $id)->where('date', date('Y-m-d'))->where('break_out', '=', NULL)->first();
        $total_time = Breaks::where('employee_id', '=', $id)->where('date', $date)->sum(DB::raw("TIME_TO_SEC(break_time)"));

        if ($break) {
            $break->break_out = $time;
            $break->employee_id = $id;
            $startTime = Carbon::parse($break->break_in);
            $finishTime = Carbon::parse($break->break_out);
            $Duration = $finishTime->diffInSeconds($startTime);
            $totalDuration =  gmdate('H:i:s', $Duration);

            $break->break_time = $totalDuration;
            $break->date = $date;
            $break->save();

            $data = Breaks::where('employee_id', $id)->where('date', $date)->get();
            $total_time = Breaks::where('employee_id', '=', $id)->where('date', $date)->sum(DB::raw("TIME_TO_SEC(break_time)"));
            $hours = floor($total_time / 3600);
            $minutes = floor(($total_time % 3600) / 60);
            $seconds = (($total_time % 3600) % 60);
            $sumTime = $hours . ':' . $minutes . ':' . $seconds;
            $time            = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            // dd($time);


            $update_Attendance = Attendance::where([['user_id', $id], ['date', date('Y-m-d')], ['clock_out', '00:00:00']])->first();
            $update_Attendance->total_break_time = $time;
            $update_Attendance->save();


            

            // $rotas = Rotas::where('user_id', $id)->first();

            // $minutes = $rotas->time_diff_in_minut ?: 0;
            // $hours = floor($minutes / 60);
            // $min = $minutes - ($hours * 60);
            // $secs  = floor($min % 60);

            // $company_time = sprintf('%02d:%02d:%02d', $hours, $min, $secs);


            // $startTime = Carbon::parse($update_Attendance->clock_in);
            // $finishTime = Carbon::parse($update_Attendance->clock_out);
            // $Duration = $finishTime->diffInSeconds($startTime);
            // $totalDuration =  gmdate('H:i:s', $Duration);

            // $start = Carbon::parse($totalDuration);
            // $end = Carbon::parse($time);

            // $mins = $end->diffInMinutes($start);
            // $hours = floor($mins / 60);
            // $min = $mins - ($hours * 60);
            // $secs  = floor($min % 60);

            // $company_time = sprintf('%02d:%02d:%02d', $hours, $min, $secs);
            // if($update_Attendance->clock_out == "00:00:00"  ||  $update_Attendance->clock_out != NULL ||  $update_Attendance->clock_out != NULL  )
            // {
            // $update_Attendance->total_time   = $company_time;
            // }
            // $update_Attendance->save();
            return redirect()->route('attendance.index')->with('success', __('Break Out .'));

        }

        else{
        $break = new Breaks();
        $break->break_in = $time;
        $break->employee_id = $id;

        $break->date = $date;
        $break->save();

        }
        return redirect()->route('attendance.index')->with('success', __('Break In.'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('breaks.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
