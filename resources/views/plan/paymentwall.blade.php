@php
    $plan_id= \Illuminate\Support\Facades\Crypt::decrypt($data['plan_id']);
    $plandata=App\Models\Plan::where('id',$plan_id)->first();
    $amount = $plandata->price;
    $logo=asset(Storage::url('uploads/logo/'));
    $company_favicon=Utility::getValByName('company_favicon');

@endphp

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'RotaGo SaaS')}} - Plan PaymentWall</title>
    <link rel="icon" href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" type="image" sizes="16x16">
</head>

<script src="https://api.paymentwall.com/brick/build/brick-default.1.5.0.min.js"> </script>
<div id="payment-form-container"> </div>
<script>
  var brick = new Brick({
    public_key: '{{ $admin_payment_setting['paymentwall_public_key'] }}', // please update it to Brick live key before launch your project
    amount: '{{ $amount }}',
    currency: '{{ $setting['site_currency'] }}',
    container: 'payment-form-container',
    action: '{{route("plan.pay.with.paymentwall",[$data["plan_id"],$data["coupon"]])}}',
    form: {
      merchant: 'Paymentwall',
      product: '{{ $plandata->name }}',
      pay_button: 'Pay',
      show_zip: true, // show zip code
      show_cardholder: true // show card holder name
    }
});

brick.showPaymentForm(function(data) {
      if(data.flag == 1){
        window.location.href ='{{route("error.plan.show",1)}}';
      }else{
        window.location.href ='{{route("error.plan.show",2)}}';
      }
    }, function(errors) {
      if(errors.flag == 1){
        window.location.href ='{{route("error.plan.show",1)}}';
      }else{
        window.location.href ='{{route("error.plan.show",2)}}';
      }

    });

</script>
