@extends('layouts.main')

@section('page-title')
    {{ __('Order') }}
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
                                <h4 class="m-b-10">{{ __('Order') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Order') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">

                        </div>
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
                                            <th scope="col" class="sort" data-sort="name"> {{ __('Order Id') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="budget">{{ __('Date') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Name') }}
                                            </th>
                                            <th scope="col">{{ __('Plan Name') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Price') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Payment Type') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Status') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Coupon') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Invoice') }}</th>
                                            @if(Auth::user()->type == 'super admin')
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $order->order_id }}</td>
                                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                                <td>{{ $order->user_name }}</td>
                                                <td>{{ $order->plan_name }}</td>
                                                <td>{{ env('CURRENCY_SYMBOL') . $order->price }}</td>
                                                <td>{{ $order->payment_type }}</td>
                                                <td>
                                                    @if($order->payment_status == 'succeeded' || $order->payment_status == 'success')
                                                        <span class="status_badge badge bg-primary  p-2 px-3 rounded">{{__('succeeded')}}</span>
                                                    @elseif($order->payment_status == 'Rejected' || $order->payment_status == 'Fail')
                                                        <span class="status_badge badge bg-danger p-2 px-3 rounded">{{ __('Rejected') }}</span>
                                                    @elseif($order->payment_status == 'pending')
                                                            <span class="status_badge badge bg-warning p-2 px-3 rounded">{{ __('Pending') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ !empty($order->total_coupon_used)? (!empty($order->total_coupon_used->coupon_detail)? $order->total_coupon_used->coupon_detail->code: '-'): '-' }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($order->receipt != 'free coupon' && $order->payment_type == 'STRIPE')
                                                        <a href="{{ $order->receipt }}" class="btn  btn-outline-primary" target="_blank">
                                                            <i class="fas fa-file-invoice"></i> {{ __('Invoice') }}
                                                        </a>
                                                    @elseif($order->receipt == 'free coupon')
                                                        <p>{{ __('Used 100 % discount coupon code.') }}</p>
                                                    @elseif($order->payment_type == 'Manually')
                                                        <p>{{ __('Manually plan upgraded by super admin') }}</p>
                                                    @elseif($order->payment_type == 'Bank Transfer')
                                                        <a href="{{ $order->receipt }}" class="btn  btn-outline-primary" target="_blank">
                                                            <i class="fas fa-file-invoice"></i> {{ __('Invoice') }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(Auth::user()->type == 'super admin')
                                                    <span>
                                                        @if($order->payment_type == 'Bank Transfer' && $order->payment_status == 'pending')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="#"
                                                                    data-url="{{ route('bankpays.show', $order->order_id) }}"
                                                                    data-size="md" data-ajax-popup="true"
                                                                    data-title="{{ __('Payment Status') }}"
                                                                    class="mx-3 btn btn-sm align-items-center">
                                                                    <i class="ti ti-caret-right text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Payment Status') }}"></i>
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div class="action-btn bg-danger ms-2" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{ __('Delete') }}">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['bankpays.destroy', $order->order_id]]) !!}
                                                                <a href="#!" class="mx-3 btn btn-sm show_confirm">
                                                                    <i class="ti ti-trash text-white"></i>
                                                                </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </span>
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
