@extends('layouts.main')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

@php
$settings = App\Models\Utility::settings();
@endphp

@section('content')
    <style>
        .fc-event,
        .fc-event:not([href]) {
            border: none;
        }
    </style>
    <div class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center mobile-screen justify-content-between">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Dashboard') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Dashboard') }}</li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-6 d-flex align-items-center  justify-content-end">
                            @if (Auth::user()->type != 'employee')
                                <div class="card-option w-10">
                                    <button type="button" class="btn btn-sm btn-primary btn-icon m-1"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-filter" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ __('Filter Role') }}"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        @if (!empty($roles))
                                            <a class="dropdown-item" data-roll="no_role" onclick="filter_role('no_role')">
                                                <i class="ti ti-circle" style="color: #8492a6;"></i>
                                                {{ __('Without Role') }}
                                            </a>
                                            @foreach ($roles as $role)
                                                <a class="dropdown-item" data-roll="{{ $role['id'] }}"
                                                    onclick="filter_role({{ $role['id'] }})">
                                                    {!! $role['name'] !!}
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="card-option w-10">
                                    <button type="button" class="btn btn-sm btn-primary btn-icon m-1"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-flag" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ __('Filter Role') }}"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end calender_locatin_list">
                                        <a class="dropdown-item calender_location_active" data-location='0'
                                            onclick="filter_location(0)">{{ __('Select All') }}</a>
                                        @foreach ($locations as $location)
                                            <a class="dropdown-item" data-location='{{ $location['id'] }}'
                                                onclick="filter_location({{ $location['id'] }})">{{ $location['name'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="card-option">
                                <button type="button" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ __('View') }}"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ url('dashboard') }}"
                                        class="dropdown-item {{ Request::segment(1) == 'dashboard' ? 'calender_active' : '' }}"
                                        onclick="window.location.href=this;">{{ __('Calendar View') }}</a>
                                    <a href="{{ url('day') }}"
                                        class="dropdown-item {{ Request::segment(1) == 'day' ? 'calender_active' : '' }}"
                                        onclick="window.location.href=this;">{{ __('Daily View') }}</a>
                                    <a href="{{ url('user-view') }}"
                                        class="dropdown-item {{ Request::segment(1) == 'user' ? 'calender_active' : '' }}"
                                        onclick="window.location.href=this;">{{ __('User View') }}</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Calendar') }}</h5>
                            @if(isset($settings['Google_Calendar']) && $settings['Google_Calendar'] == 'on')
                            <select class="form-control calenrar_class" name="is_live" id="is_live" style="float:right; width: 200px;" onchange="calenderrr()">
                                <option value="1">{{ __('Google Calender') }}</option>
                                <option value="0" selected="true">{{ __('Local Calender') }}</option>
                            </select>
                            @endif
                        </div>
                        <div class="card-body callne">
                            <div id='calendar' class='calendar' data-toggle="calendar"></div>
                        </div>
                    </div>
                </div>
                {{-- @dd(Auth::user()); --}}
                <div class="col-lg-4">
                    @if(Auth::user()->acount_type == '2' || Auth::user()->acount_type == '3')
                        <div class="card" style="height: 215px;">
                            <div class="card-header">
                                <h5>{{ __('Mark Attandance') }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted pb-0-5">
                                    {{ __('My Office Time: ' . $settings['company_start_time'] . ' to ' . $settings['company_end_time']) }}</p>
                                <div class="row">
                                    <div class="col-md-6 float-right">
                                        {{ Form::open(['route' => 'clock_in.userattendance', 'method' => 'post']) }}
                                        @if (empty($employeeAttendance) || $employeeAttendance->clock_out != '00:00:00')
                                            <button type="submit" value="0" name="clock_in" id="clock_in"
                                                class="btn btn-primary">{{ __('CLOCK IN') }}</button>
                                        @else
                                            <button type="submit" value="0" name="clock_in" id="clock_in"
                                                class="btn btn-primary disabled" disabled>{{ __('CLOCK IN') }}</button>
                                        @endif
                                        {{ Form::close() }}
                                    </div>
                                    {{-- @dd( $employeeAttendance->id) --}}
                                    <div class="col-md-6 float-left">
                                        @if (!empty($employeeAttendance) && $employeeAttendance->clock_out == '00:00:00')
                                            {{ Form::model($employeeAttendance, ['route' => ['attendance.update', $employeeAttendance->id], 'method' => 'PUT']) }}
                                            <button type="submit" value="1" name="clock_out" id="clock_out"
                                                class="btn btn-danger">{{ __('CLOCK OUT') }}</button>
                                        @else
                                            <button type="submit" value="1" name="clock_out" id="clock_out"
                                                class="btn btn-danger disabled" disabled>{{ __('CLOCK OUT') }}</button>
                                        @endif
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Current Month events') }}</h5>
                        </div>
                        <div class="card-body table-scroll" @if(Auth::user()->acount_type == '2' || Auth::user()->acount_type == '3')
                            style='height: 500px; overflow:auto;' @else style="height: 500px;  overflow:auto;" @endif>
                            <ul class="event-cards list-group list-group-flush w-100">
                                @forelse ($current_month_rotas as $item)
                                {{-- @dd($item) --}}
                                <li class="list-group-item card mb-3" data_role_id="{{ !empty($item->role_id) ? $item->role_id : 'no_role' }}">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="theme-avtar bg-warning" style="background-color: {{ (!empty($item->getrotarole->color)) ? $item->getrotarole->color : '#8492a6' }} !important">
                                                    <i class="ti ti-building-bank"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="m-0">
                                                        {{-- @dd($item->getrotauser->first_name) --}}
                                                        {{ $item->getrotauser->first_name }}
                                                        <small class="text-muted text-xs">
                                                            {{ $item->getrotalocation->name }}
                                                        </small>
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ date("Y M d", strtotime($item->rotas_date)) }}
                                                        {{ date("h:i A", strtotime($item->start_time)) }}
                                                         {{ __('To') }}
                                                        {{ date("h:i A", strtotime($item->end_time)) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="list-group-item card mb-3">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                {{ __('No Rotas Found.') }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <!--@if(Auth::user()->acount_type == '1' || Auth::user()->acount_type == '1')-->
                    <!--    <div class="card">-->
                    <!--        <div class="card-header">-->
                    <!--            <h4 >{{ __('Storage Status') }} <small>({{ $users->company_storage . 'MB' }} / {{ $plan->company_storage . 'MB' }})</small></h4>-->
                    <!--        </div>-->
                    <!--        <div class="card shadow-none mb-0">-->
                    <!--            <div class="card-body border rounded  p-3">-->
                    <!--                <div id="device-chart"></div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--@else-->
                    <!--    @php-->
                    <!--        $storage_limit = 0;-->
                    <!--    @endphp-->
                    <!--@endif-->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('pagescript')
    <script src="{{ asset('assets\js\plugins\main.min.js') }}"></script>
    <script>
        var feed_calender = {!! $feed_calender !!};

        function filter_role(role_id = 0) {

            $('#calendar').find('.badge1').show();
            if (role_id != 0) {
                $('#calendar').find('.badge1').hide();
                $('#calendar').find('.badge1[data_role_id="' + role_id + '"]').show();
                $('.next-event').find('.list-group-item').hide();
                $('.next-event').find('.list-group-item[data_role_id="' + role_id + '"]').show();
            }
            $('.calender_role_list a').removeClass('calender_role_active');
            $('.calender_role_list a[data-roll="' + role_id + '"]').addClass('calender_role_active');
        }

        function filter_location(location_id = 0) {
            var data = {
                location_id: location_id,
            }

            $.ajax({
                url: '{{ route('dashboard.location_filter') }}',
                method: 'post',
                data: data,
                success: function(data) {
                    var feed_calender = data;

                    $('.calender_locatin_list a').removeClass('calender_location_active');
                    $('.calender_locatin_list a[data-location="' + location_id + '"]').addClass(
                        'calender_location_active');

                    $('#calendar').remove();
                    $('.callne').html("<div id='calendar' class='calendar' data-toggle='calendar'></div>");

                    calenderrr(feed_calender);
                }
            });
        }

        $(document).ready(function() {
            calenderrr(feed_calender)

            $(this).find('.fc-daygrid-block-event').removeClass(".fc-daygrid-event");
        });

        $(document).ready(function(){
            calenderrr(feed_calender)

        });

            function calenderrr(feed_calender)
            {
                var is_live=$('#is_live :selected').val();
                $.ajax({
                    url: $("#path_admin").val()+"/get_rota_data" ,
                    method:"POST",
                    data: {"_token": "{{ csrf_token() }}",'is_live':is_live},
                    success: function(data) {
                     (function() {
                            var etitle;
                            var etype;
                            var etypeclass;
                            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                                headerToolbar: {
                                    left: "prev,next today",
                                    center: "title",
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
                                eventContent: function(event, element, view) {
                                    var customHtml = event.event._def.extendedProps.html;
                                    return {
                                        html: customHtml
                                    }
                            }
                            });
                            calendar.render();
                        })();
                    }
                });
            }



    (function () {
        var options = {
            series: [{{ $storage_limit }}],
            chart: {
                height: 350,
                type: 'radialBar',
                offsetY: -20,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: "#e7e7e7",
                        strokeWidth: '97%',
                        margin: 5, // margin is in pixels
                    },
                    dataLabels: {
                        name: {
                            show: true
                        },
                        value: {
                            offsetY: -50,
                            fontSize: '20px'
                        }
                    }
                }
            },
            grid: {
                padding: {
                    top: -10
                }
            },
            colors: ["#6FD943"],
            labels: ['Used'],
        };
        var chart = new ApexCharts(document.querySelector("#device-chart"), options);
        chart.render();
    })();

    </script>
@endpush
