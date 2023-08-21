@extends('layouts.main')
@php
$dir = asset(Storage::url('uploads/plan'));
@endphp
@section('page-title')
    {{ __('Plan') }}
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
                                <h4 class="m-b-10">{{ __('Plan') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Plan') }}</li>
                            </ul>
                        </div>
                        @if (Auth::user()->type == 'super admin')
                            <div class="col-md-6 d-flex justify-content-end text-right">
                                <div class="btn btn-sm btn-primary btn-icon m-1">
                                    <a href="#" data-url="{{ route('plan.create') }}" data-size="lg"
                                        data-ajax-popup="true" data-title="{{ __('Create New Plan') }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create Plan') }}">
                                        <i class="ti ti-plus text-white"></i></a>
                                    </a>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                @if (!empty($plans))
                    @foreach ($plans as $plan)
                        <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6">
                            <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s" style="
                                visibility: visible;
                                animation-delay: 0.2s;
                                animation-name: fadeInUp;
                              ">
                                <div class="card-body">
                                    <span class="price-badge bg-primary">{{ $plan->name }}</span>

                                    @if (\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                                        <div class="d-flex flex-row-reverse m-0 p-0 ">
                                            <span class="d-flex align-items-center ">
                                                <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                                <span class="ms-2">{{ __('Active') }}</span>
                                            </span>
                                        </div>
                                    @endif

                                    @if (\Auth::user()->type == 'super admin')
                                    <div class="d-flex flex-row-reverse m-0 p-0 ">
                                        <div class="action-btn bg-primary ms-2">
                                            <a href="#" class="btn btn-sm"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title={{ __("Edit Plan" ) }}
                                                data-ajax-popup="true" data-size="lg" data-title={{ __("Edit Plan" ) }}
                                                data-url="{{ route('plan.edit', $plan->id) }}" >
                                                <span class="text-white"><i class="ti ti-edit"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    <h1 class="mb-4 f-w-600 ">
                                        {{ env('CURRENCY_SYMBOL') . $plan->price }}<small class="text-sm">/{{ __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</small>
                                    </h1>

                                    <ul class="list-unstyled mt-5">
                                        <li>
                                            <span class="theme-avtar"> <i class="text-primary ti ti-circle-plus"></i> </span>
                                            {{ $plan->max_employee }} {{ __('Max Employee') }}
                                        </li>
                                        <li>
                                            <span class="theme-avtar"> <i class="text-primary ti ti-circle-plus"></i> </span>
                                            {{ $plan->company_storage }} {{ __('MB Storage Limit') }}
                                        </li>
                                        <li>
                                            <span class="theme-avtar"> <i class="text-primary ti ti-circle-plus"></i> </span>
                                            {{ __('Unlimited') }} {{ __('Location') }}
                                        </li>
                                    </ul>

                                    <h5 class="h6 mt-3">{{ $plan->description }}</h5>
                                    @if (\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                                        <p class="mb-0 mt-3"> {{ __('Expired ') }} :
                                            {{ \Auth::user()->plan_expire_date ? \Auth::user()->dateFormat(\Auth::user()->plan_expire_date) : __('Unlimited') }} <br>  </p>
                                    @endif

                                    @if (\Auth::user()->type == 'company' && \Auth::user()->plan != $plan->id)
                                        <div class="row">
                                            @if ($plan->price > 0)
                                                <div class="col-8">
                                                    <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                        class="btn bg-primary d-flex justify-content-center align-items-center text-white border-0"><i class="ti ti-shopping-cart m-1"></i>
                                                        {{ __('Subscribe ') }}
                                                    </a>
                                                </div>
                                            @endif

                                            @if ($plan->id != 1)
                                                <div class="col-4 mb-2">
                                                    @if (\Auth::user()->requested_plan != $plan->id)
                                                        <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                            class="btn  bg-primary d-flex justify-content-center align-items-center border-0">
                                                            <i class="ti ti-arrow-forward-up m-1 text-white"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                            class="btn  btn-danger d-flex justify-content-center align-items-center"
                                                            title="{{ __('Cancle Request') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top">
                                                            <span class="btn-inner--icon"><i
                                                                    class="fas fa-times m-1"></i></span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if (Auth::user()->type == 'super admin')
                <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6">
                    <a href="#" class="btn-addnew-project py-5" data-url="{{ route('plan.create') }}" data-size="lg"
                    data-ajax-popup="true" data-title="{{ __('Create New Plan') }}"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create Plan') }}">
                        <div class="bg-primary proj-add-icon py-2 mt-5">
                            <i class="ti ti-plus"></i>
                        </div>
                        <h6 class="mt-4 mb-2">{{ __('New Plan') }}</h6>
                        <p class="text-muted text-center mb-5">{{ __('Click here to add new plan') }}</p>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
