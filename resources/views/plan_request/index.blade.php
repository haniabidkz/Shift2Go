@extends('layouts.main')
@section('page-title')
    {{ __('Plan-Request') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="titleIn h4 d-inline-block font-weight-400 mb-0">{{ __('Plan Request') }}</h5>
    </div>
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
                                <h4 class="m-b-10">{{ __('Plan Request') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Plan Request') }}</li>
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
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Plan Name') }}</th>
                                            <th>{{ __('Max Employee') }}</th>
                                            <th>{{ __('Duration') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($plan_requests->count() > 0)
                                            @foreach ($plan_requests as $prequest)
                                                <tr>
                                                    <td>
                                                        <div class="font-style font-weight-bold">
                                                            {{ !empty($prequest->user) ? $prequest->user->first_name : '' }}
                                                            {{ !empty($prequest->user) ? $prequest->user->last_name : '' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-style font-weight-bold">
                                                            {{ $prequest->plan->name }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="font-weight-bold">{{ $prequest->plan->max_employee }}
                                                        </div>
                                                        <div>{{ __('Employee') }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="font-style font-weight-bold">
                                                            {{ $prequest->duration == 'monthly' ? __('One Month') : __('One Year') }}
                                                        </div>
                                                    </td>
                                                    <td> {{ \App\Models\Utility::getDateFormated($prequest->created_at, true) }}
                                                    </td>
                                                    <td class="Action text-end">
                                                        <span>
                                                            <div class="action-btn bg-success ms-2">
                                                                <a href="{{ route('response.request', [$prequest->id, 1]) }}"
                                                                    data-toggle="tooltip" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="{{ __('Approve') }}"
                                                                    class="mx-3 btn btn-sm  align-items-center">
                                                                    <i class="ti ti-check text-white"></i>
                                                                </a>
                                                            </div>
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('response.request', [$prequest->id, 0]) }}"
                                                                    data-toggle="tooltip" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="{{ __('Disapprove') }}"
                                                                    class="mx-3 btn btn-sm  align-items-center">
                                                                    <i class="ti ti-x text-white"></i>
                                                                </a>
                                                            </div>

                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <th scope="col" colspan="7">
                                                    <h6 class="text-center">
                                                        {{ __('No Manually Plan Request Found.') }}
                                                    </h6>
                                                </th>
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
