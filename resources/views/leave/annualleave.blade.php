{{ Form::model($annual_holiday, ['route' => ['holidays.annual-leave-response', $annual_holiday->id], 'method' => 'POST']) }}
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                {{ Form::label('', __('Annual Holiday Allowance'), ['class' => 'form-label']) }}
                <div class="row">
                    <div class="col-sm-7 ">
                        {{ Form::number('leave[time]', $holiday['time'], ['class' => 'form-control'] ) }}
                    </div>
                    <div class="col-sm-5 ">
                        {!! Form::select('leave[type]', ['day' => __('Day')], $holiday['type'], ['required' => false, 'id'=>'choices-multiple-11' ,'class'=> 'form-control multi-select']) !!}
                    </div>
                </div>
                <span class="clearfix"> </span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            {{ Form::label('', __('Apply To'), ['class' => 'form-label']) }}
            <div class="form-group">
                {!! Form::select('leave[apply_to]', ['all_year' => __('Default Allowance')], $holiday['apply_to'], ['required' => false, 'id'=>'choices-multiple-location_id' ,'class'=> 'form-control multi-select']) !!}
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
