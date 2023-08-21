{{ Form::open(['url' => 'employees', 'enctype' => 'multipart/form-data']) }}
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                {{ Form::label('', __('First Name'), ['class' => 'form-label']) }}
                {{ Form::text('first_name', null, ['class' => 'form-control', 'required' => '']) }}
            </div>
            <div class="col-6">
                {{ Form::label('', __('Last Name'), ['class' => 'form-label']) }}
                {{ Form::text('last_name', null, ['class' => 'form-control', 'required' => '']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('', __('Email'), ['class' => 'form-label']) }}
        {{ Form::email('email', null, ['class' => 'form-control', 'required' => '']) }}
    </div>
    <div class="form-group">
        {{ Form::label('', __('Employee Role'), ['class' => 'form-label']) }}
        {{ Form::select('role_id[]', $role_select,null, ['class' => 'form-control multi-select', 'id'=>'choices-multiple' ,'multiple'=>'','required'=>false]) }}
    </div>
    <div class="form-group">
        {{ Form::label('', __('Location'), ['class' => 'form-label']) }}
        {!! Form::select('location_id[]', $location_select, null, ['required' => false, 'multiple' => 'multiple', 'id'=>'choices-multiple-location_id' ,'class'=> 'form-control multi-select']) !!}
    </div>
    <div class="modal-footer border-0 p-0">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
    </div>
{{ Form::close() }}
