@extends('layouts.main')

@section('page-title')
    {{ __('Contract') }}
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
                                <h4 class="m-b-10">{{ __('Contract') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Contract') }}</li>
                            </ul>
                        </div>
                        @if(\Auth::user()->type == 'company')
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Create') }}" data-url="{{ route('contract.create') }}"
                                    data-size="lg" data-ajax-popup="true" data-title="{{ __('Create New Contract') }}">
                                    <i class="ti ti-plus text-white"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <div class="row">
                <div class="col-xl-3 col-6 contract-box">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{__('Total Contracts')}}</h6>
                                    <h3 class="text-primary">{{ $cnt_contract['total'] }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake bg-success text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-6 contract-box">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{__('This Month Total Contracts')}}</h6>
                                    <h3 class="text-info">{{ $cnt_contract['this_month'] }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake bg-info text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-6 contract-box">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{__('This Week Total Contracts')}}</h6>
                                    <h3 class="text-warning">{{ $cnt_contract['this_week'] }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake bg-warning text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-6 contract-box">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{__('Last 30 Days Total Contracts')}}</h6>
                                    <h3 class="text-danger">{{ $cnt_contract['last_30days'] }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake bg-danger text-white"></i>
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
                                        	<th scope="col"> {{__('ID')}} </th>
			                                <th scope="col">{{__('Name')}}</th>
			                                @if(\Auth::user()->type !='employee' )
			                                    <th scope="col">{{__('Employee')}}</th>
			                                @endif
			                                <th scope="col">{{__('Contract Type')}}</th>
			                                <th scope="col">{{__('Contract Value')}}</th>
			                                <th scope="col">{{__('Start Date')}}</th>
			                                <th scope="col">{{__('End Date')}}</th>
			                                <th scope="col">{{__('Status')}}</th>
			                                <th scope="col" class="text-right">{{__('Action')}}</th>
                            			</tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contracts as $contract)
                                        <tr class="font-style">
                                            <td> <a href="{{route('contract.show',$contract->id)}}"class="btn btn-outline-primary"> {{ \Auth::user()->ContractNumberFormat($contract->id)}}</a></td>
                                            <td>{{ !empty($contract->contract_name)?$contract->contract_name:''}}</td>
                                                @if(\Auth::user()->type !='employee' )
                                                <td>{{ !empty($contract->employees)?$contract->employees->first_name:'' }}
                                                    {{ !empty($contract->employees)?$contract->employees->last_name:'' }}</td>
                                            @endif
                                            <td>{{ !empty($contract->types)?$contract->types->name:'' }}</td>
                                            <td>{{ \Auth::user()->priceFormat($contract->value) }}</td>
                                            <td>{{  \Auth::user()->dateFormat($contract->start_date )}}</td>
                                            <td>{{  \Auth::user()->dateFormat($contract->end_date )}}</td>
                                            {{-- <td>{{ucfirst($contract->edit_status)}}</td> --}}
                                            <td>
                                                {{-- @dd($contract->edit_status == 'accept') --}}
                                            @if($contract->edit_status == 'accept')
                                                <span class="status_badge badge bg-primary  p-2 px-3 rounded">{{__('Accept')}}</span>
                                            @elseif($contract->edit_status == 'decline')
                                                <span class="status_badge badge bg-danger p-2 px-3 rounded">{{ __('Decline') }}</span>
                                            @elseif($contract->edit_status == 'pending')
                                                    <span class="status_badge badge bg-warning p-2 px-3 rounded">{{ __('Pending') }}</span>
                                            @endif
                                            </td>
                                            <!-- <td>{{  !empty(ucfirst($contract->status)) ? ucfirst($contract->status) : 'Close' }}</td> -->
                                            <td class="action text-right">
                                                @if(\Auth::user()->type=='company' && $contract->edit_status == 'accept')
                                                    <div class="action-btn bg-primary btn-icon ms-2">
                                                        <a href="#" data-url="{{ route('contract.copy',$contract->id) }}" class="mx-3 btn btn-sm align-items-center" data-ajax-popup="true"
                                                            data-title="{{ __('Copy Contract') }}" data-size="lg">
                                                            <i class="feather icon-copy text-white" data-bs-toggle="tooltip"
                                                                    title="{{ __('Copy') }}"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{route('contract.show',$contract->id)}}" class="mx-3 btn btn-sm  align-items-center"
                                                        data-bs-whatever="{{__('View')}}"> <span class="text-white"> <i
                                                                class="ti ti-eye"  data-bs-toggle="tooltip" data-bs-original-title="{{__('View')}}"></i></span></a>
                                                    </div>
                                                @if(\Auth::user()->type=='company' || \Auth::user()->type == 'employee' && $contract->edit_status == 'accept')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-ajax-popup="true" data-size="lg" data-url="{{ route('contract.edit',$contract->id) }}" data-title="{{ __('Edit Contract') }}"
                                                        title="{{__('Edit Contract')}}" > <span class="text-white"> <i
                                                                class="ti ti-edit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}"></i></span></a>
                                                    </div>
                                                @endif
                                                @if(\Auth::user()->type=='company' || \Auth::user()->type == 'employee' && $contract->edit_status == 'accept')
                                                    <div class="action-btn bg-danger ms-2">

                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['contract.destroy', $contract->id]]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm">
                                                            <i class="ti ti-trash text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
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

@push('availabilityscriptlink')

@endpush
