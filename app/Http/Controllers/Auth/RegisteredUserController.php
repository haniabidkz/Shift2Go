<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Utility;
use App\Models\Plan;
use App\Models\Profile;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['required', 'string'],
        ]);

        if(env('RECAPTCHA_MODULE') == 'yes')
        {
            $validation['g-recaptcha-response'] = 'required|captcha';
        }else{
            $validation = [];
        }
        $this->validate($request, $validation);

        if(Utility::getValByName('Email_verify')=='on')
        {
            $user = User::create([
                'first_name' =>$request->first_name,
                'last_name' => $request->last_name,
                'company_name' => $request->company_name,
                'type' => 'company',
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'acount_type' => 1,
                'company_setting' => '{"emp_show_rotas_price":"1","emp_show_avatars_on_rota":0,"emp_only_see_own_rota":0,"emp_can_see_all_locations":0,"company_week_start":"monday","company_time_format":"12","company_date_format":"","company_currency_symbol":"$","company_currency_symbol_position":"pre","see_note":"none","employees_can_set_availability":"1"}',
                'plan' => Plan::first()->id,
                'created_by' => 1,
            ]);

            Auth::login($user);
            try{

                event(new Registered($user));

                $insert_id = $user->id;
                $profile = new Profile();
                $profile->user_id = $insert_id;
                $profile->save();

            }catch(\Exception $e){

                $user->delete();

                return redirect('/register/lang?')->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
            }

            return view('auth.verify-email');
        }
        else
        {
            $user = User::create([
                'first_name' =>$request->first_name,
                'last_name' => $request->last_name,
                'company_name' => $request->company_name,
                'type' => 'company',
                'email' => $request->email,
                'email_verified_at' => date("Y-m-d H:i:s"),
                'password' => Hash::make($request->password),
                'acount_type' => 1,
                'company_setting' => '{"emp_show_rotas_price":"1","emp_show_avatars_on_rota":0,"emp_only_see_own_rota":0,"emp_can_see_all_locations":0,"company_week_start":"monday","company_time_format":"12","company_date_format":"","company_currency_symbol":"$","company_currency_symbol_position":"pre","see_note":"none","employees_can_set_availability":"1"}',
                'plan' => Plan::first()->id,
                'created_by' => 1,
            ]);
            $insert_id = $user->id;

            $profile = new Profile();
            $profile->user_id = $insert_id;
            $profile->save();

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        }


    }

    public function showRegistrationForm($lang = '')
    {
        \App::setLocale($lang);
        return view('auth.register',compact('lang'));
        if(Utility::getValByName('SIGNUP') == 'on'){
            \App::setLocale($lang);
            return view('auth.register',compact('lang'));
        }
        else{
            return abort('404' , 'Page Not Found');
        }
    }
}
