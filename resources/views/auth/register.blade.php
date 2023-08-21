@extends('layouts.auth')

@section('page-title')
    {{ __('Registration') }}
@endsection

@push('custom-scripts')
    @if (env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endpush

@section('lang-selectbox')
    <select name="language" id="language" class="btn btn-primary ms-2 me-2 language_option_bg"
        onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
        @foreach (\App\Models\Utility::languages() as $language)
            <option @if ($lang == $language) selected @endif value="{{ route('register', $language) }}">
                {{ Str::upper($language) }}</option>
        @endforeach
    </select>
@endsection

@section('content')
    <div class="card">
        <div class="row align-items-center text-start">
            <div class="col-xl-6">
                <div class="card-body">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-lg text-green-600 text-danger">
                            {{ __('Email SMTP settings does not configured so please contact to your site admin.') }}
                        </div>
                    @endif
                    <div class="">
                        <h2 class="mb-3 f-w-600">{{__('Register')}}</h2>
                    </div>
                    <div class="">
                        <form method="POST" action="{{ route('register') }}" role="form">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Enter First Name') }}</label>
                                <input id="first_name" type="text"
                                    class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                    value="{{ old('first_name') }}" required autocomplete="first_name" autofocus
                                    placeholder="{{ __('First Name') }}">
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Last Name') }}</label>
                                <input id="last_name" type="text"
                                    class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                    value="{{ old('last_name') }}" required autocomplete="last_name" autofocus
                                    placeholder="{{ __('Last Name') }}">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Company Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="company_name" value="{{ old('company_name') }}" required autocomplete="name"
                                    autofocus placeholder="{{ __('Company Name') }}">
                                @error('company_name')
                                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email"
                                    placeholder="{{ __('Email') }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="new-password" placeholder="{{ __('Password') }}">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="{{ __('Confirm Password') }}">
                            </div>
                            @if (env('RECAPTCHA_MODULE') == 'yes')
                                <div class="form-group mb-3">
                                    {!! NoCaptcha::display() !!}
                                    @error('g-recaptcha-response')
                                        <span class="small text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif

                            <div class="d-grid">
                                <button class="btn btn-primary btn-block mt-2">{{ __('Register') }}</button>
                            </div>
                        </form>
                    </div>
                    <p class="mb-2 my-4 text-center">{{ __('Already have an account?') }} <a href="{{route('login',$lang)}}" class="f-w-400 text-primary">{{ __('Login') }}</a></p>
                </div>
            </div>
            <div class="col-xl-6 img-card-side">
                <div class="auth-img-content">
                    <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                    <h3 class="text-white mb-4 mt-5">{{ __('“Attention is the new currency”') }}</h3>
                    <p class="text-white">
                        {{ __('The more effortless the writing looks, the more effort the writer
                        								actually put into the process.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
