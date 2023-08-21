{{ Form::open(['route' => ['timesheet.store']]) }}

    <div class="row">
        @if(\Auth::user()->enable_chatgpt())
        <div class="text-end">
            <a href="#" class="btn btn-print-invoice btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['timesheet']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
                <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
            </a>
        </div>
        @endif
        @if (\Auth::user()->type != 'employee')
            <div class="form-group col-md-6">
                {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
                {!! Form::select('employee_id', $employees, null, ['class' => 'form-control  select2 user_change' , 'id'=>'choices-multiple', 'required' => 'required','placeholder'=>'Select employee']) !!}
            </div>
        @endif
        @if (\Auth::user()->type == 'employee')
            <div class="form-group col-md-6">
                {{ Form::label('loaction_id', __('Location'), ['class' => 'col-form-label']) }}
                {!! Form::select('loaction_id', $loaction, null, ['class' => 'form-control  select2' , 'id'=>'choices-multiple datas', 'required' => 'required','placeholder'=>'Select employee']) !!}
                </select>
            </div>
        @else
            <div class="form-group col-md-6">
                {{ Form::label('loaction_id', __('Location'), ['class' => 'col-form-label']) }}
                <select name="loaction_id" class='form-control select2' id='datas' required='required' placeholder='Select employee'>
                    <option value="">Select Location</option>
                </select>
            </div>
        @endif

        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
            {{ Form::date('date', '', ['class' => 'form-control d_week current_date', 'autocomplete' => 'off', 'required' => 'required' ,'placeholder'=>'Select date']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }}
            {{ Form::number('hours', '', ['class' => 'form-control','autocomplete' => 'off' ,'required' => 'required', 'step' => '0.01' ,'placeholder'=>'Enter hours']) }}
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('remark', __('Remark'), ['class' => 'col-form-label']) }}
            {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '2' ,'placeholder'=>'Enter remark']) !!}
        </div>
    </div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script>

<script>
    $(document).on('change', '.user_change', function() {
        var employee_id = $(this).val();

        $.ajax({
            url: '{{ route('timesheet.changelocation') }}',
            method: 'post',
            data: {'id' : employee_id},
            success: function(response) {
                html = "";
                $.each(response.data,function(key, value)
                {
                    html += "<option value=" + key  + ">" + value + "</option>"
                });
                document.getElementById("datas").innerHTML = html;
            }
        });
    });
</script>
{{-- <script>
    $(document).on('change', '.user_change', function() {
        var employee_id = $(this).val();

        $.ajax({
            url: '{{ route('timesheet.changelocation') }}',
            method: 'post',
            data: {'id' : employee_id},
            success: function(response) {
                var $select = $('#datas');
                $.each(response.data,function(key, value)
                {
                    $select.append('<option value=' + key + '>' + value + '</option>');
                });
            }
        });
    });
</script> --}}

