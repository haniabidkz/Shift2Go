<?php
    $setting = \App\Models\Utility::settings();
?>
<?php if($setting['cookie_consent'] == 'on'): ?>
    <?php echo $__env->make('layouts.cookie_consent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<footer class="dash-footer">
    <div class="footer-wrapper">
        <div class="py-1">
            <span class="text-muted">  <?php echo e((Utility::getValByName('footer_text')) ? Utility::getValByName('footer_text') :  __('Copyright RotaGo')); ?> <?php echo e(date('Y')); ?></span>
        </div>
    </div>
</footer>
<?php /**PATH F:\Laragon\www\shift2go\resources\views/partision/footer.blade.php ENDPATH**/ ?>