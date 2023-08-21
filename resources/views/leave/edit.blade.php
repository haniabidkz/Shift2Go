{{ Form::model($leave, ['route' => ['holidays.update', $leave->id], 'method' => 'PUT','id' => 'leave_request_delete']) }}
    <div class="row">
        <div class="col-sm-12 col-md-7 col-sm-7">
            <div class="form-group">
                {{ Form::label('', __('Employee'), ['class' => 'form-label']) }}
                {!! Form::select('user_id[]', $employees_select, null, ['required' => true, 'multiple' => 'multiple', 'id'=>'choices-multiple-location_id' ,'class'=> 'form-control multi-select']) !!}
            </div>
        </div>

        <div class="col-sm-12 col-md-5 col-lg-5">
            <div class="form-group">
                {{ Form::label('', __('Type'), ['class' => 'form-label']) }}
                {!! Form::select('leave_type', ['1' => 'Holiday', '2' => 'Other Leave'], null, ['required' => true, 'id'=>'choices-multiple-employee' ,'class'=> 'form-control multi-select']) !!}
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                {{ Form::label('', __('Start Date'), ['class' => 'form-label']) }}
                {{ Form::date('start_date', null, ['class' => 'form-control leave_date_start' , 'required' => '']) }}
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                {{ Form::label('', __('End Date'), ['class' => 'form-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control leave_date_due', 'required' => '']) }}
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                {{ Form::label('', __(''), ['class' => 'form-label']) }}
                <div class="form-check form-switch">
                    {{ Form::checkbox('paid_status', 'paid', (!empty($paid_status)) ? true : false , ['class' => 'form-check-input input-primary', 'id' => 'customCheckdef4']) }}
                    <label class="form-check-label" for="customCheckdef4">{{ __('Paid/unpaid') }}</label>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                {{ Form::label('', __('Days'), ['class' => 'form-label']) }}
                {{ Form::number('days', null, ['class' => 'form-control leave_days',  'required' => false, 'readonly' => true, 'disabled' => true ]) }}
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                {{ Form::label('', __('Record Hours'), ['class' => 'form-label']) }}
                {!! Form::select('leave_time_type', ['total' => 'Total', 'daily' => 'Daily'], (!empty($leave_time['leave_time_type'])) ? $leave_time['leave_time_type'] : null, ['required' => true,  'id'=>'choices-multiple' ,'class'=> 'form-control multi-select total_daily_hour']) !!}
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="form-group total_all_hour " >
                {{ Form::label('', __('Total Hours'), ['class' => 'form-label']) }}
                {{ Form::number('leave_time1', (!empty($leave_time['leave_time1'])) ? $leave_time['leave_time1'] : null, ['class' => 'form-control', 'required' => false]) }}
            </div>
            <div class="form-group total_date_hour " >
                {!! (!empty($leave_time['leave_time_by_dail_hour'])) ? $leave_time['leave_time_by_dail_hour'] : null !!}
                <span class="clearfix"></span>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
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
