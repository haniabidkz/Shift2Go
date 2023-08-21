{{ Form::open(array('url' => 'coupon','method' =>'post')) }}
<div class="row">
    {{-- <a href="#" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['coupon']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
        <i class="fas fa-robot"></i>
    </a> --}}
    <div class="text-end">
        <a href="#" class="btn btn-print-invoice btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['coupon']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
        </a>
    </div>

    <div class="form-group col-md-12">
        {{Form::label('name',__('Name'), ['class'=>'form-label'] )}}
        {{Form::text('name',null,array('class'=>'form-control font-style','required'=>'required'))}}
    </div>

    <div class="form-group col-md-6">
        {{Form::label('discount',__('Discount'), ['class'=>'form-label'] )}}
        {{Form::number('discount',null,array('class'=>'form-control','required'=>'required','step'=>'0.01'))}}
        <span class="small">{{__('Note: Discount in Percentage')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('limit',__('Limit'), ['class'=>'form-label'] )}}
        {{Form::number('limit',null,array('class'=>'form-control','required'=>'required'))}}
    </div>
    <div class="form-group">
        <div class="d-flex radio-check">
            <div class="form-check m-1">
                <input type="radio" id="manual_code" value="manual" name="icon-input" class="form-check-input code" checked="checked">
                <label class="form-check-label" for="manual_code">{{ __('Manual') }}</label>
            </div>
            <div class="form-check m-1">
                <input type="radio" id="auto_code" value="auto" name="icon-input" class="form-check-input code">
                <label class="form-check-label" for="auto_code">{{ __('Auto Generate') }}</label>
            </div>
        </div>
    </div>
    <div class="form-group col-md-12 d-block" id="manual">
        <input class="form-control font-uppercase" name="manualCode" type="text">
    </div>
    <div class="form-group col-md-12 d-none" id="auto">
        <div class="row">
            <div class="col-md-10">
                <input class="form-control" name="autoCode" type="text" id="auto-code">
            </div>
            <div class="col-md-2">
                <a href="#" class="btn btn-sm btn-secondary btn-icon rounded-pill" id="code-generate">
                    <i class="ti ti-history"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 p-0">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
    </div>

</div>
{{ Form::close() }}

