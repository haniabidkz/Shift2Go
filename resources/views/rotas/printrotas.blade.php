
<form method="POST" action="{{ route('rotas.print') }}" target="_blank">
    @csrf
     <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
             {{ Form::hidden('week', $week) }}
             {{ Form::hidden('role_id', $role_id) }}
             {{ Form::hidden('create_by', $create_by) }}
             {{ Form::hidden('location_id', $location_id) }}
             {{ Form::label('', __('Select User'), ['class' => 'form-control-label mb-4 h6 d-block']) }}

             @if (!empty($user_array) && count($user_array) > 0)
                @foreach ($user_array as $key => $val)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input user_checkbox" id="{{ 'emp_'.$val['id'] }}" name="user[{{ $key }}]" type="checkbox" value="{{ $val['id'] }}" checked>
                        <label class="form-check-label" for="{{ 'emp_'.$val['id'] }}"> {{ $val['name'] }} </label>
                    </div>
                @endforeach
            @else
                <p>{{ __('No user found.') }}</p>
            @endif
         </div>
         <div class="modal-footer border-0 p-0 mt-3">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
        </div>
     </div>
</form>
