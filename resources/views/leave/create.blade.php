{{ Form::open(['url' => 'holidays', 'enctype' => 'multipart/form-data' ]) }}
    <div class="row">
        @if(\Auth::user()->enable_chatgpt())
        <div class="text-end">
            <a href="#" class="btn btn-print-invoice btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['leave']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
                <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
            </a>
        </div>
        @endif
        <div class="col-sm-12 col-md-7 col-sm-7">
            <div class="form-group">
                {{ Form::label('', __('Employee'), ['class' => 'form-label']) }}
                @if(Auth::user()->acount_type == 3 || Auth::user()->acount_type == 2)
                    {!! Form::select('user_id[]', $employees_select, null, ['required' => true, 'id'=>'choices-multiple-employee' ,'class'=> 'form-control multi-select']) !!}
                @else
                    {!! Form::select('user_id[]', $employees_select, null, ['required' => true, 'multiple' => 'multiple', 'id'=>'choices-multiple-location_id' ,'class'=> 'form-control multi-select']) !!}
                @endif

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
        @if(Auth::user()->acount_type == 1)
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                {{ Form::label('', __(''), ['class' => 'form-label']) }}
                <div class="form-check form-switch">
                    <input type="checkbox" name="paid_status" value="paid" class="form-check-input input-primary" id="customCheckdef4">
                    <label class="form-check-label" for="customCheckdef4">{{__('Unpaid/Paid')}}</label>
                </div>

            </div>
        </div>
        @endif
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                {{ Form::label('', __('Days'), ['class' => 'form-label']) }}
                {{ Form::number('days', null, ['class' => 'form-control leave_days', 'readonly' => true , 'disabled'=> true, 'required' => false]) }}
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                {{ Form::label('', __('Record Hours'), ['class' => 'form-label']) }}
                {!! Form::select('leave_time_type', ['total' => 'Total', 'daily' => 'Daily'], null, ['required' => true, 'class'=> 'form-control multi-select total_daily_hour', 'id'=>'choices-multiple-leave-type' ]) !!}
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="form-group total_all_hour">
                {{ Form::label('', __('Total Hours'), ['class' => 'form-label']) }}
                {{ Form::number('leave_time1', null, ['class' => 'form-control', 'required' => false]) }}
            </div>
            <div class="form-group total_date_hour" style="display: none;">
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
                <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
            </div>
        </div>
    </div>
{{ Form::close() }}
