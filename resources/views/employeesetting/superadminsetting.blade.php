@extends('layouts.main')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
// $logo = asset(Storage::url('uploads/logo/'));
$logo=\App\Models\Utility::get_file('uploads/logo/');
$meta_image=\App\Models\Utility::get_file('uploads/meta_image/');

$user = Auth::user();
$file_type = config('files_types');
$setting = App\Models\Utility::settings();


$local_storage_validation    = $setting['local_storage_validation'];
$local_storage_validations   = explode(',', $local_storage_validation);

$s3_storage_validation    = $setting['s3_storage_validation'];
$s3_storage_validations   = explode(',', $s3_storage_validation);

$wasabi_storage_validation    = $setting['wasabi_storage_validation'];
$wasabi_storage_validations   = explode(',', $wasabi_storage_validation);

@endphp

@section('content')
    <div class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Settings') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Settings') }}</li>
                            </ul>
                        </div>
                        {{-- <div class="col-md-6 d-flex justify-content-end text-end">
                        </div> --}}
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#Brand_Setting" class="list-group-item list-group-item-action border-0">
                                {{ __('Brand Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#Email_Setting" class="list-group-item list-group-item-action border-0">
                                {{ __('Email Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#Payment_Settings" class="list-group-item list-group-item-action border-0">
                                {{ __('Payment Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#Pusher_Setting" class="list-group-item list-group-item-action border-0">
                                {{ __('Pusher Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#ReCaptcha_Setting" class="list-group-item list-group-item-action border-0">
                                {{ __('ReCaptcha Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#Storage_Setting" class="list-group-item list-group-item-action border-0">
                                {{ __('Storage Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#Seo_Setting" class="list-group-item list-group-item-action border-0">
                                {{ __('SEO Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#Cache_Setting" class="list-group-item list-group-item-action border-0">
                                {{ __('Cache Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#Cookie_Setting" class="list-group-item list-group-item-action border-0">
                                {{ __('Cookie Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#pills-chatgpt-settings" class="list-group-item list-group-item-action border-0">
                                {{ __('Chat GPT Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="Brand_Setting" class="card">
                        <div class="card-header">
                            <h5>{{ __('Brand Settings   ') }}</h5>
                            <small class="text-muted">{{ __('Edit your Brand Settings') }}</small>
                        </div>
                        <div class="card-body">
                            {{ Form::model($settings, ['route' => ['setting.update', $profile->id], 'method' => 'PUT', 'class' => 'permission_table_information', 'enctype' => 'multipart/form-data']) }}

                            <div class="row mt-3">
                                {{-- Light Logo --}}
                                <div class="col-lg-4 col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Light Logo') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{$logo.(isset($light_logo) && !empty($light_logo)? $light_logo:'logo-light.png')}}" target="_blank">
                                                    <img src="{{$logo.(isset($light_logo) && !empty($light_logo)? $light_logo:'logo-light.png')}}"
                                                        class="big-logo img_setting" id="blah1" style="width: 150px"></a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="light_logo" class="form-label choose-files bg-primary "><i
                                                            class="ti ti-upload px-1"></i>{{ __('Select Image') }}</label>
                                                    <input type="file" name="light_logo" id="light_logo"
                                                        class="custom-input-file d-none" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">
                                                </div>

                                                 @error('light_logo')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                            @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Dark Logo --}}
                                <div class="col-lg-4 col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Dark Logo') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{$logo.(isset($dark_logo) && !empty($dark_logo)? $dark_logo:'logo-dark.png')}}" target="_blank">
                                                    <img src="{{$logo.(isset($logo_dark) && !empty($logo_dark)? $logo_dark:'logo-dark.png')}}"
                                                        class="big-logo" id="blah" style="width: 150px"></a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="dark_logo" class="form-label choose-files bg-primary "><i
                                                            class="ti ti-upload px-1"></i>{{ __('Select Image') }}</label>
                                                    <input type="file" name="dark_logo" id="dark_logo"
                                                        class="custom-input-file d-none"  onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                </div>
                                                @error('dark_logo')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Favicon Logo --}}
                                <div class="col-lg-4 col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Favicon') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{$logo.(isset($favicon) && !empty($favicon)? $favicon :'favicon.png')}}" target="_blank">
                                                    <img src="{{$logo.(isset($favicon) && !empty($favicon)? $company_ficon :'favicon.png')}}"
                                                        width="40px" class="big-logo img_setting" id="blah2"></a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="favicon" class="form-label choose-files bg-primary ">
                                                        <i class="ti ti-upload px-1"></i>{{ __('Select Image') }}
                                                    </label>
                                                    <input type="file" name="favicon" id="favicon"
                                                        class="custom-input-file d-none"  onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                                                </div>
                                                   @error('favicon')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                            @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    @error('logo')
                                        <div class="row">
                                            <span class="invalid-logo" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('title_text', __('Title Text'), ['class' => 'form-label text-dark']) }}
                                    {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Title Text')]) }}
                                    @error('title_text')
                                        <span class="invalid-title_text" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                @if (\Auth::user()->type == 'super admin')
                                    <div class="form-group col-md-4">
                                        {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('footer_text', null, ['class' => 'form-control', 'placeholder' => __('Footer Text')]) }}
                                        @error('footer_text')
                                            <span class="invalid-footer_text" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        {{ Form::label('', __('Default Languages'), ['class' => 'form-label text-dark']) }}
                                        <div class="changeLanguage js-single-select-custom">
                                            @php
                                                $user = Auth::user();
                                                if ($user) {
                                                    $currantLang = $user->currentLanguage();
                                                    $languages = \App\Models\Utility::languages();
                                                }

                                            @endphp
                                            <select name="default_language" id="default_language"
                                                class="form-control js-single-select1" aria-hidden="true">
                                                @if (isset($languages) && !empty($languages) && count($languages))
                                                    @foreach ($languages as $language)
                                                        <option value="{{ $language }}"
                                                            {{ $settings['default_language'] == $language ? 'selected' : '' }}>
                                                            <span>{{ Str::upper($language) }} </span>
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                {{-- <div class="form-group col-6 col-md-3">
                                    <div class="custom-control custom-switch p-0">
                                        <label class="form-label text-dark"
                                            for="gdpr_cookie">{{ __('GDPR Cookie') }}</label><br>
                                        <input type="checkbox" class="form-check-input gdpr_fulltime gdpr_type"
                                            data-toggle="switchbutton" data-onstyle="primary" name="gdpr_cookie"
                                            id="gdpr_cookie"
                                            {{ isset($settings['gdpr_cookie']) && $settings['gdpr_cookie'] == 'on' ? 'checked="checked"' : '' }}>
                                    </div>
                                </div> --}}

                                <div class="form-group col-6 col-md-3">
                                    <div class="custom-control form-switch p-0">
                                        <label class="form-label text-dark"
                                            for="display_landing_page">{{ __('Enable Landing Page') }}</label><br>
                                        <input type="checkbox" name="display_landing_page" class="form-check-input"
                                            id="display_landing_page" data-toggle="switchbutton"
                                            {{ $settings['display_landing_page'] == 'on' ? 'checked="checked"' : '' }}
                                            data-onstyle="primary">
                                    </div>
                                </div>

                                <div class="form-group col-6 col-md-3">
                                    <div class="custom-control form-switch p-0">
                                        <label class="form-label text-dark"
                                            for="SITE_RTL">{{ __('Enable RTL') }}</label><br>
                                        <input type="checkbox" class="form-check-input" data-toggle="switchbutton"
                                            data-onstyle="primary" name="SITE_RTL" id="SITE_RTL"
                                            {{ Utility::getValByName('SITE_RTL') == 'on' ? 'checked="checked"' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group col-6 col-md-3">
                                    <div class="custom-control form-switch p-0">
                                        <label class="form-label text-dark"
                                            for="SIGNUP">{{ __('Enable Sign-Up Page') }}</label><br>
                                        <input type="checkbox" name="SIGNUP" class="form-check-input" id="SIGNUP"
                                            data-toggle="switchbutton"
                                            {{ $settings['SIGNUP'] == 'on' ? 'checked="checked"' : '' }}
                                            data-onstyle="primary">
                                    </div>
                                </div>
                                <div class="form-group col-6 col-md-3">
                                    <div class="custom-control form-switch p-0">
                                        <label class="form-label text-dark"
                                            for="Email_verify">{{ __('Email verifiction') }}</label><br>
                                        <input type="checkbox" name="Email_verify" class="form-check-input" id="Email_verify"
                                            data-toggle="switchbutton"
                                            {{ $settings['Email_verify'] == 'on' ? 'checked="checked"' : '' }}
                                            data-onstyle="primary">
                                    </div>
                                </div>
                                {{-- <div class="form-group col-12 GDPR_Cookie_Text">
                                    {{ Form::label('cookie_text', __('GDPR Cookie Text'), ['class' => 'form-label text-dark']) }}
                                    {!! Form::textarea('cookie_text', $settings['cookie_text'], ['class' => 'form-control', 'rows' => '4']) !!}
                                </div> --}}
                                <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                                @php
                                    $setting = App\Models\Utility::colorset();
                                    $color = 'theme-3';
                                    if (!empty($setting['color'])) {
                                        $color = $setting['color'];
                                    }
                                @endphp
                                <div class="setting-card setting-logo-box p-3">
                                    <div class="row">
                                        <div class="col-4 my-auto">
                                            <h6 class="mt-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-credit-card me-2">
                                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                                </svg>
                                                {{ __('Primary color settings') }}
                                            </h6>
                                            <hr class="my-2">
                                            <div class="theme-color themes-color">
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-1' ? 'active_color' : '' }}" data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-1" {{ !empty($color) && $color == 'theme-1' ? 'checked' : '' }}>
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-2' ? 'active_color' : '' }}" data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-2" {{ !empty($color) && $color == 'theme-2' ? 'checked' : '' }}>
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-3' ? 'active_color' : '' }}" data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-3" {{ !empty($color) && $color == 'theme-3' ? 'checked' : '' }}>
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-4' ? 'active_color' : '' }}" data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-4" {{ !empty($color) && $color == 'theme-4' ? 'checked' : '' }}>
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-5' ? 'active_color' : '' }}" data-value="theme-5" onclick="check_theme('theme-5')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-5" {{ !empty($color) && $color == 'theme-5' ? 'checked' : '' }}>
                                                <br>
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-6' ? 'active_color' : '' }}" data-value="theme-6" onclick="check_theme('theme-6')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-6" {{ !empty($color) && $color == 'theme-6' ? 'checked' : '' }}>
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-7' ? 'active_color' : '' }}" data-value="theme-7" onclick="check_theme('theme-7')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-7" {{ !empty($color) && $color == 'theme-7' ? 'checked' : '' }}>
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-8' ? 'active_color' : '' }}" data-value="theme-8" onclick="check_theme('theme-8')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-8" {{ !empty($color) && $color == 'theme-8' ? 'checked' : '' }}>
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-9' ? 'active_color' : '' }}" data-value="theme-9" onclick="check_theme('theme-9')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-9" {{ !empty($color) && $color == 'theme-9' ? 'checked' : '' }}>
                                                <a href="#!" class="{{ !empty($color) && $color == 'theme-10' ? 'active_color' : '' }}" data-value="theme-10" onclick="check_theme('theme-10')"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-10" {{ !empty($color) && $color == 'theme-10' ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-4 my-auto">
                                            <h6>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-layout me-2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="3" y1="9" x2="21" y2="9"></line>
                                                    <line x1="9" y1="21" x2="9" y2="9"></line>
                                                </svg>
                                                {{ __('Sidebar settings') }}
                                            </h6>
                                            <hr class="my-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="cust-theme-bg"
                                                    name="cust_theme_bg"
                                                    {{ Utility::getValByName('cust_theme_bg') == 'on' ? 'checked' : '' }}>
                                                <label class="form-label text-dark f-w-600 pl-1"
                                                    for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-4 my-auto">
                                            <h6>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-sun me-2">
                                                    <circle cx="12" cy="12" r="5"></circle>
                                                    <line x1="12" y1="1" x2="12" y2="3"></line>
                                                    <line x1="12" y1="21" x2="12" y2="23"></line>
                                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                                    <line x1="1" y1="12" x2="3" y2="12"></line>
                                                    <line x1="21" y1="12" x2="23" y2="12"></line>
                                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                                                </svg>
                                                {{ __('Layout settings') }}
                                            </h6>
                                            <hr class="my-2">
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="cust-darklayout1"
                                                    name="cust_darklayout"
                                                    {{ Utility::getValByName('cust_darklayout') == 'on' ? 'checked' : '' }}>
                                                <label class="form-label text-dark f-w-600 pl-1"
                                                    for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group col-md-6">
                                    {{ Form::label('footer_link_1', __('Footer Link Title 1'), ['class' => 'form-label text-dark']) }}
                                    {{ Form::text('footer_link_1', null, ['class' => 'form-control', 'placeholder' => __('Enter Footer Link Title 1')]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('footer_value_1', __('Footer Link href 1'), ['class' => 'form-label text-dark']) }}
                                    {{ Form::text('footer_value_1', null, ['class' => 'form-control', 'placeholder' => __('Enter Footer Link 1')]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('footer_link_2', __('Footer Link Title 2'), ['class' => 'form-label text-dark']) }}
                                    {{ Form::text('footer_link_2', null, ['class' => 'form-control', 'placeholder' => __('Enter Footer Link Title 2')]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('footer_value_2', __('Footer Link href 2'), ['class' => 'form-label text-dark']) }}
                                    {{ Form::text('footer_value_2', null, ['class' => 'form-control', 'placeholder' => __('Enter Footer Link 2')]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('footer_link_3', __('Footer Link Title 3'), ['class' => 'form-label text-dark']) }}
                                    {{ Form::text('footer_link_3', null, ['class' => 'form-control', 'placeholder' => __('Enter Footer Link Title 3')]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('footer_value_3', __('Footer Link href 3'), ['class' => 'form-label text-dark']) }}
                                    {{ Form::text('footer_value_3', null, ['class' => 'form-control', 'placeholder' => __('Enter Footer Link 3')]) }}
                                </div>
                                <div class="card-footer text-end py-0 pe-2 border-0">
                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    <div id="Email_Setting" class="card">
                        <div class="card-header">
                            <h5>{{ __('Email     Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit email settings') }}</small>
                        </div>
                        <div class="">
                            {{ Form::model($settings, ['route' => ['email.setting'], 'method' => 'POST', 'class' => 'permission_table_information', 'enctype' => 'multipart/form-data']) }}
                            {{ Form::hidden('form_type', 'email_setting') }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('mail_driver', env('MAIL_DRIVER'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')]) }}
                                        @error('mail_driver')
                                            <span class="invalid-mail_driver" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('mail_host', env('MAIL_HOST'), ['class' => 'form-control ', 'placeholder' => __('Enter Mail Driver')]) }}
                                        @error('mail_host')
                                            <span class="invalid-mail_driver" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('mail_port', env('MAIL_PORT'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')]) }}
                                        @error('mail_port')
                                            <span class="invalid-mail_port" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('mail_username', env('MAIL_USERNAME'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
                                        @error('mail_username')
                                            <span class="invalid-mail_username" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-label text-dark']) }}
                                        <input class="form-control" placeholder="{{ __('Enter Mail Password') }}"
                                            name="mail_password" type="password" value="{{ env('MAIL_PASSWORD') }}"
                                            id="mail_password">
                                        @error('mail_password')
                                            <span class="invalid-mail_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('mail_encryption', env('MAIL_ENCRYPTION'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
                                        @error('mail_encryption')
                                            <span class="invalid-mail_encryption" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('mail_from_address', env('MAIL_FROM_ADDRESS'), ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
                                        @error('mail_from_address')
                                            <span class="invalid-mail_from_address" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('mail_from_name', env('MAIL_FROM_NAME'), ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
                                        @error('mail_from_name')
                                            <span class="invalid-mail_from_name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-6 mb-0">
                                        {{-- <a href="#" data-url="{{ route('test.mail') }}" data-ajax-popup="true"
                                            data-title="{{ __('Send Test Mail') }}" class="btn btn-primary">
                                            {{ __('Send Test Mail') }}
                                        </a> --}}
                                        <a href="#" data-url="{{ route('test.mail') }}"
                                        data-title="{{ __('Send Test Mail') }}"
                                        class="btn btn-primary btn-submit text-white send_email">
                                        {{ __('Send Test Mail') }}
                                    </a>
                                    </div>
                                    <div class="form-group col-6 text-end mb-0">
                                        {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-primary']) }}
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    <div id="Payment_Settings" class="card">
                        <div class="card-header">
                            <h5>{{ __('Payment Settings') }}</h5>
                            <small
                                class="text-muted">{{ __('These details will be used to collect subscription plan payments.
                                Each subscription plan will have a payment button based on the below configuration.') }}
                            </small>
                        </div>
                        <div class="card-body">
                            {{ Form::open(['route' => 'payment.setting', 'method' => 'post']) }}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('currency', __('Currency *'), ['class' => 'form-label text-dark']) }}
                                            {{ Form::text('currency', env('CURRENCY'), ['class' => 'form-control font-style', 'required']) }}
                                            <small class="text-dark">
                                                {{ __('Note: Add currency code as per three-letter ISO code.') }}<br>
                                                <a href="https://stripe.com/docs/currencies"
                                                    target="_blank">{{ __('You can find out how to do that here ') }}</a></small>
                                            <br>
                                            @error('currency')
                                                <span class="invalid-currency" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('currency_symbol', __('Currency Symbol *'), ['class' => 'form-label text-dark']) }}
                                            {{ Form::text('currency_symbol', env('CURRENCY_SYMBOL'), ['class' => 'form-control', 'required']) }}
                                            @error('currency_symbol')
                                                <span class="invalid-currency_symbol" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="faq">
                                        <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                             <!-- manul payment -->
                                             <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapseOne">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Manually') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_manul_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_manul_enabled" id="is_manul_enabled"
                                                                    {{ isset($admin_payment_setting['is_manul_enabled']) && $admin_payment_setting['is_manul_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_manul_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <p>{{ __('Requesting manual payment for the planned amount for the subcreiptions plan.')}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bank Transfer -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapseOne">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Bank Transfer') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_bank_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_bank_enabled" id="is_bank_enabled"
                                                                    {{ isset($admin_payment_setting['is_bank_enabled']) && $admin_payment_setting['is_bank_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_bank_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            {{ Form::label('bank_details', __('Bank Details'), ['class' => 'form-label']) }}
                                                            {{ Form::textarea('bank_details',isset($admin_payment_setting['bank_details']) ? $admin_payment_setting['bank_details'] : '',['class' => 'form-control','rows'=>"6"]) }}
                                                            <small class="text-muted">
                                                                {{__('Example:Bank:bank name</br> Account Number:0000 0000</br>')}}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Stripe -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Stripe') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>

                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_stripe_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_stripe_enabled" id="is_stripe_enabled"
                                                                    {{ isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_stripe_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('stripe_key', __('Stripe Key'), ['class' => 'form-label text-dark']) }}
                                                                    {{ Form::text('stripe_key', isset($admin_payment_setting['stripe_key']) ? $admin_payment_setting['stripe_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Stripe Key')]) }}
                                                                    @if ($errors->has('stripe_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('stripe_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('stripe_secret', __('Stripe Secret'), ['class' => 'form-label text-dark']) }}
                                                                    {{ Form::text('stripe_secret', isset($admin_payment_setting['stripe_secret']) ? $admin_payment_setting['stripe_secret'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Stripe Secret')]) }}
                                                                    @if ($errors->has('stripe_secret'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('stripe_secret') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Paypal -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwo">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTwo"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('PayPal') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paypal_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paypal_enabled" id="is_paypal_enabled"
                                                                    {{ isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_paypal_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"  data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="paypal-label col-form-label text-dark"
                                                                    for="paypal_mode">{{ __('Paypal Mode') }}</label> <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="paypal_mode"
                                                                                        value="sandbox" class="form-check-input"
                                                                                        {{ (isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == '') || (isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="paypal_mode"
                                                                                        value="live" class="form-check-input"
                                                                                        {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label for="paypal_client_id"
                                                                        class="form-label text-dark">{{ __('Client ID') }}</label>
                                                                    <input type="text" name="paypal_client_id" id="paypal_client_id"
                                                                        class="form-control"
                                                                        value="{{ isset($admin_payment_setting['paypal_client_id']) ? $admin_payment_setting['paypal_client_id'] : '' }}"
                                                                        placeholder="{{ __('Client ID') }}" />
                                                                    @if ($errors->has('paypal_client_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paypal_client_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label for="paypal_secret_key"
                                                                        class="form-label text-dark">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="paypal_secret_key"
                                                                        id="paypal_secret_key" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['paypal_secret_key']) ? $admin_payment_setting['paypal_secret_key'] : '' }}"
                                                                        placeholder="{{ __('Secret Key') }}" />
                                                                    @if ($errors->has('paypal_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paypal_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Paystack -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThree">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                        aria-expanded="false" aria-controls="collapseTwo">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Paystack') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paystack_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paystack_enabled" id="is_paypal_enabled"
                                                                    {{ isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_paystack_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="bussiness-hours">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id"
                                                                            class="form-label text-dark">{{ __('Public Key') }}</label>
                                                                        <input type="text" name="paystack_public_key"
                                                                            id="paystack_public_key"
                                                                            class="form-control form-label text-dark"
                                                                            value="{{ isset($admin_payment_setting['paystack_public_key']) ? $admin_payment_setting['paystack_public_key'] : '' }}"
                                                                            placeholder="{{ __('Public Key') }}" />
                                                                        @if ($errors->has('paystack_public_key'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('paystack_public_key') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key"
                                                                            class="form-label text-dark">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="paystack_secret_key"
                                                                            id="paystack_secret_key"
                                                                            class="form-control form-label text-dark"
                                                                            value="{{ isset($admin_payment_setting['paystack_secret_key']) ? $admin_payment_setting['paystack_secret_key'] : '' }}"
                                                                            placeholder="{{ __('Secret Key') }}" />
                                                                        @if ($errors->has('paystack_secret_key'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('paystack_secret_key') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- FLUTTERWAVE -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingFour">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFour"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Flutterwave') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_flutterwave_enabled" id="is_flutterwave_enabled"
                                                                    {{ isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_paystack_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paypal_client_id"
                                                                        class="form-label text-dark">{{ __('Public Key') }}</label>
                                                                    <input type="text" name="flutterwave_public_key"
                                                                        id="flutterwave_public_key" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['flutterwave_public_key']) ? $admin_payment_setting['flutterwave_public_key'] : '' }}"
                                                                        placeholder="{{ __('Public Key') }}" />
                                                                    @if ($errors->has('flutterwave_public_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('flutterwave_public_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paystack_secret_key"
                                                                        class="form-label text-dark">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="flutterwave_secret_key"
                                                                        id="flutterwave_secret_key"
                                                                        class="form-control form-label text-dark"
                                                                        value="{{ isset($admin_payment_setting['flutterwave_secret_key']) ? $admin_payment_setting['flutterwave_secret_key'] : '' }}"
                                                                        placeholder="{{ __('Secret Key') }}" />
                                                                    @if ($errors->has('flutterwave_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('flutterwave_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Razorpay -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingFive">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFive"
                                                        aria-expanded="false"
                                                        aria-controls="collapseFive">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Razorpay') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_razorpay_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_razorpay_enabled" id="is_razorpay_enabled"
                                                                    {{ isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_paystack_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paypal_client_id"
                                                                        class="form-label text-dark">{{ __('Public Key') }}</label>

                                                                    <input type="text" name="razorpay_public_key"
                                                                        id="razorpay_public_key" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['razorpay_public_key']) ? $admin_payment_setting['razorpay_public_key'] : '' }}"
                                                                        placeholder="{{ __('Public Key') }}" />
                                                                    @if ($errors->has('razorpay_public_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('razorpay_public_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paystack_secret_key"
                                                                        class="form-label text-dark">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="razorpay_secret_key"
                                                                        id="razorpay_secret_key" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['razorpay_secret_key']) ? $admin_payment_setting['razorpay_secret_key'] : '' }}"
                                                                        placeholder="{{ __('Secret Key') }}" />
                                                                    @if ($errors->has('razorpay_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('razorpay_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Mercado Pago -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingSix">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Mercado Pago') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_mercado_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_mercado_enabled" id="is_mercado_enabled"
                                                                    {{ isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_paystack_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="coingate-label form-label text-dark"
                                                                    for="mercado_mode">{{ __('Mercado Mode') }}</label>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="mercado_mode"
                                                                                        value="sandbox" class="form-check-input"
                                                                                        {{ (isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == '') || (isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="mercado_mode"
                                                                                        value="live" class="form-check-input"
                                                                                        {{ isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="mercado_access_token">{{ __('Access Token') }}</label>
                                                                    <input type="text" name="mercado_access_token"
                                                                        id="mercado_access_token" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['mercado_access_token']) ? $admin_payment_setting['mercado_access_token'] : '' }}"
                                                                        placeholder="{{ __('Access Token') }}" />
                                                                    @if ($errors->has('mercado_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('mercado_access_token') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Paytm -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingseven">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseseven" aria-expanded="false" aria-controls="collapseseven">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Paytm') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paytm_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paytm_enabled" id="is_paytm_enabled"
                                                                    {{ isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_paytm_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseseven" class="accordion-collapse collapse" aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="paypal-label form-label text-dark"
                                                                    for="paypal_mode">{{ __('Paytm Environment') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="paytm_mode"
                                                                                        value="local" class="form-check-input"
                                                                                        {{ (isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == '') || (isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'local') ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Local') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="paytm_mode"
                                                                                        value="production" class="form-check-input"
                                                                                        {{ isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Production') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paytm_public_key"
                                                                        class="form-label text-dark">{{ __('Merchant ID') }}</label>
                                                                    <input type="text" name="paytm_merchant_id"
                                                                        id="paytm_merchant_id" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['paytm_merchant_id']) ? $admin_payment_setting['paytm_merchant_id'] : '' }}"
                                                                        placeholder="{{ __('Merchant ID') }}" />
                                                                    @if ($errors->has('paytm_merchant_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytm_merchant_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paytm_secret_key"
                                                                        class="form-label text-dark">{{ __('Merchant Key') }}</label>
                                                                    <input type="text" name="paytm_merchant_key"
                                                                        id="paytm_merchant_key" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['paytm_merchant_key']) ? $admin_payment_setting['paytm_merchant_key'] : '' }}"
                                                                        placeholder="{{ __('Merchant Key') }}" />
                                                                    @if ($errors->has('paytm_merchant_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytm_merchant_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paytm_industry_type"
                                                                        class="form-label text-dark">
                                                                        {{ __('Industry Type') }}</label>
                                                                    <input type="text" name="paytm_industry_type"
                                                                        id="paytm_industry_type" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['paytm_industry_type']) ? $admin_payment_setting['paytm_industry_type'] : '' }}"
                                                                        placeholder="{{ __('Industry Type') }}" />
                                                                    @if ($errors->has('paytm_industry_type'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytm_industry_type') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Mollie -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingseven">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsedata" aria-expanded="false" aria-controls="collapseseven">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Mollie') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_mollie_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_mollie_enabled" id="is_mollie_enabled"
                                                                    {{ isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_mollie_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapsedata" class="accordion-collapse collapse" aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mollie_api_key"
                                                                        class="form-label text-dark">{{ __('Mollie Api Key') }}</label>
                                                                    <input type="text" name="mollie_api_key" id="mollie_api_key"
                                                                        class="form-control"
                                                                        value="{{ isset($admin_payment_setting['mollie_api_key']) ? $admin_payment_setting['mollie_api_key'] : '' }}"
                                                                        placeholder="{{ __('Mollie Api Key') }}" />
                                                                    @if ($errors->has('mollie_api_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('mollie_api_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mollie_profile_id"
                                                                        class="form-label text-dark">{{ __('Mollie Profile Id') }}</label>
                                                                    <input type="text" name="mollie_profile_id"
                                                                        id="mollie_profile_id" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['mollie_profile_id']) ? $admin_payment_setting['mollie_profile_id'] : '' }}"
                                                                        placeholder="{{ __('Mollie Profile Id') }}" />
                                                                    @if ($errors->has('mollie_profile_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('mollie_profile_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mollie_partner_id"
                                                                        class="form-label text-dark">{{ __('Mollie Partner Id') }}</label>
                                                                    <input type="text" name="mollie_partner_id"
                                                                        id="mollie_partner_id" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['mollie_partner_id']) ? $admin_payment_setting['mollie_partner_id'] : '' }}"
                                                                        placeholder="{{ __('Mollie Partner Id') }}" />
                                                                    @if ($errors->has('mollie_partner_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('mollie_partner_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Skrill -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapsedata1" aria-expanded="false" aria-controls="collapseOne">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Skrill') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_skrill_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_skrill_enabled" id="is_skrill_enabled"
                                                                    {{ isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_skrill_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapsedata1" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mollie_api_key"
                                                                        class="form-label text-dark">{{ __('Skrill Email') }}</label>
                                                                    <input type="email" name="skrill_email" id="skrill_email"
                                                                        class="form-control"
                                                                        value="{{ isset($admin_payment_setting['skrill_email']) ? $admin_payment_setting['skrill_email'] : '' }}"
                                                                        placeholder="{{ __('Mollie Api Key') }}" />
                                                                    @if ($errors->has('skrill_email'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('skrill_email') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- CoinGate -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapsedata2" aria-expanded="false" aria-controls="collapseOne">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('CoinGate') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_coingate_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_coingate_enabled" id="is_coingate_enabled"
                                                                    {{ isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_coingate_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapsedata2" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="coingate-label form-label text-dark"
                                                                    for="coingate_mode">{{ __('CoinGate Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="coingate_mode"
                                                                                        value="sandbox" class="form-check-input"
                                                                                        {{ (isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == '') || (isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="coingate_mode"
                                                                                        value="live" class="form-check-input"
                                                                                        {{ isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="coingate_auth_token"
                                                                        class="form-label text-dark">{{ __('CoinGate Auth Token') }}</label>
                                                                    <input type="text" name="coingate_auth_token"
                                                                        id="coingate_auth_token" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['coingate_auth_token']) ? $admin_payment_setting['coingate_auth_token'] : '' }}"
                                                                        placeholder="{{ __('CoinGate Auth Token') }}" />
                                                                    @if ($errors->has('coingate_auth_token'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('coingate_auth_token') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- PaymentWall -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapsedata3" aria-expanded="false" aria-controls="collapseOne">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('PaymentWall') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paymentwall_enabled" id="is_paymentwall_enabled"
                                                                    {{ isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_paymentwall_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapsedata3" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paypal_client_id"
                                                                        class="form-label text-dark">{{ __('Public Key') }}</label>
                                                                    <input type="text" name="paymentwall_public_key"
                                                                        id="paymentwall_public_key"
                                                                        class="form-control form-label text-dark"
                                                                        value="{{ isset($admin_payment_setting['paymentwall_public_key']) ? $admin_payment_setting['paymentwall_public_key'] : '' }}"
                                                                        placeholder="{{ __('Public Key') }}" />
                                                                    @if ($errors->has('paymentwall_public_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paymentwall_public_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paymentwall_secret_key"
                                                                        class="form-label text-dark">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="paymentwall_secret_key"
                                                                        id="paymentwall_secret_key"
                                                                        class="form-control form-label text-dark"
                                                                        value="{{ isset($admin_payment_setting['paymentwall_secret_key']) ? $admin_payment_setting['paymentwall_secret_key'] : '' }}"
                                                                        placeholder="{{ __('Secret Key') }}" />
                                                                    @if ($errors->has('paymentwall_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paymentwall_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Toyyibpay -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapsedata4" aria-expanded="false" aria-controls="collapseOne">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Toyyibpay') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_toyyibpay_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_toyyibpay_enabled" id="is_toyyibpay_enabled"
                                                                    {{ isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_toyyibpay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapsedata4" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="toyyibpay_secret_key"
                                                                        class="form-label">{{ __('Secret key') }}</label>
                                                                    <input type="text" name="toyyibpay_secret_key"
                                                                        id="toyyibpay_secret_key" class="form-control form-label"
                                                                        value="{{ isset($admin_payment_setting['toyyibpay_secret_key']) ? $admin_payment_setting['toyyibpay_secret_key'] : '' }}"
                                                                        placeholder="{{ __('Secret key') }}" />
                                                                    @if ($errors->has('toyyibpay_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('toyyibpay_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="toyyibpay_category_code"
                                                                        class="form-label">{{ __('Category Code') }}</label>
                                                                    <input type="text" name="toyyibpay_category_code"
                                                                        id="toyyibpay_category_code" class="form-control form-label"
                                                                        value="{{ isset($admin_payment_setting['toyyibpay_category_code']) ? $admin_payment_setting['toyyibpay_category_code'] : '' }}"
                                                                        placeholder="{{ __('Category Code') }}" />
                                                                    @if ($errors->has('toyyibpay_category_code'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('toyyibpay_category_code') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Payfast -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingseven">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapsedata5" aria-expanded="false" aria-controls="collapseseven">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Payfast') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_payfast_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_payfast_enabled" id="is_payfast_enabled"
                                                                    {{ isset($admin_payment_setting['is_payfast_enabled']) && $admin_payment_setting['is_payfast_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_payfast_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapsedata5" class="accordion-collapse collapse" aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="paypal-label col-form-label text-dark"
                                                                    for="payfast_mode">{{ __('Payfast Mode') }}</label> <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="payfast_mode"
                                                                                        value="sandbox" class="form-check-input"
                                                                                        {{ (isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == '') || (isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="payfast_mode"
                                                                                        value="live" class="form-check-input"
                                                                                        {{ isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="payfast_merchant_id"
                                                                        class="form-label text-dark">{{ __('Merchant ID') }}</label>
                                                                    <input type="text" name="payfast_merchant_id" id="payfast_merchant_id"
                                                                        class="form-control"
                                                                        value="{{ isset($admin_payment_setting['payfast_merchant_id']) ? $admin_payment_setting['payfast_merchant_id'] : '' }}"
                                                                        placeholder="{{ __('Merchant ID') }}" />
                                                                    @if ($errors->has('payfast_merchant_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payfast_merchant_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="payfast_merchant_key"
                                                                        class="form-label text-dark">{{ __('Merchant Key') }}</label>
                                                                    <input type="text" name="payfast_merchant_key"
                                                                        id="payfast_merchant_key" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['payfast_merchant_key']) ? $admin_payment_setting['payfast_merchant_key'] : '' }}"
                                                                        placeholder="{{ __('Merchant Key') }}" />
                                                                    @if ($errors->has('payfast_merchant_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payfast_merchant_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="payfast_salt_passphrase"
                                                                        class="form-label text-dark">{{ __('Salt Passphrase') }}</label>
                                                                    <input type="text" name="payfast_salt_passphrase"
                                                                        id="payfast_salt_passphrase" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['payfast_salt_passphrase']) ? $admin_payment_setting['payfast_salt_passphrase'] : '' }}"
                                                                        placeholder="{{ __('Salt Passphrase') }}" />
                                                                    @if ($errors->has('payfast_salt_passphrase'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payfast_salt_passphrase') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                             <!-- Iyzipay -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingseven">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapseseven">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('Iyzipay') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_Iyzipay_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_Iyzipay_enabled" id="is_Iyzipay_enabled"
                                                                    {{ isset($admin_payment_setting['is_Iyzipay_enabled']) && $admin_payment_setting['is_Iyzipay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_Iyzipay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="paypal-label col-form-label text-dark"
                                                                    for="iyzipay_mode">{{ __('Iyzipay Mode') }}</label> <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="iyzipay_mode"
                                                                                        value="sandbox" class="form-check-input"
                                                                                        {{ (isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == '') || (isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="iyzipay_mode"
                                                                                        value="live" class="form-check-input"
                                                                                        {{ isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="iyzipay_public_key"
                                                                        class="form-label text-dark">{{ __('iyzipay key') }}</label>
                                                                    <input type="text" name="iyzipay_public_key"
                                                                        id="iyzipay_public_key" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['iyzipay_public_key']) ? $admin_payment_setting['iyzipay_public_key'] : '' }}"
                                                                        placeholder="{{ __('iyzipay key') }}" />
                                                                    @if ($errors->has('iyzipay_public_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('iyzipay_public_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="iyzipay_secret_key"
                                                                        class="form-label text-dark">{{ __('iyzipay secret Key') }}</label>
                                                                    <input type="text" name="iyzipay_secret_key"
                                                                        id="iyzipay_secret_key" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['iyzipay_secret_key']) ? $admin_payment_setting['iyzipay_secret_key'] : '' }}"
                                                                        placeholder="{{ __('iyzipay secret Key') }}" />
                                                                    @if ($errors->has('iyzipay_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('iyzipay_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- SSPAY -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingseven">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse9" aria-expanded="false" aria-controls="collapseseven">
                                                        <span class="d-flex align-items-center">
                                                            <b>{{ __('SSPAY') }}</b>
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('Enable:')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_sspay_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_sspay_enabled" id="is_sspay_enabled"
                                                                    {{ isset($admin_payment_setting['is_sspay_enabled']) && $admin_payment_setting['is_sspay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_sspay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse9" class="accordion-collapse collapse" aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="sspay_secret_key"
                                                                        class="form-label text-dark">{{ __('sspay secret key') }}</label>
                                                                    <input type="text" name="sspay_secret_key"
                                                                        id="sspay_secret_key" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['sspay_secret_key']) ? $admin_payment_setting['sspay_secret_key'] : '' }}"
                                                                        placeholder="{{ __('sspay secret key') }}" />
                                                                    @if ($errors->has('sspay_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('sspay_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="sspay_category_code"
                                                                        class="form-label text-dark">{{ __('sspay code') }}</label>
                                                                    <input type="text" name="sspay_category_code"
                                                                        id="sspay_category_code" class="form-control"
                                                                        value="{{ isset($admin_payment_setting['sspay_category_code']) ? $admin_payment_setting['sspay_category_code'] : '' }}"
                                                                        placeholder="{{ __('sspay code') }}" />
                                                                    @if ($errors->has('sspay_category_code'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('sspay_category_code') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end mt-4 py-0 pe-2 border-0">
                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-primary']) }}
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    <div id="Pusher_Setting" class="card">
                        {{ Form::model($settings, ['route' => ['pusher.setting'], 'method' => 'POST', 'class' => 'permission_table_information', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <h5 class="">{{ __('Pusher Settings') }}</h5>
                                    <small
                                        class="text-secondary font-weight-bold">{{ __('Edit Pusher settings') }}</small>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                    <input type="hidden" name="is_mercado_enabled" value="off">
                                    <input type="checkbox" name="enable_chat" id="enable_chat" data-toggle="switchbutton"
                                        data-onstyle="primary"
                                        {{ !empty(env('ENABLE_CHAT')) && env('ENABLE_CHAT') == 'on' ? 'checked="checked"' : '' }}>
                                    <label class="custom-label form-label" for="enable_chat"></label>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {{ Form::label('', __('Pusher App Id'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('pusher_app_id', env('PUSHER_APP_ID'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Pusher App Id')]) }}
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('', __('Pusher App Key'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('pusher_app_key', env('PUSHER_APP_KEY'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Pusher App Key')]) }}
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('', __('Pusher App Secret'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('pusher_app_secret', env('PUSHER_APP_SECRET'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Pusher App Secret')]) }}
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('', __('Pusher App Cluster'), ['class' => 'form-label text-dark']) }}
                                        {{ Form::text('pusher_app_cluster', env('PUSHER_APP_CLUSTER'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Pusher App Cluster')]) }}
                                    </div>
                                </div>
                                {{-- <div class="card-footer text-end py-0 pe-2 border-0">
                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-primary']) }}
                                </div> --}}
                            </div>
                            <div class="card-footer">
                                <div class="col-lg-12  text-end">
                                    <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div id="ReCaptcha_Setting" class="card">
                        <form method="POST" action="{{ route('recaptcha.settings.store') }}" accept-charset="UTF-8">
                            @csrf
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5>{{ __('ReCaptcha Settings') }}</h5>
                                        <small class="text-muted"><a
                                                href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                target="_blank" class="text-blue">
                                                <small>({{ __('How to Get Google reCaptcha Site and Secret key') }})</small>
                                            </a> </small>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        <input type="checkbox" name="recaptcha_module" id="recaptcha_module"
                                            data-toggle="switchbutton" value="yes" data-onstyle="primary"
                                            {{ env('RECAPTCHA_MODULE') == 'yes' ? 'checked="checked"' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            <label for="google_recaptcha_key"
                                                class="form-label text-dark">{{ __('Google Recaptcha Key') }}</label>
                                            <input class="form-control"
                                                placeholder="{{ __('Enter Google Recaptcha Key') }}"
                                                name="google_recaptcha_key" type="text"
                                                value="{{ env('NOCAPTCHA_SITEKEY') }}" id="google_recaptcha_key">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            <label for="google_recaptcha_secret"
                                                class="form-label text-dark">{{ __('Google Recaptcha Secret') }}</label>
                                            <input class="form-control "
                                                placeholder="{{ __('Enter Google Recaptcha Secret') }}"
                                                name="google_recaptcha_secret" type="text"
                                                value="{{ env('NOCAPTCHA_SECRET') }}" id="google_recaptcha_secret">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="col-lg-12  text-end">
                                        <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!--storage Setting-->
                    <div id="Storage_Setting" class="card mb-3">
                        {{ Form::open(array('route' => 'storage.setting.store', 'enctype' => "multipart/form-data")) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="">{{ __('Storage Settings') }}</h5>
                                        <small class="text-muted">{{ __('Edit storage settings') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="pe-2">
                                        <input type="radio" class="btn-check" name="storage_setting" id="local-outlined" autocomplete="off" {{  $settings['storage_setting'] == 'local'?'checked':'' }} value="local" checked>
                                        <label class="btn btn-outline-primary" for="local-outlined">{{ __('Local') }}</label>
                                    </div>
                                    <div  class="pe-2">
                                        <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined" autocomplete="off" {{  $settings['storage_setting']=='s3'?'checked':'' }}  value="s3">
                                        <label class="btn btn-outline-primary" for="s3-outlined"> {{ __('AWS S3') }}</label>
                                    </div>
                                    <div  class="pe-2">
                                        <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined" autocomplete="off" {{  $settings['storage_setting']=='wasabi'?'checked':'' }} value="wasabi">
                                        <label class="btn btn-outline-primary" for="wasabi-outlined">{{ __('Wasabi') }}</label>
                                    </div>
                                </div>
                                <div class="local-setting row">
                                    {{-- <h4 class="small-title">{{ __('Local Settings') }}</h4> --}}
                                    <div class="form-group col-8 switch-width">
                                        {{Form::label('local_storage_validation',__('Only Upload Files'),array('class'=>' form-label')) }}
                                            <select name="local_storage_validation[]" class="form-control multi-select"  id="local_storage_validation"  multiple>
                                                @foreach($file_type as $f)
                                                    <option @if (in_array($f, $local_storage_validations)) selected @endif>{{$f}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)')}}</label>
                                            <input type="number" name="local_storage_max_upload_size" class="form-control" value="{{(!isset($settings['local_storage_max_upload_size']) || is_null($settings['local_storage_max_upload_size'])) ? '' : $settings['local_storage_max_upload_size']}}" placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="s3-setting row {{  $settings['storage_setting']=='s3'?' ':'d-none' }}">
                                    <div class=" row ">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_key">{{ __('S3 Key') }}</label>
                                                <input type="text" name="s3_key" class="form-control" value="{{(!isset($settings['s3_key']) || is_null($settings['s3_key'])) ? '' : $settings['s3_key']}}" placeholder="{{ __('S3 Key') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_secret">{{ __('S3 Secret') }}</label>
                                                <input type="text" name="s3_secret" class="form-control" value="{{(!isset($settings['s3_secret']) || is_null($settings['s3_secret'])) ? '' : $settings['s3_secret']}}" placeholder="{{ __('S3 Secret') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_region">{{ __('S3 Region') }}</label>
                                                <input type="text" name="s3_region" class="form-control" value="{{(!isset($settings['s3_region']) || is_null($settings['s3_region'])) ? '' : $settings['s3_region']}}" placeholder="{{ __('S3 Region') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_bucket">{{ __('S3 Bucket') }}</label>
                                                <input type="text" name="s3_bucket" class="form-control" value="{{(!isset($settings['s3_bucket']) || is_null($settings['s3_bucket'])) ? '' : $settings['s3_bucket']}}" placeholder="{{ __('S3 Bucket') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_url">{{ __('S3 URL')}}</label>
                                                <input type="text" name="s3_url" class="form-control" value="{{(!isset($settings['s3_url']) || is_null($settings['s3_url'])) ? '' : $settings['s3_url']}}" placeholder="{{ __('S3 URL')}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_endpoint">{{ __('S3 Endpoint')}}</label>
                                                <input type="text" name="s3_endpoint" class="form-control" value="{{(!isset($settings['s3_endpoint']) || is_null($settings['s3_endpoint'])) ? '' : $settings['s3_endpoint']}}" placeholder="{{ __('S3 Bucket') }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-8 switch-width">
                                            {{Form::label('s3_storage_validation',__('Only Upload Files'),array('class'=>' form-label')) }}
                                            {{-- {{ Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => 'form-label']) }} --}}
                                                <select name="s3_storage_validation[]" class="form-control multi-select" id="s3_storage_validation" multiple>
                                                    @foreach($file_type as $f)
                                                        <option @if (in_array($f, $s3_storage_validations)) selected @endif>{{$f}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_max_upload_size">{{ __('Max upload size ( In KB)')}}</label>
                                                <input type="number" name="s3_max_upload_size" class="form-control" value="{{(!isset($settings['s3_max_upload_size']) || is_null($settings['s3_max_upload_size'])) ? '' : $settings['s3_max_upload_size']}}" placeholder="{{ __('Max upload size') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="wasabi-setting row {{  $settings['storage_setting']=='wasabi'?' ':'d-none' }}">
                                    <div class=" row ">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_key">{{ __('Wasabi Key') }}</label>
                                                <input type="text" name="wasabi_key" class="form-control" value="{{(!isset($settings['wasabi_key']) || is_null($settings['wasabi_key'])) ? '' : $settings['wasabi_key']}}" placeholder="{{ __('Wasabi Key') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_secret">{{ __('Wasabi Secret') }}</label>
                                                <input type="text" name="wasabi_secret" class="form-control" value="{{(!isset($settings['wasabi_secret']) || is_null($settings['wasabi_secret'])) ? '' : $settings['wasabi_secret']}}" placeholder="{{ __('Wasabi Secret') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_region">{{ __('Wasabi Region') }}</label>
                                                <input type="text" name="wasabi_region" class="form-control" value="{{(!isset($settings['wasabi_region']) || is_null($settings['wasabi_region'])) ? '' : $settings['wasabi_region']}}" placeholder="{{ __('Wasabi Region') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="wasabi_bucket">{{ __('Wasabi Bucket') }}</label>
                                                <input type="text" name="wasabi_bucket" class="form-control" value="{{(!isset($settings['wasabi_bucket']) || is_null($settings['wasabi_bucket'])) ? '' : $settings['wasabi_bucket']}}" placeholder="{{ __('Wasabi Bucket') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="wasabi_url">{{ __('Wasabi URL')}}</label>
                                                <input type="text" name="wasabi_url" class="form-control" value="{{(!isset($settings['wasabi_url']) || is_null($settings['wasabi_url'])) ? '' : $settings['wasabi_url']}}" placeholder="{{ __('Wasabi URL')}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="wasabi_root">{{ __('Wasabi Root')}}</label>
                                                <input type="text" name="wasabi_root" class="form-control" value="{{(!isset($settings['wasabi_root']) || is_null($settings['wasabi_root'])) ? '' : $settings['wasabi_root']}}" placeholder="{{ __('Wasabi Bucket') }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-8 switch-width">
                                            {{Form::label('wasabi_storage_validation',__('Only Upload Files'),array('class'=>'form-label')) }}

                                            <select name="wasabi_storage_validation[]" class="form-control multi-select" id="wasabi_storage_validation" multiple>
                                                @foreach($file_type as $f)
                                                    <option @if (in_array($f, $wasabi_storage_validations)) selected @endif>{{$f}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label" for="wasabi_root">{{ __('Max upload size ( In KB)')}}</label>
                                                <input type="number" name="wasabi_max_upload_size" class="form-control" value="{{(!isset($settings['wasabi_max_upload_size']) || is_null($settings['wasabi_max_upload_size'])) ? '' : $settings['wasabi_max_upload_size']}}" placeholder="{{ __('Max upload size') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-lg-12  text-end">
                                    <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                                </div>
                            </div>
                        {{Form::close()}}
                    </div>

                    <!--Seo Setting-->
                    <div id="Seo_Setting" class="card">
                        {{ Form::model($settings, ['route' => ['seo.setting.store'], 'method' => 'POST', 'class' => 'permission_table_information', 'enctype' => 'multipart/form-data']) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="">{{ __('SEO settings') }}</h5>
                                        <small class="text-secondary font-weight-bold">{{__('Edit SEO settings')}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mt-6">
                                    <div class="text-end">
                                        <a href="#" class="btn btn-print-invoice btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['seo']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
                                            <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
                                        </a>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('meta_keyword', __('Meta Keywords'), ['class' => 'form-label']) }}
                                            {{ Form::text('meta_keyword', null, ['class' => 'form-control', 'placeholder' => __('Meta Keywords')]) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('meta_description', __('Meta Description'), ['class' => 'form-label']) }}
                                            {{ Form::textarea('meta_description', null, ['class' => 'form-control', 'rows'=>'3' , 'placeholder' => __('Meta Description')]) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            {{ Form::label('meta_image', __('Meta Image'), ['class' => 'form-label']) }}
                                            <a href="{{$meta_image.(isset($image) && !empty($image)? $image:'meta_image.png')}}" target="_blank">
                                            <img src="{{$meta_image.(isset($image) && !empty($image)? $image:'meta_image.png')}}"
                                            class="big-logo img_setting" id="meta" style="width: 100%"></a>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_image" class="form-label choose-files bg-primary "><i
                                                    class="ti ti-upload px-1"></i>{{ __('Select Image') }}</label>
                                            <input type="file" name="meta_image" id="meta_image"
                                                class="custom-input-file d-none"  onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-lg-12  text-end">
                                    <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                                </div>
                            </div>
                        {{Form::close()}}
                    </div>

                    <!--cache-->
                    <div id="Cache_Setting" class="card">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card-header">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="">{{ __('Cache Settings') }}</h5>
                                        <small class="text-secondary font-weight-bold">
                                            {{__('This is a page meant for more advanced users, simply ignore it if you don`t understand what cache is.')}}
                                        </small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('', __('Current cache size'), ['class' => 'form-label']) }}
                                            <div class="input-group search-form">
                                                <input type="text" value="{{ App\Models\Utility::GetCacheSize() }}" class="form-control" readonly ="true" >
                                                <span class="input-group-text bg-transparent">{{ __('MB')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" text-end">
                                        <a href="{{ url('config-cache') }}" class="btn  btn-primary">{{ __('Clear Cache') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Cookie_Setting-->
                    <div class="card" id="Cookie_Setting">
                        {{Form::model($settings,array('route'=>'dashboard-store','method'=>'post'))}}
                        <div class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                            <h5>{{ __('Cookie Settings') }}</h5>
                            <div class="d-flex align-items-center">
                                {{ Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold me-3']) }}
                                <div class="custom-control custom-switch"  onclick="enablecookie()">
                                    <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" name="cookie_consent" class="form-check-input input-primary "
                                           id="cookie_consent" {{ $settings['cookie_consent'] == 'on' ? ' checked ' : '' }} >
                                    <label class="custom-control-label mb-1" for="cookie_consent"></label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body cookieDiv {{ $settings['cookie_consent'] == 'off' ? 'disabledCookie ' : '' }}">
                            <div class="row ">
                                <div class="text-end">
                                    <a href="#" class="btn btn-print-invoice btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['cookie']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
                                        <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                        <input type="checkbox" name="cookie_logging" class="form-check-input input-primary cookie_setting"
                                               id="cookie_logging"{{ $settings['cookie_logging'] == 'on' ? ' checked ' : '' }}>
                                        <label class="form-check-label" for="cookie_logging">{{__('Enable logging')}}</label>
                                    </div>
                                    <div class="form-group" >
                                        {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label' ]) }}
                                        {{ Form::text('cookie_title', null, ['class' => 'form-control cookie_setting'] ) }}
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label']) }}
                                        {!! Form::textarea('cookie_description', null, ['class' => 'form-control cookie_setting', 'rows' => '3']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch custom-switch-v1 ">
                                        <input type="checkbox" name="necessary_cookies" class="form-check-input input-primary"
                                               id="necessary_cookies" checked onclick="return false">
                                        <label class="form-check-label" for="necessary_cookies">{{__('Strictly necessary cookies')}}</label>
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('strictly_cookie_title', null, ['class' => 'form-control cookie_setting']) }}
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label']) }}
                                        {!! Form::textarea('strictly_cookie_description', null, ['class' => 'form-control cookie_setting ', 'rows' => '3']) !!}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h5>{{__('More Information')}}</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        {{ Form::label('contact_us_description', __('Contact Us Description'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('contact_us_description', null, ['class' => 'form-control cookie_setting']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        {{ Form::label('contact_us_url', __('Contact Us URL'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('contact_us_url', null, ['class' => 'form-control cookie_setting']) }}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-6 mb-0">
                                    @if(isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on')
                                        <label for="file" class="form-label">{{__('Download cookie accepted data')}}</label>
                                        <a href="{{ asset(Storage::url('uploads/sample')) . '/data.csv' }}" class="btn btn-primary mr-2 ">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    @endif
                                </a>
                                </div>
                                <div class="form-group col-6 text-end mb-0">
                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                     <!--Chat_GPT_Setting-->
                    <div class="card" id="pills-chatgpt-settings">
                        {{Form::model($settings,array('route'=>'settings.chatgptkey','method'=>'post'))}}
                            <div class="card-header">
                                <h5>{{ __('Chat GPT Key Settings') }}</h5>
                                <small>{{ __('Edit your key details') }}</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group">
                                        {{ Form::text('chatgpt_key',isset($settings['chatgpt_key']) ? $settings['chatgpt_key'] : '',['class'=>'form-control','placeholder'=>__('Enter Chatgpt Key Here')]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('pagescript')

<script type="text/javascript">
    function enablecookie() {
        const element = $('#cookie_consent').is(':checked');
        $('.cookieDiv').addClass('disabledCookie');
        if (element==true) {
            $('.cookieDiv').removeClass('disabledCookie');
            $("#cookie_logging").attr('checked', true);
        } else {
            $('.cookieDiv').addClass('disabledCookie');
            $("#cookie_logging").attr('checked', false);
        }
    }
</script>

<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300,
    })
    $(".list-group-item").click(function(){
        $('.list-group-item').filter(function(){
            return this.href == id;
        }).parent().removeClass('text-primary');
    });

    function check_theme(color_val) {
        $('#theme_color').prop('checked', false);
        $('input[value="' + color_val + '"]').prop('checked', true);
    }

    $(document).on('change','[name=storage_setting]',function(){
    if($(this).val() == 's3'){
        $('.s3-setting').removeClass('d-none');
        $('.wasabi-setting').addClass('d-none');
        $('.local-setting').addClass('d-none');
    }else if($(this).val() == 'wasabi'){
        $('.s3-setting').addClass('d-none');
        $('.wasabi-setting').removeClass('d-none');
        $('.local-setting').addClass('d-none');
    }else{
        $('.s3-setting').addClass('d-none');
        $('.wasabi-setting').addClass('d-none');
        $('.local-setting').removeClass('d-none');
    }
});
</script>

    <script>
        $(document).ready(function() {
            $('#gdpr_cookie').trigger('change');

            var type = window.location.hash.substr(1);
            $('.list-group-item').removeClass('active');
            $('.list-group-item').removeClass('text-primary');
            if (type != '') {
                $('a[href="#' + type + '"]').addClass('active').removeClass('text-primary');
            } else {
                $('.list-group-item:eq(0)').addClass('active').removeClass('text-primary');
            }
        });

        $(document).on('click', '.list-group-item', function() {
            setTimeout(() => {
                $('.list-group-item').removeClass('text-primary');
                var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                    target: '#useradd-sidenav',
                    offset: 300,
                });
            }, 100);
        });

        $(document).on('change', '#gdpr_cookie', function() {
            $('.GDPR_Cookie_Text').hide();
            if ($("#gdpr_cookie").prop('checked') == true) {
                $('.GDPR_Cookie_Text').show();
            }
        });

        function check_theme(color_val) {
            $('input[value="' + color_val + '"]').prop('checked', true);
            $('a[data-value]').removeClass('active_color');
            $('a[data-value="' + color_val + '"]').addClass('active_color');
        }
    </script>

    <script>
         $(document).on("click", '.send_email', function(e) {

            e.preventDefault();
            var title = $(this).attr('data-title');

            var size = 'md';
            var url = $(this).attr('data-url');
            if (typeof url != 'undefined') {
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);
                $("#commonModal").modal('show');

                $.post(url, {
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                }, function(data) {
                    $('#commonModal .modal-body').html(data);
                });
            }
        });


        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            $("#email_sending").show();
            var post = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_email .btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if (data.is_success) {
                        show_toastr('Success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                    $("#email_sending").hide();
                    $('#commonModal').modal('hide');
                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        });
    </script>
@endpush
