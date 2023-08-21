<?php

namespace App\Http\Controllers;

use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebhookController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $method  = Webhook::method();
        $module = Webhook::module();
        return view('webhook.create',compact('module','method'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $validator = \Validator::make(
            $request->all(),
            [
                'module' => 'required|string|max:50',
                'url' => 'required',
                'method' => 'required|string|max:50',
            ]);
        if ($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $webhook             = new Webhook();
        $webhook->module     = $request->module;
        $webhook->url        = $request->url;
        $webhook->method     = $request->method;
        $webhook->created_by = $created_by;
        $webhook->save();

        return redirect()->back()->with('success', __('Webhook successfully created.'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $method  = Webhook::method();
        $module  = Webhook::module();
        $webhook = Webhook::where('created_by',$created_by)->where('id',$id)->first();
        return view('webhook.edit',compact('module','method','webhook'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'module' => 'required|string|max:50',
                'url' => 'required',
                'method' => 'required|string|max:50',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $webhook             = Webhook::find($id);
        $webhook->module     = $request->module;
        $webhook->url        = $request->url;
        $webhook->method     = $request->method;
        $webhook->save();

        return redirect()->back()->with('success', __('Webhook successfully updated.'));
    }

    public function destroy($id)
    {
        $webhook = Webhook::find($id);
        $webhook->delete();

        return redirect()->back()->with('success', __('Webhook successfully deleted.'));
    }

    public function WebhookResponse(Request $request)
    {
        $user = User::where('email',$request['email'])->first();
        if(empty($user))
        {
            User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
        }
        \Log::debug('*******************************************************************************');
        \Log::debug($request->all());
        \Log::debug('*******************************************************************************');
    }
}
