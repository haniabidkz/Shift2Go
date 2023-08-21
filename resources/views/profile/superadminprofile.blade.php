@extends('layouts.main')
@section('page-title')
    {{ __('Profile') }}
@endsection
@section('content')
    <div class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Profile') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Profile') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row justify-content-center">
                <!-- [ sample-page ] start -->
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#useradd-1"
                                class="list-group-item list-group-item-action">{{ __('Personal Information') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-2" class="list-group-item list-group-item-action">{{ __('Change Password
                                ') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="useradd-1" class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Personal Information') }}</h5>
                        </div>
                        <div class="card-body">

                            {{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class' => 'personal_information', 'enctype' => 'multipart/form-data']) }}
                            <div class="row mt-3">
                                {{ Form::hidden('employee_id', $profile->user_id) }}
                                {{ Form::hidden('form_type', 'superadmininfo') }}
                                <div class="col-xs-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('', __('Name'), ['class' => 'form-label']) }}
                                        {{ Form::text('company_name', $profile->getUserName->company_name, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('', __('Email'), ['class' => 'form-label']) }}
                                        {{ Form::text('email', $profile->getUserName->email, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>


                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('', __('Profile Image'), ['class' => 'form-label']) }}
                                        <div>
                                            <label for="profile_pic"
                                                class="form-label choose-files bg-primary "><i
                                                    class="ti ti-upload px-1"></i>{{ __('Select Image') }}</label>
                                            <input type="file" name="profile_pic"
                                                id="profile_pic"
                                                class="custom-input-file d-none"
                                                onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                            @if ($errors->has('profile_pic'))
                                                <span
                                                    class="help-block text-danger fs-12">
                                                    <strong>{{ $errors->first('profile_pic') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                        <div class="logo-content mt-4">
                                            <img src="{{ asset(Storage::url($profile->DefaultProfilePic())) }}"
                                               style=" height: 70px;"  id="blah"
                                                class="img_setting">
                                        </div>
                                    </div>
                                </div>

                            </div>




                            <div class="card-footer text-end py-0 pe-2 border-0">
                                <input name="from" type="hidden" value="password">
                                <button type="submit"
                                    class="btn btn-primary">{{ __('Save') }}</button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div id="useradd-2" class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Change Password
                                ') }}</h5>
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('update.password') }}" role="form">
                                @csrf
                                @if (Auth::user()->id == $id)
                                    <input type="hidden" name="form_type" value="set_own_password">
                                @else
                                    <input type="hidden" name="form_type" value="set_other_password">
                                @endif
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('', __('Current Password'), ['class' => 'form-label']) }}
                                            {{ Form::password('current_password', ['class' => 'form-control', 'placeholder' => __('Enter Current Password'), 'id' => 'current_password']) }}
                                        </div>
                                        @error('current_password')
                                            <span class="invalid-current_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('', __('New Password'), ['class' => 'form-label']) }}
                                            {{ Form::password('new_password', ['class' => 'form-control', 'placeholder' => __('Enter New Password'), 'id' => 'new_password']) }}
                                        </div>
                                        @error('new_password')
                                            <span class="invalid-new_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('', __('Re-type New Password'), ['class' => 'form-label']) }}
                                            {{ Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => __('Enter Re-type New Password'), 'id' => 'confirm_password']) }}
                                        </div>
                                        @error('confirm_password')
                                            <span class="invalid-confirm_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer text-end py-0 pe-2 border-0">
                                    <button type="submit"
                                        class="btn btn-primary">{{ __('Save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-sm-12 col-md-10 col-xxl-8">
                    <div class="p-3 card">
                        <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-user-tab-4" data-bs-toggle="pill"
                                    data-bs-target="#pills-user-4" type="button">{{ __('Company Details') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-user-tab-3" data-bs-toggle="pill"
                                    data-bs-target="#pills-user-3" type="button">{{ __('Password') }}</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content" id="pills-tabContent">

                                <div class="tab-pane fade active show" id="pills-user-4" role="tabpanel"
                                    aria-labelledby="pills-user-tab-4">
                                    <h3 class="mb-0">{{ __('Company Details') }}</h3>
                                    {{ Form::model($profile, ['route' => ['profile.update', $profile->id],'method' => 'PUT','class' => 'personal_information','enctype' => 'multipart/form-data']) }}
                                    <div class="row mt-3">
                                        {{ Form::hidden('employee_id', $profile->user_id) }}
                                        {{ Form::hidden('form_type', 'superadmininfo') }}
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('', __('Name'), ['class' => 'form-label']) }}
                                                {{ Form::text('company_name', $profile->getUserName->company_name, ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('', __('Email'), ['class' => 'form-label']) }}
                                                {{ Form::text('email', $profile->getUserName->email, ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <input name="from" type="hidden" value="password">
                                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>

                                <div class="tab-pane fade" id="pills-user-3" role="tabpanel"
                                    aria-labelledby="pills-user-tab-3">
                                    <h3 class="mb-0">{{ __('Change Password') }}</h3>
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('update.password') }}" role="form">
                                        @csrf
                                        @if (Auth::user()->id == $id)
                                            <input type="hidden" name="form_type" value="set_own_password">
                                        @else
                                            <input type="hidden" name="form_type" value="set_other_password">
                                        @endif
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Current Password'), ['class' => 'form-label']) }}
                                                    {{ Form::password('current_password', ['class' => 'form-control','placeholder' => 'Enter Current Password','id' => 'current_password']) }}
                                                </div>
                                                @error('current_password')
                                                    <span class="invalid-current_password" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {{ Form::label('', __('New Password'), ['class' => 'form-label']) }}
                                                    {{ Form::password('new_password', ['class' => 'form-control','placeholder' => 'Enter New Password','id' => 'new_password']) }}
                                                </div>
                                                @error('new_password')
                                                    <span class="invalid-new_password" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Re-type New Password'), ['class' => 'form-label']) }}
                                                    {{ Form::password('confirm_password', ['class' => 'form-control','placeholder' => 'Enter Re-type New Password','id' => 'confirm_password']) }}
                                                </div>
                                                @error('confirm_password')
                                                    <span class="invalid-confirm_password" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="card-footer text-right border-0 p-0">
                                            <input class="btn btn-primary" type="submit" value="Update">
                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- [ Main Content ] end -->
            </div>
        </div>
    </div>
@endsection
