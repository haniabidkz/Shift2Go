@extends('layouts.main')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

<style>
    @media (max-width: 500px) {
        .dashboard{
            width: 100% !important;
        }
    }
</style>

@section('content')
    <div class="dash-container">
        <div class="dash-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Dashboard') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-7 dashboard">
                    <div class="row">
                        <div class="col-lg-4 col-4 dashboard">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-primary mb-3">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Paid Users') }} : <span class="text-dark">{{ $user['total_paid_user'] }}</span></p>
                                    <h6 class="mb-3">{{ __('Total Users') }}</h6>
                                    <h3 class="mb-0">{{count($users)}}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-4 dashboard">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-info mb-3">
                                        <i class="ti ti-shopping-cart-plus"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total Order Amount') }} : <span class="text-dark">{{ env('CURRENCY_SYMBOL') . $user['total_orders_price'] }}</span></p>
                                    <h6 class="mb-3">{{ __('Total Orders') }}</h6>
                                    <h3 class="mb-0">{{ $user->total_orders }}</h3>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-4 dashboard">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-danger mb-3">
                                        <i class="ti ti-trophy"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Most Purchase Plan') }} : <span class="text-dark">{{ $user['most_purchese_plan'] }}</span></p>
                                    <h6 class="mb-3">{{ __('Total Plans') }}</h6>
                                    <h3 class="mb-0">{{ $user['total_plan'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5 dashboard">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Recent Order') }}</h5>
                            <h6 class="last-day-text text-end">{{ __('Last 7 Days') }}</h6>
                        </div>
                        <div class="card-body">
                            <div id="order-chart" data-color="primary" data-height="200"></div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection

@push('pagescript')
    <script>
        (function() {
            var options = {
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: [{
                    name: "Order",
                    data: {{ json_encode($chartData['data']) }},
                }],
                xaxis: {
                    labels: {
                        format: "MMM",
                        style: {
                            fontSize: "14px",
                            cssClass: "apexcharts-xaxis-label"
                        }
                    },
                    type: "text",
                    categories: JSON.parse("{{ json_encode($chartData['label']) }}".replace(/&quot;/g,'"')),
                },
                colors: ['#ffa21d'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },

            };
            var chart = new ApexCharts(document.querySelector("#order-chart"), options);
            chart.render();
        })();
    </script>
@endpush
