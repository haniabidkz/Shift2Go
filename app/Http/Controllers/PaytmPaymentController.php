<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PaytmWallet;
use App\Models\InvoicePayment;
use App\Models\Invoice;

class PaytmPaymentController extends Controller
{
    public $currancy;

    public function planPayWithPaytm(Request $request)
    {
        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $user       = Auth::user();
        $coupons_id ='';
        if($plan)
        {
            /* Check for code usage */
            $plan->discounted_price = false;
            $price                  = $plan->price;
            if(isset($request->coupon) && !empty($request->coupon))
            {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if(!empty($coupons))
                {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;
                    $coupons_id = $coupons->id;
                    if($usedCoupun >= $coupons->limit)
                    {
                        return Utility::error_res( __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                }
                else
                {
                    return Utility::error_res( __('This coupon code is invalid or has expired.'));
                }
            }

            if($price <= 0)
            {
                $user->plan = $plan->id;
                $user->save();

                $assignPlan = $user->assignPlan($plan->id);

                if($assignPlan['is_success'] == true && !empty($plan))
                {
                    if(!empty($user->payment_subscription_id) && $user->payment_subscription_id != '')
                    {
                        try
                        {
                            $user->cancel_subscription($user->id);
                        }
                        catch(\Exception $exception)
                        {
                            \Log::debug($exception->getMessage());
                        }
                    }
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order  = $orderID;
                    $userCoupon->save();

                    $usedCoupun = $coupons->used_coupon();
                    if($coupons->limit <= $usedCoupun)
                    {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                    Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price==null?0:$price,
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'usd',
                            'txn_id' => '',
                            'payment_type' => 'Paystack',
                            'payment_status' => 'succeeded',
                            'receipt' => isset($data['receipt_url']) ? $data['receipt_url'] : 'free coupon',
                            'user_id' => $user->id,
                        ]
                    );
                    // $res['msg'] = __("Plan successfully upgraded.");
                    // $res['flag'] = 2;
                    // return $res;
                    return redirect()->route('plan.index')->with('success', __('Plan successfully upgraded.!'));
                }
                else
                {
                    return Utility::error_res( __('Plan fail to upgrade.'));
                }
            }


            try{
                $call_back = route('plan.paytm',[$request->plan_id,'payment_frequency='.$request->paytm_payment_frequency,'coupon_id='.$coupons_id]);
                $payment = PaytmWallet::with('receive');
                $payment->prepare(
                    [
                        'order' => date('Y-m-d') . '-' . strtotime(date('Y-m-d H:i:s')),
                        'user' => $user->id,
                        'mobile_number' => $request->mobile,
                        'email' => $user->email,
                        'amount' => $price,
                        'plan' => $plan->id,
                        'callback_url' => $call_back
                    ]
                );

                return $payment->receive();
            }
            catch(\Exception $e)
            {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        }
        else
        {
            return Utility::error_res( __('Plan is deleted.'));
        }
    }

    public function getPaymentStatus(Request $request,$plan)
    {
        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);
        $user       = Auth::user();
        $orderID = time();
        if($plan)
        {
            try
            {
                $this->planpaymentSetting();

                $transaction = PaytmWallet::with('receive');
                $response = $transaction->response();

                if($transaction->isSuccessful())
                {

                    if($request->has('coupon_id') && $request->coupon_id != '')
                    {
                        $coupons = Coupon::find($request->coupon_id);
                        if(!empty($coupons))
                        {
                            $userCoupon            = new UserCoupon();
                            $userCoupon->user   = $user->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();

                            $usedCoupun = $coupons->used_coupon();
                            if($coupons->limit <= $usedCoupun)
                            {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }

                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = isset($request->TXNAMOUNT) ? $request->TXNAMOUNT : 0;
                    $order->price_currency = $this->currancy;
                    $order->txn_id         = isset($request->TXNID) ? $request->TXNID : '';
                    $order->payment_type   = __('paytm');
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();

                    $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);

                    if($assignPlan['is_success'])
                    {
                        return redirect()->route('plan.index')->with('success', __('Plan activated Successfully!'));
                    }
                    else
                    {
                        return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                    }
                }
                else
                {
                    return redirect()->route('plan.index')->with('error', __('Transaction has been failed! '));
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('plan.index')->with('error', __('Plan not found!'));
            }
        }
    }

    public function invoicePayWithPaytm(Request $request){


        $validatorArray = [
            'amount' => 'required',
            'invoice_id' => 'required',
            'mobile' => 'required',
            'email' => 'required|email'
        ];
        $validator      = Validator::make(
            $request->all(), $validatorArray
        )->setAttributeNames(
            ['invoice_id' => 'Invoice']
        );
        if($validator->fails())
        {
            return Utility::error_res($validator->errors()->first());
        }
        $invoice = Invoice::find($request->invoice_id);

        $this->paymentSetting($invoice->created_by);

        $amount = number_format((float)$request->amount, 2, '.', '');

        $invoice_getdue = number_format((float)$invoice->getDue(), 2, '.', '');

        if($invoice_getdue < $amount){
            return Utility::error_res('not correct amount');
        }

        $call_back = route('invoice.paytm',[encrypt($request->invoice_id)]);


        $payment = PaytmWallet::with('receive');
        $payment->prepare(
            [
                'order' => date('Y-m-d') . '-' . strtotime(date('Y-m-d H:i:s')),
                'user' => 0,
                'mobile_number' => $request->mobile,
                'email' => $request->email,
                'amount' => $request->amount,
                'invoice_id' => $request->invoice_id,
                'callback_url' => $call_back,
            ]
        );
        return $payment->receive();
    }

    public function getInvociePaymentStatus(Request $request,$invoice_id){


        $encrypt_invoiceid = \Illuminate\Support\Facades\Crypt::encrypt(decrypt($invoice_id));

        if(!empty($invoice_id))
        {


            $invoice_id = decrypt($invoice_id);
            $invoice    = Invoice::find($invoice_id);

            $this->paymentSetting($invoice->created_by);

            if($invoice)
            {
                try
                {
                    $transaction = PaytmWallet::with('receive');
                    $response = $transaction->response();

                    if($transaction->isSuccessful())
                    {
                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->transaction_id = app('App\Http\Controllers\InvoiceController')->transactionNumber($invoice->created_by);
                        $invoice_payment->invoice_id     = $invoice_id;
                        $invoice_payment->amount         = isset($request->TXNAMOUNT) ? $request->TXNAMOUNT : 0;
                        $invoice_payment->date           = date('Y-m-d');
                        $invoice_payment->payment_id     = 0;
                        $invoice_payment->payment_type   = __('Paytm');
                        $invoice_payment->client_id      = 0;
                        $invoice_payment->notes          = '';
                        $invoice_payment->save();

                        $invoice_getdue = number_format((float)$invoice->getDue(), 2, '.', '');

                        if($invoice_getdue <= 0.0)
                        {

                            Invoice::change_status($invoice->id, 3);
                        }
                        else{

                            Invoice::change_status($invoice->id, 2);
                        }

                        return redirect()->route('pay.invoice',$encrypt_invoiceid)->with('success', __('Payment added Successfully'));

                    }else
                    {
                        return redirect()->route('pay.invoice',$encrypt_invoiceid)->with('error', __('Transaction has been failed!'));
                    }
                }
                catch(\Exception $e){
                    return redirect()->route('pay.invoice',$encrypt_invoiceid)->with('error', __('Transaction has been failed!'));
                }
            }else{
                return redirect()->route('pay.invoice',$encrypt_invoiceid)->with('error', __('Invoice not found.'));
            }
        }else{
            return redirect()->route('pay.invoice',$encrypt_invoiceid)->with('error', __('Invoice not found.'));
        }
    }



    public function planpaymentSetting()
    {

        $admin_payment_setting = Utility::plan_payment_settings($user_id = 1);
        $this->currancy = isset($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'';
        config(
            [
                'services.paytm-wallet.env' => isset($admin_payment_setting['paytm_mode'])?$admin_payment_setting['paytm_mode']:'',
                'services.paytm-wallet.merchant_id' => isset($admin_payment_setting['paytm_merchant_id'])?$admin_payment_setting['paytm_merchant_id']:'',
                'services.paytm-wallet.merchant_key' =>  isset($admin_payment_setting['paytm_merchant_key'])?$admin_payment_setting['paytm_merchant_key']:'',
                'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                'services.paytm-wallet.channel' => 'WEB',
                'services.paytm-wallet.industry_type' =>isset($admin_payment_setting['paytm_industry_type'])?$admin_payment_setting['paytm_industry_type']:'',
            ]
        );
    }
}
