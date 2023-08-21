{{ Form::open(['url' => 'embargoes', 'enctype' => 'multipart/form-data']) }}
{{ Form::hidden('issue_by', Auth::user()->id, ['class' => 'form-control']) }}
<div class="row">
    @if(\Auth::user()->enable_chatgpt())
    <div class="text-end">
        <a href="#" class="btn btn-print-invoice btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['embargo']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
        </a>
    </div>
    @endif
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('', __('Start Date'), ['class' => 'form-label']) }}
            {{ Form::date('start_date', null, ['class' => 'form-control start_date' , "id" => "date_between" , 'required' => '', 'placeholder' => __('Please Start Date') ]) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('', __('End Date'), ['class' => 'form-label']) }}
            {{ Form::date('end_date', null, ['class' => 'form-control end_date' , "id" => "pc-datepicker-2" , 'required' => '', 'placeholder' => __('Please End Date')]) }}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('', __('Message'), ['class' => 'form-label']) }}
            {{ Form::textarea('message', null, ['class' => 'form-control autogrow' ,'rows'=>'2' ,'style'=>'resize: none']) }}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('', __(' Employee'), ['class' => 'form-label']) }}
            {!! Form::select('employees[]', $employees_select, null, ['required' => false, 'multiple' => 'multiple', 'id'=>'choices-multiple-location_id' ,'class'=> 'form-control multi-select']) !!}
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
