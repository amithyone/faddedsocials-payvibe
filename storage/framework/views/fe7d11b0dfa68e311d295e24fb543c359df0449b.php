<?php $__env->startSection('panel'); ?>
<div class="row">

    <div class="col-lg-12">
        <div class="show-filter mb-3 text-end">
            <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm"><i class="las la-filter"></i> <?php echo app('translator')->get('Filter'); ?></button>
        </div>
        <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <form action="">
                    <div class="d-flex flex-wrap gap-4">
                        <div class="flex-grow-1">
                            <label><?php echo app('translator')->get('TRX/Username'); ?></label>
                            <input type="text" name="search" value="<?php echo e(request()->search); ?>" class="form-control">
                        </div>
                        <div class="flex-grow-1">
                            <label><?php echo app('translator')->get('Status'); ?></label>
                            <select name="status" class="form-control">
                                <option value=""><?php echo app('translator')->get('All'); ?></option>
                                <option value="<?php echo e(Status::ORDER_PAID); ?>" <?php if(request()->status == Status::ORDER_PAID): echo 'selected'; endif; ?>><?php echo app('translator')->get('Paid'); ?></option>
                                <option value="<?php echo e(Status::ORDER_UNPAID); ?>" <?php if(request()->status != null && request()->status == Status::ORDER_UNPAID): echo 'selected'; endif; ?>>
                                    <?php echo app('translator')->get('Unpaid'); ?>
                                </option>
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label><?php echo app('translator')->get('Date'); ?></label>
                            <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="<?php echo app('translator')->get('Start date - End date'); ?>" autocomplete="off" value="<?php echo e(request()->date); ?>">
                        </div>
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> <?php echo app('translator')->get('Filter'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('User'); ?></th>
                                <th><?php echo app('translator')->get('Payment Trx'); ?></th>
                                <th><?php echo app('translator')->get('Ordered At'); ?></th>
                                <th><?php echo app('translator')->get('Amount'); ?></th>
                                <th><?php echo app('translator')->get('Quantity'); ?></th>
                                <th><?php echo app('translator')->get('Status'); ?></th>
                                <th><?php echo app('translator')->get('Details'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $qty = @$order->orderItems->count();
                                    $perUnitPrice = @$order->orderItems->first()->price;
                                ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold"><?php echo e($order->user->fullname); ?></span>
                                        <br>
                                        <span class="small"> <a href="<?php echo e(appendQuery('search',$order->user->username)); ?>"><span>@</span><?php echo e($order->user->username); ?></a> </span>
                                    </td>

                                    <td>
                                        <div>
                                            <div class="fw-bold"><?php echo e($order->deposit->trx ?? "Trx"); ?></div>



                                        </div>
                                    </td>

                                    <td>
                                        <?php echo e(showDateTime($order->created_at)); ?><br><?php echo e(diffForHumans($order->created_at)); ?>

                                    </td>

                                    <td class="budget">
                                        <span class="d-block"><?php echo e($qty); ?> <?php echo app('translator')->get('Qty'); ?> x <?php echo e(showAmount($perUnitPrice)); ?> <?php echo e(__($general->cur_text)); ?></span>
                                        <span class="fw-bold">
                                            <?php echo e(showAmount($order->total_amount)); ?> <?php echo e(__($general->cur_text)); ?>

                                        </span>
                                    </td>

                                    <td>
                                        <?php echo e($qty); ?>

                                    </td>

                                    <td>
                                        <?php echo $order->statusBadge; ?>
                                    </td>

                                    <td>
                                        <a href="<?php echo e(route('admin.report.order.details', $order->id)); ?>" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> <?php echo app('translator')->get('Details'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td class="text-muted text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                                </tr>
                            <?php endif; ?>

                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
        <?php if($orders->hasPages()): ?>
        <div class="card-footer py-4">
            <?php echo e(paginateLinks($orders)); ?>

        </div>
        <?php endif; ?>
    </div><!-- card end -->
</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/vendor/datepicker.min.css')); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script-lib'); ?>
  <script src="<?php echo e(asset('assets/admin/js/vendor/datepicker.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/admin/js/vendor/datepicker.en.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
  <script>
    (function($){
        "use strict";
        if(!$('.datepicker-here').val()){
            $('.datepicker-here').datepicker();
        }
    })(jQuery)
  </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/public_html/core/resources/views/admin/reports/order_history.blade.php ENDPATH**/ ?>