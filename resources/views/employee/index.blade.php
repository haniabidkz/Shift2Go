@extends('layouts.main')

@section('page-title')
    {{ __('Employee') }}
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
                                <li class="breadcrumb-item">{{ __('Company') }}</li>
                                <li class="breadcrumb-item">{{ __('Employee') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="{{ url('userLogin') }}" >
                                    <i class="ti ti-user-check text-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ __('User Login History') }}"></i>
                                </a>
                            </div>
                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="{{ route('employee.export') }}" >
                                    <i class="ti ti-database-export text-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ __('Export Employee CSV file') }}"></i>
                                </a>
                            </div>

                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" data-url="{{ route('employee.file.import') }}" data-ajax-popup="true"
                                    data-title="{{ __('Import Employee CSV file') }}">
                                    <i class="ti ti-database-import text-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ __('Import Employee CSV file') }}"></i>
                                </a>
                            </div>
                            <div class="btn-group card-option rotas_filter_main_div">
                                <button type="button" class="btn btn-sm btn-primary btn-icon m-1"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{__('Menu')}}"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    <a href="{{ url('employees') }}" onclick="window.location.href=this;"
                                        class="dropdown-item">{{ __('View Employees') }}</a>
                                    <a href="{{ url('past-employees') }}" onclick="window.location.href=this;"
                                        class="dropdown-item">{{ __('Deleted Employees') }}</a>
                                </div>
                            </div>

                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Create New Employee') }}" data-url="{{ route('employees.create') }}"
                                    data-size="md" data-ajax-popup="true" data-title="{{ __('Create New Employee') }}">
                                    <i class="ti ti-plus text-white"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
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
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <h5></h5>
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th scope="sort">{{ __('Name') }}</th>
                                            <th scope="sort">{{ __('Status') }}</th>
                                            <th scope="sort">{{ __('Email') }}</th>
                                            <!--<th scope="sort">{{ __('Locations') }}</th>-->
                                            <!--<th scope="sort">{{ __('Role') }}</th>-->
                                            <!-- <th scope="sort">{{ __('Wage / Salary') }}</th>
                                            <th scope="sort">{{ __('Weekly Hours') }}</th> -->
                                            <th scope="sort" class="text-end"> {{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($employees) && count($employees) > 0)
                                            @foreach ($employees as $employee)
                                                <tr data-name="{{ $employee->first_name }} {{ $employee->last_name }}">
                                                    <th>
                                                        <div href="#" class="name h6 mb-0 text-sm">{{ $employee->first_name }}
                                                            {{ $employee->last_name }}</div>
                                                    </th>
                                                    <td>
                                                        @if ($employee->type == 'company')
                                                            <span
                                                                class="badge bg-primary p-2 px-3 rounded">{{ __('Administrator') }}</span>
                                                        @elseif($employee->acount_type == 1)
                                                            <span
                                                                class="badge bg-success p-2 px-3 rounded">{{ __('Admin') }}</span>
                                                        @elseif($employee->acount_type == 2)
                                                            <span
                                                                class="badge bg-info p-2 px-3 rounded">{{ __('Manager') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger p-2 px-3 rounded">{{ __('Employee') }}</span>
                                                        @endif
                                                    </td>
                                                    <td> {{ !empty($employee->email)?$employee->email:'' }} </td>
                                                    <!--<td> {{ $employee->getLocatopnName($employee->id) }} </td>-->
                                                    <!--<td> {!! $employee->getDefaultEmployeeRole($employee->id) !!} </td>-->
                                                    <!-- <td> {{ $employee->getwagesalary($employee->id) }} </td>
                                                    <td> {{ $employee->getweeklyhours($employee->id) }} </td> -->
                                                    <td class="Action text-end rtl-actions">
                                                        <span>
                                                            @if ($employee->type != 'company' && Auth::user()->type == 'company')
                                                                @if ($employee->password == '' || $employee->password == null)
                                                                    <div class="action-btn bg-secondary ms-2">
                                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                            onclick="showerrormsg()" >
                                                                            <i class="ti ti-settings text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Manage user type') }}" ></i>
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <div class="action-btn bg-secondary ms-2 {{ $employee->password == '' ? 'd-none' : '' }}">
                                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                            data-ajax-popup="true" data-title="{{ __('Manage user type') }}" data-size="lg"
                                                                            data-url="{{ route('employee.manage_permission', $employee->id) }}" >
                                                                            <i class="ti ti-settings text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Manage user type') }}" ></i>
                                                                        </a>
                                                                    </div>
                                                                @endif

                                                                
                                                            @endif

                                                            @if($employee->password == '')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                    data-ajax-popup="true" data-title="{{ __('Manage type') }}" data-size="md"
                                                                    data-url="{{ route('employee.set_password', $employee->id) }}"
                                                                    >
                                                                    <i class="ti ti-lock text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Set Password') }}" ></i>
                                                                </a>
                                                            </div>
                                                            @endif

                                                            @if($employee->pin == '' || $employee->pin == null)
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                    data-ajax-popup="true" data-title="{{ __('Manage type') }}" data-size="md"
                                                                    data-url="{{ route('set_pin', [ $employee->id ]) }}"
                                                                    >
                                                                    <i class="ti ti-lock text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Set Pin') }}" ></i>
                                                                </a>
                                                            </div>
                                                            @endif

                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ url('profile/' . Crypt::encrypt($employee->id) . '') }}"
                                                                    class="mx-3 btn btn-sm  align-items-center" >
                                                                    <i class="ti ti-pencil text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}" ></i>
                                                                </a>
                                                            </div>


                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="md"
                                                                    data-url="{{ route('employee.resetPin', \Crypt::encrypt($employee->id)) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Reset Pin') }}">
                                                                    <i class="ti ti-shield-lock text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Reset Pin') }}" ></i>
                                                                </a>
                                                            </div>

                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="md"
                                                                    data-url="{{ route('employee.reset', \Crypt::encrypt($employee->id)) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Reset Password') }}">
                                                                    <i class="ti ti-shield-lock text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Reset Password') }}" ></i>
                                                                </a>
                                                            </div>

                                                            <div class="action-btn bg-danger ms-2"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Delete') }}">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['employees.destroy', $employee->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm show_confirm">
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
                                                        <i class="fas fa-users text-primary fs-40"></i>
                                                        <h2>{{ __('Opps...') }}</h2>
                                                        <h6> {!! __('No Employee found...!') !!} </h6>
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
        $(document).ready(function() {
            $(document).on('keyup', '.search-user', function() {
                var value = $(this).val();
                $('.employee_tableese tbody>tr').each(function(index) {
                    var name = $(this).attr('data-name');
                    if (name.includes(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });

        function showerrormsg(event) {
            show_toastr('{{ __('Error') }}', '{!! __('You have to set password to manage user type') !!}', 'error');
        }
    </script>
@endpush
