{{ Form::model($role, ['route' => ['roles.update', $role->id], 'method' => 'PUT']) }}
    <div class="row">
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => '']) }}
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            {{ Form::label('', __('Default Break'), ['class' => 'form-label']) }}
            {{ Form::number('default_break', null, ['class' => 'form-control', 'placeholder' => '0 Minutes']) }}
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            {{ Form::label('', __('Colour'), ['class' => 'form-label']) }}
            {{ Form::input('color', 'color', null, array('class' => 'form-control', 'style' => 'min-height:40px;')) }}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('', __(' Employee'), ['class' => 'form-label']) }}
            {!! Form::select('employees[]', $employees_select, null, ['required' => false, 'multiple' => 'multiple', 'id'=>'choices-multiple-location_id' ,'class'=> 'form-control multi-select']) !!}
        </div>
    </div>
    <div class="col-12">
        <div class="modal-footer border-0 p-0">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
