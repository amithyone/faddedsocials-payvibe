<?php $__env->startSection('content'); ?>

    <div class="pc-container">
        <div class="pc-content">
            <section class="catalog-section section-bg py-<?php echo e(@$products->count() ? 120 : 60); ?>">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xxl-10 col-xl-11">
                            <div class="catalog-item-wrapper">

                                <div style="border-radius: 100px; background: #10113D;color: #ffffff; padding: 10px; margin-bottom: 15px">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-3">Product</div>
                                        <div class="col-5">Price</div>
                                        <div class="col-2">Stock</div>
                                    </div>
                                </div>


                                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php echo $__env->make($activeTemplate.'partials/products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="empty-data text-center">
                                        <div class="thumb">
                                            <img src="<?php echo e(asset($activeTemplateTrue . 'images/not-found.png')); ?>">
                                        </div>
                                        <h4 class="title"><?php echo app('translator')->get('No result found for "'.$category->name.'"'); ?></h4>
                                    </div>
                                <?php endif; ?>
                                <?php echo e(paginateLinks($products)); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

<?php $__env->stopSection(); ?>




<?php echo $__env->make($activeTemplate . 'layouts.main2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/fadded/core/resources/views/templates/basic/category_products.blade.php ENDPATH**/ ?>