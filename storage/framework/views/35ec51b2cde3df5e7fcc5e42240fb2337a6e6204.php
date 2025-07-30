<?php $__env->startSection('panel'); ?>

    <div class="row mb-none-30">


        <div class="col-lg-9 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">

                    <div class="row card-title">
                        <div class="col"> <h5 class="border-bottom pb-2 d-flex justify-content-start"><?php echo app('translator')->get('Set Coupon'); ?></h5></div>
                        <?php if($coupons->status == 0): ?>
                        <div class="col-3"> <span class="mb-4 badge badge--danger d-flex justify-content-end"><?php echo app('translator')->get('Inactive'); ?></span></div>
                        <?php else: ?>
                            <div class="col-3"> <span class="mb-4 badge badge--success d-flex justify-content-end"><?php echo app('translator')->get('Active'); ?></span></div>
                        <?php endif; ?>

                    </div>

                    <form action="<?php echo e(route('admin.update.coupon')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Coupon Code'); ?></label>
                            <input class="form-control" type="text" value="<?php echo e($coupons->coupon_code ?? "Set Name"); ?>"
                                   name="coupon_code" required>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Percentage %'); ?></label>
                            <input class="form-control" value="<?php echo e($coupons->amount ?? "Set Percentage"); ?>" type="number"
                                   name="amount" required>
                        </div>

                        <input class="form-control" value="<?php echo e($coupons->id ?? "Null"); ?>" type="text" name="id" hidden>


                        <div class="form-group">
                            <label><?php echo app('translator')->get('Status'); ?></label>
                            <select class="form-control" name="status" required>
                                <option value="">Select</option>
                                <option value="1">Activate</option>
                                <option value="0">Deactivate</option>
                            </select>
                        </div>
                </div>

                <button type="submit" class="btn btn--primary w-100 btn-lg h-45"><?php echo app('translator')->get('Continue'); ?></button>
                </form>
            </div>
        </div>
    </div>
    </div>

<?php $__env->stopSection(); ?>






<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/public_html/core/resources/views/admin/coupon.blade.php ENDPATH**/ ?>