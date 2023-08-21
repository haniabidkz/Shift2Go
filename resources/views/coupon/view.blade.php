@extends('layouts.main')
@section('page-title')
    {{__('Coupon Detail')}}
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
                            <h4 class="m-b-10">{{ __('Coupon Detail') }}</h4>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('coupon.index') }}">{{ __('Coupons') }}</a></li>
                            <li class="breadcrumb-item">{{ __('Coupon Detail') }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end text-right"> </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sort" data-sort="name"> {{__('User')}}</th>
                                        <th scope="col" class="sort" data-sort="name"> {{__('Date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userCoupons as $userCoupon)
                                        <tr class="font-style">
                                            <td>{{ !empty($userCoupon->userDetail)?$userCoupon->userDetail->first_name:'' }}
                                                {{ !empty($userCoupon->userDetail)?$userCoupon->userDetail->last_name:'' }}</td>
                                            <td>{{ $userCoupon->created_at }}</td>
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
    {{-- <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0">{{$coupon->name}}</h6>
                </div>

            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col" class="sort" data-sort="name"> {{__('User')}}</th>
                    <th scope="col" class="sort" data-sort="name"> {{__('Date')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($userCoupons as $userCoupon)
                    <tr class="font-style">
                        <td>{{ !empty($userCoupon->userDetail)?$userCoupon->userDetail->name:'' }}</td>
                        <td>{{ $userCoupon->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div> --}}

@endsection

