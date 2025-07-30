<?php $__env->startSection('content'); ?>

    <div class="pc-container">
        <div class="pc-content">

            <div class="container">
                <div class="row gy-4 mt-3">
                    <div class="col-xl-12 col-sm-12">

                        <div class="row my-3">
                            <div class="col d-flex justify-content-end">
                                <a href="/user/copy/<?php echo e($get_id); ?>" class="btn btn-main btn-sm pill p-3"><i
                                        class="fa fa-copy"></i>Download</a>
                            </div>
                        </div>


                        <div class="col-xl-12 col-sm-12 p-2">
                            <div class="dashboard-widget">
                                <h5 class="mt-4 mb-4"><?php echo app('translator')->get('Latest Order History'); ?></h5>

                                <div class="dashboard-body__item">
                                    <div class="table-responsive">
                                        <table class="table style-two">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Copy</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>

                                                    <td>


                                                        <input type="text"
                                                               id="copy<?php echo nl2br(@$item->productDetail->id) ?>"
                                                               class="form-control text-small"
                                                               style="font-size: smaller; border: none"
                                                               value="<?php echo nl2br(@$item->productDetail->details) ?>">
                                                        <span
                                                            id="message<?php echo nl2br(@$item->productDetail->id) ?>"></span>


                                                        <script>

                                                            <?php $id = $item->productDetail->id ?>
                                                            var id = <?php echo e($id); ?>;

                                                            function copyToClipboard<?php echo e($id); ?>() {
                                                                var textToCopy = document.getElementById("copy" + <?php echo e($id); ?>);
                                                                var copyMessage = document.getElementById("message" + <?php echo e($id); ?>);

                                                                // Select the text
                                                                textToCopy.select();
                                                                textToCopy.setSelectionRange(0, 99999); // For mobile devices

                                                                // Copy the selected text
                                                                document.execCommand("copy");

                                                                // Deselect the text
                                                                textToCopy.blur();

                                                                // Display copy message
                                                                copyMessage.innerText = "Text copied!";

                                                                // Clear message after 2 seconds
                                                                setTimeout(function () {
                                                                    copyMessage.innerText = "";
                                                                }, 2000);

                                                            }
                                                        </script>


                                                    </td>

                                                    <td>

                                                        <i onclick="copyToClipboard<?php echo e($id); ?>()" class="fa fa-copy"> </i>


                                                    </td>


                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <div class="card border-0 p-3">
                                                    <div class="card-body text-center p-4">

                                                        <svg width="40" height="40" viewBox="0 0 25 25" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M0.699126 22.1299L11.4851 0.936473C11.6065 0.697285 11.7856 0.49768 12.0036 0.358621C12.2215 0.219562 12.4703 0.146179 12.7237 0.146179C12.9772 0.146179 13.2259 0.219562 13.4439 0.358621C13.6618 0.49768 13.841 0.697285 13.9624 0.936473L24.7483 22.1299C24.8658 22.3607 24.9253 22.6205 24.9209 22.8835C24.9165 23.1466 24.8484 23.4039 24.7234 23.6301C24.5983 23.8562 24.4206 24.0434 24.2078 24.1732C23.995 24.303 23.7543 24.3708 23.5097 24.3701H1.93781C1.69314 24.3708 1.45252 24.303 1.23968 24.1732C1.02684 24.0434 0.849131 23.8562 0.724084 23.6301C0.599037 23.4039 0.530969 23.1466 0.526592 22.8835C0.522216 22.6205 0.581682 22.3607 0.699126 22.1299ZM14.2252 14.2749L14.9815 9.39487C15.0039 9.25037 14.9967 9.10237 14.9605 8.96116C14.9243 8.81995 14.8599 8.6889 14.7719 8.57713C14.6838 8.46536 14.5742 8.37554 14.4506 8.31391C14.327 8.25228 14.1925 8.22033 14.0563 8.22026H11.3912C11.255 8.22033 11.1204 8.25228 10.9969 8.31391C10.8733 8.37554 10.7637 8.46536 10.6756 8.57713C10.5876 8.6889 10.5232 8.81995 10.487 8.96116C10.4508 9.10237 10.4436 9.25037 10.466 9.39487L11.2223 14.2749H14.2252ZM14.7882 18.1096C14.7882 17.5208 14.5707 16.9561 14.1835 16.5398C13.7964 16.1234 13.2713 15.8895 12.7237 15.8895C12.1762 15.8895 11.6511 16.1234 11.2639 16.5398C10.8768 16.9561 10.6593 17.5208 10.6593 18.1096C10.6593 18.6984 10.8768 19.2631 11.2639 19.6794C11.6511 20.0957 12.1762 20.3296 12.7237 20.3296C13.2713 20.3296 13.7964 20.0957 14.1835 19.6794C14.5707 19.2631 14.7882 18.6984 14.7882 18.1096Z"
                                                                fill="#EA4335"/>
                                                        </svg>
                                                        <br><br>

                                                        <h6>No data found</h6>

                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col d-flex justify-content-center my-3">
                            <?php echo e(paginateLinks($orderItems)); ?>

                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make($activeTemplate.'layouts.main2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/public_html/core/resources/views/templates/basic/user/order_details.blade.php ENDPATH**/ ?>