{{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class'=>"permission_table_information" ]) }}
    {{ Form::hidden('employee_id', $profile->user_id) }}
    {{ Form::hidden('form_type', 'manage_permission') }}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('', __('Account Type'), ['class' => 'form-label']) }}
                @if(Auth::user()->acount_type == 1)
                {{ Form::select('acount_type', ['3' => 'Employee', '2' => 'Manager', '1' => 'Administrator'],(!empty($userr->acount_type)) ? $userr->acount_type : null, ['class' => 'form-control multi-select manager_manag_emp', 'id'=>'choices-multiple' ,'required'=>true]) }}
                @elseif(Auth::user()->acount_type == 2)
                {{ Form::select('acount_type', ['3' => 'Employee', '2' => 'Manager'],(!empty($userr->acount_type)) ? $userr->acount_type : null, ['class' => 'form-control multi-select manager_manag_emp', 'id'=>'choices-multiple' ,'required'=>true]) }}
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 manager_permission_data" style="display: none">
            <b>{{ Form::label('', __('Locations Managed'), ['class' => 'form-label text-dark']) }} </b>
            <br>
            @foreach ($location_option1 as $location_option1_data)
            <div class="custom-control custom-checkbox d-inline-block mx-2">
                {!! Form::checkbox('manage_location_id[]', $location_option1_data['id'], (!empty($manage_location_select[$location_option1_data['id']])) ? true : null , ['required' => false, 'class'=> 'form-check-input input-primary', 'id' => 'location_'.$location_option1_data['id']]); !!}
                {{ Form::label('location_'.$location_option1_data['id'], $location_option1_data['name'], ['class' => 'form-check-label']) }}
            </div>
            @endforeach
        </div>
        <br><br>
        <div class="col-xs-12 col-sm-12 col-md-12 manager_permission_data mt-3" style="display: none">
            <b>{{ Form::label('', __('Permissions'), ['class' => 'form-label text-dark']) }} </b>
            <br>
            <div class="form-check d-inline-block mx-2">

                {!! Form::checkbox('manager_add_edit_rotas', 1, (!empty($manager_option['manager_add_edit_delete_rotas'])) ? 1 : 0 , ['required' => false, 'class'=> 'form-check-input input-primary','id' => 'manager_add_edit_rotas']); !!}
                {{ Form::label('manager_add_edit_rotas', __('Create,edit and delete rotas for locations they manage'), ['class' => 'form-check-label']) }}
            </div>
            <br>
            <div class="form-check d-inline-block mx-2">
                {!! Form::checkbox('manager_manage_leave_and_approve_leave_requests_for_other', 1, (!empty($manager_option['manager_manage_leave_and_approve_leave_requests_for_other'])) ? 1 : 0 , ['required' => false, 'class'=> 'form-check-input input-primary','id' => 'manager_manage_leave_and_approve_leave_requests_for_other']); !!}
                {{ Form::label('manager_manage_leave_and_approve_leave_requests_for_other', __('Manage leave and approve leave requests for others and approve leave requests for others'), ['class' => 'form-check-label']) }}
            </div>
            <br>
            <div class="form-check d-inline-block mx-2">
                {!! Form::checkbox('manager_manually_add_leave_to_themselves', 1, (!empty($manager_option['manager_manually_add_leave_to_themselves'])) ? 1 : 0 , ['required' => false, 'class'=> 'form-check-input input-primary','id' => 'manager_manually_add_leave_to_themselves']); !!}
                {{ Form::label('manager_manually_add_leave_to_themselves', __('Manually add leave to themselves'), ['class' => 'form-check-label']) }}
            </div>
            <br>
            <div class="form-check d-inline-block mx-2">
                {!! Form::checkbox('manager_manage_leave_embargoes', 1, (!empty($manager_option['manager_manage_leave_embargoes'])) ? 1 : 0 , ['required' => false, 'class'=> 'form-check-input input-primary','id' => 'manager_manage_leave_embargoes']); !!}
                {{ Form::label('manager_manage_leave_embargoes', __('Manage leave embargoes'), ['class' => 'form-check-label']) }}
            </div>
            <br>
            <div class="form-check d-inline-block mx-2">
                {!! Form::checkbox('manager_add_employees_and_manage_basic_information', 1, (!empty($manager_option['manager_add_employees_and_manage_basic_information'])) ? 1 : 0 , ['required' => false, 'class'=> 'form-check-input input-primary','id' => 'manager_add_employees_and_manage_basic_information']); !!}
                {{ Form::label('manager_add_employees_and_manage_basic_information', __('Add employees and manage basic information (email, locations, roles, allowances, etc)'), ['class' => 'form-check-label']) }}
            </div>
            <br>
            <div class="form-check d-inline-block mx-2">
                {!! Form::checkbox('manager_view_and_edit_employee_salary', 1, (!empty($manager_option['manager_view_and_edit_employee_salary'])) ? 1 : 0 , ['required' => false, 'class'=> 'form-check-input input-primary','id' => 'manager_view_and_edit_employee_salary']); !!}
                {{ Form::label('manager_view_and_edit_employee_salary', __('View and edit employee salary information'), ['class' => 'form-check-label']) }}
            </div>
            <br>
            <div class="form-check d-inline-block mx-2">
                {!! Form::checkbox('manager_manage_roles', 1, (!empty($manager_option['manager_manage_roles'])) ? 1 : 0 , ['required' => false, 'class'=> 'form-check-input input-primary','id' => 'manager_manage_roles']); !!}
                {{ Form::label('manager_manage_roles', __('Manage roles'), ['class' => 'form-check-label']) }}
            </div>
            <br>
            <div class="form-check d-inline-block mx-2">
                {!! Form::checkbox('manager_view_reports', 1, (!empty($manager_option['manager_view_reports'])) ? 1 : 0 , ['required' => false, 'class'=> 'form-check-input input-primary','id' => 'manager_view_reports']); !!}
                {{ Form::label('manager_view_reports', __('View reports'), ['class' => 'form-check-label']) }}
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 p-0">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
    </div>
{{ Form::close() }}
