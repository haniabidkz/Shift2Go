@extends('layouts.main')
@push('pagescript')
    <script>
        $(document).on('click', '.code', function() {
            var type = $(this).val();
            if (type == 'manual') {
                $('#manual').removeClass('d-none');
                $('#manual').addClass('d-block');
                $('#auto').removeClass('d-block');
                $('#auto').addClass('d-none');
            } else {
                $('#auto').removeClass('d-none');
                $('#auto').addClass('d-block');
                $('#manual').removeClass('d-block');
                $('#manual').addClass('d-none');
            }
        });

        $(document).on('click', '#code-generate', function() {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush

@section('page-title')
    {{ __('Coupon') }}
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
                                <h4 class="m-b-10">{{ __('Coupon') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Coupon') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title="{{ __('Create New Coupon') }}" data-url="{{ route('coupon.create') }}"
                                    data-size="md" data-ajax-popup="true" data-title="{{ __('Create New Coupon') }}">
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
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name"> {{ __('Name') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="budget">{{ __('Code') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="status">
                                                {{ __('Discount (%)') }}</th>
                                            <th scope="col">{{ __('Limit') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Used') }}</th>
                                            <th scope="col" class="action text-end">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupons as $coupon)
                                            <tr>

                                                <td class="budget">{{ $coupon->name }} </td>
                                                <td>{{ $coupon->code }}</td>
                                                <td>
                                                    {{ $coupon->discount }}
                                                </td>
                                                <td>{{ $coupon->limit }}</td>
                                                <td>{{ $coupon->used_coupon() }}</td>

                                                <td class="Action text-end">
                                                    <span>
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{ route('coupon.show', $coupon->id) }}"
                                                                data-toggle="tooltip" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="{{ __('View') }}"
                                                                class="mx-3 btn btn-sm align-items-center"
                                                                >
                                                                <i class="ti ti-eye text-white"></i>
                                                            </a>
                                                        </div>

                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Edit') }}"
                                                                data-url="{{ route('coupon.edit', $coupon->id) }}"
                                                                data-size="lg" data-ajax-popup="true"
                                                                data-title="{{ __('Edit Coupon') }}"
                                                                class="mx-3 btn btn-sm align-items-center">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>

                                                        <div class="action-btn bg-danger ms-2" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{ __('Delete') }}">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['coupon.destroy', $coupon->id]]) !!}
                                                                <a href="#!" class="mx-3 btn btn-sm show_confirm">
                                                                    <i class="ti ti-trash text-white"></i>
                                                                </a>
                                                            {!! Form::close() !!}
                                                        </div>

                                                    </span>
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
