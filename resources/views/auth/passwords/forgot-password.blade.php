@extends('layouts.auth')

@section('page-title')
{{__('Reset Password')}}
@endsection

@push('custom-scripts')
@if(env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
@endif
@endpush

@section('lang-selectbox')
<select name="language" id="language" class="btn btn-primary ms-2 me-2 language_option_bg"
    onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
    @foreach(\App\Models\Utility::languages() as $language)
    <option @if($lang==$language) selected @endif value="{{ route('password.request',$language)}}">
        {{Str::upper($language)}}</option>
    @endforeach
</select>
@endsection

@section('content')
    <div class="card">
        <div class="row align-items-center text-start">
            <div class="col-xl-6">
                <div class="card-body">
                    <div class="">
                        <h2 class="mb-3 f-w-600">{{ __('Password Reset') }}</h2>
                    </div>
                    @if (session('status'))
                    <small class="text-muted">{{ session('status') }}</small>
                    @endif
                    <span class="clearfix"></span>
                    <div class="">
                           {{Form::open(array('route'=>'password.email','method'=>'post','id'=>'loginForm'))}}
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('Enter Email address') }}</label>
                                    {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                                    @error('email')
                                    <span class="invalid-email text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                @if(env('RECAPTCHA_MODULE') == 'yes')
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
                                    <button type="submit" id='saveBtn' class="btn btn-primary btn-block mt-2">{{ __(' Forgot Password') }}</button>
                                </div>
                            {{Form::close()}}
                            <p class="my-4 text-center">{{__('Back to')}}
                                <a href="{{route('login',$lang)}}" class="my-4 text-primary">{{ __('Login') }}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xl-6 img-card-side">
                <div class="auth-img-content">
                    <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                    <h3 class="text-white mb-4 mt-5">“{{ __('Attention is the new currency') }}”</h3>
                    <p class="text-white">{{ __('The more effortless the writing looks, the more effort the writer
                        actually put into the process.') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
