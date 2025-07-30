<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card custom--card">
            <div class="card-header">
                <h5 class="card-title text-center"><?php echo app('translator')->get('Paystack'); ?></h5>
            </div>
            <div class="card-body p-5">
                <form action="<?php echo e(route('ipn.'.$deposit->gateway->alias)); ?>" method="POST" class="text-center">
                    <?php echo csrf_field(); ?>
                    <ul class="list-group list-group-flush text-center">
                        <li class="list-group-item d-flex flex-wrap justify-content-between px-0">
                            <?php echo app('translator')->get('You have to pay'); ?>
                            <strong><?php echo e(showAmount($deposit->final_amo)); ?> <?php echo e(__($deposit->method_currency)); ?></strong>
                        </li>
                    </ul>
                    <button type="button" class="btn btn--base w-100 mt-3" id="btn-confirm"><?php echo app('translator')->get('Pay Now'); ?></button>
                    <script
                        src="//js.paystack.co/v1/inline.js"
                        data-key="<?php echo e($data->key); ?>"
                        data-email="<?php echo e($data->email); ?>"
                        data-amount="<?php echo e(round($data->amount)); ?>"
                        data-currency="<?php echo e($data->currency); ?>"
                        data-ref="<?php echo e($data->ref); ?>"
                        data-custom-button="btn-confirm"
                    >
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate.'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/test.fadded-socials.com/core/resources/views/templates/basic/user/payment/Paystack.blade.php ENDPATH**/ ?>