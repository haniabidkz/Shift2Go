
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

    {{-- scripts --}}
    <script src="{{ asset('js/chatify/autosize.js') }}"></script>

    {{-- new link --}}
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('custom/libs/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
     <!-- fileupload-custom css -->
    <link rel="stylesheet" href="{{ asset('custom/libs/dropzone/dist/dropzone.css') }}">

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
      <div class="row justify-content-between align-items-center mb-3">
          <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">

            <div class="all-button-box" style="margin-right: 122px;">
                    <a href="#" class="btn  btn-success btn-icon dsadsa py-1 px-2">
                      <i class="ti ti-download text-white"></i>
                    </a>
            </div>



          </div>
      </div>
        <div class="container" id="boxes">

            <div id="app" class="content">
                <div style="width:1000px;margin-left: auto;margin-right: auto; height: auto; padding: 20px;">
                    <div class="row " style="padding: 20px 25px;">
                        <div class="col-md-6" style="padding: 20px 25px;">
                            <img src="{{ asset('storage/uploads/logo/logo-dark.png') }}" style="width: 100%; display: inline-block; float: left; max-width: 150px;">
                            </div>
                            <div class="col-md-6 text-end" style="padding: 20px 25px;">
                                <h3 class="d-inline-block">{{ \Auth::user()->ContractNumberFormat($contract_id->id)}} </h3>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p> <h6 class="d-inline-block">{{__('Type :')}}</h6>
                                     <span>{{ !empty($contract_id->types)?$contract_id->types->name:'' }}</span> </p>
                                    <h6 class="d-inline-block">{{__('Value :')}}
                                    </h6> <span>{{ \Auth::user()->priceFormat($contract_id->value) }}</span>

                                     <p><h6 class="d-inline-block">{{__('Subject :')}}</h6>
                                        <span>{{ !empty($contract_id->subject)?$contract_id->subject:'' }}</span></p>

                                </div>
                                <div class="col-md-6 text-end">
                                    <p> <h6 class="d-inline-block">{{__('Start Date :')}} </h6>
                                    <span>{{  \Auth::user()->dateFormat($contract_id->start_date )}}</span> </p>
                                    <h6 class="d-inline-block">{{__('End Date :')}}</h6>
                                     <span>{{  \Auth::user()->dateFormat($contract_id->end_date )}}</span>
                                </div>


                            </div>

                            <p class="text-right" style="margin: 14px;"> {{$contract_id->notes}}</p>

                            <p class="tox-target pc-tinymce-2" name="contract_description">{!! $contract_id->contract_description !!}</p>


                    </div>


                        <div class="row">
                            <div class="col-6">
                                <span> {!! $contract_id->owner_signature !!}</span> </p>
                                <p> <h6 class="d-inline-block">{{__('Company Signature  :')}} </h6>


                            </div>
                            <div class="col-6 text-end">
                                <span> {!! $contract_id->client_signature !!}</span> </p>
                                <p> <h6 class="d-inline-block">{{__('Employee Signature  :')}} </h6>


                            </div>
                        </div>
                  </div>
            </div>
        </div>
       <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="signature"></p>
                    </div>
                </div>
            </div>
        </div>


            <!-- Required Js -->
        <script src="{{ asset('custom/libs/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/dash.js') }}"></script>
        <script src="{{ asset('custom/libs/moment/min/moment.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
        <script src="{{ asset('custom/libs/range-date-picker/daterangepicker.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>

        <script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
        <script src="{{ asset('custom/libs/jqueryui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('custom/libs/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('custom/libs/moment/min/moment.min.js') }}"></script>

        <script src="{{ asset('custom/js/custom.js') }}"></script>



        <!-- <script>

            $( "#commonModal" ).on('click', function(){

                $("#signature").jSignature();
              $("#signature").resize();
            });


        </script> -->




         <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="{{ asset('custom/js/html2pdf.bundle.min.js') }}"></script>

        <!-- Download pdf -->
        <script>
            function closeScript() {
                setTimeout(function () {
                    window.open(window.location, '_self').close();
                }, 1000);
            }

            $(document).on('click','.dsadsa', function () {

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

    </body>
</html>
