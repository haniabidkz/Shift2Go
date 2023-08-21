
<!DOCTYPE html>
@php
    $setting = \App\Models\Utility::colorset();
    $SITE_RTL= isset($setting['SITE_RTL'])?$setting['SITE_RTL']:'off';
    $darklayout = Utility::getValByName('cust_darklayout');
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Lato&amp;display=swap" rel="stylesheet">

      {{-- styles --}}
    <link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css' />
    <link href="{{ asset('css/chatify/style.css') }}" rel="stylesheet" />


    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif

    @if ($darklayout == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @endif

    <style>
        [dir="rtl"] .dash-sidebar {
            left: auto !important;
        }

        /* [dir="rtl"] .dash-header {
            left: 0;
            right: 280px;
        } */

        [dir="rtl"] .dash-header:not(.transprent-bg) .header-wrapper {
            padding: 0 0 0 30px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header) ~ .dash-container {
            margin-left: 0px;
        }

        [dir="rtl"] .me-auto.dash-mob-drp {
            margin-right: 10px !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: 10px !important;
        }
    </style>

</head>

<body class="overflow-x-hidden">
    <div class="main-content container">
        <div class="container" id="boxes">
            <div id="app" class="content">
                <div style="width:1000px;margin-left: auto;margin-right: auto;  height: auto; padding: 20px;">
                    <div class="row " style="padding: 20px 25px;">
                        <div class="col-md-6" style="padding: 20px 25px;">
                            <img src="{{ asset('storage/uploads/logo/logo-dark.png') }}" style="width: 100%; display: inline-block; float: left; max-width: 150px;">
                            </div>
                            <div class="col-md-6 text-end" style="padding: 20px 25px;">
                                <h5 class="d-inline-block">{{__('Contract Number :')}} </h5>
                                <span>{{ \Auth::user()->ContractNumberFormat($contract_id->id)}}</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p> <h6 class="d-inline-block">{{__('Type :')}}</h6>
                                     <span>{{ !empty($contract_id->types)?$contract_id->types->name:'' }}</span> </p>
                                    <h6 class="d-inline-block">{{__('Value :')}}
                                    </h6> <span>{{ \Auth::user()->priceFormat($contract_id->value) }}</span>
                                </div>
                                <div class="col-md-6 text-end">
                                    <p> <h6 class="d-inline-block">{{__('Start Date :')}} </h6>
                                    <span>{{  \Auth::user()->dateFormat($contract_id->start_date )}}</span> </p>
                                    <h6 class="d-inline-block">{{__('End Date :')}}</h6>
                                     <span>{{  \Auth::user()->dateFormat($contract_id->end_date )}}</span>
                                     <p> <h6 class="d-inline-block">{{__('Subject :')}}</h6>
                                        <span>{{ !empty($contract_id->subject)?$contract_id->subject:'' }}</span> </p>

                                </div>


                            </div>

                            <p class="text-right" style="margin: 14px;"> {{$contract_id->notes}}</p>

                            <p class="tox-target pc-tinymce-2" name="contract_description">{!! $contract_id->contract_description !!}</p>


                    </div>


                        <div class="row">
                            <div class="col-6">
                                <p> <h6 class="d-inline-block">{{__('Company Signature  :')}} </h6>
                                    <span> {!! $contract_id->owner_signature !!}</span> </p>

                            </div>
                            <div class="col-6 text-end">
                                <p> <h6 class="d-inline-block">{{__('Employee Signature  :')}} </h6>
                                    <span> {!! $contract_id->client_signature !!}</span> </p>

                            </div>
                        </div>
                  </div>
            </div>
        </div>
    </body>
</html>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('custom/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        function closeScript() {
            setTimeout(function () {
                window.open(window.location, '_self').close();
            }, 1000);
        }

        $(window).on('load', function () {
            var element = document.getElementById('boxes');
            var opt = {
                margin: 0.2,
                    filename: '{{Utility::ContractNumberFormat($contract_id->id)}}',
                    image: {type: 'jpeg', quality: 1},
                    html2canvas: {scale: 6, dpi: 72, letterRendering: true, bottom: 20},
                    pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
                    jsPDF: {unit: 'in', format: 'A4', orientation: 'landscape'}
            };

            html2pdf().set(opt).from(element).save().then(closeScript);
        });
    </script>


