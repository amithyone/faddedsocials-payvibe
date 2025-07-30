<?php $__env->startSection('content'); ?>
    <div class="login-main" style="background-image: url('<?php echo e(asset('assets/admin/images/login.jpg')); ?>')">
        <div class="container custom-container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-xl-5 col-lg-6 col-md-8 col-sm-11">
                    <div class="login-area">
                        <div class="login-wrapper">
                            <div class="login-wrapper__top">
                                <h3 class="title text-white"><?php echo app('translator')->get('Recover Account'); ?></h3>
                            </div>
                            <div class="login-wrapper__body">
                                <form action="<?php echo e(route('admin.password.change')); ?>" method="POST" class="login-form">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="email" value="<?php echo e($email); ?>">
                                    <input type="hidden" name="token" value="<?php echo e($token); ?>">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('New Password'); ?></label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Re-type New Password'); ?></label>
                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                                    </div>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <a href="<?php echo e(route('admin.login')); ?>" class="forget-text"><?php echo app('translator')->get('Login Here'); ?></a>
                                    </div>
                                    <button type="submit" class="btn cmn-btn w-100"><?php echo app('translator')->get('Submit'); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/public_html/core/resources/views/admin/auth/passwords/reset.blade.php ENDPATH**/ ?>