<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <?php if(isset($errors) && $errors->any()): ?>
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
                    <h4 class="text-center mt-2"><?php echo app('translator')->get('PayVibe Payment Information'); ?></h4>
                </div>
                <div class="card-body p-5">
                    <div class="alert alert-info" role="alert">
                        <p class="mb-0">
                            <?php echo app('translator')->get('Please transfer exactly'); ?> 
                            <strong><?php echo e(showAmount(is_array($data->val) ? $data->val['amount'] : $data->val->amount)); ?> <?php echo e(__(is_array($data->val) ? $data->val['currency'] : $data->val->currency)); ?></strong> 
                            <?php echo app('translator')->get('to the virtual account below'); ?>
                        </p>
                    </div>

                    
                    <div class="mt-4">
                        <div class="alert alert-warning" role="alert">
                            <p><strong><?php echo app('translator')->get('Important Transfer Instructions'); ?>:</strong> 
                                <?php echo app('translator')->get('Please copy the transaction reference below and use it as the <b>narration</b> or <b>description</b> when making your transfer. Our customer support will need this reference if you report any issues about this transaction'); ?> 
                            </p>
                            <div class="d-flex align-items-center">
                                <span class="value me-2" id="reference">FADDED-<?php echo e(is_array($data->val) ? ($data->val['reference'] ?? 'N/A') : ($data->val->reference ?? 'N/A')); ?></span>
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
                                    <span class="value" id="bankName"><?php echo e(__(is_array($data->val) ? ($data->val['bank_name'] ?? 'N/A') : ($data->val->bank_name ?? 'N/A'))); ?></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold"><?php echo app('translator')->get('Account Number'); ?>:</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="accountNumber"><?php echo e(__(is_array($data->val) ? ($data->val['virtual_account'] ?? 'N/A') : ($data->val->virtual_account ?? 'N/A'))); ?></span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('accountNumber')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold"><?php echo app('translator')->get('Account Name'); ?>:</span>
                                    <span class="value"><?php echo e(__(is_array($data->val) ? ($data->val['account_name'] ?? 'N/A') : ($data->val->account_name ?? 'N/A'))); ?></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold"><?php echo app('translator')->get('Amount'); ?>:</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="amount"><?php echo e(showAmount(is_array($data->val) ? $data->val['amount'] : $data->val->amount)); ?> <?php echo e(__(is_array($data->val) ? $data->val['currency'] : $data->val->currency)); ?></span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('amount')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold"><?php echo app('translator')->get('Reference'); ?>:</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="reference2">FADDED-<?php echo e(is_array($data->val) ? ($data->val['reference'] ?? 'N/A') : ($data->val->reference ?? 'N/A')); ?></span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('reference2')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <button type="button" 
                            class="btn btn-outline-primary btn-lg w-100 pill p-3 mt-4" 
                            data-toggle="modal" 
                            data-target="#payvibeAccountModal">
                        <i class="fas fa-question-circle me-2"></i>
                        <?php echo app('translator')->get('Need Help? View Transfer Instructions'); ?>
                    </button>

                    
                    <div class="alert alert-warning mt-3" role="alert">
                        <ul class="mb-0">
                            <li><?php echo app('translator')->get('Please transfer the exact amount to avoid payment issues'); ?></li>
                            <li><?php echo app('translator')->get('Use the reference as narration to help us track your payment'); ?></li>
                            <li><?php echo app('translator')->get('Your account will be credited automatically after successful transfer'); ?></li>
                            <li><?php echo app('translator')->get('This virtual account is valid for this transaction only'); ?></li>
                        </ul>
                    </div>

                    <a href="javascript:void(0);" 
                       id="paymentButton"
                       style="background: #00be9c; border: 0px; color:#ffffff; margin-top: 0;"
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PayVibe Help Modal -->
<div class="modal fade" id="payvibeAccountModal" tabindex="-1" role="dialog" aria-labelledby="payvibeAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payvibeAccountModalLabel">
                    <i class="fas fa-question-circle me-2"></i>
                    <?php echo app('translator')->get('PayVibe Transfer Instructions'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="alert alert-info" role="alert">
                    <h6><i class="fas fa-info-circle me-2"></i><?php echo app('translator')->get('Account Details Summary'); ?>:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><?php echo app('translator')->get('Bank'); ?>:</strong> <?php echo e(__(is_array($data->val) ? ($data->val['bank_name'] ?? 'N/A') : ($data->val->bank_name ?? 'N/A'))); ?><br>
                            <strong><?php echo app('translator')->get('Account Number'); ?>:</strong> <?php echo e(__(is_array($data->val) ? ($data->val['virtual_account'] ?? 'N/A') : ($data->val->virtual_account ?? 'N/A'))); ?><br>
                            <strong><?php echo app('translator')->get('Account Name'); ?>:</strong> <?php echo e(__(is_array($data->val) ? ($data->val['account_name'] ?? 'N/A') : ($data->val->account_name ?? 'N/A'))); ?>

                        </div>
                        <div class="col-md-6">
                            <strong><?php echo app('translator')->get('Amount'); ?>:</strong> <?php echo e(showAmount(is_array($data->val) ? $data->val['amount'] : $data->val->amount)); ?> <?php echo e(__(is_array($data->val) ? $data->val['currency'] : $data->val->currency)); ?><br>
                            <strong><?php echo app('translator')->get('Reference'); ?>:</strong> FADDED-<?php echo e(is_array($data->val) ? ($data->val['reference'] ?? 'N/A') : ($data->val->reference ?? 'N/A')); ?>

                        </div>
                    </div>
                </div>

                
                <div class="alert alert-primary mt-3" role="alert">
                    <h6><i class="fas fa-list-ol me-2"></i><?php echo app('translator')->get('How to Transfer'); ?>:</h6>
                    <ol class="mb-0">
                        <li><?php echo app('translator')->get('Open your bank app or visit your bank'); ?></li>
                        <li><?php echo app('translator')->get('Select "Transfer" or "Send Money"'); ?></li>
                        <li><?php echo app('translator')->get('Enter the account number above'); ?></li>
                        <li><?php echo app('translator')->get('Enter the exact amount'); ?></li>
                        <li><?php echo app('translator')->get('Use the reference as narration/description'); ?></li>
                        <li><?php echo app('translator')->get('Complete the transfer'); ?></li>
                    </ol>
                </div>

                
                <div class="alert alert-warning mt-3" role="alert">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i><?php echo app('translator')->get('Important Notes'); ?>:</h6>
                    <ul class="mb-0">
                        <li><?php echo app('translator')->get('Transfer the exact amount to avoid issues'); ?></li>
                        <li><?php echo app('translator')->get('Use the reference as narration for tracking'); ?></li>
                        <li><?php echo app('translator')->get('Your account will be credited automatically'); ?></li>
                        <li><?php echo app('translator')->get('This virtual account is valid for this transaction only'); ?></li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                <button type="button" class="btn btn-primary" onclick="copyAllDetails()">
                    <i class="fas fa-copy me-2"></i><?php echo app('translator')->get('Copy All Details'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

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
                    button.style.backgroundColor = "#00be9c";
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
                showToast('Copied to clipboard: ' + text, 'success');
            }).catch(err => {
                console.error('Clipboard error:', err);
                showToast('Failed to copy to clipboard', 'error');
            });
        } else {
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = text;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);
            showToast('Copied to clipboard: ' + text, 'success');
        }
    }

    function copyAllDetails() {
        const accountNumber = document.getElementById('accountNumber').innerText;
        const bankName = document.getElementById('bankName').innerText;
        const accountName = document.querySelector('#payvibeAccountModal .alert-info .col-md-6:first-child').innerText.split('Account Name:')[1].trim();
        const amount = document.getElementById('amount').innerText;
        const reference = document.getElementById('reference').innerText;

        const allDetails = `Bank: ${bankName}\nAccount Number: ${accountNumber}\nAccount Name: ${accountName}\nAmount: ${amount}\nReference: ${reference}`;

        if (navigator.clipboard) {
            navigator.clipboard.writeText(allDetails).then(() => {
                showToast('All details copied to clipboard!', 'success');
            }).catch(err => {
                console.error('Clipboard error:', err);
                showToast('Failed to copy details', 'error');
            });
        } else {
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = allDetails;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);
            showToast('All details copied to clipboard!', 'success');
        }
    }

    function showToast(message, type) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        `;
        
        if (type === 'success') {
            toast.style.backgroundColor = '#28a745';
        } else {
            toast.style.backgroundColor = '#dc3545';
        }
        
        toast.textContent = message;
        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
</script>

<style>
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>
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
        background: #00be9c;
        color: white;
        border: none;
        padding: 0.3rem 0.6rem;
        border-radius: 5px;
    }
    hr {
        margin: 0.5rem 0;
        opacity: 0.15;
    }
    .modal-lg {
        max-width: 600px;
    }
    .modal-content {
        border-radius: 15px;
    }
    .modal-header {
        background: #00be9c;
        color: white;
        border-radius: 15px 15px 0 0;
    }
    .modal-header .close {
        color: white;
        opacity: 0.8;
    }
    .modal-header .close:hover {
        opacity: 1;
    }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make($activeTemplate.'layouts.main2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/amiithyone/Documents/faddedsocials/socials /resources/views/templates/basic/user/payment/PayVibe.blade.php ENDPATH**/ ?>