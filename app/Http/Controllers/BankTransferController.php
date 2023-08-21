<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\Coupon;
use App\Models\Utility;
use App\Models\UserCoupon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankTransferController extends Controller
{
    public function bankpayPost(Request $request)
    {
        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $price          = $plan->price;
        $user = Auth::user();
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $check = Order::where('plan_id' , $plan->id)->where('payment_status' , 'pending')->where('user_id', \Auth::user()->id)->first();
        if($check){
            return redirect()->route('plan.index')->with('error', __('You already send Payment request to this plan.'));
        }

        if(!empty($request->payment_receipt))
        {
            $request->validate(['payment_receipt' => 'required']);
            $validation =[
                // 'mimes:'.'png',
                    'max:'.'20480',
            ];
            $favicon = time() . '_' . 'receipt_image.png';
            $dir = 'uploads/payment_receipt/';
            $path = Utility::upload_file($request,'payment_receipt',$favicon,$dir,$validation);
            if($path['flag'] == 1){
                $favicon = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Payment receipt filed required'));
        }

        if($plan)
        {
            if($request->has('coupon') && $request->coupon != '')
            {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if(!empty($coupons))
                {
                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order  = $orderID;
                    $userCoupon->save();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $price = $price - $discount_value;
                    $usedCoupun = $coupons->used_coupon();
                    if($coupons->limit <= $usedCoupun)
                    {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            }
            if($request->coupon)
            {
                $price = $plan->price;
                $discount_value         = ($price / 100) * $coupons->discount;
                $price = $price - $discount_value;
            }

            $order                 = new Order();
            $order->order_id       = $orderID;
            $order->name           = $user->first_name.''. $user->last_name;
            $order->card_number    = '';
            $order->card_exp_month = '';
            $order->card_exp_year  = '';
            $order->plan_name      = $plan->name;
            $order->plan_id        = $plan->id;
            $order->price          = $price;
            $order->price_currency = env('CURRENCY');
            $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
            $order->payment_type   = __('Bank Transfer');
            $order->payment_status = __('pending');
            $order->receipt        = \App\Models\Utility::get_file($favicon);
            $order->user_id        = $user->id;
            $order->save();

            return redirect()->route('plan.index')->with('success', __('Plan payment request send!'));
        }
    }

    public function show($id)
    {
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $order = Order::where('order_id', $id)->first();
        return view('order.orderstatus',compact('order','admin_payment_setting'));
    }

    public function destroy($id)
    {
        $order = Order::where('order_id', $id)->first();
        $order->delete();
        return redirect()->route('order.index')->with('success', __('Plan Successfully Delete!'));
    }

    public function bankPaymentApproval(Request $request,$order)
    {
        $orders = Order::find($order);
        $user = User::find($orders->user_id);
        if($request->payment_approval == '1')
        {
            $assignPlan = $user->assignPlan($orders->plan_id);
            $orders->update([
                'payment_status' => 'succeeded',
            ]);
            return redirect()->route('order.index')->with('success', __('Plan activated Successfully!'));
        }
        else
        {
            $orders->update([
                'payment_status' => 'Rejected',
            ]);
            return redirect()->route('order.index')->with('success', __('Plan payment Rejected!'));
        }
    }
}
