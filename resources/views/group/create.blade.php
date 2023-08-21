{{ Form::open(['url' => 'groups', 'enctype' => 'multipart/form-data']) }}
    <div class="form-group">
        {{ Form::label('', __('Name'), ['class' => 'form-control-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control', 'required' => '']) }}
    </div>
    <div class="form-group">
        {{ Form::label('', __('Employee'), ['class' => 'form-control-label']) }}
        {!! Form::select('employee_id[]', $employees_select, null,  ['required' => false, 'multiple' => 'multiple', 'class'=> 'form-control js-multiple-select']) !!}
    </div>
    <div class="form-group text-right">
        <input type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" value="{{ __('Create') }}" data-ajax-popup="true">
    </div>
{{ Form::close() }}
