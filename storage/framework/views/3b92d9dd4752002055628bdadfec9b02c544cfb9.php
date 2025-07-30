<!doctype html>
<html lang="<?php echo e(config('app.locale')); ?>" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> <?php echo e($general->siteName(__($pageTitle))); ?></title>

    <?php echo $__env->make('partials.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link href="<?php echo e(asset('assets/global/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/global/css/all.min.css')); ?>" rel="stylesheet"> 

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="<?php echo e(asset('assets/global/css/line-awesome.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/owl.theme.default.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/owl.carousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/odometer.css')); ?>">
 
    <?php echo $__env->yieldPushContent('style-lib'); ?>

    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/custom.css')); ?>">

    <?php echo $__env->yieldPushContent('style'); ?>

    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/color.php')); ?>?color=<?php echo e($general->base_color); ?>">
</head>

<body>
    <?php echo $__env->yieldPushContent('fbComment'); ?>

    <div class="preloader">
        <div class="loader-p"></div>
    </div>

    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>
    <a class="scroll-top"><i class="las la-long-arrow-alt-up"></i></a>

    <?php echo $__env->yieldContent('app'); ?>

    <?php
        @$cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    ?>

    <?php if(@$cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie')): ?>
        <div class="cookies-card text-center hide">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="mt-4 cookies-card__content">
                <?php echo e($cookie->data_values->short_desc); ?> <a href="<?php echo e(route('cookie.policy')); ?>" target="_blank"><?php echo app('translator')->get('learn more'); ?></a>
            </p>
            <div class="cookies-card__btn mt-4">
                <a href="javascript:void(0)" class="btn btn--base w-100 policy"><?php echo app('translator')->get('Allow'); ?></a>
            </div>
        </div>
    <?php endif; ?>

    <script src="<?php echo e(asset('assets/global/js/jquery-3.6.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/bootstrap.bundle.min.js')); ?>"></script>

    <script src="<?php echo e(asset($activeTemplateTrue . 'js/owl.carousel.min.js')); ?>"></script>
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/owl.carousel.filter.js')); ?>"></script>
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/odometer.min.js')); ?>"></script>
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/viewport.jquery.js')); ?>"></script>
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/main.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('script-lib'); ?>
    
    <?php echo $__env->yieldPushContent('script'); ?>

    <?php echo $__env->make('partials.plugins', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->make('partials.notify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "<?php echo e(route('home')); ?>/change/" + $(this).val();
            });

            $('.policy').on('click', function() {
                $.get('<?php echo e(route('cookie.accept')); ?>', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);

                if(element.hasClass('exclude')){
                    return false;
                }

                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

        })(jQuery);
    </script>
</body>

</html>
<?php /**PATH /home/fadded/test.fadded-socials.com/core/resources/views/templates/basic/layouts/app.blade.php ENDPATH**/ ?>