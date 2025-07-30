<?php $__env->startSection('content'); ?>

    <div class="pc-container">
        <div class="pc-content">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger my-4">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
            <?php if(session()->has('error')): ?>
                <div class="alert alert-danger mt-2">
                    <?php echo e(session()->get('error')); ?>

                </div>
            <?php endif; ?>


            <div class="dashboard-body__content">
                <div class="dashboard-body__item-wrapper">


                    <div>


                        <div class="carousel my-3 mb-4">
                            <div class="carousel-images">
                                <a href="/products"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner1.jpg"
                                        alt="Banner 1"></a>
                                <a href="https://t.me/faddedsocialsgroup"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner2.jpg"
                                        alt="Banner 2"></a>
                                <a href="https://linktr.ee/faddedsocials"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner3.jpg"
                                        alt="Banner 3"></a>
                                <a href="https://chat.whatsapp.com/CuSck16moiWFH8njS7pE7C"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner4.jpg"
                                        alt="Banner 4"></a>
                                <a href="/products"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner5.jpg"
                                        alt="Banner 5"></a>
                            </div>
                            <div class="carousel-buttons">
                                <button id="prevBtn">&#10094;</button>
                                <button id="nextBtn">&#10095;</button>
                            </div>
                        </div>

                        <script>
                            const carouselImages = document.querySelector('.carousel-images');
                            const images = document.querySelectorAll('.carousel-images a'); // Updated selector to include anchor tags
                            const totalImages = images.length;
                            let currentIndex = 0;

                            function showNextImage() {
                                currentIndex = (currentIndex + 1) % totalImages;
                                updateCarousel();
                            }

                            function showPreviousImage() {
                                currentIndex = (currentIndex - 1 + totalImages) % totalImages;
                                updateCarousel();
                            }

                            function updateCarousel() {
                                const translateX = -currentIndex * 100;
                                carouselImages.style.transform = `translateX(${translateX}%)`;
                            }

                            document.getElementById('nextBtn').addEventListener('click', showNextImage);
                            document.getElementById('prevBtn').addEventListener('click', showPreviousImage);

                            setInterval(showNextImage, 3000);
                        </script>


                    </div>

                    <div class="">


                        <div class="col d-flex justify-content-start mt-5">
                            <select id="urlSelect" onchange="redirectToUrl()" class="btn btn-sm btn-dark">
                                <option value="">Shop by Categories</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option
                                        value="<?php echo e(url('')); ?>/category-products/<?php echo e($data->name); ?>/<?php echo e($data->id); ?>"><?php echo e($data->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <script>
                                function redirectToUrl() {
                                    var selectElement = document.getElementById("urlSelect");
                                    var selectedUrl = selectElement.options[selectElement.selectedIndex].value;
                                    if (selectedUrl !== "") {
                                        window.location.href = selectedUrl;
                                    }
                                }
                            </script>

                        </div>

                        <div>
                            <h5 class="d-flex justify-content-start mt-5">Our Recent Products 👌</h5>
                        </div>
                        <div class="col-12">
                            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $products = $category->products;
                                ?>
                                <div class="catalog-item-wrapper mb-2">
                                    <div class="d-grid gap-2 mb-2">
                                        <strong>
                                            <p style="font-size: 11px; background: linear-gradient(90deg, #20ccb4 0%, #3f8a7f 100%); border-radius:10px; color: white"
                                               class="p-2"><?php echo e(__($category->name)); ?></p>
                                        </strong>
                                    </div>

                                </div>




                                <div class="col-12">
                                    <?php $__currentLoopData = $products->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo $__env->make($activeTemplate . 'partials/products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>


                                <div class="col-12 d-flex justify-content-end mb-4">
                                    <a style="background: linear-gradient(90deg, #000000 0%, #000000 100%); border-radius:10px; color: white"
                                       href="<?php echo e(route('category.products', ['search' => request()->search, 'slug' => slug($category->name), 'id' => $category->id])); ?>"
                                       class="btn btn-main btn-lg w-100 pill">
                                        <?php echo app('translator')->get('View All'); ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-arrow-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                  d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                        </svg>
                                    </a>
                                </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="empty-data text-center">
                                    <div class="thumb">
                                        <img src="<?php echo e(asset($activeTemplateTrue . 'images/not-found.png')); ?>">
                                    </div>

                                    <h4 class="title"><?php echo app('translator')->get('No result found for "' . request()->search . '"'); ?></h4>
                                </div>
                            <?php endif; ?>
                            <?php echo e(paginateLinks($categories)); ?>



                                <div class="col-12 my-4">
                                    <?php if(auth()->guard()->check()): ?>

                                        <div class="card-title mt-3 text-center">
                                            <h6 style="background: #565656; padding: 10px; border-radius: 10px; color: white"
                                                class="text-left">RECENT ORDER</h6>
                                        </div>


                                        <div style="height:400px; width:100%; overflow-y: scroll;" class="card">
                                            <div class="card-body">


                                                <div class="dashboard-body__item">
                                                    <div class="table-responsive">
                                                        <table class="table style-two">
                                                            <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Time</th>

                                                            </tr>

                                                            <?php if($bought_qty == 0): ?>
                                                            <?php else: ?>
                                                                <?php $__currentLoopData = $bought; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                                    <tr>
                                                                        <td><?php echo e(\Illuminate\Support\Str::limit($data->user_name,4, '.')); ?>, <span style="color: #038f8b">just bought</span><br/> <?php echo e(\Illuminate\Support\Str::limit($data->item,
                                    16, '...')); ?><span style="color: #000000">₦<?php echo e(number_format($data->amount)); ?></span></td>
                                                                        <td><?php echo e(diffForHumans($data->created_at)); ?></td>
                                                                    </tr>

                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>



                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>





                                            </div>
                                        </div>
                                    <?php else: ?>

                                    <?php endif; ?>

                                </div>

                        </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make($activeTemplate . 'layouts.main2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/public_html/core/resources/views/templates/basic/products.blade.php ENDPATH**/ ?>