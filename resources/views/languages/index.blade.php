@extends('layouts.main')
@section('page-title')
    {{ __('Language') }}
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
                                <h4 class="m-b-10">{{ __('Language') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Language') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-end">
                            @if ($currantLang != (env('DEFAULT_LANG') ?? 'en'))
                                <div class="btn btn-sm btn-danger btn-icon m-1">
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['lang.destroy', $currantLang], 'id' => 'delete-lang-' . $currantLang]) !!}
                                    <a href="#!" class="align-items-center show_confirm">
                                        <i class="ti ti-trash text-white"></i>
                                    </a>
                                    {!! Form::close() !!}
                                </div>
                            @endif
                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title="{{ __('Create New Language') }}" data-url="{{ route('create.language') }}"
                                    data-ajax-popup="true" data-title="{{ __('Create New Language') }}"
                                    data-ajax-popup="true">
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
                        <div class="card-body">
                            <div class="language-wrap">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-12 language-list-wrap">
                                        <div class="language-list">
                                            <ul class="nav nav-pills nav-pills-lang flex-column" id="myTab4" role="tablist">
                                                @foreach($languages as $lang)
                                                    <li class="nav-item mb-3">
                                                        <a href="{{route('manage.language',[$lang])}}" class="nav-link {{($currantLang == $lang) ? 'active' : ''}}">{{Str::upper($lang)}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-sm-12 language-form-wrap">
                                        <div class="tab-content">
                                            <div id="nav-pills-tabs-component" class="tab-pane tab-example-result fade show active" role="tabpanel" aria-labelledby="nav-pills-tabs-component-tab">
                                                <div class="nav-wrapper mb-4">
                                                    <ul class="nav nav-pills nav-pills-lang nav-fill flex-column flex-md-row" id="pills-tab" role="tablist">
                                                        <li class="nav-item mx-2" role="presentation">
                                                            <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                                                                data-bs-target="#home" type="button">{{ __('Labels')}}</button>
                                                        </li>
                                                        <li class="nav-item mx-2" role="presentation">
                                                            <button class="nav-link mb-sm-3 mb-md-0" id="pills-user-tab-2" data-bs-toggle="pill"
                                                                data-bs-target="#profile" type="button">{{ __('Messages')}}</button>
                                                        </li>

                                                    </ul>
                                                </div>
                                                <div class="col-xl-12 col-md-12">
                                                    <div class="card card-fluid">
                                                        <div class="card-body" style="position: relative;">
                                                            <div class="tab-content no-padding" id="myTab2Content">
                                                                <div class="tab-pane fade show active" id="lang1" role="tabpanel" aria-labelledby="home-tab4">
                                                                    <div class="tab-content" id="myTabContent">
                                                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                                                            <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                                                                                @csrf
                                                                                <div class="row">
                                                                                    @foreach($arrLabel as $label => $value)
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label class="form-label" for="example3cols1Input">{{$label}} </label>
                                                                                                <input type="text" class="form-control" name="label[{{$label}}]" value="{{$value}}">
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                    <div class="col-lg-12">
                                                                                        <div class="text-end">
                                                                                            <div class="d-flex justify-content-end">
                                                                                                    {{Form::submit(__('Save Changes'),array('class'=>'btn btn-xs btn-primary'))}}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                                            <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                                                                                @csrf
                                                                                <div class="row">
                                                                                    @foreach($arrMessage as $fileName => $fileValue)
                                                                                        <div class="col-lg-12">
                                                                                            <h5>{{ucfirst($fileName)}}</h5>
                                                                                        </div>
                                                                                        @foreach($fileValue as $label => $value)
                                                                                            @if(is_array($value))
                                                                                                @foreach($value as $label2 => $value2)
                                                                                                    @if(is_array($value2))
                                                                                                        @foreach($value2 as $label3 => $value3)
                                                                                                            @if(is_array($value3))
                                                                                                                @foreach($value3 as $label4 => $value4)
                                                                                                                    @if(is_array($value4))
                                                                                                                        @foreach($value4 as $label5 => $value5)
                                                                                                                            <div class="col-md-6">
                                                                                                                                <div class="form-group">
                                                                                                                                    <label class="form-label">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}.{{$label5}}</label>
                                                                                                                                    <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}][{{$label5}}]" value="{{$value5}}">
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        @endforeach
                                                                                                                    @else
                                                                                                                        <div class="col-lg-6">
                                                                                                                            <div class="form-group">
                                                                                                                                <label class="form-label">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}</label>
                                                                                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}]" value="{{$value4}}">
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    @endif
                                                                                                                @endforeach
                                                                                                            @else
                                                                                                                <div class="col-lg-6">
                                                                                                                    <div class="form-group">
                                                                                                                        <label class="form-label">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}</label>
                                                                                                                        <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}]" value="{{$value3}}">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    @else
                                                                                                        <div class="col-lg-6">
                                                                                                            <div class="form-group">
                                                                                                                <label class="form-label">{{$fileName}}.{{$label}}.{{$label2}}</label>
                                                                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}]" value="{{$value2}}">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @else
                                                                                                <div class="col-lg-6">
                                                                                                    <div class="form-group">
                                                                                                        <label class="form-label">{{$fileName}}.{{$label}}</label>
                                                                                                        <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}]" value="{{$value}}">
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endforeach
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <div class="text-end">
                                                                                        <div class="d-flex justify-content-end">
                                                                                                {{Form::submit(__('Save Changes'),array('class'=>'btn btn-xs btn-primary'))}}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
