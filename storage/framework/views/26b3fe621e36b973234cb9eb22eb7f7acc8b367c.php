<?php $__env->startSection('content'); ?>

    <section class="blog py-120">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                <?php $__empty_1 = true; $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <div class="blog-item__thumb">
                                <img src="<?php echo e(getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->image, '410x275')); ?>" alt="<?php echo app('translator')->get('image'); ?>">
                            </div>
                            <div class="blog-item__content">
                                <span class="blog-item__meta"><i class="las la-calendar-week"></i><?php echo e($blog->created_at->format('d M, Y')); ?></span>
                                <h4 class="blog-item__title">
                                    <a class="blog-item__title-link" href="<?php echo e(route('blog.details', [slug($blog->data_values->title), $blog->id])); ?>"><?php echo e(__(@$blog->data_values->title)); ?></a>
                                </h4>
                                <p class="blog-item__desc">
                                    <?php
                                        echo strLimit(strip_tags($blog->data_values->description), 40);
                                    ?>
                                </p>
                                <a href="<?php echo e(route('blog.details', [slug($blog->data_values->title), $blog->id])); ?>" class="blog-item__link"><?php echo app('translator')->get('Read More'); ?> <i class="las la-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> 
                    <h4 class="text-center"><?php echo e(__($emptyMessage)); ?></h4>
                <?php endif; ?>
            </div>
            <?php echo e(paginateLinks($blogs)); ?>

        </div>
    </section>

    <?php if($sections->secs != null): ?>
        <?php $__currentLoopData = json_decode($sections->secs); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make($activeTemplate . 'sections.' . $sec, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wolrdhome/public_html/core/resources/views/templates/basic/blogs.blade.php ENDPATH**/ ?>