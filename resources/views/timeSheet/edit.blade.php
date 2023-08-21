{{ Form::model($timeSheet, ['route' => ['timesheet.update', $timeSheet->id], 'method' => 'PUT']) }}

    <div class="row">
        
        @if (\Auth::user()->type != 'employee')
            <div class="form-group col-md-6">
                {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
                {!! Form::select('employee_id', $employees, null, ['class' => 'form-control font-style select2', 'id'=>'choices-multiple','required' => 'required']) !!}
            </div>
        @endif
        <div class="form-group col-md-6">
            {{ Form::label('location_id', __('Employee'), ['class' => 'col-form-label']) }}
            {!! Form::select('location_id', $loaction, null, ['class' => 'form-control font-style select2', 'id'=>'choices-multiple','required' => 'required']) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
            {{ Form::date('date', null, ['class' => 'form-control d_week', 'autocomplete' => 'off', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }}
            {{ Form::number('hours', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.01']) }}
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('remark', __('Remark'), ['class' => 'col-form-label']) }}
            {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '2' ,'placeholder'=>'Enter remark']) !!}
        </div>

        <div class="form-group  col-md-12">
   
            <!-- {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }} -->
            <label class="col-3 col-form-label text-right required">Status</label>

            <select class="form-control select2 form-control-solid" data-size="7" data-live-search="true" name="status" id="status" required>
                      <option selected disabled value="">Select Staus</option>
                   
                      <option value="pending"  @if($timeSheet->status == 'pending') selected @endif>Pending</option>
                      <option value="approved"  @if($timeSheet->status == 'approved') selected @endif>Approved</option>


                    </select>
            <!-- {!! Form::text('status', null, ['class' => 'form-control' ,'placeholder'=>'Enter status']) !!} -->
        </div>
    </div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}
