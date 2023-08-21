@extends('layouts.main')
@section('page-title')
    {{ __('Manage Attendance List') }}
@endsection

@push('pagescript')
    <script>
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();

            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.date').addClass('d-none');
                $('.date').removeClass('d-block');
            } else {
                $('.date').addClass('d-block');
                $('.date').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });

        $('input[name="type"]:radio:checked').trigger('change');
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
                                <h4 class="m-b-10">{{ __('Manage Attendance List') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Attendance List') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">

                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            @if (session('status'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {!! session('status') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            
            <!-- Full Image Modal -->
<div class="modal fade" id="fullImageModal{{$attendance->id}}" tabindex="-1" role="dialog" aria-labelledby="fullImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullImageModalLabel">Full Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset("public/uploads/$attendance->selfie") }}" alt="Selfie Image" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>
            @endif
            <div class="row">
                <div class="col-sm-12">
                    <div class=" mt-2 " id="multiCollapseExample1">
                        <div class="card">
                            <div class="card-body">
                                {{ Form::open(['route' => ['attendance.index'], 'method' => 'get', 'id' => 'attendanceemployee_filter']) }}
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-xl-10">
                                        <div class="row">

                                            <div class="col-3">
                                                <label class="form-label">{{ __('Type') }}</label> <br>

                                                <div class="form-check form-check-inline form-group">
                                                    <input type="radio" id="monthly" value="monthly" name="type"
                                                        class="form-check-input"
                                                        {{ isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : 'checked' }}>
                                                    <label class="form-check-label" for="monthly">{{ __('Monthly') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline form-group">
                                                    <input type="radio" id="daily" value="daily" name="type"
                                                        class="form-check-input"
                                                        {{ isset($_GET['type']) && $_GET['type'] == 'daily' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="daily">{{ __('Daily') }}</label>
                                                </div>

                                            </div>

                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 month">
                                                <div class="btn-box">
                                                    {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}
                                                    {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control month-btn']) }}
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                                <div class="btn-box">
                                                    {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                                                    {{ Form::date('date', isset($_GET['date']) ? $_GET['date'] : '', ['class' => 'form-control month-btn']) }}
                                                </div>
                                            </div>
                                            @if (\Auth::user()->type != 'employee')
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                    <div class="btn-box">
                                                        {{ Form::label('branch', __('Role'), ['class' => 'form-label']) }}
                                                        {{ Form::select('branch', $role, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'form-control select']) }}
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                    <div class="btn-box">
                                                        {{ Form::label('department', __('Location'), ['class' => 'form-label']) }}
                                                        {{ Form::select('department', $location, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'form-control select']) }}
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-auto mt-4">
                                        <div class="row">
                                            <div class="col-auto">

                                                <a href="#" class="btn btn-sm btn-primary"
                                                    onclick="document.getElementById('attendanceemployee_filter').submit(); return false;"
                                                    data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                                    data-original-title="{{ __('apply') }}">
                                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                                </a>

                                                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-danger "
                                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                                    data-original-title="{{ __('Reset') }}">
                                                    <span class="btn-inner--icon"><i
                                                            class="ti ti-trash-off text-white-off "></i></span>
                                                </a>

                                                <a href="#" data-url="{{ route('attendance.file.import') }}"
                                                    data-ajax-popup="true" data-title="{{ __('Import  Attendance CSV File') }}"
                                                    data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
                                                    data-bs-original-title="{{ __('Import') }}">
                                                    <i class="ti ti-file"></i>
                                                </a>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>




                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple">
                                    <thead>
                                        <tr>
                                            @if (\Auth::user()->type != 'employee')
                                                <th>{{ __('Employee') }}</th>
                                            @endif
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Clock In') }}</th>
                                            <th>{{ __('Clock Out') }}</th>
                                            <th>{{ __('Clock In Image') }}</th>
                                            <th>{{ __('Clock Out Image') }}</th>

                                            <th>{{ __('Total Break Time') }}</th>
                                            <th>{{ __('Total  Time') }}</th>
                                            <!--<th>{{ __('Late') }}</th>-->
                                            <!--<th>{{ __('Early Leaving') }}</th>-->
                                            <!--<th>{{ __('Overtime') }}</th>-->
                                            @if (Auth::user()->type == "company")
                                                <th width="200px">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>



                                        @foreach ($attendanceEmployee as $attendance)
                                            <tr>
                                                @if (\Auth::user()->type != 'employee')
                                                    <td>{{ !empty($attendance->employees) ? $attendance->employees->first_name : '' }}
                                                        {{ !empty($attendance->employees) ? $attendance->employees->last_name : '' }}
                                                    </td>
                                                @endif
                                                <td>{{ \Auth::user()->dateFormat($attendance->date) }}</td>
                                                <td>{{ $attendance->status }}</td>
                                                 <td>{{ $attendance->clock_in != '00:00:00' ? \Auth::user()->timeFormat($attendance->clock_in) : '00:00' }}
                                                </td>
                                                <td>{{ $attendance->clock_out != '00:00:00' ? \Auth::user()->timeFormat($attendance->clock_out) : '00:00' }}
                                                </td>
                                                    <td>
                                                        
                                                        <!--<img src="{{asset("public/uploads/$attendance->selfie")}}" alt="Selfie Image" width="50" height="50" data-toggle="modal" data-target="#fullImageModal{{$attendance->id}}">-->
                                                        @if ("$attendance->selfie" !=null)
    <img src="{{ asset("public/uploads/$attendance->selfie") }}" alt="Selfie Image" width="50" height="50" data-toggle="modal" data-target="#fullImageModal{{ $attendance->id }}">
@else
    <p>Pending</p>
@endif
                                                    </td>
                                                    
                                                                                                        <td>
                                                        
                                                        <!--<img src="{{asset("public/uploads/$attendance->clockout_selfie")}}" alt="Selfie Image" width="50" height="50" data-toggle="modal" data-target="#fullImageModal{{$attendance->id}}">-->
                                                        @if ("$attendance->clockout_selfie" !=null)
    <img src="{{ asset("public/uploads/$attendance->clockout_selfie") }}" alt="Selfie Image" width="50" height="50" data-toggle="modal" data-target="#fullImageModal{{ $attendance->id }}">
@else
    <p>Pending</p>
@endif
                                                    </td>
                                                    
                                                     

                                                <!--<td>{{ $attendance->total_break_time?:'00:00:00' }}</td>-->
                                                <td>
                                                    @if ($attendance->total_break_time !== null)
                                                        {{ date('H:i', strtotime($attendance->total_break_time)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <!--<td>{{ $attendance->total_time?:'00:00:00' }}</td>-->
                                                 <td>
                                                    @if ($attendance->total_time !== null)
                                                        {{ date('H:i', strtotime($attendance->total_time)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <!--<td>{{ $attendance->late }}</td>-->
                                                <!--<td>{{ $attendance->early_leaving }}</td>-->
                                                <!--<td>{{ $attendance->overtime }}</td>-->
                                                <td class="Action">
                                                    @if (Auth::user()->type == "company")
                                                        <span>

                                                         @if( $attendance['clock_out'] == "00:00:00"  &&   $attendance['date'] == date('Y-m-d'))
                                                         <a href="{{ route('breaks', ['id' => $attendance->user_id]) }}" type="button" class ="btn btn-danger btn-sm">BreakIns</a> 
                                                         @endif
                                                       
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ URL::to('attendance/' . $attendance->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Attendance') }}"
                                                                data-size="lg" data-bs-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>

                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['attendance.destroy', $attendance->id]]) !!}
                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para  show_confirm"
                                                                    data-bs-toggle="tooltip" title=""data-bs-original-title="Delete" aria-label="Delete">
                                                                    <i class="ti ti-trash text-white text-white"></i></a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        </span>
                                                    @endif
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
