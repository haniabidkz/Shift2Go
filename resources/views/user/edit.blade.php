{{ Form::model($user, array('route' => array('user.update', $user->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{Form::label('name',__('Name'), ['class'=>'form-label'] ) }}
    {{Form::text('company_name',null,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))}}
</div>
<div class="form-group">
    {{Form::label('email',__('Email'), ['class'=>'form-label'] )}}
    {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}
</div>
<div class="modal-footer border-0 p-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
</div>
{{ Form::close() }}
