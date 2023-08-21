@extends('layouts.main')
@section('page-title')
    {{ __('Manage Timesheet') }}
@endsection

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
                                <h4 class="m-b-10">{{ __('Manage Timesheet') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Timesheet') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="btn-icon m-1">
                                <a href="{{ route('timesheet.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                    data-bs-original-title="{{ __('Export') }}">
                                    <i class="ti ti-file-export"></i>
                                </a>
                            </div>
                            <div class="btn-icon m-1">
                                <a href="#" data-url="{{ route('timesheet.file.import') }}" data-ajax-popup="true"
                                    data-title="{{ __('Import Timesheet CSV file') }}" data-bs-toggle="tooltip" title=""
                                    class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Import') }}">
                                    <i class="ti ti-file-import"></i>
                                </a>
                            </div>
                            <div class="btn-icon m-1">
                                <a href="#" data-url="{{ route('timesheet.create') }}" data-ajax-popup="true" data-size="md"
                                    data-title="{{ __('Create New Timesheet') }}" data-bs-toggle="tooltip" title=""
                                    class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
                    <div class=" mt-2 " id="multiCollapseExample1" style="">
                        <div class="card">
                            <div class="card-body">
                                {{ Form::open(['route' => ['timesheet.index'], 'method' => 'get', 'id' => 'timesheet_filter']) }}
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                        <div class="btn-box">
                                            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('start_date', isset($_GET['start_date']) ? $_GET['start_date'] : '', ['class' => 'month-btn form-control d_week current_date', 'autocomplete' => 'off', 'id' => 'current_date']) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                        <div class="btn-box">
                                            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : '', ['class' => 'month-btn form-control d_week current_date', 'autocomplete' => 'off', 'id' => 'current_date']) }}
                                        </div>
                                    </div>
                                    @if(Auth::user()->acount_type == 1 || Auth::user()->acount_type == 1)
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                        <div class="btn-box">
                                            {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}
                                            {{ Form::select('employee', $employeesList, isset($_GET['employee']) ? $_GET['employee'] : '', ['class' => 'form-control select ', 'id' => 'id']) }}
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-auto float-end ms-2 mt-4">
                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('timesheet_filter').submit(); return false;"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('timesheet.index') }}" class="btn btn-sm btn-danger"
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
                            {{-- <h5> </h5> --}}
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple">
                                    <thead>
                                        <tr>
                                            @if (\Auth::user()->type != 'employee')
                                                <th>{{ __('Employee') }}</th>
                                            @endif
                                          
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('clock_in') }}</th>
                                            <th>{{ __('clock_out') }}</th>
                                             <th>{{ __('clock In Selfie') }}</th>
                                             <th>{{ __('clock Out Selfie') }}</th>
                                            <th>{{ __('Approval Status') }}</th>
                                            <th>{{ __('total_break_time') }}</th>
                                            <th>{{ __('total_time') }}</th>
                                            <th width="200ox">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($timeSheets as $timeSheet)
                                            <tr>
                                                @if (\Auth::user()->type != 'employee')
                                                    <td>{{ !empty($timeSheet->first_name) ? $timeSheet->first_name : '' }}
                                                        {{ !empty($timeSheet->employee) ? $timeSheet->last_name : '' }}
                                                    </td>
                                                @endif
                                                <td>{{ \Auth::user()->dateFormat($timeSheet->date) }}</td>
                                                <td>{{ $timeSheet->status }}</td>

                                            
                                                <td>{{ $timeSheet->clock_in != '00:00:00' ? \Auth::user()->timeFormat($timeSheet->clock_in) : '00:00' }}
                                                </td>
                                                <td>{{ $timeSheet->clock_out != '00:00:00' ? \Auth::user()->timeFormat($timeSheet->clock_out) : '00:00' }}
                                                </td>
                                                
                                                 <td>
                                                        <!--<img src="https://admin.shift2go.io/public/images/9236752701690870217.jpg" alt="Selfie Image" width="50" height="50"class="thumbnail-image" data-full-image="https://admin.shift2go.io/public/images/9236752701690870217.jpg">-->
                                                        <!--<img src="{{asset("public/uploads/$timeSheet->selfie")}}" alt="Selfie Image" width="50" height="50" data-toggle="modal" data-target="#fullImageModal{{$timeSheet->id}}">-->
                                                        @if ("$timeSheet->selfie" !=null)
    <img src="{{ asset("public/uploads/$timeSheet->selfie") }}" alt="Selfie Image" width="50" height="50" data-toggle="modal" data-target="#fullImageModal{{ $timeSheet->id }}">
    
@else
    <p>Pending</p>
@endif
                                                    </td>
                                                    
                                                                                                        <td>
                                                        <!--<img src="https://admin.shift2go.io/public/images/9236752701690870217.jpg" alt="Selfie Image" width="50" height="50"class="thumbnail-image" data-full-image="https://admin.shift2go.io/public/images/9236752701690870217.jpg">-->
                                                           @if ("$timeSheet->clockout_selfie" !=null)
    <img src="{{ asset("public/uploads/$timeSheet->clockout_selfie") }}" alt="Selfie Image" width="50" height="50" data-toggle="modal" data-target="#fullImageModal{{ $timeSheet->id }}">
@else
    <p>Pending</p>
@endif
                                                        <!--<img src="{{asset("public/uploads/$timeSheet->clockout_selfie")}}" alt="Selfie Image" width="50" height="50" data-toggle="modal" data-target="#fullImageModal{{$timeSheet->id}}">-->
                                                    </td>
                                                    <td>
                                                        @if ($timeSheet->approval_status == 'pending')
                                                            <span
                                                                class="badge bg-danger p-2 px-3 rounded">{{ __('Pending') }}</span>
                                                        @elseif($timeSheet->approval_status == 'approved')
                                                            <span
                                                                class="badge bg-primary  p-2 px-3 rounded">{{ __('Approved') }}</span>
                                                     
                                                        @endif
                                                    </td>
                                                
                                                <td>
                                                    @if ($timeSheet->total_break_time !== null)
                                                        {{ date('H:i', strtotime($timeSheet->total_break_time)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if ($timeSheet->total_time !== null)
                                                        {{ date('H:i', strtotime($timeSheet->total_time)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="Action">
                                                    <span>


                                                    <!-- <a href="{{ route('breaks', ['id' => $timeSheet->user_id]) }}" type="button" class ="btn btn-danger btn-sm">BreakIns</a>  -->

                                                       
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ URL::to('attendance/' . $timeSheet->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Attendance') }}"
                                                                data-size="lg" data-bs-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>

                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['attendance.destroy', $timeSheet->id]]) !!}
                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para  show_confirm"
                                                                    data-bs-toggle="tooltip" title=""data-bs-original-title="Delete" aria-label="Delete">
                                                                    <i class="ti ti-trash text-white text-white"></i></a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
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
            $(document).ready(function() {
                var now = new Date();
                var month = (now.getMonth() + 1);
                var day = now.getDate();
                if (month < 10) month = "0" + month;
                if (day < 10) day = "0" + day;
                var today = now.getFullYear() + '-' + month + '-' + day;
                $('.current_date').val(today);
            });
        </script>
    @endpush


