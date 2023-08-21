@extends('layouts.main')
@section('page-title')
    {{ __('Leave') }}
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
                            <h4 class="m-b-10">{{ __('Leave') }}</h4>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item">{{ __('Leave') }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end text-right"></div>
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
                                <div class="d-inline-block text-white">
                                    <i class="fa fa-caret-left weak-prev-left weak-prev1 weak_go1 bg-primary"></i>
                                    <span class="weak_go_html1 text-primary mx-3">{{ $week_date[0].' - '.$week_date[6] }}</span>
                                    <i class="fa fa-caret-right weak-prev-left weak-left1 weak_go1 bg-primary"></i>
                                    <input type="hidden" data-start="{{ $week_date['week_start'] }}" data-end="{{ $week_date['week_end'] }}"  class="week_last_daye1">
                                    <input type="hidden" value="{{ $temp_week }}" data-created-by="{{ $created_by }}" class="week_add_sub1">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-center justify-content-between justify-content-md-end">
                                <div class="rotas_filter_main_div">
                                    @if(Auth::user()->acount_type == 1 || $haspermission['leave_request'] == 1)
                                    @if($leave_status_requests > 0)
                                        <div class="btn-group card-option">
                                            <button type="button" class="btn btn-sm btn-primary m-1">
                                                <a href="{{ url('leave-request') }}" class="text-white"> {{ $leave_status_requests.' '.__('Leave requests available') }} </a>
                                            </button>
                                        </div>
                                        @endif
                                    @endif
                                    {{-- export --}}
                                    <div class="btn btn-sm btn-primary btn-icon m-1">
                                        <a href="{{route('leave.export')}}" >
                                            <i class="ti ti-database-export text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Export Leave CSV file')}}"  ></i>
                                        </a>
                                    </div>

                                    {{-- Menu --}}
                                    @if(Auth::user()->acount_type == 1 || Auth::user()->acount_type == 2 )
                                    <div class="btn-group card-option">
                                        <button type="button" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti ti-dots-vertical" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Menu" aria-label="Menu"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                            <a href="{{ url('holidays') }}" onclick="window.location.href=this;" class="dropdown-item" id="view_employee">{{__('View All Leave')}}</a>
                                            @if(Auth::user()->acount_type == 1 || $haspermission['embargoes'] == 1)
                                            <a href="{{ url('embargoes') }}" onclick="window.location.href=this;" class="dropdown-item" id="removed_employee">{{__('Embargoes')}}</a>
                                            @endif

                                            @if(Auth::user()->acount_type == 1)
                                            <a href="{{ url('rules') }}" onclick="window.location.href=this;" class="dropdown-item d-none" id="edit_group">{{__('Request Rules')}}</a>
                                            @endif

                                            @if(Auth::user()->acount_type == 1 || $haspermission['leave_request'] == 1)
                                            <a href="{{ url('leave-request') }}" onclick="window.location.href=this;" class="dropdown-item" id="edit_group">{{__('Leave Request')}}</a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    {{-- add --}}
                                    <div class="btn btn-sm btn-primary btn-icon m-1">
                                        <a href="#"
                                            data-size="lg" data-ajax-popup="true" data-title="{{__('Add Employee Leave')}}"
                                            data-url="{{route('holidays.create')}}">
                                            <i class="ti ti-plus text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Add New Leave')}}"  ></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Listing -->
            <div class="card">
                <div class="card-wrapper project-timesheet overflow-auto">
                    <table class="table ajax-table">
                        <thead>
                        <tr class="text-center week_go_table1">
                            <th></th>
                            <th>{{ __('Holiday Allowance') }}</th>
                            <th>{{ __('Holiday Used') }}</th>
                            <th>{{ __('Holiday Remaining') }}</th>
                            <th><span>{{ __(date('D', strtotime($week_date[0]))) }}</span><br><span>{{ $week_date[0] }}</span></th>
                            <th><span>{{ __(date('D', strtotime($week_date[1]))) }}</span><br><span>{{ $week_date[1] }}</span></th>
                            <th><span>{{ __(date('D', strtotime($week_date[2]))) }}</span><br><span>{{ $week_date[2] }}</span></th>
                            <th><span>{{ __(date('D', strtotime($week_date[3]))) }}</span><br><span>{{ $week_date[3] }}</span></th>
                            <th><span>{{ __(date('D', strtotime($week_date[4]))) }}</span><br><span>{{ $week_date[4] }}</span></th>
                            <th><span>{{ __(date('D', strtotime($week_date[5]))) }}</span><br><span>{{ $week_date[5] }}</span></th>
                            <th><span>{{ __(date('D', strtotime($week_date[6]))) }}</span><br><span>{{ $week_date[6] }}</span></th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(!empty($employees)  && count($employees) > 0)
                                @foreach ($employees as $employee)
                                    <tr class="text-center" data-id="{{ $employee->id }}">
                                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                        <td><span class="create_time_sheet">{!! $employee->getAnnualHoliday($employee->id) !!}</span></td>
                                        <td><span >{!! $employee->getUsedHoliday($employee->id) !!}</span></td>
                                        <td><span >{!! $employee->getRemaingHoliday($employee->id) !!}</span></td>
                                        {!! $employee->getHasLeave($employee->id,$temp_week) !!}
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="11">
                                    <div class="text-center">
                                        <i class="fas fa-user-slash text-primary fs-40"></i>
                                        <h2>{{ __('Opps...') }}</h2>
                                        <h6> {!! __('No loaction found...!') !!} </h6>
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
@endsection

@push('pagescript')
<script>
    $(document).on('click','.edit_leavex', function () {
        // alert('dsad');
        $('#commonModal').modal('toggle');
        setTimeout(() => {
            $('.edit_leavex_popup').click();
        }, 100);
    });

    $(document).ready(function() {
        $(document).on('click','.weak_go1', function () {
            ajaxLeaveTimesheetTableView();
        });

        $('body').on('click','.delete_leave_ppp', function () {
            $( ".delete_leave_form" ).submit();
            loadConfirm();
        });

    });

    function ajaxLeaveTimesheetTableView() {
        var start_date = $('.week_last_daye1').attr('data-start');
        var end_date = $('.week_last_daye1').attr('data-end');
        var week = $('.week_add_sub1').val();
        var created_by = $('.week_add_sub1').attr('data-created-by');
        var data = {
            start_date: start_date,
            end_date: end_date,
            week: week,
            created_by: created_by,
        }

        $.ajax({
            url: '{{ route('holidays.leave_sheet') }}',
            method: 'post',
            data: data,
            success: function (data)
            {
                $('.ajax-table').html(data.table);
                $('.weak_go_html1').html(data.title);
            }
        });
    }

</script>
@endpush
