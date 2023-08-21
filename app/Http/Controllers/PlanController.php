<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Utility;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{

    public function index()
    {
        if (\Auth::user()->type == 'super admin' || (\Auth::user()->type == 'company')) {
            $plans = Plan::get();
            return view('plan.index', compact('plans'));
        } else {

          //  return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->type == 'super admin') {
            $arrDuration = Plan::$arrDuration;

            return view('plan.create', compact('arrDuration'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        // dd($request->all());
        if (Auth::user()->type == 'super admin') {
            $admin_payment_setting = Utility::getAdminPaymentSetting();

            if(!empty($admin_payment_setting) &&
                 ($admin_payment_setting['is_stripe_enabled'] == 'on' ||
                 $admin_payment_setting['is_paypal_enabled'] == 'on' ||
                 $admin_payment_setting['is_paystack_enabled'] == 'on' ||
                 $admin_payment_setting['is_flutterwave_enabled'] == 'on' ||
                 $admin_payment_setting['is_razorpay_enabled'] == 'on' ||
                 $admin_payment_setting['is_mercado_enabled'] == 'on' ||
                 $admin_payment_setting['is_paytm_enabled'] == 'on' ||
                 $admin_payment_setting['is_mollie_enabled'] == 'on' ||
                 $admin_payment_setting['is_paypal_enabled'] == 'on' ||
                 $admin_payment_setting['is_skrill_enabled'] == 'on' ||
                 $admin_payment_setting['is_coingate_enabled'] == 'on' ))
            {
                $validation                 = [];
                $validation['name']         = 'required|unique:plans';
                $validation['price']        = 'required|numeric|min:0';
                $validation['duration']     = 'required';
                $validation['company_storage'] = 'required|numeric';
                $validation['max_employee'] = 'required|numeric';


                if ($request->image) {
                    $validation['image'] = 'required|max:20480';
                }

                $request->validate($validation);
                $post = $request->all();

                if($request->enable_chatgpt == 'on')
                {
                    $post['enable_chatgpt'] = $request->enable_chatgpt;
                }
                else
                {
                    $post['enable_chatgpt'] = 'off';
                }

                if($request->hasFile('image'))
                {
                    $filenameWithExt = $request->file('image')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('image')->getClientOriginalExtension();
                    $fileNameToStore = 'plan_' . time() . '.' . $extension;

                    $dir = storage_path('uploads/plan/');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $path          = $request->file('image')->storeAs('uploads/plan/', $fileNameToStore);
                    $post['image'] = $fileNameToStore;
                }

                if (Plan::create($post)) {
                    return redirect()->back()->with('success', __('Plan Successfully created.'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Please set stripe or paypal api key & secret key for add new plan.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($plan_id)
    {
        if (\Auth::user()->type == 'super admin') {
            $arrDuration = Plan::$arrDuration;
            $plan        = Plan::find($plan_id);

            return view('plan.edit', compact('plan', 'arrDuration'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $plan_id)
    {
        if (\Auth::user()->type == 'super admin') {

            $admin_payment_setting = Utility::getAdminPaymentSetting();
            if($admin_payment_setting['is_stripe_enabled'] == 'on' ||
                $admin_payment_setting['is_paypal_enabled'] == 'on' ||
                $admin_payment_setting['is_paystack_enabled'] == 'on' ||
                $admin_payment_setting['is_flutterwave_enabled'] == 'on' ||
                $admin_payment_setting['is_razorpay_enabled'] == 'on' ||
                $admin_payment_setting['is_mercado_enabled'] == 'on' ||
                $admin_payment_setting['is_paytm_enabled'] == 'on' ||
                $admin_payment_setting['is_mollie_enabled'] == 'on' ||
                $admin_payment_setting['is_paypal_enabled'] == 'on' ||
                $admin_payment_setting['is_skrill_enabled'] == 'on' ||
                $admin_payment_setting['is_coingate_enabled'] == 'on')
                {
                $plan = Plan::find($plan_id);

                if (!empty($plan)) {
                    $validation                 = [];
                    $validation['name']         = 'required|unique:plans,name,' . $plan_id;
                    $validation['duration']     = 'required';
                    $validation['company_storage'] = 'required|numeric';
                    $validation['max_employee'] = 'required|numeric';
                    $request->validate($validation);

                    $post = $request->all();

                    if($request->enable_chatgpt == 'on')
                    {
                        $post['enable_chatgpt'] = $request->enable_chatgpt;
                    }
                    else
                    {
                        $post['enable_chatgpt'] = 'off';
                    }

                    // if ($request->price > 0) {
                    //     if((env('ENABLE_STRIPE') == 'on' && !empty(env('STRIPE_KEY')) && !empty(env('STRIPE_SECRET'))) ||(env('ENABLE_PAYPAL') == 'on' && !empty(env('PAYPAL_CLIENT_ID')) && !empty(env('PAYPAL_SECRET_KEY'))))
                    //     {
                    //         $post['price'] = $request->price;
                    //     }
                    //     else
                    //     {
                    //         return redirect()->back()->with('error', __('Please set stripe/paypal api key & secret key for add new plan'));
                    //     }

                    // }

                    if ($request->hasFile('image')) {
                        $filenameWithExt = $request->file('image')->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('image')->getClientOriginalExtension();
                        $fileNameToStore = 'plan_' . time() . '.' . $extension;

                        $dir = storage_path('uploads/plan/');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $image_path = $dir . '/' . $plan->image;  // Value is not URL but directory file path
                        if (\File::exists($image_path)) {

                            chmod($image_path, 0755);
                            \File::delete($image_path);
                        }
                        $path = $request->file('image')->storeAs('uploads/plan/', $fileNameToStore);

                        $post['image'] = $fileNameToStore;
                    }

                    if ($plan->update($post)) {
                        return redirect()->back()->with('success', __('Plan successfully updated.'));
                    } else {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Plan not found.'));
                }
            } else {
                return redirect()->back()->with('error', __('Please set stripe api key & secret key for add new plan.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function userPlan(Request $request)
    {
        $objUser = \Auth::user();
        $planID  = \Illuminate\Support\Facades\Crypt::decrypt($request->code);
        $plan    = Plan::find($planID);
        if ($plan) {
            if ($plan->price <= 0) {
                $objUser->assignPlan($plan->id);

                return redirect()->route('plan.index')->with('success', __('Plan successfully activated.'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Plan not found.'));
        }
    }

    public function show(plan $plan)
    {

    }
}
