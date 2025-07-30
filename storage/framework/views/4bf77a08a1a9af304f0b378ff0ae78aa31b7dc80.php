<?php $__env->startSection('content'); ?>

    <div class="container">

    <div class="row justify-content-end mb-4">
    <div class="col-xl-4 col-md-6">
        <form action="">
            <div class="input-group">
                <input type="text" name="search" class="form-control form--control" value="<?php echo e(request()->search); ?>" placeholder="<?php echo app('translator')->get('Search by Trx'); ?>">
                <button class="input-group-text bg--base border-0 text--white">
                    <i class="las la-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
          <div class="table-responsive">
            <table class="table">

                <thead>
                    <tr>
                        <th><?php echo app('translator')->get('Trx'); ?></th>
                        <th><?php echo app('translator')->get('Time'); ?></th>
                        <th><?php echo app('translator')->get('Amount'); ?></th>
                        <th><?php echo app('translator')->get('Status'); ?></th>
                        <th><?php echo app('translator')->get('Action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <span class="info"> <?php echo e($deposit->trx); ?> </span>
                        </td>

                        <td>
                            <span class=""><?php echo e(diffForHumans($deposit->created_at)); ?></span>
                        </td>
                        <td>

                            <?php echo e(__($general->cur_sym)); ?><?php echo e(showAmount($deposit->amount)); ?>

                        </td>

                        <td>
                            <?php echo $deposit->statusBadge ?>
                        </td>
                        <?php
                        $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                        ?>
                        <td>
                            <div class="action-buttons">
                                <?php if($deposit->status == 0): ?>
                                <a href="/user/resolve-deposit?trx=<?php echo e($deposit->trx); ?>" class="btn btn-sm btn-danger" type="button">Resolve</button>
                                 <?php endif; ?>

                        </td>



                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="100%" class="text-center"><?php echo e(__($emptyMessage)); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo e(paginateLinks($deposits)); ?>

</div>


<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><?php echo app('translator')->get('Details'); ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush userData">
                </ul>
                <div class="feedback"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn--sm" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
            </div>
        </div>
    </div>
</div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function ($) {
            "use strict";

            $('.detailBtn').on('click', function () {
                var modal = $('#detailModal');

                var userData = $(this).data('info');
                var html = '';
                if(userData){
                    userData.forEach(element => {
                        if(element.type != 'file'){
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        }
                    });
                }

                if($(this).data('admin_feedback') != undefined){
                    var adminFeedback = `
                        <div class="my-3 ms-2">
                            <strong><?php echo app('translator')->get('Admin Feedback'); ?></strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                }else{
                    var adminFeedback = '';
                }

                if(!html && !adminFeedback){
                    html = `<span class='d-block text-center mt-2 mb-2'><?php echo e(__($emptyMessage)); ?></span>`;
                }

                modal.find('.userData').html(html);
                modal.find('.feedback').html(adminFeedback);
                modal.modal('show');
            });
        })(jQuery);

    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make($activeTemplate.'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/test.fadded-socials.com/core/resources/views/templates/basic/user/deposit_history.blade.php ENDPATH**/ ?>