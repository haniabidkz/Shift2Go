{{ Form::model($location, ['route' => ['locations.update', $location->id], 'method' => 'PUT']) }}
<div class="form-group">
    <div class="row">
        <div class="col-6">
            {{ Form::label('', __('First Name'), ['class' => 'form-label']) }}
            {{ Form::text('first_name', 'joeee', ['class' => 'form-control', 'required' => '']) }}
        </div>
        <div class="col-6">
            {{ Form::label('', __('Last Name'), ['class' => 'form-label']) }}
            {{ Form::text('last_name', 'manr', ['class' => 'form-control', 'required' => '']) }}
        </div>
    </div>
</div>
<div class="form-group">
    {{ Form::label('', __('Email'), ['class' => 'form-label']) }}
    {{ Form::email('email', 'test@gmail.com', ['class' => 'form-control', 'required' => '']) }}
</div>
<div class="form-group">
    {{ Form::label('', __(' Default Role'), ['class' => 'form-label']) }}
    {!! Form::select('default_role_id', $role_select, null, ['required' => false, 'class' => 'form-control']) !!}
</div>
<div class="form-group text-right">
    <input type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" value="{{ __('Save') }}" data-ajax-popup="true">
</div>
{{ Form::close() }}
