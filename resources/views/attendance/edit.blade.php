{{ Form::model($attendanceEmployee, ['route' => ['attendance.update', $attendanceEmployee->id], 'method' => 'PUT']) }}

<div class="row">
<!--<td>-->
<!--    <div style="display: flex; align-items: center;">-->
<!--        <div style="text-align: center; margin-right: 20px;">-->
<!--            <label>ClockIn Image</label>-->
<!--            <br>-->
<!--            @if ($attendanceEmployee->selfie != null)-->
<!--                <img src="{{ asset("public/uploads/$attendanceEmployee->selfie") }}" alt="Selfie Image" width="220" height="150" data-toggle="modal" data-target="#selfieModal{{ $attendanceEmployee->id }}">-->
<!--            @else-->
<!--                <p>Pending</p>-->
<!--            @endif-->
<!--        </div>-->

<!--        <div style="text-align: center;">-->
<!--            <label>ClockOut Image</label>-->
<!--            <br>-->
<!--            @if ($attendanceEmployee->clockout_selfie != null)-->
<!--                <img src="{{ asset("public/uploads/$attendanceEmployee->clockout_selfie") }}" alt="Selfie Image" width="220" height="150" data-toggle="modal" data-target="#fullImageModal{{ $attendanceEmployee->id }}">-->
<!--            @else-->
<!--                <p>Pending</p>-->
<!--            @endif-->
<!--        </div>-->
<!--    </div>-->
<!--</td>-->

<td>
    <div style="display: flex; align-items: center;">
        @if ($attendanceEmployee->selfie != null)
        <div style="text-align: center;">
            <label>ClockIn Image</label>
            <br>
          <img src="{{ asset("public/uploads/$attendanceEmployee->selfie") }}" alt="Selfie Image" width="220" height="150" data-toggle="modal" data-target="#imageModal{{ $attendanceEmployee->id }}" style="margin-right: 20px;">
            <div class="modal fade" id="imageModal{{ $attendanceEmployee->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Image</h5>
                             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset("public/uploads/$attendanceEmployee->selfie") }}" alt="Selfie Image" style="max-width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
         </div>
        @else
            <p style="margin-right: 10px;">Pending</p>
        @endif

        @if ($attendanceEmployee->clockout_selfie != null)
        <div style="text-align: center;">
        <label>ClockOut Image</label>
            <br>
             <img src="{{ asset("public/uploads/$attendanceEmployee->clockout_selfie") }}" alt="Selfie Image" width="220" height="150" data-toggle="modal" data-target="#imageModal{{ $attendanceEmployee->id }}">
            <div class="modal fade" id="imageModal{{ $attendanceEmployee->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Image</h5>
                             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset("public/uploads/$attendanceEmployee->clockout_selfie") }}" alt="Selfie Image" style="max-width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
                </div>
                
        @else
            <p>Pending</p>
            
        @endif
    </div>
</td>
<!--<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">-->
<!--    <div class="modal-dialog" role="document">-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header">-->
<!--                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>-->
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
                <!--    <span aria-hidden="true">&times;</span>-->
                <!--</button>-->
<!--                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
<!--            </div>-->
<!--            <div class="modal-body">-->
<!--                <img src="Full-size Image" alt="Full-size Image" id="fullImage" class="img-fluid">-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->



    <div class="form-group col-lg-6 col-md-6 ">
        {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
        {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2']) }}
    </div>
    <div class="form-group col-lg-6 col-md-6">
        {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
        {{ Form::text('date', null, ['class' => 'form-control d_week','autocomplete'=>'off']) }}
    </div>

    <div class="form-group col-lg-6 col-md-6">
        {{ Form::label('clock_in', __('Clock In'), ['class' => 'col-form-label']) }}
        {{ Form::time('clock_in', null, ['class' => 'form-control pc-timepicker-2','id'=>'clock_in']) }}
    </div>

    <div class="form-group col-lg-6 col-md-6">
        {{ Form::label('clock_out', __('Clock Out'), ['class' => 'col-form-label']) }}
        {{ Form::time('clock_out', null, ['class' => 'form-control pc-timepicker-2 ','id'=>'clock_out']) }}
    </div>


    <div class="form-group  col-md-12">
   
   <!-- {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }} -->
   <label class="col-3 col-form-label text-right required">Status</label>

   <select class="form-control select2 form-control-solid" data-size="7" data-live-search="true" name="status" id="status" required>
             <option selected disabled value="">Select Staus</option>
          
             <option value="pending"  @if($attendanceEmployee->approval_status == 'pending') selected @endif>Pending</option>
             <option value="approved"  @if($attendanceEmployee->approval_status == 'approved') selected @endif  >Approved</option>


           </select>
   <!-- {!! Form::text('status', null, ['class' => 'form-control' ,'placeholder'=>'Enter status']) !!} -->
</div>

    <div class="form-group col-lg-12 col-md-12">
        {{ Form::label('Breaks', __('Breaks'), ['class' => 'col-form-label']) }}

        <div class="table-responsive">
            <table class="table table-bordered" id="dynamic_field">
                <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>

                @foreach($breaks as $break)
                <tr>

                    <td><input type="time" name="start_time[]" placeholder="Enter your Name" value="{{$break['break_in']}}" class="form-control name_list time" /></td>
                    <td><input type="time" name="end_time[]" placeholder="Enter your Name" value="{{$break['break_out']}}" class="form-control name_list time" /></td>
                    <!-- <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button> -->
                </tr>
                @endforeach

            </table>
            <!-- <button type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" /></button> -->
        </div>
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Edit') }}" class="btn btn-primary">
</div>

{{ Form::close() }}


<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var imageSrc = button.data('src'); // Extract image source from data-src attribute

            // Update the modal image source and alt text
            var modal = $(this);
            modal.find('#fullImage').attr('src', imageSrc);
            modal.find('#fullImage').attr('alt', 'Full-size Image');
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        // var postURL = "<?php echo url('addmore'); ?>";
        var i = 1;


        $('#add').click(function() {
            i++;
            $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="time" name="start_time[]" placeholder="Enter your Name" class="form-control name_list time" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr><tr id="row'+i+'" class="dynamic-added"><td><input type="time" name="end_time[]" placeholder="Enter your Name" class="form-control name_list time" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
        });


        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

    });
</script>

<script>
$(document).ready(function () {
  $('.time').timepicker({
    format: 'HH/MM',
    locale: 'en'
  });
</script>