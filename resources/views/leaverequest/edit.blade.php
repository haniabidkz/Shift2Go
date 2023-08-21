{{ Form::model($leaveRequest, ['route' => ['leave-request.update', $leaveRequest->id], 'method' => 'PUT', 'class'=>"leave_request" ]) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('', __('Employee'), ['class' => 'form-label']) }}
            {!! Form::select('leave_type', $employee_option, null, ['required' => true, 'id'=>'choices-multiple-location_id1' ,'class'=> 'form-control multi-select']) !!}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('', __('Type'), ['class' => 'form-label']) }}
            {!! Form::select('leave_type', ['1' => 'Holiday', '2' => 'Other Leave'], null, ['required' => true, 'id'=>'choices-multiple-1' ,'class'=> 'form-control multi-select']) !!}
        </div>
    </div>
    <div class="col-5">
        <div class="form-group">
            {{ Form::label('', __('Start Date'), ['class' => 'form-label']) }}
            {{ Form::date('start_date', null, ['class' => 'form-control' , 'required' => '']) }}
        </div>
    </div>
    <div class="col-5">
        <div class="form-group">
            {{ Form::label('', __('End Date'), ['class' => 'form-label']) }}
            {{ Form::date('end_date', null, ['class' => 'form-control' , 'required' => '']) }}
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            {{ Form::label('', __('Day'), ['class' => 'form-label']) }}
            {{ Form::text('', '0', ['class' => 'form-control total_day', 'readonly' => true, 'disabled' => true, ]) }}
        </div>
    </div>
    <div class="col-12 {{  (!empty($has_permission)) ? '' : 'd-none' }}">
        <div class="form-group total_all_hour">
            {{ Form::label('', __('Total Hours'), ['class' => 'form-label']) }}
            {{ Form::number('leave_time1', null, ['class' => 'form-control', 'required' => false]) }}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('', __('Message'), ['class' => 'form-label']) }}
            {{ Form::textarea('message', null, ['class' => 'form-control autogrow' ,'rows'=>'2' ,'style'=>'resize: none']) }}
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
