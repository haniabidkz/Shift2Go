@extends('layouts.main')

@section('page-title')
    {{ __('Contract Type') }}
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
                                <h4 class="m-b-10">{{ __('Contract Type') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Contract Type') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Create') }}" data-url="{{ route('contract_type.create') }}"
                                    data-size="md" data-ajax-popup="true" data-title="{{ __('Create New Contract Type') }}">
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
			                                <th scope="col">{{__('Name')}}</th>
			                                @if(\Auth::user()->type=='company')
			                                    <th scope="col" class="text-end">{{__('Action')}}</th>
			                                @endif
			                            </tr>
                                    </thead>
                                    <tbody>
			                            @foreach ($types as $type)
			                                <tr class="font-style">
			                                    <td>{{!empty($type->name)?$type->name:'' }}</td>
			                                    @if(\Auth::user()->type=='company')
			                                        <td class="action text-end">
			                                            <div class="action-btn bg-info ms-2">
			                                            	<a href="#" data-bs-toggle="tooltip" data-bs-placement="top" class="mx-3 btn btn-sm  align-items-center"
				                                    title="{{ __('Edit') }}" data-url="{{ route('contract_type.edit',$type->id) }}"
				                                    data-size="md" data-ajax-popup="true" data-title="{{ __('Edit Contract Type') }}">
				                                    <i class="ti ti-edit text-white" ></i>
				                                </a>


			                                              <!--   <a href="#" class="mx-3 btn btn-sm  align-items-center"  data-url="{{ route('contract_type.edit',$type->id) }}"
			                                                    data-bs-whatever="{{__('Edit Contract Type')}}" > <span class="text-white"> <i
			                                                            class="ti ti-edit" ></i></span></a> -->
			                                            </div>

			                                            <div class="action-btn bg-danger ms-2">
			                                                {!! Form::open(['method' => 'DELETE', 'route' => ['contract_type.destroy', $type->id]]) !!}
			                                                <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm ">
			                                                    <i class="ti ti-trash text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}"></i>
			                                                </a>
			                                                {!! Form::close() !!}


			                                            </div>
			                                        </td>
			                                    @endif
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
