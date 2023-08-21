<?php

namespace App\Exports;

use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveExport implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $auth = Auth::user();
         $id = $auth->id;
        $data = Leave::get();
        if($auth->type == 'company') {
            $data = Leave::where('created_by',$auth->id)->get();
        }
        if($auth->type == 'employee') {
            $data = Leave::where('id', $id)->get();
        }

        foreach($data as $k => $leave)
        {
            if($leave->	issue_by == 1){
                $issue_by = "Company";
            }
            else {
                $issue_by = "Employee";
            }

            if($leave->leave_type == 1){
                $leave_type = "Holiday";
            }
            elseif($leave->leave_type == 2){
                $leave_type = "Other Leave";
            }
            else {
                $leave_type = "Other Static Leave";
            }

            if($leave->leave_approval == 0){
                $leave_approval = "New Request";
            }
            else if($leave->leave_approval == 1){
                $leave_approval = "Confirm";
            }
            else if($leave->leave_approval == 2){
                $leave_approval = "Reject";
            }
            else {
                $leave_approval = "Cancel";
            }

            if($leave->approved_by == 1){
                $approved_by = "Company";
            }
            else {
                $approved_by = "Employee";
            }

            if($leave->response_message == ''){
                $response_message = '-';
            }


            $username   = Leave::username($leave->user_id);
            unset($leave->id,$leave->created_by,$leave->created_at,$leave->updated_at,$leave->response_message,$leave->leave_time);
            $data[$k]["user_id"]     = $username;
            $data[$k]["issue_by"]     = $issue_by;
            $data[$k]["leave_type"]     = $leave_type;
            $data[$k]["leave_approval"]  = $leave_approval;
            $data[$k]["approved_by"]  = $approved_by;


        }
        return $data;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Issue By",
            "Leave Type",
            "Start Date",
            "End Date",
            "message",
            "Leave Approval",
            "Approved By",
            "Approved Date",
            "Paid Status",
        ];
    }
}
