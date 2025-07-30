<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'basic' => false 
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'basic' => false 
]); ?>
<?php foreach (array_filter(([
    'basic' => false 
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php if($basic): ?>
    <div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"><?php echo app('translator')->get('Confirmation Alert!'); ?></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body"> 
                        <p class="question"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--sm btn-dark" data-bs-dismiss="modal"><?php echo app('translator')->get('No'); ?></button>
                        <button type="submit" class="btn btn--sm btn--base"><?php echo app('translator')->get('Yes'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div> 
<?php else: ?> 
    <div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Confirmation Alert!'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <p class="question"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal"><?php echo app('translator')->get('No'); ?></button>
                        <button type="submit" class="btn btn--primary"><?php echo app('translator')->get('Yes'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php $__env->startPush('script'); ?>

<script>
    (function ($) {
        "use strict";
        $(document).on('click','.confirmationBtn', function () {
            var modal   = $('#confirmationModal');
            let data    = $(this).data();
            modal.find('.question').text(`${data.question}`);
            modal.find('form').attr('action', `${data.action}`);
            modal.modal('show');
        });
    })(jQuery);
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/fadded/core/resources/views/components/confirmation-modal.blade.php ENDPATH**/ ?>