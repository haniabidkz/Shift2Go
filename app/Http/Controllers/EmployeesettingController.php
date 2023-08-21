<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Employeesetting;
use App\Models\Location;
use App\Models\Profile;
use App\Models\Settings;
use App\Models\Utility;
use App\Models\User;
use App\Models\Webhook;
use App\Models\IpRestrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\DB;
use File;

use Artisan;

use function GuzzleHttp\json_decode;

class EmployeesettingController extends Controller
{
    public function index()
    {

        $userId = Auth::user()->id;
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $date = date('Y-01-01');

        if(Auth::user()->type != 'super admin')
        {
            $timezones = config('timezones');
            $webhook   = Webhook::where('created_by',$created_by)->get();
            $ips       = IpRestrict::where('created_by', $created_by)->get();
            $profile   = Employee::whereRaw('is_delete = 0')->WhereRaw('id ='. $userId.'')->first();
            $company_setting = [];
            $company_setting['company_currency_symbol_position'] = 'pre';
            if(!empty($profile->company_setting) && Auth::user()->type == 'company')
            {
                $setting = json_decode($profile->company_setting,true);
                $company_setting['emp_show_rotas_price'] = (!empty($setting['emp_show_rotas_price'])) ? $setting['emp_show_rotas_price'] : 0 ;
                $company_setting['emp_hide_rotas_hour']  = (!empty($setting['emp_hide_rotas_hour'])) ? $setting['emp_hide_rotas_hour'] : 0 ;
                $company_setting['include_unpublished_shifts']  = (!empty($setting['include_unpublished_shifts'])) ? $setting['include_unpublished_shifts'] : 0 ;
                $company_setting['emp_show_avatars_on_rota'] = (!empty($setting['emp_show_avatars_on_rota'])) ? $setting['emp_show_avatars_on_rota'] : 0 ;
                $company_setting['emp_only_see_own_rota'] = (!empty($setting['emp_only_see_own_rota'])) ? $setting['emp_only_see_own_rota'] : 0 ;
                $company_setting['emp_can_see_all_locations'] = (!empty($setting['emp_can_see_all_locations'])) ? $setting['emp_can_see_all_locations'] : 0 ;
                $company_setting['company_week_start'] = (!empty($setting['company_week_start'])) ? $setting['company_week_start'] : null ;
                $company_setting['company_time_format'] = (!empty($setting['company_time_format'])) ? $setting['company_time_format'] : null ;
                $company_setting['company_date_format'] = (!empty($setting['company_date_format'])) ? $setting['company_date_format'] : 'Y-m-d' ;
                $company_setting['company_currency_symbol'] = (!empty($setting['company_currency_symbol'])) ? $setting['company_currency_symbol'] : '$' ;
                $company_setting['company_currency_symbol_position'] = (!empty($setting['company_currency_symbol_position'])) ? $setting['company_currency_symbol_position'] : 'pre' ;
                $company_setting['leave_start_month'] = (!empty($setting['leave_start_month'])) ? $setting['leave_start_month'] : 1 ;
                $company_setting['break_paid'] = (!empty($setting['break_paid'])) ? $setting['break_paid'] : 'paid' ;
                $company_setting['see_note'] = (!empty($setting['see_note'])) ? $setting['see_note'] : null ;
                $company_setting['enable_availability'] = (!empty($setting['enable_availability'])) ? $setting['enable_availability'] : 0 ;
                $company_setting['employees_can_set_availability'] = (!empty($setting['employees_can_set_availability'])) ? $setting['employees_can_set_availability'] : 0 ;
            }
            $settings = Utility::settings();

            return view('employeesetting.index',compact('profile','company_setting','settings','timezones','ips','webhook'));
        }
        else
        {
            $profile = Employee::whereRaw('is_delete = 0')->WhereRaw('id ='. $userId.'')->first();
            $settings = Utility::settings();
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            return view('employeesetting.superadminsetting',compact('profile','settings','admin_payment_setting'));
        }
    }

    public function update(Request $request, Employeesetting $employeesetting, $id)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $company_setting = Employee::find($id);
        if($request->form_type == 'rotas_setting'  && Auth::user()->type == 'company')
        {
            $company_setting = User::find(Auth::user()->id);
            $setting['emp_show_rotas_price'] = (!empty($request->emp_show_rotas_price)) ? $request->emp_show_rotas_price : 0 ;
            $setting['emp_hide_rotas_hour'] = (!empty($request->emp_hide_rotas_hour)) ? $request->emp_hide_rotas_hour : 0 ;
            $setting['include_unpublished_shifts'] = (!empty($request->include_unpublished_shifts)) ? $request->include_unpublished_shifts : 0 ;
            $setting['emp_show_avatars_on_rota'] = (!empty($request->emp_show_avatars_on_rota)) ? $request->emp_show_avatars_on_rota : 0 ;
            $setting['emp_only_see_own_rota'] = (!empty($request->emp_only_see_own_rota)) ? $request->emp_only_see_own_rota : 0 ;
            $setting['emp_can_see_all_locations'] = (!empty($request->emp_can_see_all_locations)) ? $request->emp_can_see_all_locations : 0 ;
            $setting['company_week_start'] = (!empty($request->company_week_start)) ? $request->company_week_start : '' ;
            $setting['company_time_format'] = (!empty($request->company_time_format)) ? $request->company_time_format : '' ;
            $setting['company_date_format'] = (!empty($request->company_date_format)) ? $request->company_date_format : 'Y-m-d' ;
            $setting['company_currency_symbol'] = (!empty($request->company_currency_symbol)) ? $request->company_currency_symbol : '$' ;
            $setting['company_currency_symbol_position'] = (!empty($request->company_currency_symbol_position)) ? $request->company_currency_symbol_position : 'pre' ;
            $setting['leave_start_month'] = (!empty($request->leave_start_month)) ? $request->leave_start_month : 01 ;
            $setting['break_paid'] = (!empty($request->break_paid)) ? $request->break_paid : 'paid' ;
            $setting['see_note'] = (!empty($request->see_note)) ? $request->see_note : '' ;
            $setting['employees_can_set_availability'] = (!empty($request->employees_can_set_availability)) ? $request->employees_can_set_availability : 0 ;
            if(!(empty($setting)))
            {
                $company_setting->company_setting = json_encode($setting);
            }
            $company_setting->save();

            return redirect()->back()->with('success', __('Setting Update Successfully'));
        }
        if(Auth::user()->type == 'super admin')
        {
            if($request->favicon)
            {
                $request->validate(
                    [
                        'favicon' => 'image|mimes:png|max:20480',
                    ]
                );

                 $validation =[
                    'mimes:'.'png',
                    'max:'.'20480',
                ];

                $favicon = 'favicon.png';


                $dir = 'uploads/logo/';


                $path = Utility::upload_file($request,'favicon',$favicon,$dir,$validation);
                if($path['flag'] == 1){
                    $favicon = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }

                return redirect()->back()->with('success', 'setting successfully updated.');


            }
            if($request->dark_logo)
            {
                // dd($request->dark_logo);
                $request->validate(
                    [
                        'dark_logo' => 'image|mimes:png|max:20480',
                    ]
                );

                 $validation =[
                    'mimes:'.'png',
                    'max:'.'20480',
                ];

                $logoName = 'logo-dark.png';
                $dir = 'uploads/logo/';


                $path = Utility::upload_file($request,'dark_logo',$logoName,$dir,$validation);
                if($path['flag'] == 1){
                    $dark_logo = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
                return redirect()->back()->with('success', 'setting successfully updated.');
            }
            if($request->light_logo)
            {
                $request->validate(
                    [
                        'light_logo' => 'image|mimes:png|max:20480',
                    ]
                );

                  $validation =[
                    'mimes:'.'png',
                    'max:'.'20480',
                ];

                $lightlogoName = 'logo-light.png';

                $dir = 'uploads/logo/';

                $path = Utility::upload_file($request,'light_logo',$lightlogoName,$dir,$validation);
                if($path['flag'] == 1){
                    $light_logo = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
                return redirect()->back()->with('success', 'setting successfully updated.');


                // $path     = $request->file('logo_light')->storeAs('uploads/logo/', $lightlogoName);
            }

            // $arrEnv = [
            //     'SITE_RTL' => !isset($request->SITE_RTL) ? 'off' : 'on',
            //     'THEME_COLOR' => $request->color,
            // ];

            $request->user = Auth::user()->id;

            // Artisan::call('config:cache');
            // Artisan::call('config:clear');

            // Utility::setEnvironmentValue($arrEnv);

            if (!empty($request->title_text) || !empty($request->footer_text) || !empty($request->default_language) || !empty($request->gdpr_cookie))
            {
                $post = $request->all();

                $SITE_RTL = $request->has('SITE_RTL') ? $request-> SITE_RTL : 'off';
                $post['SITE_RTL'] = $SITE_RTL;
                unset($post['_token'], $post['dark_logo'], $post['light_logo'], $post['favicon']);

                $post['gdpr_cookie'] = (!empty($post['gdpr_cookie'])) ? 'on' : 'off' ;
                if($post['gdpr_cookie'] == 'off')
                {
                    $post['cookie_text'] = '';
                }

                if(!isset($request->display_landing_page))
                {
                    $post['display_landing_page'] = 'off';
                }

                if(!isset($request->SIGNUP)){
                    $post['SIGNUP'] = 'off';
                }

                if(!isset($request->Email_verify)){
                    $post['Email_verify'] = 'off';
                }

                $post['color']             = $request->has('color') ? $request->color : 'theme-3';
                $post['cust_theme_bg']     = (!empty($request->cust_theme_bg)) ? 'on' : 'off';
                $post['cust_darklayout']   = (!empty($request->cust_darklayout)) ? 'on' : 'off';

                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                        $data,
                                                                                                                                                        $key,
                                                                                                                                                        $request->user,
                                                                                                                                                    ]
                    );
                }
                return redirect()->back()->with('success', 'setting successfully updated.');
            }
        }
        else if(\Auth::user()->type == 'company')
        {
            if($request->company_logo)
            {
                $request->validate( [ 'company_logo' => 'image|mimes:png' ]);

                $validation =[
                    'mimes:'.'png',
                    'max:'.'20480',
                ];
                $image_size = $request->file('company_logo')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->id, $image_size);

                if($result==1)
                {
                    $logoName     = $user->id . '-logo-light.png';
                    $dir = 'uploads/logo/';
                    $file_path = $dir.$logoName;
                    Utility::changeStorageLimit(\Auth::user()->id, $file_path);
                    $path = Utility::upload_file($request,'company_logo',$logoName,$dir,$validation);
                }

                if($path['flag'] == 1){
                    $light_logo = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY
                    UPDATE `value` = VALUES(`value`) ', [ $logoName, 'company_logo', $created_by, ]
                );

                return redirect()->back()->with('success', __('Setting Update Successfully'));
            }

            if($request->company_dark_logo)
            {
                $request->validate(['company_dark_logo' => 'required|image|mimes:png|max:1024']);

                $validation =[
                    'mimes:'.'png',
                    'max:'.'20480',
                ];
                $image_size = $request->file('company_dark_logo')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->id, $image_size);

                if($result==1)
                {
                    $logoName     = $user->id . '-logo-dark.png';
                    $dir = 'uploads/logo/';
                    $file_path = $dir.$logoName;
                    Utility::changeStorageLimit(\Auth::user()->id, $file_path);
                    $path = Utility::upload_file($request,'company_dark_logo',$logoName,$dir,$validation);
                }

                if($path['flag'] == 1){
                    $company_dark_logo = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY
                    UPDATE `value` = VALUES(`value`) ', [ $logoName, 'company_dark_logo', $created_by, ]
                );

                return redirect()->back()->with('success', __('Setting Update Successfully'));
            }

            if($request->company_favicon)
            {
                $request->validate(
                    [
                        'company_favicon' => 'image|mimes:png|max:20480',
                    ]
                );

                 $validation =[
                    'mimes:'.'png',
                    'max:'.'20480',
                ];
                $image_size = $request->file('company_favicon')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->id, $image_size);
                if($result==1)
                {
                    $company_favicon = $user->id . '-favicon.png';
                    $dir = 'uploads/logo/';
                    $file_path = $dir.$company_favicon;
                    Utility::changeStorageLimit(\Auth::user()->id, $file_path);
                    $path = Utility::upload_file($request,'company_favicon',$company_favicon,$dir,$validation);
                }

                if($path['flag'] == 1){

                    $company_favicon_icon = $path['url'];
                }else{

                    return redirect()->back()->with('error', __($path['msg']));
                }

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $company_favicon,
                                                                                                                                                 'company_favicon',
                                                                                                                                                 $created_by,
                                                                                                                                             ]
                );
                return redirect()->back()->with('success', __('Setting Update Successfully'));
            }

            $post = $request->all();
            $post['color']             = $request->has('color') ? $request->color : 'theme-3';
            $post['cust_theme_bg']     = (!empty($request->cust_theme_bg)) ? 'on' : 'off';
            $post['cust_darklayout']   = (!empty($request->cust_darklayout)) ? 'on' : 'off';

            $SITE_RTL = $request->has('SITE_RTL') ? $request-> SITE_RTL : 'off';
            $post['SITE_RTL'] = $SITE_RTL;

            unset($post['_token'], $post['dark_logo'], $post['light_logo'], $post['company_favicon'] ,$post['company_logo'], $post['company_dark_logo'], $post['favicon']);
            if($post != '')
            {
                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                        $data,
                                                                                                                                                        $key,
                                                                                                                                                        $created_by,
                                                                                                                                                    ]
                    );
                }
                return redirect()->back()->with('success', __('Setting Update Successfully'));
            }
            else
            {
                return redirect()->back()->with('error', __('Setting Not Update Successfully'));
            }

            if($request->form_type == 'rotas_setting'  && Auth::user()->type == 'company')
            {
                $company_setting = User::find(Auth::user()->id);
                $setting['emp_show_rotas_price'] = (!empty($request->emp_show_rotas_price)) ? $request->emp_show_rotas_price : 0 ;
                $setting['emp_hide_rotas_hour'] = (!empty($request->emp_hide_rotas_hour)) ? $request->emp_hide_rotas_hour : 0 ;
                $setting['include_unpublished_shifts'] = (!empty($request->include_unpublished_shifts)) ? $request->include_unpublished_shifts : 0 ;
                $setting['emp_show_avatars_on_rota'] = (!empty($request->emp_show_avatars_on_rota)) ? $request->emp_show_avatars_on_rota : 0 ;
                $setting['emp_only_see_own_rota'] = (!empty($request->emp_only_see_own_rota)) ? $request->emp_only_see_own_rota : 0 ;
                $setting['emp_can_see_all_locations'] = (!empty($request->emp_can_see_all_locations)) ? $request->emp_can_see_all_locations : 0 ;
                $setting['company_week_start'] = (!empty($request->company_week_start)) ? $request->company_week_start : '' ;
                $setting['company_time_format'] = (!empty($request->company_time_format)) ? $request->company_time_format : '' ;
                $setting['company_date_format'] = (!empty($request->company_date_format)) ? $request->company_date_format : 'Y-m-d' ;
                $setting['company_currency_symbol'] = (!empty($request->company_currency_symbol)) ? $request->company_currency_symbol : '$' ;
                $setting['company_currency_symbol_position'] = (!empty($request->company_currency_symbol_position)) ? $request->company_currency_symbol_position : 'pre' ;
                $setting['leave_start_month'] = (!empty($request->leave_start_month)) ? $request->leave_start_month : 01 ;
                $setting['break_paid'] = (!empty($request->break_paid)) ? $request->break_paid : 'paid' ;
                $setting['see_note'] = (!empty($request->see_note)) ? $request->see_note : '' ;
                $setting['employees_can_set_availability'] = (!empty($request->employees_can_set_availability)) ? $request->employees_can_set_availability : 0 ;
                if(!(empty($setting)))
                {
                    $company_setting->company_setting = json_encode($setting);
                }
                $company_setting->save();

                return redirect()->back()->with('success', __('Setting Update Successfully'));
            }
            else
            {
                return redirect()->back()->with('Error', __('Permission denied'));
            }
        }
    }

    public function saveEmailSettings(Request $request, Employeesetting $employeesetting)
    {
        if(Auth::user()->type == 'company' || Auth::user()->type == 'super admin')
        {
            $request->validate(
                [
                    'mail_driver' => 'required|string|max:50',
                    'mail_host' => 'required|string|max:50',
                    'mail_port' => 'required|string|max:50',
                    'mail_username' => 'required|string|max:50',
                    'mail_password' => 'required|string|max:50',
                    'mail_encryption' => 'required|string|max:50',
                    'mail_from_address' => 'required|string|max:50',
                    'mail_from_name' => 'required|string|max:50',
                ]
            );
            $arrEnv = [
                'MAIL_DRIVER' => $request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_NAME' => $request->mail_from_name,
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            ];

            $env = Utility::setEnvironmentValue($arrEnv);

            // Artisan::call('cache:clear');
            // Artisan::call('config:cache');

            if($env)
            {

                return redirect()->back()->with('success', __('Setting successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Employeesetting $employeesetting)
    {
        //
    }

    public function testMail(Request $request)
    {
        $user = \Auth::user();

        $data                      = [];
        $data['mail_driver']       = $request->mail_driver;
        $data['mail_host']         = $request->mail_host;
        $data['mail_port']         = $request->mail_port;
        $data['mail_username']     = $request->mail_username;
        $data['mail_password']     = $request->mail_password;
        $data['mail_encryption']   = $request->mail_encryption;
        $data['mail_from_address'] = $request->mail_from_address;
        $data['mail_from_name']    = $request->mail_from_name;

        return view('employeesetting.test_mail', compact('data'));
    }


    public function testSendMail(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'email' => 'required|email',
                               'mail_driver' => 'required',
                               'mail_host' => 'required',
                               'mail_port' => 'required',
                               'mail_username' => 'required',
                               'mail_password' => 'required',
                               'mail_from_address' => 'required',
                               'mail_from_name' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return response()->json(
                [
                    'is_success' => false,
                    'message' =>$messages->first(),
                ]
            );
        }

        try
        {
            config(
                [
                    'mail.driver' => $request->mail_driver,
                    'mail.host' => $request->mail_host,
                    'mail.port' => $request->mail_port,
                    'mail.encryption' => $request->mail_encryption,
                    'mail.username' => $request->mail_username,
                    'mail.password' => $request->mail_password,
                    'mail.from.address' => $request->mail_from_address,
                    'mail.from.name' => $request->mail_from_name,
                ]
            );
            Mail::to($request->email)->send(new TestMail());
        }
        catch(\Exception $e)
        {
            return response()->json(
                [
                    'is_success' => false,
                    'message' => $e->getMessage(),
                ]
            );
        }

        return response()->json(
            [
                'is_success' => true,
                'message' => __('Email send Successfully'),
            ]
        );
    }

    public function saveCompanySettings(Request $request)
    {
    //    dd($request->all());
        if(\Auth::user()->type == 'company')
        {
             $request->validate(
                [
                    'company_email' => 'required',
                    'company_email_from_name' => 'required|string',
                    'timezone' => 'required',
                ]
            );

            $post = $request->all();
            unset($post['_token']);

            if (!isset($request->ip_restrict)) {
                $post['ip_restrict'] = 'off';
            }

            $settings = Utility::settings();

                foreach($post as $key => $data)
                {
                    if(in_array($key, array_keys($settings)))
                    {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                        $data,
                                        $key,
                                        \Auth::user()->get_created_by(),
                                ]
                        );
                    }
                }
                $arrEnv = [
                    'TIMEZONE' => $request->timezone,
                ];
                Utility::setEnvironmentValue($arrEnv);
                return redirect()->back()->with('success', __('Setting Update Successfully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function savePaymentSettings(Request $request)
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        $created_by = $user->get_created_by();
        if(Auth::user()->type == 'super admin')
        {
            $request->validate(
                [
                    'currency' => 'required|string|max:255',
                    'currency_symbol' => 'required|string|max:255',
                ]
            );
            $arrEnv = [
                'CURRENCY_SYMBOL' => $request->currency_symbol,
                'CURRENCY' => $request->currency
            ];
            $request->user = \Auth::user()->id;
            Utility::setEnvironmentValue($arrEnv);
            $post = $request->all();
            unset($post['_token']);
            foreach($post as $key => $data)
            {
                \DB::insert(
                    'insert into admin_payment_settings (`value`, `name`,`created_by`,`created_at`,`updated_at`)
                    values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                            $data,
                                                            $key,
                                                            $request->user,
                                                            date('Y-m-d H:i:s'),
                                                            date('Y-m-d H:i:s'),
                                                                                ]
                );
            }
            self::adminPaymentSettings($request);


            return redirect()->back()->with('success', __('Payment setting successfully saved.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function adminPaymentSettings($request)
    {

        $request->validate(
            [
                'CURRENCY_SYMBOL' => 'required',
                'CURRENCY' =>'required',
            ]
        );
        $post = $request->all();
        unset($post['_token']);

        if(isset($request->is_manul_enabled) && $request->is_manul_enabled == 'on')
        {
            $post['is_manul_enabled'] = $request->is_manul_enabled;
        }
        else
        {
            $post['is_manul_enabled'] = 'off';
        }

        if(isset($request->is_bank_enabled) && $request->is_bank_enabled == 'on')
        {
            $post['is_bank_enabled'] = $request->is_bank_enabled;
            $request->validate([
                'bank_details' =>'required',
            ]);
            $post['bank_details'] = $request->bank_details;
        }
        else
        {
            $post['is_bank_enabled'] = 'off';
        }

        if(isset($request->is_stripe_enabled) && $request->is_stripe_enabled == 'on')
        {
            $post['is_stripe_enabled']     = $request->is_stripe_enabled;
            $post['stripe_secret']         = $request->stripe_secret;
            $post['stripe_key']            = $request->stripe_key;
        }
        else
        {
            $post['is_stripe_enabled'] = 'off';
        }

        if(isset($request->is_paypal_enabled) && $request->is_paypal_enabled == 'on')
        {
            $request->validate(
                [
                    'paypal_mode' => 'required|string',
                    'paypal_client_id' => 'required|string',
                    'paypal_secret_key' => 'required|string',
                ]
            );

            $post['is_paypal_enabled'] = $request->is_paypal_enabled;
            $post['paypal_mode']       = $request->paypal_mode;
            $post['paypal_client_id']  = $request->paypal_client_id;
            $post['paypal_secret_key'] = $request->paypal_secret_key;
        }
        else
        {
            $post['is_paypal_enabled'] = 'off';
        }

        if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
        {
            $request->validate(
                [
                    'paystack_public_key' => 'required|string',
                    'paystack_secret_key' => 'required|string',
                ]
            );
            $post['is_paystack_enabled'] = $request->is_paystack_enabled;
            $post['paystack_public_key'] = $request->paystack_public_key;
            $post['paystack_secret_key'] = $request->paystack_secret_key;
        }
        else
        {
            $post['is_paystack_enabled'] = 'off';
        }

        if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
        {
            $request->validate(
                [
                    'flutterwave_public_key' => 'required|string',
                    'flutterwave_secret_key' => 'required|string',
                ]
            );
            $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
            $post['flutterwave_public_key'] = $request->flutterwave_public_key;
            $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
        }
        else
        {
            $post['is_flutterwave_enabled'] = 'off';
        }
        if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
        {
            $request->validate(
                [
                    'razorpay_public_key' => 'required|string',
                    'razorpay_secret_key' => 'required|string',
                ]
            );
            $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
            $post['razorpay_public_key'] = $request->razorpay_public_key;
            $post['razorpay_secret_key'] = $request->razorpay_secret_key;
        }
        else
        {
            $post['is_razorpay_enabled'] = 'off';
        }

        if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
        {
            $request->validate(
                [
                    'mercado_access_token' => 'required|string',
                ]
            );
            $post['is_mercado_enabled'] = $request->is_mercado_enabled;
            $post['mercado_access_token']     = $request->mercado_access_token;
            $post['mercado_mode'] = $request->mercado_mode;
        }
        else
        {
            $post['is_mercado_enabled'] = 'off';
        }

        if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
        {
            $request->validate(
                [
                    'paytm_mode' => 'required',
                    'paytm_merchant_id' => 'required|string',
                    'paytm_merchant_key' => 'required|string',
                    'paytm_industry_type' => 'required|string',
                ]
            );
            $post['is_paytm_enabled']    = $request->is_paytm_enabled;
            $post['paytm_mode']          = $request->paytm_mode;
            $post['paytm_merchant_id']   = $request->paytm_merchant_id;
            $post['paytm_merchant_key']  = $request->paytm_merchant_key;
            $post['paytm_industry_type'] = $request->paytm_industry_type;
        }
        else
        {
            $post['is_paytm_enabled'] = 'off';
        }
        if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
        {
            $request->validate(
                [
                    'mollie_api_key' => 'required|string',
                    'mollie_profile_id' => 'required|string',
                    'mollie_partner_id' => 'required',
                ]
            );
            $post['is_mollie_enabled'] = $request->is_mollie_enabled;
            $post['mollie_api_key']    = $request->mollie_api_key;
            $post['mollie_profile_id'] = $request->mollie_profile_id;
            $post['mollie_partner_id'] = $request->mollie_partner_id;
        }
        else
        {
            $post['is_mollie_enabled'] = 'off';
        }

        if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
        {
            $request->validate(
                [
                    'skrill_email' => 'required|email',
                ]
            );
            $post['is_skrill_enabled'] = $request->is_skrill_enabled;
            $post['skrill_email']      = $request->skrill_email;
        }
        else
        {
            $post['is_skrill_enabled'] = 'off';
        }

        if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
        {
            $request->validate(
                [
                    'coingate_mode' => 'required|string',
                    'coingate_auth_token' => 'required|string',
                ]
            );

            $post['is_coingate_enabled'] = $request->is_coingate_enabled;
            $post['coingate_mode']       = $request->coingate_mode;
            $post['coingate_auth_token'] = $request->coingate_auth_token;
        }
        else
        {
            $post['is_coingate_enabled'] = 'off';
        }

        if(isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on')
        {

            $request->validate(
                [
                    'paymentwall_public_key' => 'required|string',
                    'paymentwall_private_key' => 'required|string',
                ]
            );
            $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
            $post['paymentwall_public_key'] = $request->paymentwall_public_key;
            $post['paymentwall_private_key'] = $request->paymentwall_private_key;
        }
        else
        {
            $post['is_paymentwall_enabled'] = 'off';
        }

        if(isset($request->is_toyyibpay_enabled) && $request->is_toyyibpay_enabled == 'on')
        {
            $request->validate(
                [
                    'toyyibpay_secret_key' => 'required|string',
                    'toyyibpay_category_code' => 'required|string',
                ]
            );
            $post['is_toyyibpay_enabled'] = $request->is_toyyibpay_enabled;
            $post['toyyibpay_secret_key'] = $request->toyyibpay_secret_key;
            $post['toyyibpay_category_code'] = $request->toyyibpay_category_code;
        }
        else
        {
            $post['is_toyyibpay_enabled'] = 'off';
        }
        if(isset($request->is_payfast_enabled) && $request->is_payfast_enabled == 'on')
        {
            $request->validate(
                [
                    'payfast_mode' => 'required',
                    'payfast_merchant_id' => 'required|string',
                    'payfast_merchant_key' => 'required|string',
                    'payfast_salt_passphrase' => 'required',
                ]
            );
            $post['is_payfast_enabled'] = $request->is_payfast_enabled;
            $post['payfast_mode']          = $request->paytm_mode;
            $post['payfast_merchant_id']    = $request->payfast_merchant_id;
            $post['payfast_merchant_key'] = $request->payfast_merchant_key;
            $post['payfast_salt_passphrase'] = $request->payfast_salt_passphrase;
        }
        else
        {
            $post['is_payfast_enabled'] = 'off';
        }

        if(isset($request->is_Iyzipay_enabled) && $request->is_Iyzipay_enabled == 'on')
        {
            $request->validate(
                [
                    'Iyzipay_secret_key' => 'required|string',
                    'iyzipay_code' => 'required|string',
                ]
            );
            $post['iyzipay_mode']          = $request->iyzipay_mode;
            $post['is_Iyzipay_enabled'] = $request->is_Iyzipay_enabled;
            $post['Iyzipay_secret_key']    = $request->Iyzipay_secret_key;
            $post['iyzipay_code'] = $request->iyzipay_code;
        }
        else
        {
            $post['is_Iyzipay_enabled'] = 'off';
        }

        if(isset($request->is_sspay_enabled) && $request->is_sspay_enabled == 'on')
        {
            $request->validate(
                [
                    'sspay_secret_key' => 'required|string',
                    'sspay_category_code' => 'required|string',
                ]
            );
            $post['is_sspay_enabled'] = $request->is_sspay_enabled;
            $post['sspay_secret_key']    = $request->sspay_secret_key;
            $post['sspay_category_code'] = $request->sspay_category_code;
        }
        else
        {
            $post['is_sspay_enabled'] = 'off';
        }

        foreach($post as $key => $data)
        {
            $arr = [
                $data,
                $key,
                Auth::user()->id,
            ];
            \DB::insert(
                'insert into admin_payment_settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', $arr
            );

        }
    }

    public function pusherSetting(Request $request)
    {
        $ENABLE_CHAT = (!empty($request->enable_chat) && $request->enable_chat == 'on') ? 'on' : 'off';
        $PUSHER_APP_ID = $request->pusher_app_id;
        $PUSHER_APP_KEY = $request->pusher_app_key;
        $PUSHER_APP_SECRET = $request->pusher_app_secret;
        $PUSHER_APP_CLUSTER = $request->pusher_app_cluster;

        if($ENABLE_CHAT == 'on')
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'pusher_app_id' => 'required|string',
                    'pusher_app_key' => 'required|string',
                    'pusher_app_secret' => 'required|string',
                    'pusher_app_cluster' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $ENABLE_CHAT = (!empty($request->enable_chat) && $request->enable_chat == 'on') ? 'on' : 'off';
            $PUSHER_APP_ID = $request->pusher_app_id;
            $PUSHER_APP_KEY = $request->pusher_app_key;
            $PUSHER_APP_SECRET = $request->pusher_app_secret;
            $PUSHER_APP_CLUSTER = $request->pusher_app_cluster;

            $arrEnv = [
                'ENABLE_CHAT' => $ENABLE_CHAT,
                'PUSHER_APP_ID' => $PUSHER_APP_ID,
                'PUSHER_APP_KEY' => $PUSHER_APP_KEY,
                'PUSHER_APP_SECRET' => $PUSHER_APP_SECRET,
                'PUSHER_APP_CLUSTER' => $PUSHER_APP_CLUSTER,
            ];

            Utility::setEnvironmentValue($arrEnv);
        }
        else{
            $arrEnv = [
                'ENABLE_CHAT' => 'off',
                'PUSHER_APP_ID' => '',
                'PUSHER_APP_KEY' => '',
                'PUSHER_APP_SECRET' => '',
                'PUSHER_APP_CLUSTER' => '',
            ];

            Artisan::call('config:cache');
            Artisan::call('config:clear');

            Utility::setEnvironmentValue($arrEnv);
        }
        return redirect()->back()->with('success', __('Setting successfully updated.'));
    }

    public function saveZoomSettings(Request $request)
    {
        $post = $request->all();
        unset($post['_token']);
        $created_by = Auth::user()->get_created_by();
        foreach($post as $key => $data)
        {
            DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                $data,
                                                                                                                                                                                $key,
                                                                                                                                                                                $created_by,
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                            ]
            );
        }
        return redirect()->back()->with('success', __('Setting added successfully saved.'));
    }

    public function slack(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'slack_webhook' => 'required'
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $post = [];
        $post['slack_webhook'] = $request->input('slack_webhook');
        $post['rotas_notification'] = $request->has('rotas_notification')?$request->input('rotas_notification'):0;
        $post['rotas_cancle_notificaation'] = $request->has('rotas_cancle_notificaation')?$request->input('rotas_cancle_notificaation'):0;
        $post['rotas_time_change_notificaation'] = $request->has('rotas_time_change_notificaation')?$request->input('rotas_time_change_notificaation'):0;
        $post['days_off_notificaation'] = $request->has('days_off_notificaation')?$request->input('days_off_notificaation'):0;
        $post['availability_create_notificaation'] = $request->has('availability_create_notificaation')?$request->input('availability_create_notificaation'):0;
        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [                                                                                                                                                                                                                    $data,                                                                                                                                                                                                                    $key,                                                                                                                                                                                                                     Auth::user()->id,
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }

    public function telegram(Request $request)
    {
        return redirect()->back()->with('error', __('This operation is not perform due to demo mode.'));

        $validator = \Validator::make(
            $request->all(),
            [
                'telegrambot' => 'required',
                'telegramchatid' => 'required'
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $post = [];
        $post['telegrambot'] = $request->input('telegrambot');
        $post['telegramchatid'] = $request->input('telegramchatid');
        $post['telegram_rotas_notification'] = $request->has('telegram_rotas_notification')?$request->input('telegram_rotas_notification'):0;
        $post['telegram_rotas_cancle_notificaation'] = $request->has('telegram_rotas_cancle_notificaation')?$request->input('telegram_rotas_cancle_notificaation'):0;
        $post['telegram_rotas_time_change_notificaation'] = $request->has('telegram_rotas_time_change_notificaation')?$request->input('telegram_rotas_time_change_notificaation'):0;
        $post['telegram_days_off_notificaation'] = $request->has('telegram_days_off_notificaation')?$request->input('telegram_days_off_notificaation'):0;
        $post['telegram_availability_create_notificaation'] = $request->has('telegram_availability_create_notificaation')?$request->input('telegram_availability_create_notificaation'):0;

        $created_by = Auth::user()->id;
        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');
            foreach($post as $key => $data)
            {
                \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                $data,
                                                                                                                                                                                $key,
                                                                                                                                                                                $created_by,
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                            ]
            );
            }
        }
        return redirect()->back()->with('success', __('Setting added successfully saved.'));
    }

    public function recaptchaSettingStore(Request $request)
    {
        $user = \Auth::user();
        $rules = [];
        if($request->recaptcha_module == 'yes')
        {
            $rules['google_recaptcha_key'] = 'required|string|max:50';
            $rules['google_recaptcha_secret'] = 'required|string|max:50';
        }
        $validator = \Validator::make(
            $request->all(), $rules
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $arrEnv = [
            'RECAPTCHA_MODULE' => $request->recaptcha_module ?? 'no',
            'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_key,
            'NOCAPTCHA_SECRET' => $request->google_recaptcha_secret,
        ];
        if(Utility::setEnvironmentValue($arrEnv))
        {
            return redirect()->back()->with('success', __('Recaptcha Settings updated successfully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function storageSettingStore(Request $request)
    {
        if(isset($request->storage_setting) && $request->storage_setting == 'local')
        {
            $request->validate(
                [

                    'local_storage_validation' => 'required',
                    'local_storage_max_upload_size' => 'required',
                ]
            );
            $post['storage_setting'] = $request->storage_setting;
            $local_storage_validation = implode(',', $request->local_storage_validation);
            $post['local_storage_validation'] = $local_storage_validation;
            $post['local_storage_max_upload_size'] = $request->local_storage_max_upload_size;
        }

        if(isset($request->storage_setting) && $request->storage_setting == 's3')
        {
            $request->validate(
                [
                    's3_key'                  => 'required',
                    's3_secret'               => 'required',
                    's3_region'               => 'required',
                    's3_bucket'               => 'required',
                    's3_url'                  => 'required',
                    's3_endpoint'             => 'required',
                    's3_max_upload_size'      => 'required',
                    's3_storage_validation'   => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['s3_key']                     = $request->s3_key;
            $post['s3_secret']                  = $request->s3_secret;
            $post['s3_region']                  = $request->s3_region;
            $post['s3_bucket']                  = $request->s3_bucket;
            $post['s3_url']                     = $request->s3_url;
            $post['s3_endpoint']                = $request->s3_endpoint;
            $post['s3_max_upload_size']         = $request->s3_max_upload_size;
            $s3_storage_validation              = implode(',', $request->s3_storage_validation);
            $post['s3_storage_validation']      = $s3_storage_validation;
        }

        if(isset($request->storage_setting) && $request->storage_setting == 'wasabi')
        {
            $request->validate(
                [
                    'wasabi_key'                    => 'required',
                    'wasabi_secret'                 => 'required',
                    'wasabi_region'                 => 'required',
                    'wasabi_bucket'                 => 'required',
                    'wasabi_url'                    => 'required',
                    'wasabi_root'                   => 'required',
                    'wasabi_max_upload_size'        => 'required',
                    'wasabi_storage_validation'     => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['wasabi_key']                 = $request->wasabi_key;
            $post['wasabi_secret']              = $request->wasabi_secret;
            $post['wasabi_region']              = $request->wasabi_region;
            $post['wasabi_bucket']              = $request->wasabi_bucket;
            $post['wasabi_url']                 = $request->wasabi_url;
            $post['wasabi_root']                = $request->wasabi_root;
            $post['wasabi_max_upload_size']     = $request->wasabi_max_upload_size;
            $wasabi_storage_validation          = implode(',', $request->wasabi_storage_validation);
            $post['wasabi_storage_validation']  = $wasabi_storage_validation;
        }

        foreach($post as $key => $data)
        {
            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', $arr
            );
        }
        return redirect()->back()->with('success', 'Storage setting successfully updated.');
    }

    public function saveGoogleCalendaSetting(Request $request)
    {
        $Google_Calendar = (!empty($request->Google_Calendar) && $request->Google_Calendar == 'on') ? 'on' : 'off';

        $post['Google_Calendar'] = $Google_Calendar;

            if ($request->google_calender_json_file)
            {
                $dir       = storage_path() . '/' .md5(time());
                if (!is_dir($dir)) {
                    File::makeDirectory($dir, $mode = 0777, true, true);
                }
                $file_name = $request->google_calender_json_file->getClientOriginalName();
                $file_path =  md5(time()) . '/' . md5(time()) .".". $request->google_calender_json_file->getClientOriginalExtension();

                $file = $request->file('google_calender_json_file');
                $file->move($dir, $file_path);
                $post['google_calender_json_file']            = $file_path;
            }
            if ($request->google_clender_id)
            {
                $post['google_clender_id']            = $request->google_clender_id;
                foreach ($post as $key => $data) {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $data,
                            $key,
                            \Auth::user()->id,
                            date('Y-m-d H:i:s'),
                            date('Y-m-d H:i:s'),
                        ]
                    );
                }
            }
        return redirect()->back()->with('success', 'Storage setting successfully updated.');
    }

    public function createIp()
    {
        return view('restrict_ip.create');
    }

    public function storeIp(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $validator = \Validator::make(
            $request->all(),
            [
                'ip' => 'required',
            ]);
        if ($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $ip             = new IpRestrict();
        $ip->ip         = $request->ip;
        $ip->created_by = $created_by;
        $ip->save();

        return redirect()->back()->with('success', __('IP successfully created.'));
    }

    public function editIp($id)
    {
        $ip = IpRestrict::find($id);
        return view('restrict_ip.edit', compact('ip'));
    }

    public function updateIp(Request $request, $id)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'ip' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $ip     = IpRestrict::find($id);
            $ip->ip = $request->ip;
            $ip->save();

            return redirect()->back()->with('success', __('IP successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroyIp($id)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin')
        {
            $ip = IpRestrict::find($id);
            $ip->delete();

            return redirect()->back()->with('success', __('IP successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveSeoSetting(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        if($request->meta_image)
        {
            $request->validate(
            [
                'meta_image' => 'required',
            ]);

             $validation =[
                'mimes:'.'png',
                'max:'.'20480',
            ];

            $logoName = 'meta_image.png';
            $dir = 'uploads/meta_image/';


            $path = Utility::upload_file($request,'meta_image',$logoName,$dir,$validation);
            if($path['flag'] == 1){
                $dark_logo = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }
        }
        $request->validate(
            [
                'meta_keyword' => 'required|string',
                'meta_description' => 'required|string',
            ]
        );

        $post = $request->all();
        unset($post['_token']);

        foreach($post as $key => $data)
        {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                $data,
                                                                                                                                                $key,
                                                                                                                                                $created_by,
                                                                                                                                            ]
            );
        }
        return redirect()->back()->with('success', 'setting successfully updated.');
    }

    public function saveCookieSetting(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $validator = \Validator::make(
            $request->all(), [
                'cookie_title' => 'required',
                'cookie_description' => 'required',
                'strictly_cookie_title' => 'required',
                'strictly_cookie_description' => 'required',
                'contact_us_description' => 'required',
                'contact_us_url' => 'required',
            ]
        );

        $post = $request->all();
        unset($post['_token']);

        if ($request->cookie_consent)
        {
            $post['cookie_consent'] = 'on';
        }
        else{
            $post['cookie_consent'] = 'off';
        }

        if ($request->cookie_logging)
        {
            $post['cookie_logging'] = 'on';
        }
        else{
            $post['cookie_logging'] = 'off';
        }

        if($request->cookie_title)
        {
            $post['cookie_title'] = $request->cookie_title;
        }

        if($request->cookie_description)
        {
            $post['cookie_description'] = $request->cookie_description;
        }

        if($request->strictly_cookie_title)
        {
            $post['strictly_cookie_title'] = $request->strictly_cookie_title;
        }

        if($request->strictly_cookie_description)
        {
            $post['strictly_cookie_description'] = $request->strictly_cookie_description;
        }

        if($request->contact_us_description)
        {
            $post['contact_us_description'] = $request->contact_us_description;
        }

        if($request->contact_us_url)
        {
            $post['contact_us_url'] = $request->contact_us_url;
        }

        $settings = Utility::settings();
        foreach ($post as $key => $data)
        {
            if (in_array($key, array_keys($settings))) {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                        $data,
                        $key,
                        $created_by,
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Cookie setting successfully saved.');
    }

    public function CookieConsent(Request $request)
    {
        $settings= Utility::settings();
        if($settings['cookie_consent'] == "on" && $settings['cookie_logging'] == "on"){
            $allowed_levels = ['necessary', 'analytics', 'targeting'];
            $levels = array_filter($request['cookie'], function($level) use ($allowed_levels) {
                return in_array($level, $allowed_levels);
            });
            $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
            // Generate new CSV line
            $browser_name = $whichbrowser->browser->name ?? null;
            $os_name = $whichbrowser->os->name ?? null;
            $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
            $device_type = self::get_device_type($_SERVER['HTTP_USER_AGENT']);

            $ip = $_SERVER['REMOTE_ADDR'];
            // $ip = '49.36.83.154';
            $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));

            $date = (new \DateTime())->format('Y-m-d');
            $time = (new \DateTime())->format('H:i:s') . ' UTC';

            $new_line = implode(',', [$ip, $date, $time,json_encode($request['cookie']), $device_type, $browser_language, $browser_name, $os_name,
                isset($query)?$query['country']:'',isset($query)?$query['region']:'',isset($query)?$query['regionName']:'',isset($query)?$query['city']:'',isset($query)?$query['zip']:'',isset($query)?$query['lat']:'',isset($query)?$query['lon']:'']);

            if(!file_exists(storage_path(). '/uploads/sample/data.csv')) {

                $first_line = 'IP,Date,Time,Accepted cookies,Device type,Browser language,Browser name,OS Name,Country,Region,RegionName,City,Zipcode,Lat,Lon';
                file_put_contents(storage_path() . '/uploads/sample/data.csv', $first_line . PHP_EOL , FILE_APPEND | LOCK_EX);
            }
            file_put_contents(storage_path() . '/uploads/sample/data.csv', $new_line . PHP_EOL , FILE_APPEND | LOCK_EX);

            return response()->json('success');
        }
        return response()->json('error');
    }


    static function get_device_type($user_agent)
    {
        $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';

        if(preg_match_all($mobile_regex, $user_agent))
        {
            return 'mobile';
        }
        else
        {
            if(preg_match_all($tablet_regex, $user_agent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
    }
    public function chatgptkey(Request $request)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();

        if (\Auth::user()->type == 'super admin') {
            $user = \Auth::user();
            if (!empty($request->chatgpt_key)) {
                $post = $request->all();
                $post['chatgpt_key'] = $request->chatgpt_key;

                unset($post['_token']);
                foreach ($post as $key => $data) {
                    $settings = Utility::settings();
                    if (in_array($key, array_keys($settings))) {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                $data,
                                $key,
                                $created_by,
                            ]
                        );
                    }
                }
            }
            return redirect()->back()->with('success', __('Chatgpykey successfully saved.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
