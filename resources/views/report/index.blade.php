@extends('layouts.main')
@section('page-title')
    {{ __('Report') }}
@endsection

<style>
    @media (max-width: 1700px) {
        .location_totals_chart{
            width: 100% !important;
        }
    }
</style>
@section('content')
    <!-- Page content -->
    <div class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Report') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Report') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header card-body">
                            {{ Form::open(['url' => 'reports', 'method' => 'get', 'enctype' => 'multipart/form-data', 'id' => 'reportt_filterr']) }}
                            <div class="row d-flex align-items-center">
                                {{-- date --}}
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('', __('Date'), ['class' => 'form-label']) }}
                                        {!! Form::text('date', $get_date_val, ['class'=> 'form-control date-range-report pc-daterangepicker-1',
                                        'id' => 'pc-daterangepicker-1']) !!}
                                        {{-- <input type='text' class="form-control date-range-report pc-daterangepicker-1"
                                            id="pc-daterangepicker-1" placeholder="Select time" type="text" /> --}}
                                        <input type="hidden" name="start_date" class="start_date">
                                        <input type="hidden" name="end_date" class="end_date">
                                    </div>
                                </div>

                                {{-- location --}}
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('', __('Location'), ['class' => 'form-label']) }}
                                        {!! Form::select('location[]', $filter_locations, $get_location_val, ['required' => false, 'multiple' => 'multiple', 'id' => 'choices-multiple-location_id', 'class' => 'form-control multi-select', 'data-placeholder' => __('Select Location')]) !!}
                                    </div>
                                </div>

                                {{-- user --}}
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('', __('User'), ['class' => 'form-label']) }}
                                        {!! Form::select('employees[]', $filter_employees, $get_user_val, ['required' => false, 'multiple' => 'multiple', 'id' => 'choices-multiple-employees', 'class' => 'form-control multi-select', 'data-placeholder' => __('Select Employee')]) !!}
                                        {{-- {!! Form::select('employees[]', $filter_employees, $get_user_val, ['required' => false, 'multiple' => 'multiple', 'data-placeholder'=> __('Select user') ,'class'=> 'form-control js-multiple-select']) !!} --}}
                                    </div>
                                </div>

                                {{-- role --}}
                                <div class="col-sm-12 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        {{ Form::label('', __('Role'), ['class' => 'form-label']) }}
                                        {!! Form::select('role[]', $filter_role, $get_role_val, ['required' => false, 'multiple' => 'multiple', 'id' => 'choices-multiple-get_role_val', 'class' => 'form-control multi-select', 'data-placeholder' => __('Select Role')]) !!}
                                        {{-- {!! Form::select('role[]', $filter_role, $get_role_val, ['required' => false, 'multiple' => 'multiple', 'data-placeholder'=> __('Select role') ,'class'=> 'form-control js-multiple-select']) !!} --}}
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1 col-lg-1">
                                    <div class="btn-group card-option">
                                        <button type="submit" class="btn btn-sm btn-primary btn-icon">
                                            <i class="ti ti-search" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Search ') }}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="card sticky-top" style="top:30px">
                                <div class="list-group list-group-flush" id="useradd-sidenav">
                                    <a href="#useradd-1"
                                        class="list-group-item list-group-item-action active border-0">{{ __('Daily Total') }}</a>
                                    <a href="#useradd-2"
                                        class="list-group-item list-group-item-action border-0">{{ __('Monthly Total') }}</a>
                                    <a href="#useradd-3"
                                        class="list-group-item list-group-item-action border-0">{{ __('Employee Totals') }}</a>
                                    <a href="#useradd-4"
                                        class="list-group-item list-group-item-action border-0">{{ __('Location / Role Totals') }}</a>
                                    <a href="#useradd-6"
                                        class="list-group-item list-group-item-action border-0">{{ __('Leave Totals') }}</a>
                                    <a href="#useradd-7"
                                        class="list-group-item list-group-item-action border-0">{{ __('Leave by Employee') }}</a>
                                    <a href="#useradd-8"
                                        class="list-group-item list-group-item-action border-0">{{ __('Paid/Unpaid Leave') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-9">
                            <div id="useradd-1" class="card">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ __('Daily Totals') }}</h3>
                                    <div class="card-body">
                                        <div id="Daily_Totals"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="useradd-2" class="card">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ __('Monthly Total') }}</h3>
                                    <div class="card-body">
                                        <div id="monthly_totals_chart"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="useradd-3" class="card">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ __('Employee Totals') }}</h3>
                                    <div class="card-body align-self-center">
                                        <div id="employee_wise_totals_chart"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="useradd-4" class="card">
                                <div class="row">
                                    <div class="col-md-6 location_totals_chart">
                                        <div class="card-body">
                                            <h3 class="mb-0">{{ __('Location Totals') }}</h3>
                                            <div class="card-body align-self-center">
                                                <div id="location_totals_chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body">
                                            <h3 class="mb-0">{{ __('Role Totals') }}</h3>
                                            <div class="card-body align-self-center">
                                                <div id="role_totals_chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="useradd-6" class="card">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ __('Leave Totals') }}</h3>
                                    <div class="card-body">
                                        <div id="leave_totals_chart"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="useradd-7" class="card">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ __('Leave by Employee') }}</h3>
                                    <div class="card-body">
                                        <div id="employee_leave_totals_chart"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="useradd-8" class="card">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ __('Paid/Unpaid Leave') }}</h3>
                                    <div class="card-body">
                                        <div id="paid_leave_totals_chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ sample-page ] end -->
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @dd( $daily_totals['cost'],$daily_totals['hour'],$daily_totals['date']) --}}
@push('pagescript')
    <script>
        $(document).ready(function() {
            if ($('.pc-daterangepicker-1').length > 0) {
                $($(".pc-daterangepicker-1")).each(function(index, element) {
                    var id = '#' + $(element).attr('id');
                    document.querySelector(id).flatpickr({
                        mode: "range"
                    });
                });
            }

            $(".date-range-report").change(function() {
                var date = $(this).val();

                var date_array = date.split(' to ');
                $('.start_date').val(date_array[0]);
                $('.end_date').val(date_array[1]);
                if (date_array.length == 2) {
                    $('.end_date').val(date_array[1]);
                }

                filter_report(0, 'none');
            });
        });

        filter_report(0, 'none');

        function filter_report(data_id, class_name) {
            var data_id = data_id;
            var class_name = class_name;
            $('.' + class_name + ' a[data-id="0"]').removeClass('active');
            if (data_id == 0) {
                $('.' + class_name + ' a').removeClass('active');
            }
            if ($('.' + class_name + ' a[data-id="' + data_id + '"]').hasClass('active')) {
                $('.' + class_name + ' a[data-id="' + data_id + '"]').removeClass('active');
            } else {
                $('.' + class_name + ' a[data-id="' + data_id + '"]').addClass('active');
            }

            var location_id = $('.locatoin_filter_report a.dropdown-item.active');
            var location_array = [];
            location_id.each(function(index) {
                location_array.push($(this).attr('data-id'));
            });
            location_id = location_array.join(',');

            var user_id = $('.user_filter_report a.dropdown-item.active');
            var user_id_array = [];
            user_id.each(function(index) {
                user_id_array.push($(this).attr('data-id'));
            });
            user_id = user_id_array.join(',');

            var role_id = $('.role_filter_report a.dropdown-item.active');
            var role_array = [];
            role_id.each(function(index) {
                role_array.push($(this).attr('data-id'));
            });
            role_id = role_array.join(',');

            $('.location_id[name="location"]').attr('value', location_id);
            $('.user_id[name="user"]').attr('value', user_id);
            $('.role_id[name="role"]').attr('value', role_id);

            var start_date = $('.start_date').val();
            var end_date = $('.end_date').val();
        }


        /* ***********
         * Daily Total
         *********** */
        var Daily_Totals_options = {
            chart: {
                height: 300,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                    name: '{{ __('Hours') }}',
                    data: {!! $daily_totals['hour'] !!}
                },
                {
                    name: '{{ __('Cost') }}',
                    data: {!! $daily_totals['cost'] !!}
                }
            ],
            xaxis: {
                type: 'categories',
                categories: {!! $daily_totals['date'] !!},
            },
            colors: ['#ffa21d', '#FF3A6E'],

            grid: {
                strokeDashArray: 4,
            },
            legend: {
                show: false,
            },

            yaxis: {
                tickAmount: 3,
                min: 0,
                max: 70,
            }
        };
        var Daily_Totals_chart = new ApexCharts(document.querySelector("#Daily_Totals"), Daily_Totals_options);
        Daily_Totals_chart.render();


        /* *********
         * monthly rotas chart
         ********* */
        var monthly_total_options = {
            series: [{
                    name: '{{ __('Hours') }}',
                    data: {!! $monthly['monthly_hour'] !!}
                },
                {
                    name: '{{ __('Cost') }}',
                    data: {!! $monthly['monthly_cost'] !!}
                },
            ],
            chart: {
                height: '400px',
                width: "100%",
                zoom: {
                    enabled: !1
                },
                toolbar: {
                    show: 1
                },
                shadow: {
                    enabled: !1
                },
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            grid: {
                borderColor: '#dee2e6',
                strokeDashArray: 5
            },
            xaxis: {
                type: 'categories',
                categories: {!! $monthly['last_months'] !!}
            },
            tooltip: {
                x: {
                    format: 'dd/MM/y'
                },
                y: {
                    formatter: function(value, {
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        var value = value;
                        if (seriesIndex == 0 && value != 0) {
                            var res = value.toString();
                            res = res.split('.');
                            var min = '00';
                            if (!isNaN(res[1])) {
                                min = Math.round(60 * res[1] / 100);
                            }
                            var value = res[0] + ':' + min;
                        }
                        return value;
                    }
                },
            },
            legend: {
                show: true,
                horizontalAlign: 'left',
                height: '100px',
            },
        };
        var monthly_totals_chart = $("#monthly_totals_chart");
        var monthly_total = new ApexCharts(monthly_totals_chart[0], monthly_total_options);
        monthly_total.render();

        /* *********
         * Employee wise rotas chart
         ********* */
        var employee_wise_totals_chart_options = {
            series: [{
                    name: '{{ __('Hour') }}',
                    data: {!! $employee_wise['user_hour'] !!}
                },
                {
                    name: '{{ __('Cost') }}',
                    data: {!! $employee_wise['user_cost'] !!}
                },
            ],
            chart: {
                height: '400px',
                width: "100%",
                zoom: {
                    enabled: !1
                },
                toolbar: {
                    show: 1
                },
                shadow: {
                    enabled: !1
                },
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: {!! $employee_wise['employee'] !!},
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(value, {
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        var value = value;
                        if (seriesIndex == 0 && value != 0) {
                            var res = value.toString();
                            res = res.split('.');
                            var min = '00';
                            if (!isNaN(res[1])) {
                                min = Math.round(60 * res[1] / 100);
                            }
                            var value = res[0] + ':' + min;
                        }
                        return value;
                    }
                },
                legend: {
                    show: true,
                    horizontalAlign: 'left',
                },
            }
        };

        var employee_wise_totals_chart = new ApexCharts(document.querySelector("#employee_wise_totals_chart"),
            employee_wise_totals_chart_options);
        employee_wise_totals_chart.render();

        /* *********
         * location wise rotas chart
         ********* */
        var location_totals_chart_options = {
            series: {!! $locations_wise['location_hour'] !!},
            chart: {
                width: 480,
                zoom: {
                    enabled: !1
                },
                toolbar: {
                    show: 1
                },
                shadow: {
                    enabled: !1
                },
                type: 'pie',
            },
            labels: {!! $locations_wise['location'] !!},
            title: {
                align: 'center',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    fontFamily: undefined,
                    color: '#263238'
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 240
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            tooltip: {
                y: {
                    formatter: function(value, {
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        var value = value;
                        if (seriesIndex == undefined && value != 0) {
                            var res = value.toString();
                            res = res.split('.');
                            var min = '00';
                            if (!isNaN(res[1])) {
                                min = Math.round(60 * res[1] / 100);
                            }
                            var value = res[0] + ':' + min;
                        }
                        return value;
                    }
                }
            },
        };

        var location_totals_chart = new ApexCharts(document.querySelector("#location_totals_chart"),
            location_totals_chart_options);
        location_totals_chart.render();

        /* *********
         * Role wise rotas chart
         ********* */
        var role_totals_chart_options = {
            series: {!! $role_wise['role_hour'] !!},
            chart: {
                width: 480,
                zoom: {
                    enabled: !1
                },
                toolbar: {
                    show: 1
                },
                shadow: {
                    enabled: !1
                },
                type: 'pie',
            },
            labels: {!! $role_wise['role'] !!},
            title: {
                align: 'center',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    fontFamily: undefined,
                    color: '#263238'
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 240
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            tooltip: {
                y: {
                    formatter: function(value, {
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        var value = value;
                        if (seriesIndex == undefined && value != 0) {
                            var res = value.toString();
                            res = res.split('.');
                            var min = '00';
                            if (!isNaN(res[1])) {
                                min = Math.round(60 * res[1] / 100);
                            }
                            var value = res[0] + ':' + min;
                        }
                        return value;
                    }
                }
            },
        };

        var role_totals_chart = new ApexCharts(document.querySelector("#role_totals_chart"), role_totals_chart_options);
        role_totals_chart.render();

        /* *********
         * leave chart
         ********* */
        var leave_totals_chart_options = {
            series: [{
                name: '{{ __('Leave') }}',
                data: {!! $leaves_wise['leave'] !!}
            }],
            chart: {
                width: "100%",
                zoom: {
                    enabled: !1
                },
                stacked: true,
                toolbar: {
                    show: !1
                },
                shadow: {
                    enabled: !1
                },
                type: 'bar',
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                },
            },
            xaxis: {
                type: 'categories',
                categories: {!! $leaves_wise['type'] !!},
            },
            legend: {
                position: 'right',
                offsetY: 40
            },
            fill: {
                opacity: 1
            },
            legend: {
                show: true,
                horizontalAlign: 'left'
            },
        };

        var leave_totals_chart = new ApexCharts(document.querySelector("#leave_totals_chart"), leave_totals_chart_options);
        leave_totals_chart.render();

        /* *********
         * employee leave chart
         ********* */
        var employee_leave_totals_chart_options = {
            series: {!! $employee_wise_leave['leave'] !!},
            chart: {
                width: "100%",
                zoom: {
                    enabled: !1
                },
                stacked: true,
                toolbar: {
                    show: !1
                },
                shadow: {
                    enabled: !1
                },
                type: 'bar',
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                },
            },
            xaxis: {
                type: 'categories',
                categories: {!! $employee_wise_leave['employee'] !!},
            },
            legend: {
                position: 'right',
                offsetY: 40
            },
            fill: {
                opacity: 1
            },
            legend: {
                show: true,
                horizontalAlign: 'left'
            },
        };

        var employee_leave_totals_chart = new ApexCharts(document.querySelector("#employee_leave_totals_chart"),
            employee_leave_totals_chart_options);
        employee_leave_totals_chart.render();

        /* *********
         * Paid Leave Chart
         ********* */
        var paid_leave_options = {
            series: [{
                    name: '{{ __('Paid') }}',
                    data: {!! $paid_leave_data['paid'] !!}
                },
                {
                    name: '{{ __('Unpaid') }}',
                    data: {!! $paid_leave_data['unpaid'] !!}
                },
            ],
            chart: {
                height: '400px',
                width: "100%",
                zoom: {
                    enabled: !1
                },
                toolbar: {
                    show: !1
                },
                shadow: {
                    enabled: !1
                },
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },

            grid: {
                borderColor: '#dee2e6',
                strokeDashArray: 5
            },
            xaxis: {
                type: 'categories',
                categories: {!! $paid_leave_data['date'] !!},
            },
            tooltip: {
                x: {
                    format: 'dd/MM/y'
                },
            },
            legend: {
                show: true,
                horizontalAlign: 'left',
            },
        };

        var paid_leave = new ApexCharts(document.querySelector("#paid_leave_totals_chart"), paid_leave_options);
        paid_leave.render();

    </script>
@endpush
