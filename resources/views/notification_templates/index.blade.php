@extends('layouts.main')
@section('page-title')
    {{ __('Notification Template') }}
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
                                <h4 class="m-b-10">{{ __('Notification Template') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Notification Template') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="row">
                                <div class="text-end mb-3">
                                    <div class="text-end">
                                        <div class="d-flex justify-content-end drp-languages">
                                            <ul class="list-unstyled mb-0 m-2">
                                                <li class="dropdown dash-h-item drp-language">
                                                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                                       href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                                       id="dropdownLanguage">
                                                        <span
                                                            class="drp-text hide-mob text-primary">{{ Str::upper($curr_noti_tempLang->lang) }}</span>
                                                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                                    </a>
                                                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                         aria-labelledby="dropdownLanguage">
                                                        @foreach ($languages as $lang)
                                                            <a href="{{ route('notification-templates.index', [$notification_template->id, $lang,$type]) }}"
                                                               class="dropdown-item {{ $curr_noti_tempLang->lang == $lang ? 'text-primary' : '' }}">{{ Str::upper($lang) }}</a>
                                                        @endforeach
                                                    </div>
                                                </li>
                                            </ul>
                                            <ul class="list-unstyled mb-0 m-2">
                                                <li class="dropdown dash-h-item drp-language">
                                                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                                       href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                                       id="dropdownLanguage">
                                                        <span
                                                            class="drp-text hide-mob text-primary">{{ __('Type: ') }}{{ $type }}</span>
                                                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                                    </a>
                                                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                                                        @foreach ($types as $key => $t)
                                                        <a href="{{ route('notification-templates.index', [$notification_template->id,(Request::segment(3)?Request::segment(3):\Auth::user()->lang),$key]) }}"
                                                               class="dropdown-item {{$type == $key ? 'text-primary' : '' }}">{{ $t }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </li>
                                            </ul>
                                            <ul class="list-unstyled mb-0 m-2">
                                                <li class="dropdown dash-h-item drp-language">
                                                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                                       href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                                       id="dropdownLanguage">
                                                        <span
                                                            class="drp-text hide-mob text-primary">{{ __('Template: ') }}{{ $notification_template->name }}</span>
                                                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                                    </a>
                                                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                                                        @foreach ($notification_templates as $notification_template)
                                                            <a href="{{ route('notification-templates.index', [$notification_template->id,(Request::segment(3)?Request::segment(3):\Auth::user()->lang),$type]) }}"
                                                               class="dropdown-item {{$notification_template->name == $notification_template->name ? 'text-primary' : '' }}">{{ $notification_template->name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body ">
                        <h5 class= "font-weight-bold pb-3">{{ __('Placeholders') }}</h5>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                        <div class="card-header card-body">
                                            <div class="row text-xs">
                                                <h6 class="font-weight-bold mb-4">{{__('Variables')}}</h6>
                                                @php
                                                    $variables = json_decode($curr_noti_tempLang->variables);
                                                @endphp
                                                @if(!empty($variables) > 0)
                                                @foreach  ($variables as $key => $var)
                                                <div class="col-6 pb-1">
                                                    <p class="mb-1">{{__($key)}} : <span class="pull-right text-primary">{{ '{'.$var.'}' }}</span></p>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                            </div>
                            {{Form::model($curr_noti_tempLang,array('route' => array('notification-templates.update', $curr_noti_tempLang->parent_id), 'method' => 'PUT')) }}
                                <div class="row">
                                    @if(\Auth::user()->enable_chatgpt())
                                    <div class="text-end">
                                        <a href="#" class="btn btn-print-invoice btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['notification template']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
                                            <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
                                        </a>
                                    </div>
                                    @endif
                                    <div class="form-group col-12">
                                        {{Form::label('content',__('Notification Message'),['class'=>'form-label text-dark'])}}
                                        {{Form::textarea('content',$curr_noti_tempLang->content,array('class'=>'form-control','required'=>'required','rows'=>'04','placeholder'=>'EX. Hello, {company_name}'))}}
                                        <small>{{ __('A variable is to be used in such a way.')}} <span class="text-primary">{{ __('Ex. Hello, {company_name}')}}</span></small>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12 text-end">
                                    {{Form::hidden('lang',null)}}
                                    <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection