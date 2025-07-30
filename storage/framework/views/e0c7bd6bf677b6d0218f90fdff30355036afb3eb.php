<?php
    $banned = @getContent('banned.content', true)->data_values;
?>

<?php $__env->startSection('app'); ?>
    <div class="banned-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-8 col-12 text-center">
                    <div class="ban-section">
                        <h4 class="text-center text-danger mb-4">
                            <?php echo e(__(@$banned->heading)); ?>

                        </h4>
                        <img src="<?php echo e(getImage('assets/images/frontend/banned/' . @$banned->image)); ?>" alt="<?php echo app('translator')->get('Banned Image'); ?>">
                        <div class="mt-4">
                            <p class="fw-bold mb-2"><?php echo app('translator')->get('Reason'); ?></p>
                            <p><?php echo e(__($user->ban_reason)); ?></p>
                        </div>
                        <a href="<?php echo e(route('home')); ?>" class="btn btn--base mt-4 btn--sm">
                            <i class="las la-undo"></i>
                            <?php echo app('translator')->get('Go Back'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .banned-section {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/public_html/core/resources/views/templates/basic/user/auth/authorization/ban.blade.php ENDPATH**/ ?>