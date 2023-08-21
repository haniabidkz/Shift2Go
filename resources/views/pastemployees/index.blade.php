@extends('layouts.main')
@section('page-title')
    {{ __('Deleted Employees') }}
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
                            <h4 class="m-b-10">{{ __('Employee') }}</h4>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item">{{ __('Employee') }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end text-right">

                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        @if(Auth::user()->type == 'company')
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-success">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted"> {{ $box['total_employee'] }}</small>
                                        <h6 class="m-0">{{ __('Total Employee  ') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <h4 class="m-0">{{ $box['month_employee'] }}</h4>
                                <small class="text-muted">{{ __('Current month new employee') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-warning">
                                        <i class="ti ti-calendar-time"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted">{{ $box['month_rotas'] }}</small>
                                        <h6 class="m-0">{{ __('Current Month Rotas') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">

                                <h4 class="m-0"> {{ \App\Models\User::CompanycurrencySymbol() }}
                                    {{ $box['month_rotas_cost'] }}</h4>
                                <small class="text-muted">{{ __('Total cost : ') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-danger">
                                        <i class="ti ti-user-off"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted">{{ $box['month_leave'] }}</small>
                                        <h6 class="m-0">{{ __('Current Month Leave') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <h4 class="m-0">{{ $box['month_comapany_leave_use'] }}</h4>
                                <small class="text-muted">{{ __('Company Leave ') }} </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <h5></h5>
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th scope="sort">{{__('Name')}}</th>
                                        <th scope="sort">{{__('Email')}}</th>
                                        <th scope="sort">{{__('Added')}}</th>
                                        <th scope="sort">{{__('Deleted')}}</th>
                                        <th scope="sort" class="text-end"> {{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($past_employees))
                                        @foreach($past_employees as $past_employee)
                                            <tr>
                                                <th scope="row">
                                                    <div class="media align-items-center">
                                                        <div class="media-body ml-4">
                                                            <a href="#" class="name h6 mb-0 text-sm">{{$past_employee->first_name}} {{$past_employee->last_name}}</a> <br>
                                                        </div>
                                                    </div>
                                                </th>
                                                <td> {{$past_employee->email}} </td>
                                                <td> {{$past_employee->created_at}} </td>
                                                <td> {{$past_employee->deleted_at}} </td>
                                                <td class="Action text-end rtl-actions">
                                                    <span>
                                                        <div class="action-btn bg-danger ms-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['employee.restore', $past_employee->id]]) !!}
                                                            <a href="#!"
                                                                class="mx-3 btn btn-sm show_confirm">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </span>

                                                    <!-- Actions -->
                                                    {{-- <div class="actions ml-3">
                                                        <a href="#" class="action-item text-danger mr-2 emp_delete " data-toggle="tooltip" data-original-title="{{__('Restore')}}"
                                                        data-confirm="{{ __('Are You Sure?') }}|{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="document.getElementById('restore-form-{{$past_employee->id}}').submit();">
                                                            <i class="fas fa-trash-restore-alt"></i>
                                                        </a>
                                                        {!! Form::open(['method' => 'POST', 'route' => ['employee.restore', $past_employee->id],'id' => 'restore-form-'.$past_employee->id]) !!}
                                                        {!! Form::close() !!}
                                                    </div> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <div class="text-center">
                                                        <i class="fas fa-user text-primary fs-40"></i>
                                                        <h2>{{ __('Opps...') }}</h2>
                                                        <h6> {!! __('No data found.') !!} </h6>
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

