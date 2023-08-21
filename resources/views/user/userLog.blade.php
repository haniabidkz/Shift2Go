@extends('layouts.main')

@section('page-title')
    {{ __('User Login Details') }}
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
                                <h4 class="m-b-10">{{ __('User Login Details') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Company') }}</li>
                                <li class="breadcrumb-item">{{ __('User Login Details') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <div class="row">
                <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            {{ Form::open(['route' => ['userLogin.index'], 'method' => 'get', 'id' => 'userLogin_filter']) }}
                            <div class="row align-items-center justify-content-end">
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                    <div class="btn-box">
                                        {{ Form::label('month', __('Select Month'), ['class' => 'form-label']) }}
                                        {{ Form::select('month', $month, null, ['class' => 'form-control select', 'id' => 'month']) }}
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                    <div class="btn-box">
                                        {{ Form::label('username', __('Select User name'), ['class' => 'form-label']) }}
                                        {{ Form::select('username', $user, null, ['class' => 'form-control select', 'id' => 'id']) }}
                                    </div>
                                </div>
                                <div class="col-auto float-end ms-2 mt-4">
                                    <a href="#" class="btn btn-sm btn-primary"
                                        onclick="document.getElementById('userLogin_filter').submit(); return false;"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>
                                    <a href="{{ route('userLogin.index') }}" class="btn btn-sm btn-danger"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <h5></h5>
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th scope="sort">{{ __('Name') }}</th>
                                            {{-- <th scope="sort">{{ __('Email') }}</th> --}}
                                            <th scope="sort">{{ __('Role') }}</th>
                                            <th scope="sort">{{ __('IP') }}</th>
                                            <th scope="sort">{{ __('Last Login') }}</th>
                                            <th scope="sort">{{ __('Country') }}</th>
                                            <th scope="sort">{{ __('Device Type') }}</th>
                                            <th scope="sort">{{ __('OS Name') }}</th>
                                            <th scope="sort" class="text-end"> {{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($users) && count($users) > 0)
                                            @foreach ($users as $user)
                                                @php
                                                    $result = json_decode($user->details, true);
                                                    // dd($result);
                                                @endphp
                                                <tr data-name="{{ $user->user_name }}">
                                                    <th>
                                                        <div href="#" class="name h6 mb-0 text-sm">{{ $user->user_name }}
                                                            {{ $user->last_name }}</div>
                                                    </th>
                                                    {{-- <td>{{ $user->user_email }}</td> --}}
                                                    <td>
                                                        <span class="badge bg-primary p-2 px-3 rounded">
                                                            {{ Str::title($user->role)}}
                                                        </span>
                                                    </td>
                                                    <td>{{ $user->ip}}</td>
                                                    <td>{{ $user->date}}</td>
                                                    <td>{{ !empty($result['country']) ? $result['country'] : '' }}</td>
                                                    <td>{{ !empty($result['device_type']) ? $result['device_type'] : '' }}</td>
                                                    <td>{{ !empty($result['os_name']) ? $result['os_name'] : '' }}</td>
                                                    <td class="Action text-end rtl-actions">
                                                        <span>
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                    data-url="{{ route('userLogin.show', $user->id) }}"
                                                                    data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                                                                    title="" data-title="{{ __('User Login Details') }}"
                                                                    data-bs-original-title="{{ __('Show') }}">
                                                                    <i class="ti ti-eye text-white"></i>
                                                                </a>
                                                            </div>
                                                            <div class="action-btn bg-danger ms-2" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="{{ __('Delete') }}">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['userLogin.destroy', $user->id]]) !!}
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
                                                <td colspan="9">
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
