<?php
    $footerContent = getContent('footer.content', true);
    $policyPages = getContent('policy_pages.element', orderById: true);
    $socials = getContent('social_icon.element', orderById: true);
    $contact = getContent('contact_us.content', true);
?>
<footer class="footer-area">
   
    <div class="bottom-footer py-3">
        <div class="container">
            <div class="row gy-3">
                <div class="col-sm-6">
                    <div class="bottom-footer__text"> &copy;  <?php echo date('Y') ?> . <?php echo app('translator')->get('Ace Logstore'); ?>.</div>
                </div>
                <div class="col-sm-6">
                    <div class="bottom-footer__menu d-flex align-items-end justify-content-end">
                        <?php $__currentLoopData = $policyPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('policy.pages', [slug(@$policy->data_values->title), @$policy->id])); ?>" class="bottom-footer__link">
                                <?php echo e(__(@$policy->data_values->title)); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH /home/fadded/public_html/core/resources/views/templates/basic/partials/footer.blade.php ENDPATH**/ ?>