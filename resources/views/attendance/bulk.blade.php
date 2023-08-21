@extends('layouts.main')
@section('page-title')
    {{ __('Manage Bulk Attendance') }}
@endsection

@push('pagescript')
    <script>
        $('#present_all').click(function(event)
        {
            if (this.checked)
            {
                $('.present').each(function()
                {
                    this.checked = true;
                });
                $('.present_check_in').removeClass('d-none');
                $('.present_check_in').addClass('d-block');
            }
            else
            {
                $('.present').each(function()
                {
                    this.checked = false;
                });
                $('.present_check_in').removeClass('d-block');
                $('.present_check_in').addClass('d-none');
            }
        });

        $('.present').click(function(event) {
            var div = $(this).parent().parent().parent().parent().find('.present_check_in');

            if (this.checked) {
                div.removeClass('d-none');
                div.addClass('d-block');

            } else {
                div.removeClass('d-block');
                div.addClass('d-none');
            }

        });
    </script>
@endpush

@section('action-button')
@endsection

@section('content')
    <div class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Manage Bulk Attendance') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Bulk Attendance') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">

                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
                    <div class=" mt-2" id="" style="">
                        <div class="card">
                            <div class="card-body">
                                {{ Form::open(['route' => ['attendanceemployee.bulkattendance'], 'method' => 'get', 'id' => 'bulkattendance_filter']) }}
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                        <div class="btn-box">
                                            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('date', isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'), ['class' => 'form-control month-btn']) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                        <div class="btn-box">
                                            {{ Form::label('branch', __('Role'), ['class' => 'form-label']) }}
                                            {{ Form::select('branch', $role, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'form-control select ', 'id' => 'branch_id']) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                        <div class="btn-box">
                                            {{ Form::label('department', __('Location'), ['class' => 'form-label']) }}
                                            {{ Form::select('department', $location, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'form-control select ', 'id' => 'department_id']) }}
                                        </div>
                                    </div>

                                    <div class="col-auto float-end ms-2 mt-4">
                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('bulkattendance_filter').submit(); return false;"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('attendanceemployee.bulkattendance') }}" class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
                                            <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                        </a>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            {{ Form::open(['route' => ['attendanceemployee.bulkattendance'], 'method' => 'post']) }}
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th width="10%">{{ __('Employee Id') }}</th>
                                            <th>{{ __('Employee') }}</th>
                                            <th>{{ __('Role') }}</th>
                                            <th>{{ __('Location') }}</th>
                                            <th>
                                                <div class="form-group my-auto">
                                                    <div class="custom-control ">
                                                        <input class="form-check-input" type="checkbox" name="present_all"
                                                            id="present_all" {{ old('remember') ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="present_all">
                                                            {{ __('Attendance') }}</label>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $employee)
                                            @php
                                                $attendance = $employee->present_status($employee->id, isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'));
                                            @endphp
                                            <tr>
                                                <td class="Id">
                                                    {{ $employee->user_id }}
                                                    <input type="hidden" value="{{ $employee->id }}" name="employee_id[]">
                                                </td>
                                                <td>{{ $employee->getUserName->first_name }}</td>
                                                <td> {!! $employee->getDefaultEmployeeRole($employee->id) !!} </td>
                                                <td> {{ $employee->getLocatopnName($employee->id) }} </td>

                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="form-check-input present" type="checkbox"
                                                                        name="present-{{ $employee->id }}"
                                                                        id="present{{ $employee->id }}"
                                                                        {{ !empty($attendance) && $attendance->status == 'Present' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                        for="present{{ $employee->id }}">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="col-md-8 present_check_in {{ empty($attendance) ? 'd-none' : '' }} ">
                                                            <div class="row">
                                                                <label class="col-md-2 control-label">{{ __('In') }}</label>
                                                                <div class="col-md-4">
                                                                    <input type="time" class="form-control timepicker"
                                                                        name="in-{{ $employee->id }}"
                                                                        value="{{ !empty($attendance) && $attendance->clock_in != '00:00:00' ? $attendance->clock_in : \Utility::getValByName('company_start_time') }}">
                                                                </div>

                                                                <label for="inputValue"
                                                                    class="col-md-2 control-label">{{ __('Out') }}</label>
                                                                <div class="col-md-4">
                                                                    <input type="time" class="form-control timepicker"
                                                                        name="out-{{ $employee->id }}"
                                                                        value="{{ !empty($attendance) && $attendance->clock_out != '00:00:00' ? $attendance->clock_out : \Utility::getValByName('company_end_time') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="attendance-btn float-end pt-4">
                                <input type="hidden" value="{{ isset($_GET['date']) ? $_GET['date'] : date('Y-m-d') }}"
                                    name="date">
                                <input type="hidden" value="{{ isset($_GET['branch']) ? $_GET['branch'] : '' }}" name="branch">
                                <input type="hidden" value="{{ isset($_GET['department']) ? $_GET['department'] : '' }}"
                                    name="department">
                                {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('pagescript')
    <script>
        $(document).ready(function() {
            if ($('.daterangepicker').length > 0) {
                $('.daterangepicker').daterangepicker({
                    format: 'yyyy-mm-dd',
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                });
            }
        });
    </script>
@endpush

