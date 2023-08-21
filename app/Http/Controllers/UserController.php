<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\Profile;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if(\Auth::user()->type == 'super admin')
        {
            $users = User::where('created_by', '=', Auth::id())->where('type', '=', 'company')->get();
            return view('user.index', compact('users'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $default_language = Utility::getValByName('default_language');
        if(\Auth::user()->type == 'super admin')
        {
            $user                       = new User();
            $user['first_name']         = $request->name;
            $user['company_name']       = $request->name;
            $user['email']              = $request->email;
            $user['password']           = Hash::make($request->password);
            $user['type']               = 'company';
            $user['lang']               = !empty($default_language) ? $default_language : 'en';
            $user['created_by']         = $created_by;
            $user['plan']               = 1;
            $user['acount_type']        = 1;
            $user['email_verified_at']  = date("Y-m-d H:i:s");
            $user['company_setting']   = '{"emp_show_rotas_price":"1","emp_show_avatars_on_rota":0,"emp_only_see_own_rota":0,"emp_can_see_all_locations":0,"company_week_start":"monday","company_time_format":"12","company_date_format":"","company_currency_symbol":"$","company_currency_symbol_position":"pre","see_note":"none","employees_can_set_availability":"1"}';
            $user['avatar']='';
            $user->save();

            $profile = new Profile();
            $profile['user_id'] = $user->id;
            $profile->save();
            return redirect()->back()->with('success', __('User successfully created.'));
        }
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $user          = User::find($id);
            $user['company_name']  = $request->company_name;
            $user['email'] = $request->email;
            $user->save();
            return redirect()->back()->with('success', __('User successfully updated.'));
        }
    }

    public function destroy($id)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $user = User::find($id);
            $user->delete();
            return redirect()->back()->with('success', __('User successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function profile()
    {
        $userDetail = \Auth::user();
        return view('user.profile', compact('userDetail'));
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();
        $user = User::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                    ]
        );

        if($user->type == 'client')
        {
            $this->validate(
                $request, [
                            'mobile' => 'required',
                            'address_1' => 'required',
                            'city' => 'required',
                            'state' => 'required',
                            'country' => 'required',
                            'zip_code' => 'required',
                        ]
            );
            $client            = Client::where('user_id', $user->id)->first();
            $client->mobile    = $request->mobile;
            $client->address_1 = $request->address_1;
            $client->address_2 = $request->address_2;
            $client->city      = $request->city;
            $client->state     = $request->state;
            $client->country   = $request->country;
            $client->zip_code  = $request->zip_code;
            $client->save();
        }
        if($request->hasFile('profile'))
        {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $dir        = storage_path('uploads/avatar');
            $image_path = $dir . $userDetail['avatar'];

            if(\File::exists($image_path))
            {
                \File::delete($image_path);
            }

            if(!file_exists($dir))
            {
                mkdir($dir, 0777, true);
            }

            $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);

        }
        if(!empty($request->profile))
        {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        return redirect()->back()->with('success', 'Profile successfully updated.');
    }

    public function updatePassword(Request $request)
    {
        if(\Auth::Check())
        {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            $objUser          = \Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if(Hash::check($request_data['current_password'], $current_password))
            {
                $user_id            = \Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            }
            else
            {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        }
        else
        {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }

    public function upgradePlan($user_id)
    {
        $user = User::find($user_id);
        $plans = Plan::get();
        return view('user.plan', compact('user', 'plans'));
    }

    public function activePlan($user_id, $plan_id)
    {
        $user       = User::find($user_id);
        $assignPlan = $user->assignPlan($plan_id);
        $plan       = Plan::find($plan_id);
        if($assignPlan['is_success'] == true && !empty($plan))
        {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $plan->price,
                    'price_currency' => env('CURRENCY'),
                    'txn_id' => '',
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'payment_type' => __('Manually'),
                    'user_id' => $user->id,
                ]
            );

            return redirect()->back()->with('success', 'Plan successfully upgraded.');
        }
        else
        {
            return redirect()->back()->with('error', 'Plan fail to upgrade.');
        }
    }

    public function clientCompanyInfoEdit(Request $request, $id)
    {
        $this->validate(
            $request, [
                        'company_name' => 'required',
                        'website' => 'required',
                        'tax_number' => 'required',
                    ]
        );
        $client               = Client::where('user_id', $id)->first();
        $client->company_name = $request->company_name;
        $client->website      = $request->website;
        $client->tax_number   = $request->tax_number;
        $client->notes        = $request->notes;
        $client->save();

        return redirect()->back()->with('success', 'Company info successfully updated.');
    }

    public function clientPersonalInfoEdit(Request $request, $id)
    {
        $this->validate(
            $request, [
                        'mobile' => 'required',
                        'address_1' => 'required',
                        'city' => 'required',
                        'state' => 'required',
                        'country' => 'required',
                        'zip_code' => 'required',
                    ]
        );
        $client            = Client::where('user_id', $id)->first();
        $client->mobile    = $request->mobile;
        $client->address_1 = $request->address_1;
        $client->address_2 = $request->address_2;
        $client->city      = $request->city;
        $client->state     = $request->state;
        $client->country   = $request->country;
        $client->zip_code  = $request->zip_code;
        $client->save();

        $user       = User::find($id);
        $user->name = $request->name;
        $user->save();

        return redirect()->back()->with('success', 'Company info successfully updated.');
    }

    // change mode 'dark or light'
    public function changeMode()
    {
        $usr = Auth::user();
        if($usr->mode == 'light')
        {
            $usr->mode      = 'dark';
            $usr->dark_mode = 1;
        }
        else
        {
            $usr->mode      = 'light';
            $usr->dark_mode = 0;
        }
        $usr->save();

        return redirect()->back();
    }

    public function userPassword($id)
    {
        $eId        = \Crypt::decrypt($id);
        $user = User::find($eId);

        $user = User::where('id', $eId)->first();
        return view('user.reset', compact('user', 'user'));
    }

    public function userPasswordReset(Request $request, $id){
        $validator = \Validator::make(
            $request->all(), [
                               'password' => 'required|confirmed|same:password_confirmation',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $user                 = User::where('id', $id)->first();
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('user.index')->with(
                     'success', 'Employee Password successfully updated.'
                 );

    }

}
