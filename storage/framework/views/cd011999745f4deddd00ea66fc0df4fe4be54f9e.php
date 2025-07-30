<?php $__env->startSection('content'); ?>
    <div class="pc-container">
        <div class="pc-content">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger my-4">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
            <?php if(session()->has('error')): ?>
                <div class="alert alert-danger mt-2">
                    <?php echo e(session()->get('error')); ?>

                </div>
            <?php endif; ?>

            <form action="change-password" method="POST">
                <?php echo csrf_field(); ?>

                <div class="dashboard-body__content">
                    <div class="dashboard-body__item-wrapper">

                        <div class="p-3">

                            <h6 class="mt-3 p-3">Update your password</h6>

                        </div>


                        <div class="p-3">
                            <div class="card-body">
                                <h6>Enter new password</h6>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                        </div>

                        <div class="p-3">
                            <div class="card-body">
                                <h6>Confirm password</h6>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <div class="p-3">

                            <button type="submit"
                                    style="background: #20CCB4FF; border: 0px; color: white"
                                    class="btn btn-main btn-lg w-100 pill p-3" id="btn-confirm"><?php echo app('translator')->get('Reset Password'); ?>

                        </div>


            </form>

        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate.'layouts.main2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/test.fadded-socials.com/core/resources/views/templates/basic/user/change-password.blade.php ENDPATH**/ ?>