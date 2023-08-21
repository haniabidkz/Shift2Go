<h1>{{__('Email Verification Mail')}}</h1>

{{__('Please verify your email with bellow link:')}}
<a href="{{ route('user.verify', $token) }}">{{__('Verify Email')}}</a>
