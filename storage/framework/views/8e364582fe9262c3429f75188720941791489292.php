<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body">

            <ul class="list-group list-group-flush">
                <li class="list-group-item px-0 py-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="<?php echo e(url('')); ?>/assets/images/product/<?php echo e($product->image); ?>" alt="img"
                                 class="wid-35 rounded-circle">
                        </div>

                        <div class="flex-grow-1 mx-3">
                            <p class="mb-1"><?php $text = $product->name.' | '.strLimit(strip_tags($product->description), 20); ?>
                                <a class="text-small text-dark" href="<?php echo e(route('product.details', $product->id)); ?>"
                                   style="font-size: 12px;"
                                   class="text-dark font-size">
                                    <?php echo $text; ?>
                                </a>
                            </p>


                            <p class="mb-0 text-muted"><a class="text-white btn btn-dark btn-rounded btn-sm"
                                                          style="font-size: 12px; font-weight: bolder"> <?php echo e($general->cur_sym); ?><?php echo e(showAmount($product->price)); ?> </a>
                                | <a class="text-white btn btn-dark btn-rounded btn-sm"
                                     style="font-size: 12px; font-weight: bolder"> <?php echo e($product->in_stock); ?> pcs </a></p>
                        </div>

                        <div class="col-2">

                            <?php if($product->in_stock == 0): ?>
                                <div>
                                    
                                    
                                    
                                </div>
                            <?php else: ?>
                                <?php if(auth()->guard()->check()): ?>

                                    <div class="d-flex justify-content-center row">

                                        <div class="col-12">
                                            <span class="text-small col-sm-12 badge bg-dark mb-1"></span>

                                        </div>

                                        <div class="col-12 d-flex justify-content-center">
                                            <a href="/product/details/<?php echo e($product->id); ?>"
                                               style="font-size: 8px"
                                               class="btn btn-sm">
                                                <svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_206_491)">
                                                        <path d="M18.4615 4.9659H14.6154C14.6154 3.7335 14.1291 2.55156 13.2636 1.68011C12.398 0.808664 11.2241 0.319092 10 0.319092C8.77592 0.319092 7.60198 0.808664 6.73643 1.68011C5.87087 2.55156 5.38462 3.7335 5.38462 4.9659H1.53846C1.13044 4.9659 0.739123 5.12909 0.450604 5.41958C0.162087 5.71005 0 6.10403 0 6.51484V18.1319C0 18.5427 0.162087 18.9366 0.450604 19.2271C0.739123 19.5176 1.13044 19.6808 1.53846 19.6808H18.4615C18.8696 19.6808 19.2609 19.5176 19.5494 19.2271C19.8379 18.9366 20 18.5427 20 18.1319V6.51484C20 6.10403 19.8379 5.71005 19.5494 5.41958C19.2609 5.12909 18.8696 4.9659 18.4615 4.9659ZM6.92309 8.83824C6.92309 9.04365 6.84204 9.24062 6.69777 9.38588C6.55351 9.53111 6.35785 9.61271 6.15385 9.61271C5.94983 9.61271 5.75417 9.53111 5.60992 9.38588C5.46566 9.24062 5.38462 9.04365 5.38462 8.83824V7.2893C5.38462 7.0839 5.46566 6.88692 5.60992 6.74167C5.75417 6.59643 5.94983 6.51484 6.15385 6.51484C6.35785 6.51484 6.55351 6.59643 6.69777 6.74167C6.84204 6.88692 6.92309 7.0839 6.92309 7.2893V8.83824ZM10 1.86803C10.816 1.86803 11.5987 2.19441 12.1757 2.77537C12.7527 3.35635 13.0769 4.14428 13.0769 4.9659H6.92309C6.92309 4.14428 7.24726 3.35635 7.82428 2.77537C8.40132 2.19441 9.18396 1.86803 10 1.86803ZM14.6154 8.83824C14.6154 9.04365 14.5343 9.24062 14.3901 9.38588C14.2458 9.53111 14.0502 9.61271 13.8461 9.61271C13.6421 9.61271 13.4465 9.53111 13.3022 9.38588C13.158 9.24062 13.0769 9.04365 13.0769 8.83824V7.2893C13.0769 7.0839 13.158 6.88692 13.3022 6.74167C13.4465 6.59643 13.6421 6.51484 13.8461 6.51484C14.0502 6.51484 14.2458 6.59643 14.3901 6.74167C14.5343 6.88692 14.6154 7.0839 14.6154 7.2893V8.83824Z" fill="#20CCB4"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_206_491">
                                                            <rect width="20" height="20" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>

                                            </a>
                                        </div>

                                    </div>

                                <?php else: ?>

                                    <div class="d-flex justify-content-center row">


                                        <div class="col-12 d-flex justify-content-center">
                                            <a href="/user/login"
                                               style="font-size: 8px"
                                               class="btn btn-sm btn-dark"> <i class="fa fa-lock"> </i></a>
                                        </div>


                                    </div>

                                <?php endif; ?>
                            <?php endif; ?>

                        </div>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>


<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/fadded/core/resources/views/templates/basic/partials/products.blade.php ENDPATH**/ ?>