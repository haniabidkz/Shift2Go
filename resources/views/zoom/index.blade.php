@extends('layouts.main')

@section('page-title')
    {{ __('Zoom Meeting') }}
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
                                <h4 class="m-b-10">{{ __('Zoom Meeting') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Zoom Meeting') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="{{ route('zoommeeting.calender') }}">
                                    <i class="ti ti-calendar-minus text-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ __('Calendar') }}"></i>
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <h5></h5>
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Meeting Time') }}</th>
                                            <th>{{ __('Duration') }}</th>
                                            <th>{{ __('Employees') }}</th>
                                            <th>{{ __('Join URL') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($ZoomMeetings) && count($ZoomMeetings) > 0)
                                            @foreach ($ZoomMeetings as $ZoomMeeting)
                                                <tr>
                                                    <td>{{ $ZoomMeeting->title }}</td>
                                                    <td>{{ $ZoomMeeting->start_date }}</td>
                                                    <td>{{ $ZoomMeeting->duration }} {{ __(' Minute') }}</td>
                                                    <td>
                                                        {{ !empty($ZoomMeeting->getUserInfo) ? $ZoomMeeting->getUserInfo->first_name : '' }}
                                                        {{ !empty($ZoomMeeting->getUserInfo) ? $ZoomMeeting->getUserInfo->last_name : '' }}
                                                    </td>
                                                    <td>
                                                        @if ($ZoomMeeting->created_by == \Auth::user()->id && $ZoomMeeting->checkDateTime())
                                                            <a href="{{ $ZoomMeeting->start_url }}" target="_blank">
                                                                {{ __('Start meeting') }} <i
                                                                    class="fas fa-external-link-square-alt "></i></a>
                                                        @elseif($ZoomMeeting->checkDateTime())
                                                            <a href="{{ $ZoomMeeting->join_url }}" target="_blank">
                                                                {{ __('Join meeting') }} <i
                                                                    class="fas fa-external-link-square-alt "></i></a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($ZoomMeeting->checkDateTime())
                                                        @if ($ZoomMeeting->status == 'waiting')
                                                            <span
                                                                class="badge bg-info p-2 px-3 rounded">{{ ucfirst($ZoomMeeting->status) }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-success p-2 px-3 rounded">{{ ucfirst($ZoomMeeting->status) }}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-danger p-2 px-3 rounded">{{ __('End') }}</span>
                                                    @endif
                                                    </td>
                                                    <td class="Action text-end rtl-actions">
                                                        <span>
                                                            <div class="action-btn bg-danger ms-2" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="{{ __('Delete') }}">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['zoom-meeting.destroy', $ZoomMeeting->id], 'id' => 'delete-form-' . $ZoomMeeting->id]) !!}
                                                                    <a href="#!" class="mx-3 btn btn-sm show_confirm">
                                                                        <i class="ti ti-trash text-white"></i>
                                                                    </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">
                                                    <div class="text-center">
                                                        <i class="fas fa-database text-primary fs-40"></i>
                                                        <h2>{{ __('Opps...') }}</h2>
                                                        <h6> {!! __('No Data found...!') !!} </h6>
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
        function ddatetime_range() {
            $('.datetime_class').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "autoApply": true,
                "locale": {
                    "format": 'YYYY-MM-DD H:mm'
                },
                "timePicker24Hour": true,
            }, function(start, end, label) {
                $('.start_date').val(start.format('YYYY-MM-DD H:mm'));
            });
        }

    </script>
@endpush
