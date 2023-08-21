{{ Form::open(['url' => 'store-language']) }}

<div class="form-group">    
    {{ Form::label('', __('Language Code'), ['class' => 'form-label']) }}
    {{ Form::text('code', null, ['class' => 'form-control', 'placeholder' => __('Enter new Language Code'), 'required' => 'required']) }}
</div>
<div class="modal-footer border-0 p-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Save') }}</button>
</div>
{{ Form::close() }}

