@extends('layouts.auth')
@section('page-title')
    {{ __('Reset Password') }}
@endsection
@section('content')
    <div class="container-fluid container-application">
        <!-- Content -->
        <div class="main-content position-relative">
            <!-- Page content -->
            <div class="page-content">
                <div class="min-vh-100 py-5 d-flex align-items-center">
                    <div class="w-100">
                        <div class="row justify-content-center">
                            <div class="form-group auth-lang">                                
                                <select name="language" id="language" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                    @foreach(\App\Utility::languages() as $language)
                                        <option @if($lang == $language) selected @endif value="{{ route('password.request', $language) }}">{{Str::upper($language)}}</option>
                                    @endforeach
                                </select>                                
                            </div>
                            <div class="col-sm-8 col-lg-5 col-xl-4">
                                <div class="row justify-content-center mb-3">
                                    <a class="navbar-brand" href="#">
                                        <img src="{{ asset(Storage::url('uploads/logo/logo.png')) }}"  class="auth-logo" width="250" alt="{{ __('Company Logo') }}">
                                    </a>
                                </div>
                                <div class="card shadow zindex-100 mb-0">
                                    <div class="card-body px-md-5 py-5">
                                        <div class="mb-5">
                                            <h6 class="h3">{{ __('Password reset') }}</h6>
                                            <p class="text-muted mb-0">{{ __('Enter your email below to proceed.') }}</p>
                                        </div>
                                        <span class="clearfix"></span>
                                        @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif

                                        {{Form::open(array('route'=>'password.email','method'=>'post','id'=>'loginForm'))}}                                           

                                            <div class="form-group">
                                                <label class="form-control-label">{{ __('Email address') }}</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    </div>
                                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Enter Your Email') }}">
                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
                                                    <a href="#"><span class="btn-inner--text text-white">{{ __('Reset password') }}</span></a>                                                    
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer px-md-5"><small>{{ __('Back to?') }}</small>
                                        <a href="{{ url('login',$lang) }}" class="small font-weight-bold">{{ __('Login') }}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
