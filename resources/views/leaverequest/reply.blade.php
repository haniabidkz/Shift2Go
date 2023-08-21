{{ Form::model($leaverequest, ['route' => ['leave-request.response', $leaverequest->id], 'method' => 'POST', 'id' => '']) }}
    <div class="row">
        @if(\Auth::user()->enable_chatgpt())
        <div class="text-end">
            <a href="#" class="btn btn-print-invoice btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['leave request']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
                <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
            </a>
        </div>
        @endif
        <div class="col-12">
            <div class="form-group">
                {!! $requsst_string !!}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group"> <span class="request_message">"{{ $leaverequest->message }}"</span> </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Message'), ['class' => 'form-label']) }}
                {{ Form::textarea('response_message', null, ['class' => 'form-control autogrow' ,'rows'=>'2' ,'style'=>'resize: none']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-control-label"> </label>
                <div class="form-check form-switch">
                    {{ Form::checkbox('paid_status', 'paid', $paid_status, ['class' => 'form-check-input input-primary' ,'id'=>'customSwitch2']) }}
                    <label class="form-check-label" for="customSwitch2">{{ __('Unpaid/Paid') }}</label>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="modal-footer border-0 p-0">
                {{ Form::hidden('leave_approval',null,['class' => 'leave_approval']) }}
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <input type="submit" class="btn btn-danger deny_request_button" value="{{ __('Deny') }}">
                <input type="submit" class="btn btn-primary approve_request_button" value="{{ __('Approve') }}">
            </div>
        </div>
    </div>
{{ Form::close() }}
