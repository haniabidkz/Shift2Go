{{ Form::open(['url' => 'rules', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Name'), ['class' => 'form-control-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'id'=>'#datepickerid', 'required' => '']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('', __('Start Date'), ['class' => 'form-control-label']) }}
                {{ Form::date('start_date', null, ['class' => 'form-control', 'id'=>'#datepickerid','required' => '']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('', __('End Date'), ['class' => 'form-control-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control', 'required' => '']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Message'), ['class' => 'form-control-label']) }}
                {{ Form::textarea('message', null, ['class' => 'form-control autogrow', 'rows' => '3' ,'style' => 'resize: none;']) }}
                <small>This message will be shown to employees who have leave requests blocked due to this rule</small>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Rules'), ['class' => 'form-control-label']) }} <br>
                <div class="alert alert-primary shadow-lg">
                    <small>Future leave requests that overlap the dates on this rule will be evaluated. Any employee with existing leave overlapping the requested range will not be considered available. If any rule is not satisfied, the request will be blocked.</small>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="rules_div">
                <div class="rules">
                    <div class="form-group">
                        {{ Form::label('', __('Rule'), ['class' => 'form-control-label']) }}
                        <i class="fas fa-times float-right pointer close_leave_rules"></i>
                        <span class="clearfix"></span>
                        {!! Form::select('rule_id[role_val]', $requestrule_select, null, ['required' => false, 'data-placeholder'=> 'Select Rule' ,'class'=> 'form-control leave_rule']) !!}
                        <div class="set_rules num_employees">
                            <div class="mt-2">
                                <small class="float-left lh-50">There must be at least &nbsp; </small>
                                {{ Form::number('rule_id[1][min_employee]', null, ['class' => 'form-control w-20 float-left', 'placeholder' => '1', 'min' => '1']) }}
                                <small class="float-left  lh-50"> &nbsp; employees available to work </small>
                                <span class="clearfix"></span>
                            </div>
                        </div>
                        <div class="set_rules num_employees_on_location" style="display: none;">
                            <div class="mt-2">
                                <small class="float-left lh-50">There must be at least &nbsp; </small>
                                {{ Form::number('rule_id[2][min_employee_in_location]', null, ['class' => 'form-control w-20 float-left', 'placeholder' => '1', 'min' => '1']) }}
                                <small class="float-left  lh-50"> &nbsp; employees on  &nbsp; </small>
                                {!! Form::select('rule_id[2][location_id]', $location_select, null, ['required' => false, 'data-placeholder'=> 'Select Rule' ,'class'=> 'form-control leave_rule w-20']) !!}
                                <span class="clearfix"></span>
                            </div>
                        </div>
                        <div class="set_rules num_employees_in_group" style="display: none;">
                            <div class="mt-2">
                                <small class="float-left lh-50">There must be at least &nbsp; </small>
                                {{ Form::number('rule_id[3][min_employee_in_group]', null, ['class' => 'form-control w-20 float-left', 'placeholder' => '1', 'min' => '1']) }}
                                <small class="float-left  lh-50"> &nbsp; employees on  &nbsp; </small>
                                {!! Form::select('rule_id[3][group_id]', $group_select, null, ['required' => false, 'data-placeholder'=> 'Select Rule' ,'class'=> 'form-control leave_rule w-20']) !!}
                                <span class="clearfix"></span>
                            </div>
                        </div>
                        <div class="set_rules num_employees_with_role" style="display: none;">
                            <div class="mt-2">
                                <small class="float-left lh-50">There must be at least &nbsp; </small>
                                {{ Form::number('rule_id[4][min_employee_in_role]', null, ['class' => 'form-control w-20 float-left', 'placeholder' => '1', 'min' => '1']) }}
                                <small class="float-left  lh-50"> &nbsp; employees on  &nbsp; </small>
                                {!! Form::select('rule_id[4][role_id]', $roles_select, null, ['required' => false, 'data-placeholder'=> 'Select Rule' ,'class'=> 'form-control leave_rule w-20']) !!}
                                <span class="clearfix"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <small class="add_rule pointer d-none">Add Rule</small>
        </div>
        <div class="col-12">
            <div class="form-group text-right">
                <input type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" value="{{ __('Create') }}" data-ajax-popup="true">
            </div>
        </div>
    </div>
{{ Form::close() }}
