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
                    
                    <div class="mt-4">
                        <div class="alert alert-warning" role="alert">
                            <p><strong><?php echo app('translator')->get('Important Transfer Instructions'); ?>:</strong> 
                                <?php echo app('translator')->get('Please copy the transaction reference below and use it as the <b>narration</b> or <b>description</b> when making your transfer. Our customer support will need this reference if you report any issues about this transaction'); ?> 
                            </p>
                            <div class="d-flex align-items-center">
                                <span class="value me-2" id="reference">FADDED-<?php echo e($data->val->reference ?? 'N/A'); ?></span>
                                <button class="btn btn-sm copy-btn" onclick="copyToClipboard('reference')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
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
                                        <span class="value me-2" id="accountNumber"><?php echo e(__($data->val->virtual_account ?? 'N/A')); ?></span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('accountNumber')">
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
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold"><?php echo app('translator')->get('Amount'); ?>:</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="amount"><?php echo e(showAmount($data->val->amount)); ?> <?php echo e(__($data->val->currency)); ?></span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('amount')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                  <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold"><?php echo app('translator')->get('Reference'); ?>:</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="reference">FADDED-<?php echo e($data->val->reference ?? 'N/A'); ?></span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('reference')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>

                    

                    
                    <div class="alert alert-warning mt-3" role="alert">
                        <ul class="mb-0">
                            <li><?php echo app('translator')->get('Please transfer the exact amount to avoid payment issues'); ?></li>
                            <li><?php echo app('translator')->get('Use the reference as narration to help us track your payment and resolve any delays'); ?></li>
                            <li><?php echo app('translator')->get('Your account will be credited automatically after successful transfer'); ?></li>
                            <li><?php echo app('translator')->get('This virtual account is valid for this transaction only'); ?></li>
                        </ul>
                    </div>

                    <a href="javascript:void(0);" 
                       id="paymentButton"
                       style="background: #20CCB4FF; border: 0px; color:#ffffff; margin-top: 0;"
                       class="btn btn-main btn-lg w-100 pill p-3">
                        I have sent
                    </a>

                    <div id="loadingIcon" style="display: none; text-align: center; margin-top: 10px;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                        <p>Checking for transaction...</p>
                    </div>

                    <p id="timerText" style="font-weight: bold; text-align: center; margin-top: 10px; font-size: 18px;">
                        Account number expires in <span id="countdown">10:00</span>
                    </p>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            let button = document.getElementById("paymentButton");
                            let buttonCountdownElement = document.getElementById("buttonCountdown");
                            let countdownElement = document.getElementById("countdown");
                            let loadingIcon = document.getElementById("loadingIcon");
                            let timeLeft = 10;
                            let accountTimeLeft = 10 * 60;

                            let accountCountdownTimer = setInterval(function() {
                                let minutes = Math.floor(accountTimeLeft / 60);
                                let seconds = accountTimeLeft % 60;
                                countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                                accountTimeLeft--;

                                if (accountTimeLeft < 0) {
                                    clearInterval(accountCountdownTimer);
                                    countdownElement.textContent = "Expired";
                                }
                            }, 1000);

                            button.addEventListener("click", function() {
                                button.disabled = true;
                                button.style.backgroundColor = "#ccc";
                                loadingIcon.style.display = "block";

                                let buttonCountdownTimer = setInterval(function() {
                                    if (timeLeft <= 0) {
                                        clearInterval(buttonCountdownTimer);
                                        buttonCountdownElement.textContent = "0";
                                        button.disabled = false;
                                        button.style.backgroundColor = "#20CCB4FF";
                                        loadingIcon.style.display = "none";
                                        return;
                                    } else {
                                        buttonCountdownElement.textContent = timeLeft;
                                        timeLeft--;
                                    }
                                }, 1000);

                                setTimeout(function() {
                                    window.location.href = "/user/deposit/new";
                                }, 30000);
                            });
                        });

                        function copyToClipboard(elementId) {
                            const text = document.getElementById(elementId).innerText;
                            if (navigator.clipboard) {
                                navigator.clipboard.writeText(text).then(() => {
                                    alert('Copied to clipboard: ' + text);
                                }).catch(err => {
                                    console.error('Clipboard error:', err);
                                });
                            } else {
                                const tempTextArea = document.createElement('textarea');
                                tempTextArea.value = text;
                                document.body.appendChild(tempTextArea);
                                tempTextArea.select();
                                document.execCommand('copy');
                                document.body.removeChild(tempTextArea);
                                alert('Copied to clipboard: ' + text);
                            }
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

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
    .alert-primary {
        background-color: #e2e3ff;
        border-color: #c8c9ff;
    }
    .copy-btn {
        background: #20CCB4FF;
        color: white;
        border: none;
        padding: 0.3rem 0.6rem;
        border-radius: 5px;
    }
    hr {
        margin: 0.5rem 0;
        opacity: 0.15;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate.'layouts.main2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wolrdhome/public_html/core/resources/views/templates/basic/user/payment/Xtrapay.blade.php ENDPATH**/ ?>