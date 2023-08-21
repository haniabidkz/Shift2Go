{{Form::model($plan, array('route' => array('plan.update', $plan->id), 'method' => 'PUT', 'enctype' => "multipart/form-data")) }}
<div class="row">
    <div class="form-group col-md-6">
        {{Form::label('name',__('Name'), ['class' => 'form-label'])}}
        {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('Enter Plan Name'),'required'=>'required'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('price',__('Price'), ['class' => 'form-label'])}}
        {{Form::number('price',null,array('class'=>'form-control','placeholder'=>__('Enter Plan Price'),'step'=>'0.01'))}}
    </div>

    <div class="form-group col-md-6">
        {{Form::label('max_employee',__('Maximum Employee'), ['class' => 'form-label'])}}
        {{Form::number('max_employee',null,array('class'=>'form-control','required'=>'required'))}}
        <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('company_storage',__('Storage Limit'), ['class' => 'form-label'])}}
        {{Form::number('company_storage',null,array('class'=>'form-control','required'=>'required'))}}
        <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('duration', __('Duration'), ['class' => 'form-label']) }}
        {!! Form::select('duration', $arrDuration, null,array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) !!}
    </div>
    <div class="form-group col-md-6">
        <div class="custom-control form-switch pt-2">
            <input type="checkbox" name="enable_chatgpt" id="enable_chatgpt"  class="form-check-input"
                {{ $plan['enable_chatgpt'] == 'on' ? 'checked' : '' }}>
            <label class="custom-label text-dark" for="enable_chatgpt">{{ __('Enable Chatgpt') }}</label>
        </div>
    </div>
    {{-- <div class="form-group col-md-12">
        {{ Form::label('image', __('Image'), ['class' => 'form-label']) }} <br>
        <label for="logo" class="form-label choose-files bg-primary "><i class="ti ti-upload px-1"></i>{{ __('Select Image') }}</label>
        <input type="file" name="image" id="logo" class="custom-input-file d-none">
    </div> --}}
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
    </div>
    <div class="modal-footer border-0 p-0">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
    </div>
</div>
{{ Form::close() }}

