{{ Form::open(['url' => 'roles', 'enctype' => 'multipart/form-data']) }}
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
                {{ Form::number('default_break', null, ['class' => 'form-control', 'placeholder' => __('0 Minutes')]) }}
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                {{ Form::label('', __('Colour'), ['class' => 'form-label']) }}                
                {{ Form::input('color', 'color', '#eeeeee', array('class' => 'form-control', 'style' => 'min-height:40px;')) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __(' Employee'), ['class' => 'form-label']) }}
                {!! Form::select('employees[]', $employees_select, null, ['required' => false, 'multiple' => 'multiple', 'id'=>'choices-multiple-location_id' ,'class'=> 'form-control multi-select']) !!}                
            </div>
        </div>
        <div class="modal-footer border-0 p-0">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
        </div>
    </div>
{{ Form::close() }}
