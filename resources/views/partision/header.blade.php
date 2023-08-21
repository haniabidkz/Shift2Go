@php
$unseenCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)->where('seen', 0)->count();
// $profile_pic = asset(Storage::url(Auth::user()->getUserInfo->DefaultProfilePic()));
$profile_pic=\App\Models\Utility::get_file(Auth::user()->getUserInfo->DefaultProfilePic());
// dd($profile_pic);
$name = !empty(Auth::user()->first_name.' '.Auth::user()->last_name) ? Auth::user()->first_name.' '. Auth::user()->last_name : Auth::user()->company_name;
$users = \Auth::user();
$currantLang = $users->currentLanguage();
if (empty($currantLang)) {
    $currantLang = 'en';
}
$languages = \App\Models\Utility::languages();
$footer_text = isset(\App\Models\Utility::settings()['footer_text']) ? \App\Models\Utility::settings()['footer_text'] : '';
$setting = \App\Models\Utility::colorset();
$SITE_RTL= isset($setting['SITE_RTL'])?$setting['SITE_RTL']:'off';
@endphp
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on' )

<header class="dash-header transprent-bg">
@else
<header class="dash-header">
@endif
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">

                        <img class="theme-avtar" @if (!empty($profile_pic)) src="{{ $profile_pic }}" @else  avatar="{{ $name }}" @endif>

                        {{-- <img src="{{(!empty($profile_pic))?  \App\Models\Utility::get_file($profile_pic): asset(Storage::url("uploads/avatar/avatar.png"))}}" class="img-fluid rounded-circle"> --}}
                        </span>
                        <span class="hide-mob ms-2">{{__('Hi,')}} {{ $name }}!</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{ url('profile/' . Crypt::encrypt(Auth::id())) }}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{ __('Profile') }}</span>
                        </a>
                        <a href="#!" class="dropdown-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti ti-power"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                        {!! Form::open(['method' => 'POST', 'route' => ['logout'], 'id' => 'logout-form', 'style' => 'display: none;']) !!}
                        {!! Form::close() !!}
                    </div>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                @if(\Auth::user()->type != 'super admin')
                    <li class="dash-h-item">
                        <a class="dash-head-link me-0" href="{{ url('chats') }}">
                            <i class="ti ti-message-circle"></i>
                            <span class="bg-danger dash-h-badge message-counter custom_messanger_counter">{{ $unseenCounter }}
                                <span class="sr-only"></span>
                            </span>
                        </a>
                    </li>
                @endif
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob text-uppercase">{{ $currantLang }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        @foreach ($languages as $language)
                            <a href="{{ route('change.language', $language) }}" class="dropdown-item @if ($language==$currantLang) active-language @endif">
                                <span> {{ Str::upper($language) }}</span>
                            </a>
                        @endforeach
                        @if (Auth::user()->type == 'super admin')
                            <div class="dropdown-divider m-0"></div>
                            <a href="{{ url('manage-language', Auth::user()->lang) }}"
                                class="dropdown-item text-primary">{{ __('Manage Language') }}</a>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
