<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use App\Models\Profile;
use App\Models\TimeSheet;
use Illuminate\Http\Request;
use App\Exports\TimesheetExport;
use App\Imports\TimesheetImport;
use Illuminate\Support\Facades\Auth;

class TimeSheetController extends Controller
{
    public function index(Request $request)
    {
        $user = \Auth::user();
        $created_by = $user->get_created_by();

        $employeesList = [];
        if(\Auth::user()->type == 'employee')
        {
            $timeSheets = TimeSheet::where('user_id', \Auth::user()->id)->get();
        }
        else
        {
            $employeesList = User::where('created_by', $created_by)->get()->pluck('first_name', 'id');
            $employeesList->prepend(__('All'), '');

            $timesheets = TimeSheet::where('created_by', $created_by);

            if(!empty($request->start_date) && !empty($request->end_date))
            {
                $timesheets->where('date', '>=', $request->start_date);
                $timesheets->where('date', '<=', $request->end_date);
            }

            if(!empty($request->employee))
            {
                $timesheets->where('user_id', $request->employee);
            }
            $timeSheets = $timesheets->get();
        }
        return view('timeSheet.index', compact('timeSheets', 'employeesList'));
    }

    public function create()
    {
        $user = \Auth::user();
        $created_by = $user->get_created_by();

        if(\Auth::user()->type == 'employee')
        {
            $profile = Profile::where('user_id', \Auth::user()->id)->first();
            $data = explode(',',$profile['location_id']);
            $loaction = Location::whereIn('id',$data)->get()->pluck('name','id');
            $employees = User::where('created_by', '=', \Auth::user()->id)->get()->pluck('first_name', 'id');
            return view('timeSheet.create', compact('employees','loaction'));
        }
        else
        {
            // $employees = User::where('created_by', $created_by)->first();
            // $profile = Profile::where('user_id','=',$employees->id)->first();
            // $data = explode(',',$profile['location_id']);

            $employees = User::where('created_by', $created_by)->get()->pluck('first_name', 'id');
            $loaction = Location::get()->pluck('name', 'id');
            return view('timeSheet.create', compact('employees','loaction'));
        }
    }

    public function changelocation(Request $request)
    {
        $profile   = Profile::where('user_id','=',$request->id)->first();
        $data      = explode(',',$profile['location_id']);
        $location  = Location::whereIn('id',$data)->get()->pluck('name', 'id');

        return response()->json([
            'status' =>true,
            'message' => 'Data Fatch Successfully',
            'data' => $location
        ]);
    }

    public function store(Request $request)
    {

        $user = \Auth::user();
        $created_by = $user->get_created_by();

        $timeSheet = new Timesheet();
        if(\Auth::user()->type == 'employee')
        {
            $timeSheet->user_id = \Auth::user()->id;
            $timeSheet->location_id = $request->loaction_id;
        }
        else
        {
            $timeSheet->user_id = $request->employee_id;
            $timeSheet->location_id = $request->loaction_id;
        }

        $timeSheetCheck = TimeSheet::where('date', $request->date)->where('user_id', $timeSheet->user_id)->first();

        if(!empty($timeSheetCheck))
        {
            return redirect()->back()->with('error', __('Timesheet already created in this day.'));
        }

        $timeSheet->date       = $request->date;
        $timeSheet->hours      = $request->hours;
        $timeSheet->remark     = $request->remark;
        $timeSheet->created_by = $created_by;
        $timeSheet->save();

        return redirect()->route('timesheet.index')->with('success', __('Timesheet successfully created.'));
    }

    public function show(TimeSheet $timeSheet)
    {
        //
    }

    public function edit(TimeSheet $timeSheet,$id)
    {
        if(\Auth::user()->type == 'employee')
        {
            $timeSheet = Timesheet::find($id);
            $profile = Profile::where('user_id', \Auth::user()->id)->first();
            $data = explode(',',$profile['location_id']);

            $loaction = Location::whereIn('id',$data)->get()->pluck('name','id');
            $employees = User::where('created_by', '=', \Auth::user()->id)->get()->pluck('first_name', 'id');
        }
        else
        {
            $user = \Auth::user();
            $created_by = $user->get_created_by();
            $timeSheet = Timesheet::find($id);

            $profile = Profile::where('user_id', $timeSheet->user_id)->first();
            $data = explode(',',$profile['location_id']);
            $loaction = Location::whereIn('id',$data)->get()->pluck('name', 'id');
            $employees = User::where('created_by', '=', $created_by)->where('id','=',$timeSheet->user_id)->get()->pluck('first_name', 'id');
            $statuses = ["pending", "approved", 'cancel'];
           

        }
        return view('timeSheet.edit', compact('timeSheet', 'employees','loaction','statuses'));
    }


    public function status(TimeSheet $timeSheet,$id)
    {
        if(\Auth::user()->type == 'employee')
        {
            $timeSheet = Timesheet::find($id);
            $profile = Profile::where('user_id', \Auth::user()->id)->first();
            $data = explode(',',$profile['location_id']);

            $loaction = Location::whereIn('id',$data)->get()->pluck('name','id');
            $employees = User::where('created_by', '=', \Auth::user()->id)->get()->pluck('first_name', 'id');
        }
        else
        {
            $user = \Auth::user();
            $created_by = $user->get_created_by();
            $timeSheet = Timesheet::find($id);

            $profile = Profile::where('user_id', $timeSheet->user_id)->first();
            $data = explode(',',$profile['location_id']);
            $loaction = Location::whereIn('id',$data)->get()->pluck('name', 'id');
            $employees = User::where('created_by', '=', $created_by)->where('id','=',$timeSheet->user_id)->get()->pluck('first_name', 'id');
        }
        return view('timeSheet.edit', compact('timeSheet', 'employees','loaction','status'));
    }

    public function update(Request $request,$id)
    {
        $timeSheet = Timesheet::find($id);
            if(\Auth::user()->type == 'employee')
            {
                $timeSheet->user_id = \Auth::user()->id;
                $timeSheet->location_id = $request->location_id;
            }
            else
            {
                $timeSheet->user_id = $request->employee_id;
                $timeSheet->location_id = $request->location_id;
            }

            $timeSheetCheck = TimeSheet::where('date', $request->date)->where('user_id', $timeSheet->user_id)->first();

            if(!empty($timeSheetCheck) && $timeSheetCheck->id != $id)
            {
                return redirect()->back()->with('error', __('Timesheet already created in this day.'));
            }

            $timeSheet->date   = $request->date;
            $timeSheet->hours  = $request->hours;
            $timeSheet->remark = $request->remark;
            $timeSheet->status = $request->status;

            $timeSheet->save();

            return redirect()->route('timesheet.index')->with('success', __('TimeSheet successfully updated.'));
    }

    public function destroy(TimeSheet $timeSheet,$id)
    {
        $timeSheet = Timesheet::find($id);
        // dd($timeSheet);
        $timeSheet->delete();

        return redirect()->route('timesheet.index')->with('success', __('TimeSheet successfully deleted.'));
    }
    public function export(Request $request)
    {
        $name = 'Timesheet_' . date('Y-m-d i:h:s');
        $data = \Excel::download(new TimesheetExport(), $name . '.xlsx');

        return $data;
    }
    public function importFile(Request $request)
    {
        return view('timeSheet.import');
    }
    public function import(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:csv,txt,xlsx',
        ];
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $timesheet = (new TimesheetImport())->toArray(request()->file('file'))[0];

        $totalTimesheet = count($timesheet) - 1;
        $errorArray    = [];
        for ($i = 1; $i <= $totalTimesheet; $i++) {
            $timesheets = $timesheet[$i];
            $timesheetData=TimeSheet::where('user_id',$timesheets[1])->where('date',$timesheets[0])->first();
            if(!empty($timesheetData))
            {
                $errorArray[]=$timesheetData;
            }
            else
            {
                $time_sheet=new TimeSheet();
                $time_sheet->user_id=$timesheets[0];
                $time_sheet->location_id=$timesheets[1];
                $time_sheet->date=$timesheets[2];
                $time_sheet->hours=$timesheets[3];
                $time_sheet->remark=$timesheets[4];
                $time_sheet->created_by=Auth::user()->id;
                $time_sheet->save();
            }
        }


        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {

            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalTimesheet . ' ' . 'record');


            foreach ($errorArray as $errorData) {
                $errorRecord[] = implode(',', $errorData->toArray());
            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }
}
