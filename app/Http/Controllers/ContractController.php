<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractType;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use App\Models\ContractComment;
use App\Models\ContractNote;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContractSend;
use App\Models\ContractAttachment;
use App\Models\Utility;

class ContractController extends Controller
{
    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            if(\Auth::user()->type == 'company')
            {
                $contracts = Contract::where('created_by', '=', \Auth::user()->get_created_by())->get();
                $curr_month  = Contract::where('created_by', '=', \Auth::user()->ownerId())->whereMonth('start_date', '=', date('m'))->get();
                $curr_week   = Contract::where('created_by', '=', \Auth::user()->ownerId())->whereBetween(
                    'start_date', [\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek(),])->get();
                $last_30days = Contract::where('created_by', '=', \Auth::user()->id)->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

                // Contracts Summary
                $cnt_contract                = [];
                $cnt_contract['total']       = \App\Models\Contract::getContractSummary($contracts);
                $cnt_contract['this_month']  = \App\Models\Contract::getContractSummary($curr_month);
                $cnt_contract['this_week']   = \App\Models\Contract::getContractSummary($curr_week);
                $cnt_contract['last_30days'] = \App\Models\Contract::getContractSummary($last_30days);

            }
            else
            {
                $contracts = Contract::where('employee', '=', \Auth::user()->id)->get();
                $curr_month  = Contract::where('employee', '=', \Auth::user()->id)->whereMonth('start_date', '=', date('m'))->get();
                $curr_week   = Contract::where('employee', '=', \Auth::user()->id)->whereBetween(
                    'start_date', [\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek(),])->get();
                $last_30days = Contract::where('employee', '=', \Auth::user()->id)->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

                // Contracts Summary
                $cnt_contract                = [];
                $cnt_contract['total']       = \App\Models\Contract::getContractSummary($contracts);
                $cnt_contract['this_month']  = \App\Models\Contract::getContractSummary($curr_month);
                $cnt_contract['this_week']   = \App\Models\Contract::getContractSummary($curr_week);
                $cnt_contract['last_30days'] = \App\Models\Contract::getContractSummary($last_30days);
            }
            return view('contract.index', compact('contracts','cnt_contract'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $contractTypes = ContractType::where('created_by', '=', \Auth::user()->get_created_by())->get()->pluck('name', 'id');
        $employees      = User::where('type', 'employee')->where('created_by', \Auth::user()->get_created_by())->get();
        $employees_select = [];
        foreach($employees as $employee){
            $employees_select[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }
        return view('contract.create', compact('contractTypes', 'employees_select'));
    }

    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $rules = [
                'employee' => 'required',
                'contract_name' => 'required',
                'subject' => 'required',
                'type' => 'required',
                'value' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'edit_status' => 'Pending',

            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('contract.index')->with('error', $messages->first());
            }

            $contract                   = new Contract();
            $contract->employee         = $request->employee;
            $contract->contract_name    = $request->contract_name;
            $contract->subject          = $request->subject;
            $contract->type             = $request->type;
            $contract->value            = $request->value;
            $contract->start_date       = $request->start_date;
            $contract->end_date         = $request->end_date;
            $contract->notes           = $request->notes;
            $contract->created_by       = \Auth::user()->get_created_by();
            $contract->save();

            // $client      = User::find($request->client);
            // $contractArr = [
            //     'contract_subject' => $request->subject,
            //     'contract_client' => $client->name,
            //     'contract_value' => \Auth::user()->priceFormat($request->value),
            //     'contract_start_date' => \Auth::user()->dateFormat($request->start_date),
            //     'contract_end_date' => \Auth::user()->dateFormat($request->end_date),
            //     'contract_description' => $request->description,
            // ];

            // Send Email
            //$resp = Utility::sendEmailTemplate('create_contract', [$client->id => $client->email], $contractArr);
            // return redirect()->route('contract.index')->with('success', __('Contract successfully created.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

            return redirect()->route('contract.index')->with('success', __('Contract successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Request $request, Contract $contract)
    {
        $contract = Contract::find($contract->id);
        $contractattachemnts = ContractAttachment::where('contract_id', $contract->id)->get();
        $contractcomments = ContractComment::where('contract_id', $contract->id)->get();
        $contractnotes = ContractNote::where('contract_id', $contract->id)->get();
        $attachmentcount = ContractAttachment::where('contract_id', $contract->id)->where('created_by', \Auth::user()->id )->count();
        $commentscount = ContractComment::where('contract_id', $contract->id)->where('created_by', \Auth::user()->id )->count();
        $notescount = ContractNote::where('contract_id', $contract->id)->where('created_by', \Auth::user()->id )->count();

        return view('contract.view',compact('contract','contractcomments','contractnotes','commentscount','notescount','contractattachemnts','attachmentcount'));
    }

    public function edit(Contract $contract)
    {
        $contractTypes = ContractType::where('created_by', '=', \Auth::user()->get_created_by())->get()->pluck('name', 'id');
        $employee       = User::where('type', 'employee')->where('created_by', \Auth::user()->get_created_by())->get()->pluck('first_name', 'id');
        return view('contract.edit', compact('contractTypes', 'employee', 'contract'));
    }

    public function update(Request $request, Contract $contract)
    {
        if(\Auth::user()->type == 'company')
        {
            $rules = [
                'employee' => 'required',
                'contract_name' => 'required',
                'subject' => 'required',
                'type' => 'required',
                'value' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->route('contract.index')->with('error', $messages->first());
            }
            $contract->employee         = $request->employee;
            $contract->contract_name    = $request->contract_name;
            $contract->subject          = $request->subject;
            $contract->type             = $request->type;
            $contract->value            = $request->value;
            $contract->start_date       = $request->start_date;
            $contract->end_date         = $request->end_date;
            $contract->notes           = $request->notes;
            $contract->created_by       = \Auth::user()->get_created_by();
            $contract->save();

            return redirect()->route('contract.index')->with('success', __('Contract successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('contract.index')->with('success', __('Contract successfully deleted.'));
    }

    public function contractdescription(Request $request , $id)
    {
        if(\Auth::user()->type == 'company')
        {
            $contractdescription = Contract::find($id);
            $contractdescription->contract_description  = $request->contract_description;
            $contractdescription->save();
            return redirect()->back()->with('success', __('Contract Description successfully saved.'));
        }
    }

    public function contract_attachments(Request $request , $id)
    {
        $contract_attachments = Contract::find($id);
        $file = new ContractAttachment;
        $request->validate(['file' => 'required']);
        $image_size = $request->file('file')->getSize();

        $result = Utility::updateStorageLimit(\Auth::user()->id, $image_size);
        if($result==1)
        {
            $dir = 'uploads/contract_attachments/';
            $file_name = $request->file->getClientOriginalName();
            $path = Utility::upload_file($request,'file',$file_name,$dir);
        }
        if($path['flag'] == 1){
            $file = $path['url'];
        }
        else{
            return redirect()->back()->with('error', __($path['msg']));
        }
        $file = new ContractAttachment;
        $file->contract_id =$contract_attachments->id;
        $file->file_name = strip_tags($file_name);
        $file->file_path = strip_tags($dir);
        $file->created_by = \Auth::user()->id;
        $file->save();
        $return               = [];
        $return['is_success'] = true;
        return response()->json($return);
    }

    public function fileDownload($request , $id)
    {
        if(\Auth::user()->type == 'company')
        {
            $contract_attachments = Contract::find($id);
            if($contract->created_by == \Auth::user()->creatorId())
            {
                $file = ContractAttechment::find($file_id);
                if($file)
                {
                    $file_path = storage_path('uploads/contract_attachments/' . $file->file_name);
                    // $files = $file->files;
                    return \Response::download(
                        $file_path, $file->file_name, [
                                      'Content-Length: ' . filesize($file_path),
                                  ]
                    );
                }
                else
                {
                    return redirect()->back()->with('error', __('File is not exist.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function contract_attachments_destroy($id, $attachment_id)
    {
        $contract = Contract::find($id);
        $attachment = ContractAttachment::find($attachment_id);
        $file_path = $attachment->file_path.$attachment->file_name;
        $result = Utility::changeStorageLimit(\Auth::user()->id, $file_path);
        \Storage::delete($attachment->file_path);
        ContractAttachment::where("id", $attachment->id)->delete();
        return back()->with("success", "Image deleted successfully.");
    }

    public function contract_comments(Request $request , $id)
    {
        $contractcomments = Contract::find($id);
        $created_by = Auth::user()->id;
        $contract_comments = new ContractComment();
        $contract_comments['contract_id']            = $contractcomments->id;
        $contract_comments['comment']              = $request->comments;
        $contract_comments['created_by']             = $created_by;
        $contract_comments->save();
        return redirect()->back()->with('success', __('Contract Comment successfully saved.'));
    }

    public function contract_comments_destroy($id, $comment_id)
    {
        $contract = Contract::find($id);
        $comments = ContractComment::find($comment_id);
        $comments->delete();
        return redirect()->back()->with('success', __('Comment successfully deleted.'));
    }


    public function contract_notes(Request $request , $id)
    {
        $contractnotes = Contract::find($id);
        $created_by = Auth::user()->id;
        $contract_notes = new ContractNote();
        $contract_notes['contract_id']            = $contractnotes->id;
        $contract_notes['notes']                   = $request->notes;
        $contract_notes['created_by']             = $created_by;
        $contract_notes->save();
        return redirect()->back()->with('success', __('Contract Notes successfully saved.'));
    }

    public function contract_notes_destroy($id, $notes_id)
    {
        $contract = Contract::find($id);
        $notes = ContractNote::find($notes_id);
        $notes->delete();
        return redirect()->back()->with('success', __('Notes successfully deleted.'));
    }

    public function emailsend($id)
    {
         $contract    = Contract::find($id);
         $employees          = User::where('id', $contract->employee)->first();
         $employee    = !empty($employees) ? $employees->first_name : '';

            $contractArr = [
                'contract_id' => $contract->id,
            ];
            $employee = User::find($contract->employee);
            // dd( $client->email);
            $estArr = [
                'email' => $employee->email,
                'contract_start_date' => $contract->start_date,
                'contract_end_date' =>$contract->end_date ,
            ];
            try{
                Mail::to($employee->email)->send(new ContractSend($estArr,$contract,$employee));
            }
            catch(\Exception $e)
            {
                $error_msg = "E-Mail has been not sent due to SMTP configuration ";
            }
            return redirect()->route('contract.show', $contract->id)->with('success', __('Send successfully!'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }


        // $contract = Contract::find($id);


        // $employees          = User::where('id', $contract->employee)->first();

        // $employee    = !empty($employees) ? $employees->first_name : '';

        // $id            = $contract->id;
        // $contract_name = $contract->contract_name;
        // $subject       = $contract->subject;
        // $value         = $contract->value;
        // $startdate     = $contract->startdate;
        // $enddate       = $contract->enddate;
        // $employeename  = $contract->employee;


        // $contract->id = \Auth::user()->ContractNumberFormat($contract->id);

        // $contractId    = \Crypt::encrypt($contract->id);
        // // dd($contractId);
        // // $invoice->url = route('invoice.pdf', $invoiceId);

        //   if(!empty($employee->email))
        //     {
        //         $msg2 = '';
        //         try
        //         {
        //             $send_mail = Mail::to($employee->email)->send(new ContractSend($id,$contract_name,$subject,$value,$startdate,$enddate, $employeename));
        //             dd($send_mail);
        //              // Mail::to($has_user['email'])->send(new SendRotas($rotas_data, $role_datas, $location_datas, $has_user['id'], $date));
        //         }
        //         catch(\Exception $e)
        //         {
        //             dd($e);
        //             $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
        //             $msg2 = __('<br>Email send Successfully.') . (isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : '' ;
        //         }
        //     }

        //     return response()->json([
        //     'status' => 'success',
        //     'message' => __('Mail Send Successfully')
        // ]);


        // $invoiceArr = [
        //     'id' => \Auth::user()->ContractNumberFormat($contract->id);
        //     'invoice_client' => $client->name,
        //     'invoice_issue_date' => \Auth::user()->dateFormat($invoice->issue_date),
        //     'invoice_due_date' => \Auth::user()->dateFormat($invoice->expiry_date),
        //     'invoice_total' => \Auth::user()->priceFormat($invoice->getTotal()),
        //     'invoice_sub_total' => \Auth::user()->priceFormat($invoice->getSubTotal()),
        //     'invoice_due_amount' => \Auth::user()->priceFormat($invoice->getDue()),
        //     'invoice_status' => Invoice::$statues[$invoice->status],
        // ];

        // // Send Email
        // $resp = Utility::sendEmailTemplate('send_invoice', [$client->id => $client->email], $invoiceArr);
    }

    public function copycontrat($id){
        $contract = Contract::find($id);
        $contractTypes = ContractType::where('created_by', '=', \Auth::user()->get_created_by())->get()->pluck('name', 'id');
        $employee       = User::where('type', 'employee')->where('created_by', \Auth::user()->get_created_by())->get()->pluck('first_name', 'id');

        return view('contract.contractcopy', compact('contractTypes', 'employee', 'contract'));
    }


    public function copycontratdata(Request $request, $id)
    {
        $contract                   = new Contract();
        $contract->employee         = $request->employee;
        $contract->contract_name    = $request->contract_name;
        $contract->subject          = $request->subject;
        $contract->type             = $request->type;
        $contract->value            = $request->value;
        $contract->start_date       = $request->start_date;
        $contract->end_date         = $request->end_date;
        $contract->notes             = $request->notes;
        $contract->created_by       = \Auth::user()->get_created_by();
        $contract->save();

        return redirect()->route('contract.index')->with('success', __('Contract successfully updated.'));
    }

    public function contract_preview($id)
    {
        $contract_id = Contract::where('id' , $id)->first();
        $settings = Utility::settings();
        $contract_description = $contract_id->contract_description;
        $logo = asset(\Storage::url('uploads/logo/'));
        $settting = Utility::settings(Auth::user()->get_created_by());
        $logo_path = $logo . '/' . $settting['company_logo'];

        return view('contract.contractpreview', compact('contract_description', 'logo_path','settings','contract_id'));
    }

    public function contract_download($id)
    {
        $contract_id = Contract::where('id' , $id)->first();
        $settings = Utility::settings();
        $contract_description = $contract_id->contract_description;
         $logo = asset(\Storage::url('uploads/logo/'));
            $settting = Utility::settings(Auth::user()->get_created_by());
            $logo_path = $logo . '/' . $settting['company_logo'];

        return view('contract.contract_download', compact('contract_description', 'logo_path','settings','contract_id'));
    }

    public function signture(Request $request,$id)
    {
         if(isset($request->flag)){
            $flag = $request->flag;
        }else{
            $flag = 'false';
        }
         $contract = Contract::find($id);
         return view('contract.signature', compact('contract','flag'));
    }

    public function signture_data(Request $request,$id)
    {
        $contractsign= Contract::find($id);
        if(\Auth::user()->type == 'company'){
            $contractsign->owner_signature  = $request->owner_signature;
        }
        else {
            $contractsign->client_signature  = $request->owner_signature;
        }
        $contractsign->save();
        return redirect()->back()->with('success', __('Contract Description successfully saved.'));
    }

    public function contract_status_edit(Request $request, $id)
    {
        $contract = Contract::find($id);
        $contract->edit_status   = $request->edit_status;
        $contract->save();
    }
}
