{{ Form::model($rotas, ['route' => ['rotas.update', $rotas->id], 'method' => 'PUT', 'class' => 'rotas_edit_frm']) }}
    <div class="row">
        {{ Form::input('hidden', 'u_url', route('rotas.update', $rotas->id)) }}
        {{ Form::input('hidden', 'rotas_id', $rotas->id) }}
        {{ Form::input('hidden', 'user_id', $user_id) }}
        {{ Form::input('hidden', 'rotas_date', $date) }}
        {{ Form::input('hidden', 'location_id', null, array('id' => 'rotas_ctrate_location')) }}
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('', __('Start Time'), ['class' => 'form-label']) }}
                {!! Form::time('start_time', null, ["class" => "form-control start_time rotas_time",  "placeholder" => "Select time" , 'required' => true]) !!}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('', __('End Time'), ['class' => 'form-label']) }}
                {!! Form::time('end_time', null, ["class" => "form-control end_time rotas_time", "placeholder" => "Select time" , 'required' => true]) !!}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('', __('Break'), ['class' => 'form-label']) }}
                {{ Form::input('number', 'break_time', null, array('class' => 'form-control', 'required' => true)) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Role'), ['class' => 'form-label']) }}
                {{ Form::select('role_id', $role_option,null, ['class' => 'form-control multi-select', 'id'=>'choices-multiplepop_roleotiuon' ]) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Note'), ['class' => 'form-label']) }}
                {{ Form::textarea('note', null, ['class' => 'form-control autogrow' ,'rows'=>'2' ,'style'=>'resize: none']) }}
                <small>{{ __('Employees can only see notes for their own shifts') }}</small>
            </div>
        </div>

        <div class="col-12">
            <div class="modal-footer border-0 p-0">
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn  btn-primary rotas_edit_btn">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
{{ Form::close() }}
