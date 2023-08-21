@extends('layouts.main')

@section('page-title')
    {{ __('User View') }}
@endsection

@php
$date_formate = App\Models\User::CompanyDateFormat('d M Y');
@endphp

@section('content')
<style>
.ui-datepicker-calendar {
    display: none;
}
</style>
<div class="dash-container">
    <div class="dash-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="page-header-title">
                            <h4 class="m-b-10">{{ __('User View') }}</h4>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item">{{ __('User View') }}</li>
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
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-8">
                                <h5 class="fullcalendar-title h4 d-inline-block font-weight-400 mb-0">{{ $today }}
                                </h5> &nbsp;&nbsp;
                                <div class="btn-group" role="group" aria-label="Basic example" data-date="{{ $today }}">
                                    <a class="btn btn-sm btn-primary date_sub m-1 date_click">
                                        <i class="fas fa-angle-left"></i>
                                    </a>
                                    <a class="btn btn-sm btn-primary date_add m-1 date_click">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-4 d-flex justify-content-end text-right">
                                <div class="btn-group card-option">
                                    <button type="button"
                                        class="btn btn-sm btn-primary btn-icon m-1 day_view_filter_btn"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-filter" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ __('Filter') }}"></i>
                                    </button>
                                </div>
                                <div class="btn-group card-option">
                                    <button type="button" class="btn btn-sm btn-primary btn-icon m-1"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ __('Filter Role') }}"></i>
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
            </div>

            <div class="card day_view_filter" style="display: none;">
                <div class="card-body p-3 m-0 mt-2">
                    <div class="row">
                        <div class="form-group col-md-3 mb-0">
                            {{ Form::label('', __('Date'), ['class' => 'form-control-label']) }}
                            <input type="month" class="rota_date form-control h_40i" style="height: 40px;" id="datepicker" value="{{ $cur_year.'-'.$cur_month }}">
                        </div>
                        <div class="form-group col-md-3 mb-0 cus_select_h_40">
                            {{ Form::label('', __('Employee'), ['class' => 'form-control-label']) }}
                            {!! Form::select('emp_name[]', $employee_data, null, ['required' => false, 'multiple' => 'multiple', 'class'=> 'form-control emp_name multi-select', 'id'=>'choices-multiple'] ) !!}
                        </div>
                        <div class="form-group col-md-3 mb-0 cus_select_h_40">
                            {{ Form::label('', __('Location'), ['class' => 'form-control-label']) }}
                            {!! Form::select('loaction_name[]', $location_option, null, ['required' => false, 'multiple' => 'multiple', 'class'=> 'form-control loaction_name multi-select', 'id'=>'choices-multiple1']) !!}
                        </div>
                        <div class="form-group col-md-3 mb-0 cus_select_h_40">
                            {{ Form::label('', __('Role'), ['class' => 'form-control-label']) }}
                            {!! Form::select('role_name[]', $role_option, null, ['required' => false, 'multiple' => 'multiple', 'class'=> 'form-control role_name multi-select', 'id'=>'choices-multiple2']) !!}
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <h5></h5>
                        <div class="table-responsive day_view_tbl">
                            <table class="table mb-0 pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Break') }}</th>
                                        <th>{{ __('Location') }}</th>
                                        <th>{{ __('Revenue') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($rotas) && count($rotas) != 0)
                                        @foreach ($rotas as $rota)
                                            <tr>
                                                <th>
                                                    <div class="media align-items-center">
                                                        <div>
                                                            <div class="avatar-parent-child">
                                                                <img src="{{ asset(Storage::url($rota->userprofile($rota->user_id))) }}"
                                                                        class="avatar  rounded-circle" style="width: 50px;">
                                                            </div>
                                                        </div>
                                                        <div class="media-body ms-4">
                                                            <a href="#"
                                                                class="d-block name h6 mb-0 text-sm">{{ !empty($rota->getrotauser->first_name) ? $rota->getrotauser->first_name : '' }}</a>
                                                            <div class="d-inline-block day_view_dot"
                                                                style="background-color: {{ (!empty($rota->getrotarole)) ? $rota->getrotarole->color : 'rgb(132, 146, 166)'  }}"></div>
                                                            <small class="d-inline-block font-weight-bold">{{ (!empty($rota->getrotarole)) ? $rota->getrotarole->name : __('Without Role') }}</small>
                                                        </div>
                                                    </div>
                                                </th>
                                                <td> {{ date($date_formate, strtotime($rota->rotas_date)) }} </td>
                                                <td> {{ \App\Models\User::CompanyTimeFormat($rota->start_time) }} -
                                                    {{ \App\Models\User::CompanyTimeFormat($rota->end_time) }} </td>
                                                <td> {{ $rota->break_time . __('Min') }} </td>
                                                <td> {{ $rota->getrotalocation->name }} </td>
                                                <td> {{ $rota->rota_cost($rota) }} </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">
                                                <div class="text-center">
                                                    <i class="fas fa-calendar-times text-primary fs-40"></i>
                                                    <h2>{{ __('Opps...') }}</h2>
                                                    <h6> {!! __('No rotas found.') !!} </h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('pagescript')
    <script>



        $(document).on('click', '.day_view_filter_btn', function(e) {
            $('.day_view_filter').slideToggle(500);
        });

        $(document).on('click', '.date_click', function(e) {
            var rota_date = $('.rota_date').val()+'-01';
            var rota_date12 = $('.rota_date').val();
            var futureMonth = rota_date12;
            if($(this).hasClass('date_sub'))
            {
               var futureMonth = moment(rota_date).subtract(1, 'month').format("YYYY-MM");
            }
            if($(this).hasClass('date_add'))
            {
                var futureMonth = moment(rota_date).add(1, 'month').format("YYYY-MM");
            }
            $('.rota_date').val(futureMonth);
            $('.fullcalendar-title').html(futureMonth);
            $('.rota_date').trigger('change');
        });

        $(document).on('change', '.emp_name', function(e) {
            var date_type = 'date';
            dayviewfilter(date_type);
        });

        $(document).on('change', '.loaction_name', function(e) {
            var date_type = 'date';
            dayviewfilter(date_type);
        });

        $(document).on('change', '.role_name', function(e) {
            var date_type = 'date';
            dayviewfilter(date_type);
        });

        $(document).on('change', '.rota_date', function(e) {
            var date_type = 'date';
            dayviewfilter(date_type);
        });

        function dayviewfilter(date_type = 'date') {
            var date = $('.rota_date').val();
            var emp_name = $('.emp_name').val();
            var loaction_name = $('.loaction_name').val();
            var role_name = $('.role_name').val();

            var data = {
                date            : date,
                date_type       : date_type,
                emp_name        : emp_name,
                loaction_name   : loaction_name,
                role_name       : role_name,
            }

            $.ajax({
                url: '{{ route('userviewfilter') }}',
                method: 'POST',
                data: data,
                success: function (data)
                {
                    $('.day_view_tbl').html(data.returnHTML);
                    datatable_call();
                }
            });
        }

    </script>
@endpush
