<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\GoogleCalendar\Event as GoogleEvent;
use File;
// use NotificationTemplates;
// use NotificationTemplateLangs;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Utility extends Model
{
    public static function settings($user_id = 0)
    {
        $data = DB::table('settings');
        if (Auth::check()) {
            $data->where('created_by', '=', Auth::user()->get_created_by());
        } else if ($user_id !== 0) {
            $data->where('created_by', '=', $user_id);
        } else {
            $data->where('created_by', '=', 1);
        }
        $data = $data->get();

        $settings = [
            "site_currency" => "Dollars",
            "site_currency_symbol" => "$",
            "site_currency_symbol_position" => "pre",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "company_logo" => "logo-light.png",
            "company_dark_logo"=>"logo-dark.png",
            "company_favicon" => "favicon.png",
            "invoice_prefix" => "#INV",
            "invoice_title" => "",
            "title_text" => "RotaGo-SaaS",
            "invoice_text" => "",
            "invoice_color" => "#6676ef",
            "purchase_invoice_prefix" => "#PUR",
            "sale_invoice_prefix" => "#SALE",
            "quotation_invoice_prefix" => "#QUO",
            "low_product_stock_threshold" => "0",
            "footer_text" => "© Copyright RotaGo SaaS",
            "default_language" => "en",
            "invoice_footer_title" => "",
            "invoice_footer_notes" => "",
            "purchase_invoice_template" => "template1",
            "purchase_invoice_color" => "ffffff",
            "sale_invoice_template" => "template1",
            "sale_invoice_color" => "ffffff",
            "quotation_invoice_template" => "template1",
            "quotation_invoice_color" => "ffffff",
            "footer_link_1" => "Support",
            "footer_value_1" => "#",
            "footer_link_2" => "Terms",
            "footer_value_2" => "#",
            "footer_link_3" => "Privacy",
            "footer_value_3" => "#",
            "display_landing_page" => "on",
            "gdpr_cookie" => 'off',
            "cookie_text" => '',
            "SIGNUP" => 'on',
            "color" => "theme-3",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",
            "dark_logo" => "logo-dark.png",
            "light_logo" => "logo-light.png",
            "SITE_RTL" => '',
            "contract_prefix" => '#CONT',
            "storage_setting" => "",
            "local_storage_validation" => "",
            "local_storage_max_upload_size" => "",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
            "google_clender_id"=> "",
            "google_calender_json_file"=> "",
            "Google_Calendar" => "",
            "Email_verify" => "on",
            "company_start_time"=> "",
            "company_end_time" => "",
            "timezone" => "",
            "ip_restrict" => "on",
            "meta_keyword" => "",
            "meta_description" => "",
            'cookie_consent'=>'on',
            'necessary_cookies'=>'on',
            'cookie_logging'=>'on',
            'cookie_title'=>'We use cookies!',
            'cookie_description'=>'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
            'strictly_cookie_title'=>'Strictly necessary cookies',
            'strictly_cookie_description'=>'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
            'contact_us_description'=>'For any queries in relation to our policy on cookies and your choices, please contact us',
            'contact_us_url'=>'#',
            'chatgpt_key' => ''
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        // dd($settings);
        return $settings;
    }

    public static function languages()
    {
        $dir     = base_path() . '/resources/lang/';
        $glob    = glob($dir . "*", GLOB_ONLYDIR);
        $arrLang = array_map(
            function ($value) use ($dir) {
                return str_replace($dir, '', $value);
            },
            $glob
        );
        $arrLang = array_map(
            function ($value) use ($dir) {
                return preg_replace('/[0-9]+/', '', $value);
            },
            $arrLang
        );
        $arrLang = array_filter($arrLang);

        return $arrLang;
    }

    public static function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }

    // get font-color code accourding to bg-color
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
        );
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }
    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R   = $G = $B = $C = $L = $color = '';
        $R = (floor($rgb[0]));
        $G = (floor($rgb[1]));
        $B = (floor($rgb[2]));
        $C = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];
        for ($i = 0; $i < count($C); ++$i) {
            if ($C[$i] <= 0.03928) {
                $C[$i] = $C[$i] / 12.92;
            } else {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }
        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];
        if ($L > 0.179) {
            $color = 'black';
        } else {
            $color = 'white';
        }
        return $color;
    }

    public static function getValByName($key)
    {
        $setting = Utility::settings();
        if (!isset($setting[$key]) || empty($setting[$key])) {
            $setting[$key] = '';
        }
        return $setting[$key];
    }

    public static function getStartEndMonthDates()
    {
        $first_day_of_current_month = Carbon::now()->startOfMonth()->subMonth(0)->toDateString();
        // $last_day_of_current_month = Carbon::now()->subMonth(0)->endOfMonth()->toDateString();

        $first_day_of_next_month = Carbon::now()->startOfMonth()->subMonth(-1)->toDateString();
        // $last_day_of_next_month = Carbon::now()->subMonth(-1)->endOfMonth()->toDateString();

        return ['start_date' => $first_day_of_current_month, 'end_date' => $first_day_of_next_month];
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";

        return file_put_contents($envFile, $str) ? true : false;
    }

    public static function convertStringToSlug($string = '')
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    public static function templateData()
    {
        $array = [];
        $array['colors']    = [
            '003580',
            '666666',
            '5e72e4',
            'f50102',
            'f9b034',
            'fbdd03',
            'c1d82f',
            '37a4e4',
            '8a7966',
            '6a737b',
            '050f2c',
            '0e3666',
            '3baeff',
            '3368e6',
            'b84592',
            'f64f81',
            'f66c5f',
            'fac168',
            '46de98',
            '40c7d0',
            'be0028',
            '2f9f45',
            '371676',
            '52325d',
            '511378',
            '0f3866',
            '48c0b6',
            '297cc0',
            'ffffff',
            '000000',
        ];
        $array['templates'] = [
            "template1" => "New York",
            "template2" => "Toronto",
            "template3" => "Rio",
            "template4" => "London",
            "template5" => "Istanbul",
            "template6" => "Mumbai",
            "template7" => "Hong Kong",
            "template8" => "Tokyo",
            "template9" => "Sydney",
            "template10" => "Paris",
        ];
        return $array;
    }

    public static function getSuperAdminValByName($key)
    {
        $data = DB::table('settings');
        $data = $data->where('name', '=', $key);
        $data = $data->first();
        if (!empty($data)) {
            $record = $data->value;
        } else {
            $record = '';
        }
        return $record;
    }

    public static function getAdminPaymentSetting()
    {
        $data = DB::table('admin_payment_settings');
        $settings = [];
        if (\Auth::check()) {
            $user_id = 1;
            $data = $data->where('created_by', '=', $user_id);
        }
        $data = $data->get();
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function AddPlanSectionLandingpage()
    {
        $landingdata = LandingPageSections::where(['section_type' => 'section-plan'])->first();
        if (is_null($landingdata)) {
            $section_data = [
                'section_name' => 'section-plan',
                'section_order' => 8,
                'default_content' => 'plan',
                'section_demo_image' => 'plan-section.png',
                'section_blade_file_name' => 'plan-section',
                'section_type' => 'section-plan',
            ];
            LandingPageSections::create($section_data);
        }
    }

    public static function getselectedThemeColor()
    {
        $color = env('THEME_COLOR');
        if ($color == "" || $color == null) {
            $color = 'blue';
        }
        return $color;
    }

    public static function getAllThemeColors()
    {
        $colors = [
            'blue', 'denim', 'sapphire', 'olympic', 'violet', 'black', 'cyan', 'dark-blue-natural', 'gray', 'light-blue', 'light-purple', 'magenta', 'orange-mute', 'pale-green', 'rich-magenta', 'rich-red', 'sky-gray'
        ];
        return $colors;
    }

    // get date format
    public static function getDateFormated($date, $time = false)
    {
        if (!empty($date) && $date != '0000-00-00') {
            if ($time == true) {
                return date("d M Y H:i A", strtotime($date));
            } else {
                return date("d M Y", strtotime($date));
            }
        } else {
            return '';
        }
    }

    public static function plan_payment_settings($id)
    {
        $data = [];

        $user = User::where(['id' => $id])->first();
        if (!is_null($user)) {
            $data = DB::table('admin_payment_settings');
            $data->where('created_by', '=', 1);
            $data = $data->get();
            //dd($data);
        }

        $res = [];

        foreach ($data as $key => $value) {
            $res[$value->name] = $value->value;
        }

        return $res;
    }

    public static function send_slack_msg($slug,$obj)
    {
        $notification_template = NotificationTemplates::where('slug',$slug)->first();
        if (!empty($notification_template) && !empty($obj))
        {
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->first();
            if(empty($curr_noti_tempLang))
            {
                    $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->first();
            }
            if(empty($curr_noti_tempLang))
            {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content))
            {
                $msg = self::replaceVariable($slug,$curr_noti_tempLang->content, $obj);
            }
        }

        if (isset($msg))
            $settings = Utility::settings(Auth::user()->ownerId());
            try {
                if (isset($settings['slack_webhook']) && !empty($settings['slack_webhook'])) {
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $settings['slack_webhook']);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $msg]));

                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close($ch);
                }
            } catch (\Exception $e) {
        }
    }

    public static function send_telegram_msg($slug,$obj)
    {
        $notification_template = NotificationTemplates::where('slug',$slug)->first();
        if (!empty($notification_template) && !empty($obj))
        {
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->first();
            if(empty($curr_noti_tempLang))
            {
                    $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->first();
            }
            if(empty($curr_noti_tempLang))
            {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content))
            {
                $msg = self::replaceVariable($slug,$curr_noti_tempLang->content, $obj);
            }
        }

        if (isset($msg))
        {
            $settings  = Utility::settings(Auth::user()->ownerId());

            try {
                // Set your Bot ID and Chat ID.
                $telegrambot    = $settings['telegrambot'];
                $telegramchatid = $settings['telegramchatid'];

                // Function call with your own text or variable
                $url     = 'https://api.telegram.org/bot' . $telegrambot . '/sendMessage';
                $data    = array(
                    'chat_id' => $telegramchatid,
                    'text' => $msg,
                );
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type:application/x-www-form-urlencoded\r\n",
                        'content' => http_build_query($data),
                    ),
                );
                $context = stream_context_create($options);
                $result  = file_get_contents($url, false, $context);
                $url     = $url;
            } catch (\Exception $e) {
            }
        }
    }

    public static function colorset()
    {
        if(Auth::user())
        {
            $user = Auth::user();
            $created_by = $user->get_created_by();

            $user = Auth::user();
            $setting = DB::table('settings')->where('created_by',$created_by)->pluck('value','name')->toArray();
            // dd($setting);
        }
        else{
            $setting = DB::table('settings')->pluck('value','name')->toArray();
            // dd($setting);
        }
        return $setting;
    }

    public static function get_superadmin_logo()
    {
        $is_dark_mode = self::getValByName('cust_darklayout');

        if($is_dark_mode == 'on'){
            return 'logo-light.png';
        }else{
            return 'logo-dark.png';
        }
    }

    public static function get_company_logo()
    {
        $is_dark_mode = self::getValByName('cust_darklayout');
        if($is_dark_mode == 'on'){
            $logo = self::getValByName('cust_darklayout');
            return Utility::getValByName('light_logo');
        }else{
            return Utility::getValByName('dark_logo');
        }
    }

    public static function displayDates($date1, $date2, $format = 'd-m-Y')
    {
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while ($current <= $date2) {
            $dates[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        return $dates;
    }

    public static function ContractNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["contract_prefix"] . sprintf("%05d", $number);
    }

    public static function getStorageSetting()
    {
        $data = DB::table('settings');
        $data = $data->where('created_by', '=', 1);
        $data     = $data->get();
        $settings = [
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,png,jpeg",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
        ];

        foreach($data as $row)
        {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function upload_file($request,$key_name,$name,$path,$custom_validation =[])
    {
        try{
            $settings = Utility::getStorageSetting();

            if(!empty($settings['storage_setting'])){
                if($settings['storage_setting'] == 'wasabi'){

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.'.$settings['wasabi_region'].'.wasabisys.com'
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size'])? $settings['wasabi_max_upload_size']:'2048';
                    $mimes =  !empty($settings['wasabi_storage_validation'])? $settings['wasabi_storage_validation']:'';

                }else if($settings['storage_setting'] == 's3'){
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size'])? $settings['s3_max_upload_size']:'2048';
                    $mimes =  !empty($settings['s3_storage_validation'])? $settings['s3_storage_validation']:'';

                }else{
                    $max_size = !empty($settings['local_storage_max_upload_size'])? $settings['local_storage_max_upload_size']:'2048';

                    $mimes =  !empty($settings['local_storage_validation'])? $settings['local_storage_validation']:'';
                }


                $file = $request->$key_name;


                if(count($custom_validation) > 0){
                    $validation =$custom_validation;
                }else{

                    $validation =[
                        'mimes:'.$mimes,
                        'max:'.$max_size,
                    ];

                }
                $validator = \Validator::make($request->all(), [
                    $key_name =>$validation
                ]);

                if($validator->fails()){
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if($settings['storage_setting']=='local'){

                        $request->$key_name->move(storage_path($path), $name);
                        $path = $path.$name;

                    }else if($settings['storage_setting'] == 'wasabi'){

                        $path = \Storage::disk('wasabi')->putFileAs(

                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;

                    }else if($settings['storage_setting'] == 's3'){

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;
                    }


                    $res = [
                        'flag' => 1,
                        'msg'  =>'success',
                        'url'  => $path
                    ];

                    return $res;
                }

            }else{
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }

        }catch(\Exception $e){
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function get_file($path)
    {
         $settings = Utility::getStorageSetting();

        try {
            if($settings['storage_setting'] == 'wasabi'){
                config(
                    [
                        'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                        'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                        'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                        'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                        'filesystems.disks.wasabi.endpoint' => 'https://s3.'.$settings['wasabi_region'].'.wasabisys.com'
                    ]
                );
            }elseif($settings['storage_setting'] == 's3'){
                config(
                    [
                        'filesystems.disks.s3.key' => $settings['s3_key'],
                        'filesystems.disks.s3.secret' => $settings['s3_secret'],
                        'filesystems.disks.s3.region' => $settings['s3_region'],
                        'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                        'filesystems.disks.s3.endpoint' => 'https://s3.'.$settings['s3_region'].'.wasabisys.com'
                    ]
                );
            }
            return \Storage::disk($settings['storage_setting'])->url($path);
        } catch (\Throwable $th) {
            return '';
        }
    }

    public static function colorCodeData($type)
    {
        if($type == 'event')
        {
            return 1;
        }
        elseif ($type == 'zoom_meeting')
        {
            return 2;
        }
        elseif ($type == 'task')
        {
            return 3;
        }
        elseif ($type == 'appointment')
        {
            return 11;
        }
        elseif ($type == 'rotas')
        {
            return 3;
        }
        elseif ($type == 'holiday')
        {
            return 4;
        }
        elseif ($type == 'call')
        {
            return 10;
        }
        elseif ($type == 'meeting')
        {
            return 5;
        }
        elseif ($type == 'leave')
        {
            return 6;
        }
        elseif ($type == 'work_order')
        {
            return 7;
        }
        elseif ($type == 'lead')
        {
            return 7;
        }
        elseif ($type == 'deal')
        {
            return 8;
        }
        elseif ($type == 'interview_schedule')
        {
            return 9;
        }
        else{
            return 11;
        }
    }

    public static $colorCode=[
        1=>'event-warning',
        2=>'event-secondary',
        3=>'event-success',
        4=>'event-warning',
        5=>'event-danger',
        6=>'event-dark',
        7=>'event-black',
        8=>'event-info',
        9=>'event-secondary',
        10=>'event-success',
        11=>'event-warning',
    ];

    public static function googleCalendarConfig()
    {
        $setting = Utility::settings();
        $path = storage_path($setting['google_calender_json_file']);
        config([
            'google-calendar.default_auth_profile' => 'service_account',
            'google-calendar.auth_profiles.service_account.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.token_json' => $path,
            'google-calendar.calendar_id' => isset($setting['google_clender_id'])?$setting['google_clender_id']:'',
            'google-calendar.user_to_impersonate' => '',

        ]);
    }

    public static function addCalendarData($request , $type)
    {
        Self::googleCalendarConfig();

        $event = new GoogleEvent();
        $event->name = $request->get('title');
        $event->startDateTime = Carbon::parse($request->get('start_date'));
        $event->endDateTime = Carbon::parse($request->get('end_date'));
        $event->colorId = Self::colorCodeData($type);

        $event->save();
    }

    public static function getCalendarData( $type)
    {
        Self::googleCalendarConfig();
        $data= GoogleEvent::get();
        $type=Self::colorCodeData($type);
        $arrayJson = [];
        foreach($data as $val)
        {
            $end_date=date_create($val->endDateTime);
            date_add($end_date,date_interval_create_from_date_string("1 days"));
            if($val->colorId=="$type")
            {
                $arrayJson[] = [
                    "id"=> $val->id,
                    "title" => $val->summary,
                    "start" => $val->startDateTime,
                    "end" => date_format($end_date,"Y-m-d H:i:s"),
                    "className" => Self::$colorCode[$type],
                    "allDay" => true,
                ];
            }

        }
        return $arrayJson;
    }

    public static function GetCacheSize()
    {
        $file_size = 0;
        foreach (\File::allFiles(storage_path('/framework/cache/')) as $file)
        {
            $file_size += $file->getSize();
        }
        $file_size = number_format($file_size / 1000000, 4);
        return $file_size;
    }

    public static function webhookSetting($module)
    {
        $data = 0;
        $user = Auth::user();
        $created_by = $user->get_created_by();
        $webhook = Webhook::where('module',$module)->where('created_by', '=', $created_by)->first();
        if(!empty($webhook))
        {
            $url = $webhook->url;
            $method = $webhook->method;
            $reference_url  = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $data['method'] = $method;
            $data['reference_url'] = $reference_url;
            $data['url'] = $url;
        }
        return $data;
    }

    public static function WebhookCall($url = null,$parameter = null , $method = 'POST')
    {
        if(!empty($url) && !empty($parameter))
        {
            try
            {
                $curlHandle = curl_init($url);
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $parameter);
                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                $curlResponse = curl_exec($curlHandle);
                curl_close($curlHandle);
                if(empty($curlResponse))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            catch (\Throwable $th)
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    public static function replaceVariable($name='', $content, $obj)
    {
        $arrVariable = [
            '{employee_name}',
            '{app_name}',
            '{company_name}',
            '{email}',
            '{app_url}',
            '{start_date}',
            '{end_date}',
            '{rota_date}',
            '{start_time}',
            '{end_time}',
        ];
        $arrValue = [
            'employee_name' => '-',
            'app_name' => '-',
            'company_name' => '-',
            'email' => '-',
            'app_url' => '-',
            'start_date' => '-',
            'end_date' => '-',
            "rota_date" => '-',
            "start_time" => '-',
            "end_time" => '-',
        ];

        foreach ($obj as $key => $val) {
            $arrValue[$key] = $val;
        }
        $arrValue['app_name'] = env('APP_NAME');
        $arrValue['app_url'] = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';
        return str_replace($arrVariable, array_values($arrValue), $content);
    }

    public function extraKeyword()
    {
        $keyArr=[
            __('Sun'),
            __('Mon'),
            __('WED'),
            __('SAT'),
            __('Rota Date'),
            __('App Name'),
            __('App Url'),
            __('IP'),
            __('SEO'),
            __('Meta'),
        ];
    }

    public static function updateStorageLimit($company_id, $image_size)
    {
        $user = Auth::user();
        $image_size = number_format($image_size / 1048576, 4);
        if($user->type == 'company')
        {
            $user = User::where('id',$user->id)->first();
        }
        else
        {
            $user = User::where('id',$user->created_by)->first();
        }
        $plan   = Plan::find($user->plan);
        $total_storage = $user->company_storage + $image_size;
        if($plan->company_storage <= $total_storage && $plan->company_storage != -1)
        {
            $error= __('Your plan storage limit is over , so you can not see customer uploaded payment receipt.');
            return $error;
        }
        else{
            $user->company_storage = $total_storage;
        }
        $user->save();
        return 1;
    }

    public static function changeStorageLimit($company_id, $file_path)
    {
        $user = Auth::user();
        $files =  \File::glob(storage_path($file_path));
        $fileSize = 0;
        foreach($files as $file){
            $fileSize += \File::size($file);
        }
        $image_size = number_format(\File::size($file) / 1048576, 4);
        if($user->type == 'company')
        {
            $user = User::where('id',$user->id)->first();
        }
        else
        {
            $user = User::where('id',$user->created_by)->first();
        }
        $plan   = Plan::find($user->plan);
        $total_storage = $user->company_storage - $image_size;
        $user->company_storage = $total_storage;
        $user->save();
        $status = false;
        foreach($files as $key => $file)
        {
            if(\File::exists($file))
            {
                $status = \File::delete($file);
            }
        }
        return true;
    }
    public static function flagOfCountry(){
        $arr = [
            'ar' => '🇦🇪 ar',
            'da' => '🇩🇰 da',
            'de' => '🇩🇪 de',
            'es' => '🇪🇸 es',
            'fr' => '🇫🇷 fr',
            'it' =>  '🇮🇹 it',
            'ja' => '🇯🇵 ja',
            'nl' => '🇳🇱 nl',
            'pl'=> '🇵🇱 pl',
            'ru' => '🇷🇺 ru',
            'pt' => '🇵🇹 pt',
            'en' => '🇮🇳 en',
            'tr' => '🇹🇷 tr',
            'pt-br' => '🇵🇹 pt-br',
        ];
        return $arr;
    }
}
