<?php
    $counters = getContent('counter.element', orderById: true);
?>
<div class="counter bg-img py-60" data-background-image="<?php echo e(asset($activeTemplateTrue . 'images/thumbs/counter-bg.jpg')); ?>">
    <div class="container">
        <div class="row">
            <?php $__currentLoopData = $counters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $counter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-sm-6">
                    <div class="counter-item text-center">
                        <h1 class="counter-item__number mb-0">
                            <span><?php echo e(@$counter->data_values->digit); ?></span>
                        </h1>
                        <span class="counter-item__text"><?php echo e(__(@$counter->data_values->title)); ?></span>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH /home/wolrdhome/public_html/core/resources/views/templates/basic/sections/counter.blade.php ENDPATH**/ ?>