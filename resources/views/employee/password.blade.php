{{ Form::model($employee, ['route' => ['employee.addpassword', $employee->id], 'method' => 'POST']) }}
    {{ Form::hidden('employee_id', $employee->id) }}
    {{ Form::hidden('form_type', 'manage_permission') }}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('', __('Set Password'), ['class' => 'form-label']) }}                               
                {{ Form::password('password', ['class' => 'form-control', 'required' => '']) }}
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 p-0">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>   
        <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
    </div>
{{ Form::close() }}