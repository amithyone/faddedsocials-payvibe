<?php
    $feature = getContent('feature.content', true);
    $featureElement = getContent('feature.element', orderById: true);
?>
<section class="features py-120">
    <div class="container">
        <div class="section-heading">
            <span class="section-heading__subtitle"><?php echo e(__(@$feature->data_values->heading)); ?> </span>
            <h2 class="section-heading__title"><?php echo e(__(@$feature->data_values->subheading)); ?></h2>
            <p class="section-heading__desc"><?php echo e(__(@$feature->data_values->description)); ?></p>
        </div>
        <div class="row gy-4 justify-content-center feature-item-wrapper">
            <?php $__currentLoopData = $featureElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="feature-item">
                        <span class="feature-item__number"><?php echo e($loop->iteration); ?></span>
                        <span class="feature-item__icon flex-center"><?php echo @$item->data_values->icon ?> </span>
                        <h5 class="feature-item__title"><?php echo e(__(@$item->data_values->title)); ?></h5>
                        <p class="feature-item__desc"><?php echo e(__(@$item->data_values->description)); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH /home/wolrdhome/public_html/core/resources/views/templates/basic/sections/feature.blade.php ENDPATH**/ ?>