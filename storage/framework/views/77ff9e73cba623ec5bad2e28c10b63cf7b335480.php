<?php echo e(Form::open(array('url'=>'user','method'=>'post'))); ?>

<div class="form-group">
    <?php echo e(Form::label('name',__('Name'), ['class'=>'form-label'] )); ?>

    <?php echo e(Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))); ?>

</div>
<div class="form-group">
    <?php echo e(Form::label('email',__('Email'), ['class'=>'form-label'] )); ?>

    <?php echo e(Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))); ?>

</div>
<div class="form-group">
    <?php echo e(Form::label('password',__('Password'), ['class'=>'form-label'])); ?>

    <?php echo e(Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))); ?>

</div>
<div class="modal-footer border-0 p-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>   
    <button type="submit" class="btn  btn-primary"><?php echo e(__('Create')); ?></button>
</div>
<?php echo e(Form::close()); ?><?php /**PATH F:\Laragon\www\shift2go\resources\views/user/create.blade.php ENDPATH**/ ?>