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
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Account settings') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Account settings') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end text-end">
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="card sticky-top" style="top:30px">
                                <div class="list-group list-group-flush" id="useradd-sidenav">
                                    <a href="#Site_Setting" class="list-group-item list-group-item-action border-0">
                                        {{ __('Basic') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                    <a href="#Company_Details" class="list-group-item list-group-item-action border-0">
                                        {{ __('Company Details') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                    @if (!empty($userr->password))
                                        <a href="#Password" class="list-group-item list-group-item-action border-0">
                                            {{ __('Password') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (Auth::user()->acount_type == 1 || !empty($manager_option['manager_add_employees_and_manage_basic_information']))
                                        <a href="#Location" class="list-group-item list-group-item-action border-0">
                                            {{ __('Location') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                        <a href="#Roles" class="list-group-item list-group-item-action border-0">
                                            {{ __('Roles') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                        <a href="#Wage_Salary"
                                            class="list-group-item list-group-item-action border-0 {{ $manager_option['wage_salary_display'] }}">
                                            {{ __('Wage & Salary') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                        <a href="#Work_Schedule"
                                            class="list-group-item list-group-item-action border-0 {{ $manager_option['wage_salary_display'] }}">
                                            {{ __('Work Schedule') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-9">
                            <div id="Site_Setting" class="card">
                                <div id="useradd-1" class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                @php
                                                    $profile_pic=\App\Models\Utility::get_file($profile->DefaultProfilePic());
                                                @endphp
                                                <img class="theme-avtar avatar me-3" @if (!empty($profile_pic)) src="{{ $profile_pic }}" @else  avatar="{{ $name }}" @endif>
                                            </div>
                                            <div class="d-block d-sm-flex align-items-center justify-content-between w-100">
                                                <div class="mb-3 mb-sm-0">
                                                    <h4 class="mb-1 text-white">
                                                        {{ $profile->getUserName->first_name }}
                                                        {{ $profile->getUserName->last_name }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header">
                                    <h5>{{ __('Basic') }}</h5>
                                    <small
                                        class="text-muted">{{ __('Details about your Employee & Personal information') }}</small>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12 col-md-12 col-xxl-12">
                                            <div class="p-3 card">
                                                <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="pills-user-tab-1"
                                                            data-bs-toggle="pill" data-bs-target="#pills-user-1"
                                                            type="button">{{ __('Personal Detail') }}</button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill"
                                                            data-bs-target="#pills-user-2"
                                                            type="button">{{ __('Employee Detail') }}</button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="">
                                                <div class="card-body">
                                                    <div class="tab-content" id="pills-tabContent">
                                                        <div class="tab-pane fade show active" id="pills-user-1"
                                                            role="tabpanel" aria-labelledby="pills-user-tab-1">
                                                            {{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class' => 'personal_information', 'enctype' => 'multipart/form-data']) }}
                                                            <div class="row">
                                                                {{ Form::hidden('employee_id', $profile->user_id) }}
                                                                {{ Form::hidden('form_type', 'personal') }}
                                                                <div class="col-xs-12 col-sm-12 col-md-4">
                                                                    <div class="form-group">
                                                                        {{ Form::label('', __('First Name'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('first_name', $profile->getUserName->first_name, ['class' => 'form-control']) }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-12 col-md-4">
                                                                    <div class="form-group">
                                                                        {{ Form::label('', __('Middle Name'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('middle_name', $profile->getUserName->middle_name, ['class' => 'form-control']) }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-12 col-md-4">
                                                                    <div class="form-group">
                                                                        {{ Form::label('', __('Last Name'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('last_name', $profile->getUserName->last_name, ['class' => 'form-control']) }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-12 col-md-4">
                                                                    <div class="form-group">
                                                                        {{ Form::label('', __('Gender'), ['class' => 'form-label']) }}
                                                                        {{ Form::select('gender', ['male' => 'Male', 'female' => 'Female'], $profile->gender, ['class' => 'form-control multi-select', 'id' => 'choices-multiple', 'required' => false]) }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-12 col-md-4">
                                                                    <div class="form-group">
                                                                        {{ Form::label('', __('Date of Birth'), ['class' => 'form-label']) }}
                                                                        {{ Form::date('date_of_birth', $profile->date_of_birth, ['class' => 'form-control']) }}
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

                                                                <br>
                                                                <div class="card-header w-100 p-0 mb-3">
                                                                    <h5 class="h6 mb-4">{{ __('Emergency Contact') }}
                                                                    </h5>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Emergency Contact Name'), ['class' => 'form-label']) }}
                                                                            {{ Form::text('emergency_contact_name', $profile->emergency_contact_name, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Emergency Contact Phone Number'), ['class' => 'form-label']) }}
                                                                            {{ Form::text('emergency_contact_no', $profile->emergency_contact_no, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Relationship to Employee'), ['class' => 'form-label']) }}
                                                                            {{ Form::text('relationship_to_employee', $profile->relationship_to_employee, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <br>
                                                                <div class="card-header w-100 p-0 mb-3">
                                                                    <h5 class="h6 mb-4">{{ __('Contact Details') }}
                                                                    </h5>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Address'), ['class' => 'form-label']) }}
                                                                            {{ Form::text('address', $profile->address, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('City'), ['class' => 'form-label']) }}
                                                                            {{ Form::text('city', $profile->city, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Country'), ['class' => 'form-label']) }}
                                                                            {{ Form::text('county', $profile->city, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Postcode'), ['class' => 'form-label']) }}
                                                                            {{ Form::text('postcode', $profile->postcode, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Email Address'), ['class' => 'form-label']) }}
                                                                            {{ Form::text('email', $profile->getUserName->email, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Phone Number'), ['class' => 'form-label']) }}
                                                                            {{ Form::number('phone', $profile->phone, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr>

                                                                <div class="card-footer text-end py-0 pe-2 border-0">
                                                                    <input name="from" type="hidden"
                                                                        value="password">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">{{ __('Save Changes') }}</button>
                                                                </div>

                                                                {{ Form::close() }}
                                                            </div>

                                                        </div>
                                                            <div class="tab-pane fade" id="pills-user-2" role="tabpanel"
                                                                aria-labelledby="pills-user-tab-2">
                                                                {{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class' => 'employee_information']) }}
                                                                {{ Form::hidden('employee_id', $profile->user_id) }}
                                                                {{ Form::hidden('form_type', 'employee') }}
                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Weekly Hours'), ['class' => 'form-label']) }}
                                                                            {{ Form::text('weekly_hour', $profile->weekly_hour, ['class' => 'form-control', 'placeholder' => '0']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Annual Holiday Allowance'), ['class' => 'form-label']) }}
                                                                            <div class="row">
                                                                                <div class="col-sm-7 ">
                                                                                    {{ Form::number('annual_holiday[time]', $profile->getAnnualHolidayTime(), ['class' => 'form-control', 'placeholder' => '0']) }}
                                                                                </div>
                                                                                <div class="col-sm-5">
                                                                                    {{ Form::select('annual_holiday[type]', ['day' => 'Day'], $profile->getAnnualHolidayTimeType(), ['class' => 'form-control multi-select', 'id' => 'choices-multiple']) }}
                                                                                </div>
                                                                            </div>
                                                                            <span class="clearfix"> </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Start Date'), ['class' => 'form-label']) }}
                                                                            {{ Form::date('start_date', $profile->start_date, ['class' => 'form-control']) }}

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Final Working Date'), ['class' => 'form-label']) }}
                                                                            {{ Form::date('final_working_date', $profile->final_working_date, ['class' => 'form-control']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                                                        <div class="form-group">
                                                                            {{ Form::label('', __('Employment Type'), ['class' => 'form-label']) }}
                                                                            {{ Form::select('employee_type', ['fulltime' => 'Full Time', 'parttime' => 'Part Time', 'fixedterm' => 'Fixed Term', 'casual' => 'Casual', 'apprentice_trainee' => 'Apprentice/Trainee', 'agency' => 'Agency', 'contractor_freelancer' => 'Contractor/Freelancer', 'temporary' => 'Temporary'], $profile->employee_type, ['class' => 'form-control multi-select', 'id' => 'choices-multiple']) }}
                                                                        </div>
                                                                    </div>
                                                                    @if (Auth::user()->acount_type == 2 || Auth::user()->acount_type == 1)
                                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                                            <div class="form-group">
                                                                                {{ Form::label('', __('Notes'), ['class' => 'form-label']) }}
                                                                                {{ Form::textarea('note', $profile->note, ['class' => 'form-control autogrow', 'rows' => '3', 'style' => 'resize: none']) }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <hr>

                                                                <div class="card-footer text-end py-0 pe-2 border-0">
                                                                    <input name="from" type="hidden"
                                                                        value="password">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">{{ __('Save Changes') }}</button>
                                                                </div>
                                                                {{ Form::close() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="Company_Details" class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Company Detail') }}</h5>
                                        <small
                                            class="text-muted">{{ __('Details about your Company information') }}</small>
                                    </div>
                                    <div class="card-body">
                                        {{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class' => 'company_information']) }}
                                        {{ Form::hidden('employee_id', $profile->user_id) }}
                                        {{ Form::hidden('form_type', 'company_info') }}
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Company Name'), ['class' => 'form-label']) }}
                                                    {{ Form::text('company_name', !empty($userr->company_name) ? $userr->company_name : null, ['class' => 'form-control']) }}
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Company owner'), ['class' => 'form-label']) }}
                                                    {!! Form::select('company_owner', ['you' => 'You'], 'you', ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Company Telephone Number'), ['class' => 'form-label']) }}
                                                    {!! Form::number(
                                                        'company_telephone_number',
                                                        !empty($company_detail['company_telephone_number']) ? $company_detail['company_telephone_number'] : null,
                                                        ['class' => 'form-control'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Postcode'), ['class' => 'form-label']) }}
                                                    {!! Form::text(
                                                        'company_postcode',
                                                        !empty($company_detail['company_postcode']) ? $company_detail['company_postcode'] : null,
                                                        ['class' => 'form-control'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Address'), ['class' => 'form-label']) }}
                                                    {!! Form::text(
                                                        'comapany_address',
                                                        !empty($company_detail['comapany_address']) ? $company_detail['comapany_address'] : null,
                                                        ['class' => 'form-control'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('', __('City'), ['class' => 'form-label']) }}
                                                    {!! Form::text(
                                                        'company_city',
                                                        !empty($company_detail['company_city']) ? $company_detail['company_city'] : null,
                                                        ['class' => 'form-control'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Country'), ['class' => 'form-label']) }}
                                                    {!! Form::text(
                                                        'comapany_county',
                                                        !empty($company_detail['comapany_county']) ? $company_detail['comapany_county'] : null,
                                                        ['class' => 'form-control'],
                                                    ) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="card-footer text-end py-0 pe-2 border-0">
                                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>

                                <div id="Password" class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Password') }}</h5>
                                        <small class="text-muted">{{ __('Change Password') }}</small>
                                    </div>
                                    <div class="card-body">
                                        @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('update.password') }}" role="form">
                                            @csrf
                                            <input name="form_type" type="hidden" value="set_own_password">
                                            <div class="row">
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                @if (Auth::user()->acount_type == 1 || !empty($manager_option['manager_add_employees_and_manage_basic_information']))
                                    <div id="Location" class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Location Setting') }}</h5>
                                            <small
                                                class="text-muted">{{ __('Details about your Location information') }}</small>
                                        </div>
                                        <div class="card-body">
                                            {{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class' => 'loaction_information']) }}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('', __('Set Location'), ['class' => 'form-label']) }}
                                                        {!! Form::select('location_id[]', $all_location_option, $profile->location_id, [
                                                            'required' => false,
                                                            'multiple' => 'multiple',
                                                            'class' => 'form-control multi-select',
                                                            'id' => 'choices-multiple12',
                                                        ]) !!}
                                                        {{ Form::hidden('employee_id', $profile->user_id) }}
                                                        {{ Form::hidden('form_type', 'loaction') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="card-footer text-end py-0 pe-2 border-0">
                                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>

                                    <div id="Roles" class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Roles') }}</h5>
                                            <small
                                                class="text-muted">{{ __('Details about your Roles information') }}</small>
                                        </div>
                                        <div class="card-body">
                                            {{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class' => 'role_information']) }}
                                            {{ Form::hidden('employee_id', $profile->user_id) }}
                                            {{ Form::hidden('form_type', 'role') }}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('', __('Set Role'), ['class' => 'form-label']) }}
                                                        {!! Form::select('role_id[]', $role_options, $profile->role_id, [
                                                            'required' => false,
                                                            'multiple' => 'multiple',
                                                            'class' => 'form-control multi-select',
                                                            'id' => 'choices-multiple3',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="card-footer text-end py-0 pe-2 border-0">
                                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>

                                    <div id="Wage_Salary" class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Wage & Salary') }}</h5>
                                            <small class="text-muted">{{ __('Details about your Wage & Salary') }}</small>
                                        </div>
                                        <div class="card-body">
                                            {{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class' => 'salary_information']) }}
                                            {{ Form::hidden('employee_id', $profile->user_id) }}
                                            {{ Form::hidden('form_type', 'salary') }}
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label"> {{ __('Default Wage / Salary') }}
                                                        </label>
                                                        <span class="clearfix"> </span>
                                                        <div class="col-sm-5 col-md-5 float-start px-2">
                                                            <div class="input-group">
                                                                <span
                                                                    class="input-group-text">{{ \App\Models\User::CompanycurrencySymbol() }}</span>
                                                                {{ Form::number('default_salary[salary]', !empty($profile->CustomDefaultSalary()['salary']) ? $profile->CustomDefaultSalary()['salary'] : null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0.00']) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-7 col-md-7 float-start px-2">
                                                            {!! Form::select(
                                                                'default_salary[salary_per]',
                                                                ['hourly' => __("Per Hour")],
                                                                !empty($profile->CustomDefaultSalary()['salary_per']) ? $profile->CustomDefaultSalary()['salary_per'] : null,
                                                                ['required' => false, 'id' => 'choices-multiple-location_id', 'class' => 'form-control multi-select'],
                                                            ) !!}
                                                        </div>
                                                        <span class="clearfix"> </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <br>
                                            <div class="card-header w-100 p-0 mb-3">
                                                <h5 class="h6 mb-4">
                                                    <span data-toggle="tooltip"
                                                        title="Pay codes are used by payroll systems to identify which pay rate should be applied to a number of hours worked. Apply a pay code to a custom role rate and any hours worked by this employee will be labelled with this pay code.">
                                                        {{ __('Custom Role Rates') }}
                                                    </span>
                                                </h5>
                                            </div>
                                            <div class="row">
                                                @if (!empty($profile->role_id))
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        @foreach ($custom_role_rates as $key => $custom_role_rate)
                                                            <div class="form-group" data_id="{{ $key }}">
                                                                <div class="row">
                                                                    <div class="col-2">
                                                                        <span
                                                                            class="float-dark lh-50 text-dark">{{ $custom_role_rate }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <div class="input-group input-group-merge">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">
                                                                                    {{ \App\Models\User::CompanycurrencySymbol() }}
                                                                                </span>
                                                                            </div>
                                                                            {{ Form::number('salary[' . $key . '][custom_salary_by_hour]', !empty($salary_data[$key]['custom_salary_by_hour']) ? $salary_data[$key]['custom_salary_by_hour'] : null, ['class' => 'form-control', 'step' => '0.01']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-2 text-start text-dark"> <span
                                                                            class="lh-50">
                                                                            &nbsp; {{ __('Per Hour') }} </span>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <div class="input-group input-group-merge">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">
                                                                                    {{ \App\Models\User::CompanycurrencySymbol() }}
                                                                                </span>
                                                                            </div>
                                                                            {{ Form::number('salary[' . $key . '][custom_salary_by_shift]', !empty($salary_data[$key]['custom_salary_by_shift']) ? $salary_data[$key]['custom_salary_by_shift'] : null, ['class' => 'form-control', 'step' => '0.01']) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-2"> <span class="lh-50">
                                                                            &nbsp; {{ __('Per Shift') }} </span> </div>
                                                                </div>
                                                                <span class="clearfix"></span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="col-xs-12 col-sm-12 col-md-12"> {{ $custom_role_rates }}
                                                    </div>
                                                @endif
                                            </div>
                                            <hr>
                                            <div class="card-footer text-end py-0 pe-2 border-0">
                                                <input name="from" type="hidden" value="password">
                                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                    <div id="Work_Schedule" class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Work Schedule') }}</h5>
                                            <small class="text-muted">{{ __('Details about your Work Schedule') }}</small>
                                        </div>
                                        <div class="card-body">
                                            <small class="text-dark">
                                                {{ __("Use the work schedule to set your employee's regular days off. They will then display on the rota accordingly. Changes to work schedules will apply from the current day forwards.") }}
                                            </small>

                                            {{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class' => 'work_table_information']) }}
                                            {{ Form::hidden('employee_id', $profile->user_id) }}
                                            {{ Form::hidden('form_type', 'work_table') }}
                                            <div class="row">
                                                <div class="col-xs-6 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        {{ Form::label('', __('Monday'), ['class' => 'form-label']) }}
                                                        {!! Form::select(
                                                            'work_schedule[monday]',
                                                            ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                            !empty($profile->WorkSchedule()['monday']) ? $profile->WorkSchedule()['monday'] : null,
                                                            [
                                                                'id' => 'choices-multiple-monday',
                                                                'class' => 'form-control multi-select',
                                                                'data-placeholder' => __('select option'),
                                                            ],
                                                        ) !!}
                                                    </div>
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        {{ Form::label('', __('Tuesday'), ['class' => 'form-label']) }}
                                                        {!! Form::select(
                                                            'work_schedule[tuesday]',
                                                            ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                            !empty($profile->WorkSchedule()['tuesday']) ? $profile->WorkSchedule()['tuesday'] : null,
                                                            [
                                                                'id' => 'choices-multiple-tuesday',
                                                                'class' => 'form-control multi-select',
                                                                'data-placeholder' => __('select option'),
                                                            ],
                                                        ) !!}
                                                    </div>
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        {{ Form::label('', __('Wednesday'), ['class' => 'form-label']) }}
                                                        {!! Form::select(
                                                            'work_schedule[wednesday]',
                                                            ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                            !empty($profile->WorkSchedule()['wednesday']) ? $profile->WorkSchedule()['wednesday'] : null,
                                                            [
                                                                'id' => 'choices-multiple-wednesday',
                                                                'class' => 'form-control multi-select',
                                                                'data-placeholder' => __('select option'),
                                                            ],
                                                        ) !!}
                                                    </div>
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        {{ Form::label('', __('Thursday'), ['class' => 'form-label']) }}
                                                        {!! Form::select(
                                                            'work_schedule[thursday]',
                                                            ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                            !empty($profile->WorkSchedule()['thursday']) ? $profile->WorkSchedule()['thursday'] : null,
                                                            [
                                                                'id' => 'choices-multiple-thursday',
                                                                'class' => 'form-control multi-select',
                                                                'data-placeholder' => __('select option'),
                                                            ],
                                                        ) !!}
                                                    </div>
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        {{ Form::label('', __('Friday'), ['class' => 'form-label']) }}
                                                        {!! Form::select(
                                                            'work_schedule[friday]',
                                                            ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                            !empty($profile->WorkSchedule()['friday']) ? $profile->WorkSchedule()['friday'] : null,
                                                            [
                                                                'id' => 'choices-multiple-friday',
                                                                'class' => 'form-control multi-select',
                                                                'data-placeholder' => __('select option'),
                                                            ],
                                                        ) !!}
                                                    </div>
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        {{ Form::label('', __('Saturday'), ['class' => 'form-label']) }}
                                                        {!! Form::select(
                                                            'work_schedule[saturday]',
                                                            ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                            !empty($profile->WorkSchedule()['saturday']) ? $profile->WorkSchedule()['saturday'] : null,
                                                            [
                                                                'id' => 'choices-multiple-saturday',
                                                                'class' => 'form-control multi-select',
                                                                'data-placeholder' => __('select option'),
                                                            ],
                                                        ) !!}
                                                    </div>
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        {{ Form::label('', __('Sunday'), ['class' => 'form-label']) }}
                                                        {!! Form::select(
                                                            'work_schedule[sunday]',
                                                            ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                            !empty($profile->WorkSchedule()['sunday']) ? $profile->WorkSchedule()['sunday'] : null,
                                                            [
                                                                'id' => 'choices-multiple-sunday',
                                                                'class' => 'form-control multi-select',
                                                                'data-placeholder' => __('select option'),
                                                            ],
                                                        ) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="card-footer text-end py-0 pe-2 border-0">
                                                <input name="from" type="hidden" value="password">
                                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                            </div>
                                            {{ Form::close() }}

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- [ sample-page ] end -->
                    </div>
                </div>
            </div>
        </div>
    @endsection
