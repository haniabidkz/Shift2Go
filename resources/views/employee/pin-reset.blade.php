{{Form::model($employee,array('route' => array('employee.pin.update', $employee->id), 'method' => 'post')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('pin', __('Pin'), ['class' => 'form-label'] ) }}
       <input id="pin" type="text" class="form-control" name="pin" >
       @error('pin')
       <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
           </span>
       @enderror
    </div>
   
</div>
<div class="modal-footer border-0 p-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>   
    <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
</div>
{{ Form::close() }}
