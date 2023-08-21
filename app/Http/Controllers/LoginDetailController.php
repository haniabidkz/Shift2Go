<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoginDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $month = [
            ''   => __('All'),
            '01' => __('JAN'),
            '02' => __('FEB'),
            '03' => __('MAR'),
            '04' => __('APR'),
            '05' => __('MAY'),
            '06' => __('JUN'),
            '07' => __('JUL'),
            '08' => __('AUG'),
            '09' => __('SEP'),
            '10' => __('OCT'),
            '11' => __('NOV'),
            '12' => __('DEC'),
        ];

        $user = User::where('created_by',$created_by)->get()->pluck('first_name','id');
        $user->prepend('All', '');

        $users = \DB::table('login_details')
                ->join('users', 'login_details.user_id', '=', 'users.id')
                ->select(\DB::raw('login_details.*, users.first_name  as user_name ,users.last_name as last_name , users.email as user_email'))
                ->where(['login_details.created_by' => \Auth::user()->id]);
                if(!empty($request->username))
                {
                    $users->where('user_id',$request->username);
                }
                if(!empty($request->month))
                {
                    $users->whereMonth('date',$request->month);
                }
                $users = $users->get();
        return view('user.userLog', compact('users','month','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoginDetail  $loginDetail
     * @return \Illuminate\Http\Response
     */
    public function show(LoginDetail $loginDetail,$id)
    {
        $userlog = LoginDetail::find($id);
        $request = json_decode($userlog->details,true);
        // dd($request);
        return view('user.viewUserLog', compact('userlog','request'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoginDetail  $loginDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(LoginDetail $loginDetail)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoginDetail  $loginDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoginDetail $loginDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoginDetail  $loginDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoginDetail $loginDetail,$id)
    {
        $userlog = LoginDetail::find($id);
        $userlog->delete();
        return redirect()->back()->with('success', 'User Log Deleted Successfully.');
    }
}
