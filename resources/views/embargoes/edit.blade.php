{{ Form::model($embargo, ['route' => ['embargoes.update', $embargo->id], 'method' => 'PUT']) }}
<div class="row">
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('', __('Start Date'), ['class' => 'form-label']) }}
            {{ Form::date('start_date', null, ['class' => 'form-control' , "id" => "date_between" , 'required' => '', 'placeholder' => __('Please Start Date') ]) }}
            {{ Form::hidden('issue_by', Auth::user()->id, ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('', __('End Date'), ['class' => 'form-label']) }}
            {{ Form::date('end_date', null, ['class' => 'form-control end_date' , "id" => "date_between" ,  'required' => '', 'placeholder' => __('Please End Date')]) }}
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
            <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
