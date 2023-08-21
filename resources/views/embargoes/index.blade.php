@extends('layouts.main')
@section('page-title')
    {{ __('Embargo') }}
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
                                <h4 class="m-b-10">{{ __('Embargo') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Embargo') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            {{-- Menu --}}
                            @if (Auth::user()->acount_type == 1 || Auth::user()->acount_type == 2)
                                <div class="btn-group card-option">
                                    <button type="button" class="btn btn-sm btn-primary btn-icon m-1"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="" data-bs-original-title="Menu" aria-label="Menu"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a href="{{ url('holidays') }}" onclick="window.location.href=this;"
                                            class="dropdown-item" id="view_employee">{{ __('View All Leave') }}</a>
                                        @if (Auth::user()->acount_type == 1 || $haspermission['embargoes'] == 1)
                                            <a href="{{ url('embargoes') }}" onclick="window.location.href=this;"
                                                class="dropdown-item" id="removed_employee">{{ __('Embargoes') }}</a>
                                        @endif

                                        @if (Auth::user()->acount_type == 1)
                                            <a href="{{ url('rules') }}" onclick="window.location.href=this;"
                                                class="dropdown-item d-none" id="edit_group">{{ __('Request Rules') }}</a>
                                        @endif

                                        @if (Auth::user()->acount_type == 1 || $haspermission['leave_request'] == 1)
                                            <a href="{{ url('leave-request') }}" onclick="window.location.href=this;"
                                                class="dropdown-item" id="edit_group">{{ __('Leave Request') }}</a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Create New Embargo') }}" data-url="{{ route('embargoes.create') }}"
                                    data-size="md" data-ajax-popup="true" data-title="{{ __('Create New Embargo') }}">
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
                                            <th scope="sort">{{ __('Date') }}</th>
                                            <th scope="sort">{{ __('Applies To') }}</th>
                                            <th scope="sort">{{ __('Message') }}</th>
                                            <th class="text-end">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($embargoes) && count($embargoes) > 0)
                                            @foreach ($embargoes as $embargoe)
                                                <tr>
                                                    <td> {!! $embargoe->getDateBetween() !!} </td>
                                                    <td> {!! $embargoe->getCountEmployee() !!} </td>
                                                    <td> {{ $embargoe->message }} </td>
                                                    <td class="Action text-end">
                                                        <span>
                                                            <div class="actions ml-3">
                                                                <div class="action-btn bg-info ms-2">
                                                                    <a href="#"
                                                                        data-url="{{ route('embargoes.edit', $embargoe->id) }}"
                                                                        data-size="md" data-ajax-popup="true"
                                                                        data-title="{{ __('Edit Embargoe') }}"
                                                                        class="mx-3 btn btn-sm  align-items-center">
                                                                        <i class="ti ti-pencil text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}"></i>
                                                                    </a>
                                                                </div>

                                                                <div class="action-btn bg-danger ms-2" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="{{ __('Delete') }}">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['embargoes.destroy', $embargoe->id] ]) !!}
                                                                        <a href="#!" class="mx-3 btn btn-sm show_confirm">
                                                                            <i class="ti ti-trash text-white"></i>
                                                                        </a>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                                <span class="clearfix"></span>
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">
                                                    <div class="text-center">
                                                        <i class="fas fa-clock text-primary fs-40"></i>
                                                        <h2>{{ __('Opps...') }}</h2>
                                                        <h6> {!! __('No embargoes found...!') !!} </h6>
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
