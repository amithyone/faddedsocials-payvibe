<?php $__env->startSection('panel'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 bg--transparent shadow-none">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table--light style--two table bg-white">
                        <thead>
                        <tr>
                            <th><?php echo app('translator')->get('Name | Category'); ?></th>
                            <th><?php echo app('translator')->get('Price'); ?></th>
                            <th><?php echo app('translator')->get('In Stock'); ?></th>
                            <th><?php echo app('translator')->get('Status'); ?></th>
                            <th><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold"><?php echo e(strLimit($product->name, 50)); ?></span>
                                        <span class="small d-block"><?php echo e(__(@$product->category->name)); ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?php echo e(showAmount($product->price)); ?> <?php echo e(__($general->cur_text)); ?></span>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin.product.accounts', $product->id)); ?>" class="bg--primary px-2 rounded text--white" target="_blank">
                                            <?php echo e(showAmount(@$product->in_stock, 0)); ?>

                                        </a>
                                    </td>
                                    <td>
                                       <?php echo $product->statusBadge; ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline--primary dropdown-toggle id="actionButton" data-bs-toggle="dropdown">
                                                <i class="las la-ellipsis-v"></i><?php echo app('translator')->get('Action'); ?>
                                            </button>
                                            <div class="dropdown-menu p-0">
                                                <a href="<?php echo e(route('admin.product.form', $product->id)); ?>" class="dropdown-item">
                                                    <i class="la la-pencil"></i> <?php echo app('translator')->get('Edit'); ?>
                                                </a>
                                                <?php if($product->status == Status::ENABLE): ?>
                                                    <a href="javascript:void(0)" class="dropdown-item confirmationBtn"
                                                        data-action="<?php echo e(route('admin.product.status', $product->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Are you sure to disable this item?'); ?>">
                                                        <i class="la la-eye-slash"></i> <?php echo app('translator')->get('Disable'); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0)"
                                                        class="dropdown-item confirmationBtn"
                                                        data-action="<?php echo e(route('admin.product.status', $product->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Are you sure to enable this item?'); ?>">
                                                        <i class="la la-eye"></i> <?php echo app('translator')->get('Enable'); ?>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('admin.product.accounts', $product->id)); ?>" class="dropdown-item">
                                                    <i class="la la-clipboard-list"></i> <?php echo app('translator')->get('Accounts'); ?>
                                                </a>

                                                <a href="<?php echo e(route('admin.product.delete', $product->id)); ?>" class="dropdown-item">
                                                    <i class="la la-clipboard-basket"></i> <?php echo app('translator')->get('Delete'); ?>
                                                </a>


                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td class="text-muted text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            <?php if($products->hasPages()): ?>
                <div class="card-footer py-4">
                    <?php echo e(paginateLinks($products)); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (isset($component)) { $__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b = $component; } ?>
<?php $component = App\View\Components\ConfirmationModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\ConfirmationModal::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b)): ?>
<?php $component = $__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b; ?>
<?php unset($__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<style>
    .table-responsive {
        background: transparent;
        min-height: 300px;
    }
    .dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <a href="<?php echo e(route('admin.product.form')); ?>" class="btn btn-sm btn-outline--primary">
        <i class="las la-plus"></i><?php echo app('translator')->get('Add New'); ?>
    </a>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wolrdhome/public_html/core/resources/views/admin/product/all.blade.php ENDPATH**/ ?>