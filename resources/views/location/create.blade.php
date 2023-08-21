{{ Form::open(['url' => 'locations', 'enctype' => 'multipart/form-data']) }}
<div class="form-group">
    {{ Form::label('', __('Name'), ['class' => 'form-control-label']) }}
    {{ Form::text('name', null, ['class' => 'form-control', 'required' => '']) }}
</div>
<div class="form-group">
    {{ Form::label('', __('Address'), ['class' => 'form-control-label']) }}
    {{ Form::textarea('address', null, ['class' => 'form-control autogrow' ,'rows'=>'1' ,'style'=>'resize: none' ,'required' => '']) }}
</div>
<div class="form-group">
    {{ Form::label('', __(' Employee'), ['class' => 'form-control-label']) }}
    {!! Form::select('employees[]', $employees_select, null, ['required' => false, 'multiple' => 'multiple', 'id'=>'choices-multiple-location_id' ,'class'=> 'form-control multi-select']) !!}
</div>

<div class="mb-3 row">
    <label for="location" class="col-sm-3 col-form-label">Location</label>
    <div class="col-sm-9">
    <input id="searchTextField" name="location" class="form-control" type="text" placeholder="Enter a location" />
        <input id="cityLat" name="latitude" class="form-control" type="hidden" />
        <input id="cityLng" name="longitude" class="form-control" type="hidden" />
    </div>
</div>
<!-- <div class="form-group ">
    {{ Form::label('', __(''), ['class' => 'form-control-label']) }}
    <div class="custom-control custom-switch">
        {{ Form::checkbox('paid_status', 'paid', false, ['class' => 'custom-control-input', 'id' => 'customSwitch1dsad']) }}
        <label class="custom-control-label form-control-label" for="customSwitch1dsad">{{__('Unpaid/Paid')}}</label>
    </div>
</div> -->

<div class="modal-footer border-0 p-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
</div>
{{ Form::close() }}