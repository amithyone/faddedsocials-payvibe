<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e($formAction); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e(@$product->id); ?>">
                        <div class="row">
                            <div class="row">
                                <div class="col-xl-4 col-lg-12">
                                    <div class="form-group">
                                        <div class="image-upload">
                                            <div class="thumb">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview mt-3 <?php echo e(@$product->image ? 'has-image' : null); ?>"
                                                        style="background-image: url(<?php echo e(getImage(getFilePath('product') . '/' . @$product->image, getFileSize('product'))); ?>)">
                                                        <button type="button" class="remove-image"><i
                                                                class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                                <div class="avatar-edit">
                                                    <input type="file" class="profilePicUpload p-0" name="image"
                                                        id="profilePicUpload1" accept=".png, .jpg, .jpeg" <?php if(!@$product): ?> required <?php endif; ?>>
                                                    <label for="profilePicUpload1"
                                                        class="bg--success remove-star"><?php echo app('translator')->get('Upload Image'); ?></label>
                                                    <small class="mt-2  "><?php echo app('translator')->get('Supported files'); ?>: <b><?php echo app('translator')->get('jpeg'); ?>,
                                                            <?php echo app('translator')->get('jpg'); ?>, <?php echo app('translator')->get('png'); ?>.</b> <?php echo app('translator')->get('Image will be resized into'); ?>
                                                        <?php echo e(getFileSize('product')); ?> </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-8 col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Category'); ?></label>
                                                <select name="category_id" class="form-control" required>
                                                    <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($category->id); ?>"
                                                            <?php echo e(old('category_id', @$product->category_id) == $category->id ? 'selected' : ''); ?>>
                                                            <?php echo e(__($category->name)); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Name'); ?></label>
                                                <input type="text" name="name" class="form-control"
                                                    value="<?php echo e(old('name', @$product->name)); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Price'); ?></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><?php echo e($general->cur_sym); ?></span>
                                                    <input type="number" step="any" name="price" class="form-control"
                                                        value="<?php echo e(getAmount(old('price', @$product->price))); ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <div class="justify-content-between d-flex flex-wrap">
                                                    <span>
                                                        <label><?php echo app('translator')->get('Accounts'); ?></label>
                                                        <i class="las la-info-circle text--primary" title=""
                                                            data-bs-original-title="<?php echo app('translator')->get('The document must be in .txt format, with each account separated by a new line. You can download a demo template to understand the formatting'); ?>"
                                                            aria-label="<?php echo app('translator')->get('The document must be in .txt format, with each account separated by a new line. You can download a demo .txt to understand the formatting'); ?>">
                                                        </i>
                                                    </span>
                                                    <a href="<?php echo e(route('admin.product.download.demo.txt')); ?>" class="">
                                                        <i class="las la-download"></i><?php echo app('translator')->get('Demo Format'); ?>
                                                    </a>
                                                </div>
                                                <input type="file" name="file" class="form-control"
                                                    accept="text/plain" <?php if(!@$product): ?> required <?php endif; ?>>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Description'); ?> </label>
                                                <i class="las la-info-circle text--primary" title=""
                                                    data-bs-original-title="<?php echo app('translator')->get('This text is what users read when making a purchase, in order to understand the product'); ?>"
                                                    aria-label="<?php echo app('translator')->get('This text is what users read when making a purchase, in order to understand the product'); ?>">
                                                </i>
                                                <textarea name="description" class="form-control nicEdit" rows="12"><?php echo e(old('description', @$product->description)); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get('Submit'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<style>
    .avatar-preview {
        position: relative;
    }
    .avatar-preview .profilePicPreview:after {
        height: 100%;
        width: 100%;
        display: flex;
        background: #AFAFAF !important;
        font-size: 70px;
        content: "<?php echo e(getFileSize('product')); ?>";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        justify-content: center;
        align-items: center;
        color: #656565;
    }
    .avatar-preview .has-image:after {
        display: none;
    }
    .remove-star:after{
        display: none;
    }
</style>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wolrdhome/public_html/core/resources/views/admin/product/form.blade.php ENDPATH**/ ?>