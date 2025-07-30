<?php
    // List of keys to ignore from displaying
    $ignoreKeys = [
        'sessionId',
        'settlementId',
        'sourceAccountNumber',
        'sourceAccountName'
    ];
?>

<div class="row">
    <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(in_array($k, $ignoreKeys)): ?>
            
            <?php continue; ?>
        <?php endif; ?>

        <div class="col-md-12 mb-4">
            <?php if(is_object($val) || is_array($val)): ?>
                <h6><?php echo e(keyToTitle($k)); ?></h6>
                <hr>
                <div class="ms-3">
                    <?php echo $__env->make('admin.deposit.gateway_data', ['details' => $val], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            <?php else: ?>
                <h6><?php echo e(keyToTitle($k)); ?></h6>
                <?php if(($k === 'amount' || $k === 'settledAmount') && is_numeric($val)): ?>
                    <?php
                        $fixedCharge = 100;
                        $percentCharge = 1.5;
                        $afterFixedCharge = $val - $fixedCharge;
                        if ($afterFixedCharge < 0) {
                            $afterFixedCharge = 0;
                        }
                        $finalAmount = $afterFixedCharge - ($afterFixedCharge * ($percentCharge / 100));
                        if ($finalAmount < 0) {
                            $finalAmount = 0;
                        }
                    ?>
                    <p>
                        <?php echo e(number_format($finalAmount, 2)); ?>

                    </p>
                <?php else: ?>
                    <p><?php echo e($val); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /home/wolrdhome/public_html/core/resources/views/admin/deposit/gateway_data.blade.php ENDPATH**/ ?>