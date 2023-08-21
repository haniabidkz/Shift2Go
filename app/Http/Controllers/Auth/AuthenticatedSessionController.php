<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginDetail;
use App\Models\Utility;
use App\Models\Plan;
use App\Models\UserVerify;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function __construct()
    {
        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        }
    }

    /*protected function authenticated(Request $request, $user)
    {
        if($user->delete_status == 1)
        {
            auth()->logout();
        }

        return redirect('/check');
    }*/

    public function store(LoginRequest $request)
    {
        if(env('RECAPTCHA_MODULE') == 'yes')
        {
            $validation['g-recaptcha-response'] = 'required|captcha';
        }else{
            $validation = [];
        }
        $this->validate($request, $validation);
        $request->authenticate();
        $user =\Auth::user();
        if($user->type == 'company')
        {
            $plan = plan::find($user->plan);
            if($plan)
            {
                if($plan->duration != 'unlimited')
                {
                    $datetime1 = new \DateTime($user->plan_expire_date);
                    $datetime2 = new \DateTime(date('Y-m-d'));
                    $interval = $datetime2->diff($datetime1);
                    $days =$interval->format('%r%a');
                    // dd($days, $user->assignplan(1));
                    if(!empty($datetime1) && $datetime1 < $datetime2)
                    {
                        $user->assignplan(1);

                        return redirect()->intended(RouteServiceProvider::HOME)->with('error',__('Yore plan is expired'));
                    }
                }
            }
        }
        $request->session()->regenerate();
        $user = Auth::user();
        if($user->is_delete == 1)
        {
            Auth::logout();
            return redirect('/login')->with('error', __('You are account is deactivate. please contact admin.'));
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        // $ip = '49.36.83.154'; // This is static ip address
        $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
        if ($whichbrowser->device->type == 'bot') {
            return;
        }
        $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;
        /* Detect extra details about the user */
        $query['browser_name'] = $whichbrowser->browser->name ?? null;
        $query['os_name'] = $whichbrowser->os->name ?? null;
        $query['browser_language'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        $query['device_type'] = Self::get_device_type($_SERVER['HTTP_USER_AGENT']);
        $query['referrer_host'] = !empty($referrer['host']);
        $query['referrer_path'] = !empty($referrer['path']);

        isset($query['timezone']) ? date_default_timezone_set($query['timezone']): '';
        $json = json_encode($query);
        $user = \Auth::user();
        // $currentlocation = $user->current_location;
        if ($user->type != 'company' && $user->type != 'super admin')
        {
            $login_detail = LoginDetail::create([
                'user_id' => $user->id,
                'ip' => $ip,
                'date' => date('Y-m-d H:i:s'),
                'details' => $json,
                'role' => $user->type,
                'created_by' => $user->created_by,
            ]);
        }
        if($user->is_email_verified == 0)
        {
            return redirect('/login')->with('error', __('You are account is deactivate.'));
        }
        else
        {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    public function showLoginForm($lang = '')
    {
        if ($lang == '') {
            $settings = Utility::settings();
            $lang = $settings['default_language'];
        }

        \App::setLocale($lang);
        return view('auth.login', compact('lang'));
    }

    public function showLinkRequestForm($lang = '')
    {
        if ($lang == '') {
            $settings = Utility::settings();
            $lang = $settings['default_language'];
        }
        \App::setLocale($lang);
        return view('auth.passwords.forgot-password', compact('lang'));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    function get_device_type($user_agent)
    {
        $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
        if (preg_match_all($mobile_regex, $user_agent)) {
            return 'mobile';
        } else {
            if (preg_match_all($tablet_regex, $user_agent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
    }
}
