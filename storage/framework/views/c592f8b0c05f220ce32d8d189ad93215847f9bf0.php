<?php $__env->startSection('app'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title"><?php echo app('translator')->get('Payaza Payment'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group text-center">
                                <li class="list-group-item d-flex justify-content-between">
                                    <?php echo app('translator')->get('You have to pay '); ?>:
                                    <strong><?php echo e(showAmount($deposit->final_amo)); ?> <?php echo e(__($deposit->method_currency)); ?></strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="button" class="btn btn--primary w-100" id="payButton">
                                <?php echo app('translator')->get('Pay Now'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="https://js.payaza.africa/v1/inline.min.js"></script>
<script>
    'use strict';
    
    document.getElementById('payButton').addEventListener('click', function() {
        try {
            const config = {
                publicKey: "<?php echo e($data->val['public_key']); ?>",
                tx_ref: "<?php echo e($data->val['reference']); ?>",
                amount: <?php echo e($data->val['amount']); ?>,
                currency: "<?php echo e($data->val['currency']); ?>",
                customer: {
                    email: "<?php echo e($data->val['customer']['email']); ?>",
                    name: "<?php echo e($data->val['customer']['name']); ?>",
                    phone: "<?php echo e($data->val['customer']['phone']); ?>"
                },
                metadata: {
                    deposit_id: "<?php echo e($data->val['metadata']['deposit_id']); ?>",
                    transaction_id: "<?php echo e($data->val['metadata']['transaction_id']); ?>"
                },
                customizations: {
                    title: "<?php echo e($general->sitename); ?>",
                    description: "Payment for transaction <?php echo e($data->val['reference']); ?>"
                },
                callback_url: "<?php echo e($data->val['callback_url']); ?>"
            };

            console.log('Initializing Payaza with config:', config);

            const payaza = new PayazaCheckout({
                ...config,
                onSuccess: function(response) {
                    console.log('Payment successful:', response);
                    window.location.href = "<?php echo e(route('user.deposit.history')); ?>";
                },
                onError: function(error) {
                    console.error('Payment error:', error);
                    alert('Payment failed. Please try again.');
                },
                onClose: function() {
                    console.log('Payment window closed');
                }
            });

            payaza.setup();

        } catch (error) {
            console.error('Error initializing payment:', error);
            alert('Error initializing payment. Please check the console for details.');
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate.'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/test.fadded-socials.com/core/resources/views/templates/basic/user/payment/Payaza.blade.php ENDPATH**/ ?>