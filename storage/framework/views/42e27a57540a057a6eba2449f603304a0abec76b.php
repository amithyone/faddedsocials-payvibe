<?php $__env->startSection('app'); ?>
    <?php echo $__env->make($activeTemplate . 'partials.header_auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make($activeTemplate . 'partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset($activeTemplateTrue.'js/jquery.validate.js')); ?>"></script> 
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
    (function($) {
        "use strict"; 

        $('form').on('submit', function () {
            if(!$(this).hasClass('exclude')){
                if ($(this).valid()) {
                    $(':submit', this).attr('disabled', 'disabled');
                }
            }
        });

    })(jQuery);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make($activeTemplate . 'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/test.fadded-socials.com/core/resources/views/templates/basic/layouts/master.blade.php ENDPATH**/ ?>