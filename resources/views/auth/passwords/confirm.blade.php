@extends('layouts.auth')
@section('page-title')
    {{ __('Confirm Password') }}
@endsection
@section('content')
    <!-- Application container -->
    <div class="container-fluid container-application">
        <!-- Content -->
        <div class="main-content position-relative">
            <!-- Page content -->
            <div class="page-content">
                <div class="min-vh-100 py-5 d-flex align-items-center">
                    <div class="w-100">
                        <div class="row justify-content-center">
                            <div class="col-sm-8 col-lg-4">
                                <div class="card shadow zindex-100 mb-0">
                                    <div class="card-body px-md-5 py-5">
                                        <div class="mb-5">
                                            <h6 class="h3">{{ __('Confirm Password') }}</h6>                                            
                                            <p class="text-muted mb-0">{{ __('Please confirm your password before continuing.') }}</p>
                                        </div>
                                        <span class="clearfix"></span>

                                        <form method="POST" action="{{ route('password.confirm') }}">
                                            @csrf
                                            
                                            <div class="form-group">
                                                <label class="form-control-label">{{ __('Password') }}</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                </div>
                                            </div>
                    
                                            <div class="form-group">
                                                <label class="form-control-label">{{ __('Confirm Password') }}</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                    
                                            <div class="form-group row mb-0">
                                                <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
                                                    <a href="#"><span class="btn-inner--text text-white">{{ __('Confirm Password') }}</span></a>
                                                    <span class="btn-inner--icon"><i class="fas fa-long-arrow-alt-right"></i></span>
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="card-footer px-md-5"><small>{{ __('Not registered') }}?</small>
                                        <a href="{{ url('register') }}" class="small font-weight-bold">{{ __('Create account') }}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidenav-mask mask-body d-xl-none" data-action="sidenav-unpin" data-target="undefined"></div><div class="sidenav-mask mask-body d-xl-none" data-action="sidenav-unpin" data-target="undefined"></div></div>
    </div>
@endsection
