@extends('layouts.auth')

@section('page-title')
    {{ __('Login') }}
@endsection

@push('custom-scripts')
@if(env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
@endif
@endpush

@section('lang-selectbox')
<select class="btn  btn-primary ms-2 me-2 language_option_bg" name="language" id="language"
    onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
    @foreach(\App\Models\Utility::languages() as $language)
    <option @if($lang == $language) selected @endif value="{{ route('login', $language) }}">{{Str::upper($language)}}</option>
    @endforeach
</select>
@endsection

@section('content')
    <div class="card">
        <div class="row align-items-center text-start">
            <div class="col-xl-6">
                <div class="card-body">
                    <div class="">
                        <h2 class="mb-3 f-w-600">{{ __('Login') }}</h2>
                    </div>
                    <form method="POST" id="form_data" action="{{ route('login') }}">
                        @csrf
                        <div class="">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus  placeholder="{{ __('Email address') }}">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                @if (Route::has('password.request'))
                                <div class="my-2">
									<a href="{{ route('password.request',$lang) }}" class="small text-muted  border-primary">
										{{ __('Forgot Your Password?') }}
                                    </a>
								</div>
                                @endif

                            </div>

                            @if(env('RECAPTCHA_MODULE') == 'yes')
                                <div class="form-group mt-3">
                                    {!! NoCaptcha::display() !!}
                                    @error('g-recaptcha-response')
                                    <span class="small text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            @endif
                            <div class="d-grid">
                                <button class="btn btn-primary btn-block mt-2">{{ __('Login') }}</button>
                            </div>
                            @if(Utility::getValByName('SIGNUP') == 'on')
                            <p class="my-4 text-center">{{ __("Don't have an account?") }}
                                <a href="{{route('register',$lang)}}" class="my-4 text-primary">{{__('Register')}}</a>
                            </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xl-6 img-card-side">
                <div class="auth-img-content">
                    <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                    <h3 class="text-white mb-4 mt-5">“{{ __('Attention is the new currency') }}”</h3>
                    <p class="text-white">{{ __('The more effortless the writing looks, the more effort the writer actually put into the process.') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('pagescript')
<script>
    $(document).ready(function () {
    $("#form_data").submit(function (e) {
        $("#login_button").attr("disabled", true);
        return true;
         });
    });
</script>
@endpush
