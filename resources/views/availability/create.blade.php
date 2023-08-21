{{ Form::open(['url' => 'availabilities', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        @if(\Auth::user()->enable_chatgpt())
        <div class="text-end">
            <a href="#" class="btn btn-print-invoice btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['availability']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
                <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
            </a>
        </div>
        @endif
        <div class="col-sm-12 col-lg-12">
            <div class="row">
                @if(Auth::user()->acount_type == 1)
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('', __('User'), ['class' => 'form-label']) }}
                        {!! Form::select('user_id', $filter_employees, null, ['required' => false, 'id'=>'choices-multiple-location_id' ,'class'=> 'form-control multi-select']) !!}
                    </div>
                </div>
                @else
                    {!! Form::hidden('user_id', Auth::id() ) !!}
                @endif
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('', __('Name'), ['class' => 'form-label']) }}
                        {{ Form::text('name', null, ['class' => 'form-control', 'required' => '']) }}
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        {{ Form::label('', __('Start Date'), ['class' => 'form-label']) }}
                        {{ Form::date('start_date', null, ['class' => 'form-control' , 'required' => true]) }}
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        {{ Form::label('', __('End Date'), ['class' => 'form-label']) }}
                        {{ Form::date('end_date', null, ['class' => 'form-control' , 'required' => false]) }}                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        {{ Form::label('', __('Repeat Every'), ['class' => 'form-label']) }}
                        {!! Form::select('repeat_week', ['1'=>__('Week'), '2'=> __('2 Week'), '3'=> __('3 Week'), '4'=> __('4 Week')], null, ['required' => false, 'id'=>'choices-multiple', 'class'=> 'form-control multi-select']) !!}
                    </div>
                </div>
                <div class="col-12 text-danger">{{ __('If you add availabiliti of same date than system will refer last record') }}</div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-12">
            <div id="schedule4" class="jqs-demo mb-3"></div>
        </div>
        <div class="col-sm-12">
            <div class="modal-footer border-0 p-0">
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
            </div>
        </div>
    </div>
{{ Form::close() }}
