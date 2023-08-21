{{ Form::open(['url' => 'leave-request', 'enctype' => 'multipart/form-data' ]) }}
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Employee'), ['class' => 'form-control-label']) }}
                {!! Form::select('emp_id', $employee_option, null, ['required' => true, 'class'=> 'form-control']) !!}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Type'), ['class' => 'form-control-label']) }}
                {!! Form::select('leave_type', ['1' => 'Holiday', '2' => 'Other Leave'], null, ['required' => true, 'data-placeholder'=> 'Select Leave' ,'class'=> 'form-control']) !!}
            </div>
        </div>
        <div class="col-5">
            <div class="form-group">
                {{ Form::label('', __('Start Date'), ['class' => 'form-control-label']) }}
                {{ Form::date('start_date', null, ['class' => 'form-control start_date' ,'id' => 'date_between' ,'required' => '']) }}
            </div>
        </div>
        <div class="col-5">
            <div class="form-group">
                {{ Form::label('', __('End Date'), ['class' => 'form-control-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control end_date', 'id' => 'date_between', 'required' => '']) }}
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                {{ Form::label('', __('Day'), ['class' => 'form-control-label']) }}
                {{ Form::text('', '0', ['class' => 'form-control total_day', 'readonly' => true, 'disabled' => true, ]) }}
            </div>
        </div>
        <div class="col-6 {{  (!empty($has_permission)) ? '' : 'd-none' }}">
            <div class="form-group total_all_hour">
                {{ Form::label('', __('Total Hours'), ['class' => 'form-control-label']) }}
                {{ Form::number('leave_time1', null, ['class' => 'form-control', 'required' => false]) }}
            </div>
        </div>
        <div class="col-6 {{  (!empty($has_permission)) ? '' : 'd-none' }}">
            <div class="form-group py-1 ">
                {{ Form::label('', __(''), ['class' => 'form-control-label']) }}
                <div class="custom-control custom-switch">
                    {{ Form::checkbox('paid_status', 'paid', false, ['class' => 'custom-control-input', 'id' => 'customSwitch1dsad']) }}
                    <label class="custom-control-label form-control-label" for="customSwitch1dsad">{{__('Unpaid/Paid')}}</label>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Message'), ['class' => 'form-control-label']) }}
                {{ Form::textarea('message', null, ['class' => 'form-control autogrow' ,'rows'=>'2' ,'style'=>'resize: none']) }}
            </div>
        </div>

        <div class="col-12">
            <div class="form-group text-right">
                <input type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" value="{{ __('Save') }}" data-ajax-popup="true">
            </div>
        </div>
    </div>
{{ Form::close() }}
