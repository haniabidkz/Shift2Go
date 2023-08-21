@extends('layouts.main')

@section('page-title')
    {{ __('Zoom Meeting Calender') }}
@endsection

@php
$settings = App\Models\Utility::settings();
@endphp

@section('content')
<div class="dash-container">
    <div class="dash-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="page-header-title">
                            <h4 class="m-b-10">{{ __('Zoom Meeting Calender') }}</h4>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item">{{ __('Zoom Meeting Calender') }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end text-right">
                        <div class="btn btn-sm btn-primary btn-icon m-1">
                            <a href="{{ route('zoom-meeting.index') }}">
                                <i class="ti ti-list text-white" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="{{ __('Zoom Meeting List') }}"></i>
                            </a>
                        </div>

                        <div class="btn btn-sm btn-primary btn-icon m-1">
                            <a href="#" data-url="{{ route('zoom-meeting.create') }}" data-size="md"
                                data-ajax-popup="true" data-title="{{ __('Create Zoom Meeting') }}">
                                <i class="ti ti-plus text-white" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Create Zoom Meeting') }}"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Calendar') }}</h5>
                        @if(isset($settings['Google_Calendar']) && $settings['Google_Calendar'] == 'on')
                            <select class="form-control" name="is_live" id="is_live" style="float: right;width: 200px;margin-top: -30px;" onchange="get_data()">
                                <option value="1">{{ __('Google Calender') }}</option>
                                <option value="0" selected="true">{{ __('Local Calender') }}</option>
                            </select>
                        @endif
                    </div>
                    <div class="card-body">
                        <div  id='calendar' class='calendar' data-toggle="calendar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Next events</h4>
                        <ul class="event-cards list-group list-group-flush mt-3 w-100">
                            @foreach ($current_month_zoommeetings as $zoommeeting)
                            <li class="list-group-item card mb-3">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti ti-building-bank"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="m-0">{{ $zoommeeting->title }}</h6>
                                                <small class="text-muted">{{ date('d F Y', strtotime($zoommeeting->start_date)) }}, {{ __(' at ').  date('h:i A', strtotime($zoommeeting->start_date)) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('model')
<div id="comModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="model_form_title" id="modalTitle">{{ __('Add') }}</span> {{ __('Location') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('pagescript')
    <script src="{{ asset('assets\js\plugins\main.min.js') }}"></script>
    {{-- <script>
            $(document).ready(function () {
                var etitle;
                var etype;
                var etypeclass;
                var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    buttonText: {
                    timeGridDay: "{{__('Day')}}",
                    timeGridWeek: "{{__('Week')}}",
                    dayGridMonth: "{{__('Month')}}"
                },
                    themeSystem: 'bootstrap',
                    slotDuration: '00:10:00',
                    navLinks: true,
                    droppable: true,
                    selectable: true,
                    selectMirror: true,
                    editable: true,
                    dayMaxEvents: true,
                    handleWindowResize: true,
                    events: {!! $calandar !!},
                });
                calendar.render();
            });

    </script> --}}

    <script>
        $(document).ready(function()
        {
            get_data();
        });
        function get_data()
        {
            var is_live=$('#is_live :selected').val();
            $.ajax({
                url: $("#path_admin").val()+"/get_event_data" ,
                method:"POST",
                data: {"_token": "{{ csrf_token() }}",'is_live':is_live},
                success: function(data) {
                (function() {
                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            buttonText: {
                                timeGridDay: "{{ __('Day') }}",
                                timeGridWeek: "{{ __('Week') }}",
                                dayGridMonth: "{{ __('Month') }}"
                            },

                            slotLabelFormat: {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false,
                                },

                            themeSystem: 'bootstrap',
                            // slotDuration: '00:10:00',

                            allDaySlot:false,
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            height: 'auto',

                            timeFormat: 'H(:mm)',
                            events: data,
                        });
                        calendar.render();
                    })();
                }
            });

        }
    </script>
@endpush
