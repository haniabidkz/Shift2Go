@extends('layouts.main')
@section('page-title')
    {{ __('Role') }}
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
                                <h4 class="m-b-10">{{ __('Role') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Company') }}</li>
                                <li class="breadcrumb-item">{{ __('Role') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Create New Role') }}" data-url="{{ route('roles.create') }}"
                                    data-size="md" data-ajax-popup="true" data-title="{{ __('Create New Role') }}">
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
                                            <th class="sort"> {{ __('Name') }}
                                            </th>
                                            <th class="sort">{{ __('Default Break') }}
                                            </th>
                                            <th class="sort text-center">{{ __('Employees') }}</th>
                                            <th class="action text-end">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($roles)  && count($roles) > 0)
                                            @foreach($roles as $role)
                                            <tr>
                                                <td> <i class="ti ti-circle" style="color: {{ $role->color }}"></i> {{ $role->name }} </td>
                                                <td> {{ !empty($role->getDefaultBreack())?$role->getDefaultBreack():'' }} {{__('Minutes')}} </td>
                                                <td class="text-center"> {{ $role->getCountEmployees() }} </td>
                                                <td class="Action text-end">
                                                    <span>
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}"
                                                                data-url="{{ route('roles.edit', $role->id) }}"
                                                                data-size="lg" data-ajax-popup="true"
                                                                data-title="{{ __('Edit roles') }}"
                                                                class="mx-3 btn btn-sm align-items-center">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>

                                                        <div class="action-btn bg-danger ms-2" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{ __('Delete') }}">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id]]) !!}
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
                                                <td colspan="4">
                                                    <div class="text-center">
                                                        <i class="fas fa-user-tag text-primary fs-40"></i>
                                                        <h2>{{ __('Opps...') }}</h2>
                                                        <h6> {!! __('No Role found...!') !!} </h6>
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
