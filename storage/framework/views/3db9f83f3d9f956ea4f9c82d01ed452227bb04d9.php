<div class="top-header d-none d-lg-block">
    <div class="container">
        <div class="top-header__inner flex-between">
            <a class="navbar-brand logo" href="<?php echo e(route('home')); ?>">
                <img src="<?php echo e(getImage(getFilePath('logoIcon') . '/dark_logo.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>">
            </a>
           
            <div class="account-buttons flex-align gap-3">

                <?php if($general->multi_language): ?>
                <?php
                    $language = App\Models\Language::all();
                ?>
                    <div class="language-box">
                        <select class="langSel form-control form-select">
                            <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                            <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->code); ?>" <?php if(session('lang') == $item->code): ?> selected  <?php endif; ?>><?php echo e(__($item->name)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('user.logout')); ?>" class="btn--account">
                        <span class="icon fs-14 me-1"><i class="fas fa-sign-out-alt"></i></span> <?php echo app('translator')->get('Logout'); ?>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('user.login')); ?>" class="btn--account">
                        <span class="icon fs-14 me-1"><i class="fas fa-sign-in-alt"></i></span> <?php echo app('translator')->get('Login'); ?>
                    </a>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('user.home')); ?>" class="btn btn--base btn--md">
                        <span class="icon fs-14 me-1"><i class="fas fa-home"></i></span> <?php echo app('translator')->get('Dashboard'); ?>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('user.register')); ?>" class="btn btn--base btn--md">
                        <span class="icon fs-14 me-1"><i class="fas fa-user-plus"></i></span> <?php echo app('translator')->get('Register'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.search-form').on('submit', function(e) {
                e.preventDefault();
                var keyword = $(this).find('input[name=search]').val();
                window.location.href = "<?php echo e(route('products')); ?>?search=" + keyword;
            })
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/fadded/public_html/core/resources/views/templates/basic/partials/header_top.blade.php ENDPATH**/ ?>