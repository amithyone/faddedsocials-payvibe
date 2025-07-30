<?php $__env->startSection('content'); ?>
    <div class="pc-container">
        <div class="pc-content">

            <div class="col-xl-12 col-sm-12">


                <div class="row p-4">
                    <div class="col-4">
                        <div class="card border-0  p-4">
                            <p class="mb-0"><?php echo app('translator')->get('My Wallet'); ?></p>
                            <h4 class="my-1"><?php echo e($general->cur_sym); ?><?php echo e(number_format(Auth::user()->balance, 2)); ?>

                            </h4>
                            <a href="<?php echo e(route('user.deposit.new')); ?>" class="btn btn-success w-100"
                                   id="btn-confirm"><?php echo app('translator')->get('Fund Wallet'); ?></a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="card border-0  p-4">
                            <p class="mb-0"><?php echo app('translator')->get('Total Orders'); ?></p>
                            <h4 class="my-1"><?php echo e(getAmount(@$widget['total_orders'])); ?></h4>
                            <a href="/" class="btn btn-primary w-100"
                               id="btn-confirm"><?php echo app('translator')->get('Buy Log'); ?></a>
                        </div>
                    </div>


                    <div class="col-4">
                        <div class="card border-0  p-4">
                            <p class="mb-0"><?php echo app('translator')->get('Total Deposits'); ?></p>
                            <h4 class="my-1"><?php echo e($general->cur_sym); ?><?php echo e(showAmount(@$widget['total_payments'])); ?></h4>
                        </div>
                    </div>



                    </div>
                </div>




                    <div class="row g-4">
                        <div class="col-lg-12">
                            <h5 class="mt-4 mb-4"><?php echo app('translator')->get('Latest Payments History'); ?></h5>
                            <div class="table-responsive">
                                <table class="table">

                                    <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('Trx'); ?></th>
                                        <th><?php echo app('translator')->get('Time'); ?></th>
                                        <th><?php echo app('translator')->get('Amount'); ?></th>
                                        <th><?php echo app('translator')->get('Status'); ?></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $latestDeposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <span class="info"> <?php echo e($deposit->trx); ?> </span>
                                            </td>

                                            <td>
                                                <span class=""><?php echo e(diffForHumans($deposit->created_at)); ?></span>
                                            </td>
                                            <td>

                                                <?php echo e(__($general->cur_sym)); ?><?php echo e(showAmount($deposit->amount)); ?>

                                            </td>

                                            <td>
                                                <?php if($deposit->status == 1): ?>
                                                    <a href="#" class="btn btn-success btn-sm">Completed</a>
                                                <?php elseif($deposit->status == 2): ?>
                                                    <a href="#"
                                                       class="btn btn-warning btn-sm">Pending</a>
                                                <?php elseif($deposit->status == 3): ?>
                                                    <a href="#" class="btn btn-danger btn-sm">Rejected</a>
                                                <?php else: ?>
                                                    <a href="#"
                                                       class="btn btn-warning btn-sm">Pending</a>
                                                <?php endif; ?>
                                            </td>
                                            <?php
                                                $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                                            ?>



                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="100%" class="text-center"><?php echo e(__($emptyMessage)); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal -->
            </div>
        </div>
    </div>


            
            <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title"><?php echo app('translator')->get('Details'); ?></h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group list-group-flush userData">
                            </ul>
                            <div class="feedback"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark btn--sm"
                                    data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php $__env->stopSection(); ?>

            <?php $__env->startPush('script'); ?>
                <script>
                    (function ($) {

                        "use strict";
                        $('.detailBtn').on('click', function () {
                            var modal = $('#detailModal');

                            var userData = $(this).data('info');
                            var html = '';
                            if (userData) {
                                userData.forEach(element => {
                                    if (element.type != 'file') {
                                        html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                                    }
                                });
                            }

                            if ($(this).data('admin_feedback') != undefined) {
                                var adminFeedback = `
                        <div class="my-3 ms-2">
                            <strong><?php echo app('translator')->get('Admin Feedback'); ?></strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                            } else {
                                var adminFeedback = '';
                            }

                            if (!html && !adminFeedback) {
                                html = `<span class='d-block text-center mt-2 mb-2'><?php echo e(__($emptyMessage)); ?></span>`;
                            }

                            modal.find('.userData').html(html);
                            modal.find('.feedback').html(adminFeedback);
                            modal.modal('show');
                        });

                        $('.search-form').on('submit', function (e) {
                            e.preventDefault();
                            var keyword = $(this).find('input[name=search]').val();
                            window.location.href = "<?php echo e(route('products')); ?>?search=" + keyword;
                        })

                        // Responsive Sidebar
                        $(".show-sidebar-bar").on("click", function () {
                            $(".collapable-sidebar").addClass('show');
                            $(".sidebar-overlay").addClass('show');
                        });
                        $(".collapable-sidebar__close, .sidebar-overlay").on("click", function () {
                            $(".collapable-sidebar").removeClass('show');
                            $(".sidebar-overlay").removeClass('show');
                        });

                    })(jQuery);
                </script>
            <?php $__env->stopPush(); ?>

            <?php $__env->startPush('style'); ?>
                <style>
                    .custom--card-two {
                        position: relative;
                        display: flex;
                        flex-direction: column;
                        min-width: 0;
                        word-wrap: break-word;
                        background-clip: border-box;
                        border: 0px solid rgba(0, 0, 0, 0);
                        border-radius: .25rem;
                        margin-bottom: 1.5rem;
                    }

                    .widgets-icons-2 {
                        width: 55px;
                        height: 55px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 27px;
                        border-radius: 10px;
                        background: hsl(var(--white));
                        border-radius: 50%;
                    }

                    .has-anchor {
                        position: absolute;
                        inset: 0;
                        z-index: 1;
                    }

                    .domain-search-icon {
                        position: absolute;
                        left: 0;
                        top: 0;
                        height: 100%;
                        aspect-ratio: 1;
                        padding: 5px;
                        display: grid;
                        place-items: center;
                        color: #888;
                    }

                    .domain-search-icon ~ .form--control {
                        padding-left: 45px;
                    }

                    .domain-search-icon-reset {
                        position: absolute;
                        right: 0px;
                        transform: translateY(-50%);
                        top: 50%;
                        color: #888;
                        visibility: visible;
                        opacity: 1;
                        cursor: pointer;
                        margin-right: 4px;
                        height: auto;
                    }

                    @media (max-width: 991px) {
                        .collapable-sidebar {
                            position: fixed;
                            left: 0;
                            min-width: 320px;
                            top: 0;
                            background-color: hsl(var(--white));
                            z-index: 9999;
                            transform: translateX(-120%);
                            transition: .3s linear;
                            margin-right: 40px;
                        }

                        .collapable-sidebar__inner {
                            height: 100vh;
                            overflow-y: auto;
                        }

                        .collapable-sidebar.show {
                            transform: translateX(0);
                        }

                        .collapable-sidebar__close {
                            background-color: hsl(var(--danger));
                            border: 0;
                            font-size: 20px;
                            line-height: 1;
                            color: #fff;
                            position: absolute;
                            right: -40px;
                            top: 0;
                            width: 40px;
                            height: 40px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }

                        .show-sidebar-bar {
                            font-size: 30px;
                            color: hsl(var(--base));
                            line-height: 1;
                            display: inline-block;
                            transform: translateY(-15px);
                        }
                    }

                    @media (max-width: 424px) {
                        .collapable-sidebar {
                            min-width: 280px;
                        }
                    }

                    .collapable-sidebar .btn-link {
                        font-size: 0.875rem;
                        font-weight: 500;
                        color: hsl(var(--heading-color));
                        text-decoration: none;
                        border: 1px solid hsl(var(--black)/0.2);
                        text-align: center;
                        padding: 8px 10px;
                        border-radius: .25rem;
                    }

                    .collapable-sidebar .btn-link:hover {
                        background: hsl(var(--base));
                        border-color: hsl(var(--base));
                        color: hsl(var(--white));
                    }

                    .collapable-sidebar .card-footer {
                        background: transparent;
                    }

                    .collapable-sidebar .list-group-item:hover {
                        color: hsl(var(--base));
                    }

                    .user-dashboard div[class*=col-xl-4]:nth-child(1) .custom--card-two {
                        background-color: hsl(var(--info)/0.1) !important;
                        border-left: 5px solid hsl(var(--info));
                    }

                    .user-dashboard div[class*=col-xl-4]:nth-child(1) .custom--card-two .widgets-icons-2 {
                        color: hsl(var(--info));
                    }

                    .user-dashboard div[class*=col-xl-4]:nth-child(2) .custom--card-two {
                        background-color: hsl(var(--base)/0.1) !important;
                        border-left: 5px solid hsl(var(--base));
                    }

                    .user-dashboard div[class*=col-xl-4]:nth-child(2) .custom--card-two .widgets-icons-2 {
                        color: hsl(var(--base));
                    }

                    .user-dashboard div[class*=col-xl-4]:nth-child(3) .custom--card-two {
                        background-color: hsl(var(--purple)/0.1) !important;
                        border-left: 5px solid hsl(var(--purple));
                    }

                    .user-dashboard div[class*=col-xl-4]:nth-child(3) .custom--card-two .widgets-icons-2 {
                        color: hsl(var(--purple));
                    }
                </style>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.main2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/fadded/core/resources/views/templates/basic/user/dashboard.blade.php ENDPATH**/ ?>