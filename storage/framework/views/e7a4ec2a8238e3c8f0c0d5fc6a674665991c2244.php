<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="<?php echo e(route('home')); ?>">
                <img src="<?php echo e(getImage(getFilePath('logoIcon') . '/dark_logo.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>">
            </a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <?php if(auth()->guard()->check()): ?>

                         <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/"><?php echo app('translator')->get('Buy Account'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="<?php echo e(route('user.home')); ?>"><?php echo app('translator')->get(' Dashboard'); ?></a>
                    </li>

                   


                    
                    
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('user.change.password')); ?>"><?php echo app('translator')->get('Change Password'); ?></a>
                    </li>


                    <li class="nav-item d-block">
                        <div class="accounts-buttons d-flex align-items-center">
                            <a href="<?php echo e(route('user.logout')); ?>" class="accounts-buttons__link btn btn--base btn--sm">
                                <i class="fas fa-sign-in-alt"></i> <?php echo app('translator')->get('Logout'); ?>
                            </a>
                        </div>
                    </li>

                    <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="products"><?php echo app('translator')->get('Home'); ?></a>
                    </li>

                    <li class="nav-item d-block">
                        <div class="accounts-buttons d-flex align-items-center">
                            <a href="<?php echo e(route('user.logout')); ?>" class="accounts-buttons__link btn btn--base btn--sm">
                                <i class="fas fa-sign-in-alt"></i> <?php echo app('translator')->get('Login'); ?>
                            </a>
                        </div>
                    </li>

                    <li class="nav-item d-block">
                        <div class="accounts-buttons d-flex align-items-center">
                            <a href="<?php echo e(route('user.register')); ?>" class="accounts-buttons__link btn btn--danger btn--sm">
                                <i class="fas fa-user"></i> <?php echo app('translator')->get('Register'); ?>
                            </a>
                        </div>
                    </li>


                    <?php endif; ?>




                </ul>
            </div>
        </nav>
    </div>
</header>

<?php $__env->startPush('style'); ?>
<style>
    .nav-menu .nav-item:nth-last-child(2){
        padding-right: 30px;
    }
    @media screen and (max-width: 991px) {
        .nav-menu .nav-item:nth-last-child(2){
            padding-right: 0px;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/fadded/test.fadded-socials.com/core/resources/views/templates/basic/partials/header_auth.blade.php ENDPATH**/ ?>