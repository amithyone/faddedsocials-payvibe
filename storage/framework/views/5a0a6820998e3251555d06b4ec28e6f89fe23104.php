<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
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

            <div class="col-md-6">
                <div class="card border-0">
                    <div class="card-header bg-white">
                        <h4 class="text-center mt-2"><?php echo app('translator')->get('Payment Information'); ?></h4>
                    </div>
                    <div class="card-body p-5">
                        <div class="alert alert-info" role="alert">
                            <p class="mb-0">
                                <?php echo app('translator')->get('Please transfer exactly'); ?> 
                                <strong><?php echo e(showAmount($data->val->amount)); ?> <?php echo e(__($data->val->currency)); ?></strong> 
                                <?php echo app('translator')->get('to the bank account below'); ?>
                            </p>
                        </div>

                        <div class="border rounded p-4 mt-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="font-weight-bold"><?php echo app('translator')->get('Bank Name'); ?>:</span>
                                        <span class="value" id="bankName"><?php echo e(__($data->val->bank_name ?? 'N/A')); ?></span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="font-weight-bold"><?php echo app('translator')->get('Account Number'); ?>:</span>
                                        <div class="d-flex align-items-center">
                                            <span class="value" id="accountNumber"><?php echo e(__($data->val->virtual_account ?? 'N/A')); ?></span>
                                            <button class="btn btn-sm ms-2" onclick="copyToClipboard('accountNumber')" style="background: #20CCB4FF; color: white;">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="font-weight-bold"><?php echo app('translator')->get('Account Name'); ?>:</span>
                                        <span class="value"><?php echo e(__($data->val->account_name ?? 'N/A')); ?></span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="font-weight-bold"><?php echo app('translator')->get('Amount'); ?>:</span>
                                        <div class="d-flex align-items-center">
                                            <span class="value" id="amount"><?php echo e(showAmount($data->val->amount)); ?> <?php echo e(__($data->val->currency)); ?></span>
                                            <button class="btn btn-sm ms-2" onclick="copyToClipboard('amount')" style="background: #20CCB4FF; color: white;">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="alert alert-warning" role="alert">
                                <ul class="mb-0">
                                    <li><?php echo app('translator')->get('Please transfer the exact amount to avoid payment issues'); ?></li>
                                    <li><?php echo app('translator')->get('Your account will be credited automatically after successful transfer'); ?></li>
                                    <li><?php echo app('translator')->get('This virtual account is valid for this transaction only'); ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted"><?php echo app('translator')->get('Transaction Reference'); ?>: <strong><?php echo e($data->val->reference ?? 'N/A'); ?></strong></p>
                        </div>

                    <a href="javascript:void(0);" 
   id="paymentButton"
   style="background: #20CCB4FF; border: 0px; color:#ffffff; margin-top: 0;"
   class="btn btn-main btn-lg w-100 pill p-3">
    I have sent
</a>

<p id="timerText" style="font-weight: bold; text-align: center; margin-top: 10px; font-size: 18px;">
    Account number expires in <span id="countdown">30:00</span>
</p>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let button = document.getElementById("paymentButton");
    let countdownElement = document.getElementById("countdown");
    
    let timeLeft = 30 * 60; // 30 minutes in seconds

    function updateCountdown() {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        timeLeft--;

        if (timeLeft < 0) {
            clearInterval(countdownTimer);
            countdownElement.textContent = "Expired";
            document.getElementById("timerText").style.color = "red"; // Change text to red when expired
        }
    }

    let countdownTimer = setInterval(updateCountdown, 1000);

    button.addEventListener("click", function() {
        window.location.href = "<?php echo e(url('/user/deposit/new')); ?>"; // Redirect to the deposit page
    });
});
</script>




                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        const text = element.innerText;
        
        navigator.clipboard.writeText(text).then(function() {
            // Show temporary success message
            const originalBackground = element.style.backgroundColor;
            element.style.backgroundColor = '#d4edda';
            setTimeout(() => {
                element.style.backgroundColor = originalBackground;
            }, 500);
        }).catch(function(err) {
            console.error('Failed to copy text: ', err);
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
<style>
    .value {
        font-weight: 600;
        font-size: 1.1em;
    }
    .border {
        background-color: #f8f9fa;
    }
    .alert-info {
        background-color: #e8f4f8;
        border-color: #d6e9f0;
    }
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffecb5;
    }
    hr {
        margin: 0.5rem 0;
        opacity: 0.15;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate.'layouts.main2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/test.fadded-socials.com/core/resources/views/templates/basic/user/payment/Xtrapay.blade.php ENDPATH**/ ?>