@extends('layouts.main')
@section('page-title')
    {{ __('Availability_ADD') }}
@endsection
@section('availabilitylink')
    <link rel="stylesheet" href="{{ asset('custom/libs/jquery-schedule-master/dist/jquery.schedule.css') }}">
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
                                <h4 class="m-b-10">{{ __('Availability') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Availability') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="btn btn-sm btn-primary btn-icon m-1" style="height: fit-content;">
                                <a href="{{ route('availability.export') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="{{ __('Export Avalability CSV file') }}">
                                    <i class="ti ti-database-export text-white"></i>
                                </a>
                            </div>

                            <div class="btn btn-sm btn-primary btn-icon m-1 " style="height: fit-content;">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Create New Availability') }}"
                                    data-url="{{ route('availabilities.create') }}" data-size="lg" data-ajax-popup="true"
                                    data-title="{{ __('Add Availability') }}">
                                    <i class="ti ti-plus text-white"></i>
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
                                            @if (Auth::user()->type == 'company')
                                                <th scope="sort">{{ __('Name') }}</th>
                                            @endif
                                            <th scope="sort">{{ __('Title') }}</th>
                                            <th scope="sort">{{ __('Effective Dates') }}</th>
                                            <th scope="sort" class="text-end">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($availabilitys) && count($availabilitys) > 0)
                                            @foreach ($availabilitys as $availability)
                                                <tr data-id="{{ $availability->user_id }}">
                                                    @if (Auth::user()->type == 'company')

                                                        <td>{{ !empty($availability->getUserInfo)? $availability->getUserInfo->first_name:'' }}
                                                            {{ !empty($availability->getUserInfo)? $availability->getUserInfo->last_name:'' }}
                                                        </td>
                                                    @endif
                                                    <td> {{ $availability->name }}</td>
                                                    <td> {{ $availability->getAvailabilityDate() }} </td>
                                                    <td class="Action text-end">
                                                        <span>
                                                            <button type="button"
                                                                class="btn-white rounded-circle border-0 edit_schedule bg-transparent"
                                                                data-availability-json="{{ $availability->availability_json }}">
                                                                <div class="action-btn bg-info ms-2">
                                                                    <a href="#"
                                                                        data-url="{{ route('availabilities.edit', $availability->id) }}"
                                                                        data-size="lg" data-ajax-popup="true"
                                                                        data-title="{{ __('Edit Availability') }}"
                                                                        class="mx-3 btn btn-sm  align-items-center">
                                                                        <i class="ti ti-pencil text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}"></i>
                                                                    </a>
                                                                </div>
                                                            </button>
                                                            <div class="action-btn bg-danger ms-2" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="{{ __('Delete') }}">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['availabilities.destroy', $availability->id], 'id' => 'delete-form-' . $availability->id]) !!}
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
                                                @if (Auth::user()->type == 'company')
                                                    <td colspan="4">
                                                    @else
                                                    <td colspan="3">
                                                @endif
                                                <div class="text-center">
                                                    <i class="fas fa-file text-primary fs-40"></i>
                                                    <h2>{{ __('Opps...') }}</h2>
                                                    <h6> {!! __('No availability found...!') !!} </h6>
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

@section('availabilityscriptlink')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script id="add_schedule" src="{{ asset('custom/libs/jquery-schedule-master/dist/jquery.schedule.js') }}"
        data-src="{{ asset('custom/libs/jquery-schedule-master/dist/jquery.schedule.js') }}"></script>
    <script id="edit_schedule" src="{{ asset('custom/libs/jquery-schedule-master/dist/jquery.scheduleedit.js') }}"
        data-src="{{ asset('custom/libs/jquery-schedule-master/dist/jquery.scheduleedit.js') }}"></script>
    <script>
        function availabilitytablejs() {
            $('#schedule4').jqs({
                periodColors: [
                    ['rgba(0, 200, 0, 0.5)', '#0f0', '#000'],
                    ['rgba(200, 0, 0, 0.5)', '#f00', '#000'],
                ],
                periodTitle: '',
                periodBackgroundColor: 'rgba(0, 200, 0, 0.5)',
                periodBorderColor: '#000',
                periodTextColor: '#fff',
                periodRemoveButton: 'Remove please !',
                onRemovePeriod: function(period, jqs) {},
                onAddPeriod: function(period, jqs) {},
                onClickPeriod: function(period, jqs) {},
                onDuplicatePeriod: function(event, period, jqs) {},
                onPeriodClicked: function(event, period, jqs) {}
            });
        }

        function editavailabilitytablejs(data = []) {
            $('#schedule5').jqs({
                data: data,
                days: 7,
                periodColors: [
                    ['rgba(0, 200, 0, 0.5)', '#0f0', '#000'],
                    ['rgba(200, 0, 0, 0.5)', '#f00', '#000'],
                ],
                periodTitle: '',
                periodBackgroundColor: 'rgba(0, 200, 0, 0.5)',
                periodBorderColor: '#000',
                periodTextColor: '#fff',
                periodRemoveButton: 'Remove please !',
                onRemovePeriod: function(period, jqs) {},
                onAddPeriod: function(period, jqs) {},
                onClickPeriod: function(period, jqs) {},
                onDuplicatePeriod: function(event, period, jqs) {},
                onPeriodClicked: function(event, period, jqs) {}
            });
        }

    </script>
@endsection

@push('pagescript')
    <script>
        $(document).ready(function() {
            $(document).on('change', '.search-user-ava', function() {
                var value = $(this).val();
                $('.avalabilty_table tbody>tr').hide();
                if (value == 'all0') {
                    $('.avalabilty_table tbody>tr').show();
                } else {
                    $('.avalabilty_table tbody>tr[data-id="' + value + '"]').show();
                }
            });
        });

    </script>
@endpush
