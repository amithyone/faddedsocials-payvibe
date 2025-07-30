<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <form class="notify-form" action="">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Being Sent'); ?> </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><?php echo app('translator')->get('To'); ?></span>
                                        <select class="form-control" name="being_sent_to" required>
                                            <?php $__currentLoopData = $notifyToUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $toUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"><?php echo e(__($toUser)); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="input-append"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Subject'); ?> </label>
                                    <input class="form-control" name="subject" type="text" placeholder="<?php echo app('translator')->get('Email subject'); ?>" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Message'); ?> </label>
                                    <textarea class="form-control nicEdit" name="message" rows="10"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 start-from-col">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Start Form'); ?> </label>
                                            <input class="form-control" name="start_form" type="number" placeholder="<?php echo app('translator')->get('Start form user'); ?>" required />
                                        </div>
                                    </div>
                                    <div class="col-md-4 per-batch-col">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Per Batch'); ?> </label>
                                            <div class="input-group">
                                                <input class="form-control" name="batch" type="number" placeholder="<?php echo app('translator')->get('How many user'); ?>" required />
                                                <span class="input-group-text">
                                                    <?php echo app('translator')->get('User'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 cooling-period-col">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Cooling Period'); ?> </label>
                                            <div class="input-group">
                                                <input class="form-control" name="cooling_time" type="number" placeholder="<?php echo app('translator')->get('Waiting time'); ?>" required />
                                                <span class="input-group-text">
                                                    <?php echo app('translator')->get('Seconds'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn w-100 h-45 btn--primary me-2" type="submit"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notificationSending" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Notification Sending'); ?></h5>
                </div>
                <div class="modal-body">
                    <h4 class="text--danger dontCloseWarning text-center"><?php echo app('translator')->get('Don\'t close or refresh the window till finish.'); ?></h4>

                    <div class="mail-wrapper">
                        <div class="sendingIcon mail-icon world-icon"><i class="las la-globe"></i></div>
                        <div class="coolingIcon mail-icon world-icon"><i class="fas fa-spinner fa-spin"></i></div>
                        <div class='sendingIcon mailsent'>
                            <div class='envelope'>
                                <i class='line line1'></i>
                                <i class='line line2'></i>
                                <i class='line line3'></i>
                                <i class="icon fa fa-envelope"></i>
                            </div>
                        </div>
                        <div class="sendingIcon mail-icon mail-icon"><i class="las la-envelope-open-text"></i></div>
                    </div>
                    <div class="finalStatistics d-none">
                        <div class="mail-icon text--success fw-bold text-center">
                            <i class="fas fa-check"></i> <?php echo app('translator')->get('Done'); ?>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo app('translator')->get('Start From'); ?><span class="fw-bold startFrom">0</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo app('translator')->get('Ended at'); ?><span class="fw-bold sent">0</span>
                            </li>
                        </ul>
                    </div>
                    <h4 class="text--primary remainingTime d-none text-center"></h4>

                    <div class="mt-3">
                        <p class="sentStatistics text-center mb-2"><?php echo app('translator')->get('Email sent'); ?> <span class="startFrom">0</span> <?php echo app('translator')->get('to'); ?> <span class="sent">-</span> <?php echo app('translator')->get('users'); ?>
                        </p>
                        <p class="text-center sentStatistics">
                            <button class="btn btn--danger stopSending"><i class="la la-power-off"></i><?php echo app('translator')->get('Stop'); ?></button>
                        </p>
                        <div class="modelCloseButton d-none text-end">
                            <button class="btn btn--danger" data-bs-dismiss="modal" type="button" aria-label="Close">
                                <?php echo app('translator')->get('Close'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <span class="text--primary"><?php echo app('translator')->get('Notification will send via '); ?> <?php if($general->en): ?>
            <span class="badge badge--warning"><?php echo app('translator')->get('Email'); ?></span>
            <?php endif; ?> <?php if($general->sn): ?>
                <span class="badge badge--warning"><?php echo app('translator')->get('SMS'); ?></span>
            <?php endif; ?>
    </span>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .coolingIcon {
            margin: 0 auto;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict"

            var subject = null,
                message = null,
                start = null,
                perBatch = null,
                sendingStatus = true,
                coolingTime = null,
                being_sent_to = null,
                _token = null,
                user=null,
                number_of_top_deposited_user,
                number_of_days
            $('.notify-form').on('submit', function(e) {
                subject = $(this).find('[name=subject]').val();
                being_sent_to = $(this).find('[name=being_sent_to]').val();
                message = $(this).find('.nicEdit-main').html();
                start = parseInt($(this).find('[name=start_form]').val());
                perBatch = parseInt($(this).find('[name=batch]').val());
                coolingTime = parseInt($(this).find('[name=cooling_time]').val());
                user=$(".input-append").find(`#user_list`).val();

                number_of_top_deposited_user=$(".input-append").find('input[name=number_of_top_deposited_user]').val();
                number_of_days=$(".input-append").find('input[name=number_of_days]').val();
                _token = $(this).find('[name=_token]').val();

                if (<?php echo e($users); ?> <= 0) {
                    notify('error', 'Users not found');
                    return false;
                }
                if (!coolingTime) {
                    notify('error', `<?php echo app('translator')->get('Cooling period must be greater then zero'); ?>`);
                    return false;
                }
                if (!perBatch) {
                    notify('error', `<?php echo app('translator')->get('Per batch must be greater then zero'); ?>`);
                    return false;
                }
                e.preventDefault();
                sendingStatus = true;
                $('.progress-bar').css('width', `0%`);
                $('.progress-bar').text(`0%`);
                $('.sent').text('-');
                $('.stopSending,.dontCloseWarning,.sentStatistics').removeClass('d-none');
                $('.finalStatistics,.modelCloseButton').addClass('d-none');
                $('#notificationSending').modal('show');

                $('.startFrom').text(start);
                postMail();
            });

            function postMail() {
                if (!sendingStatus) {
                    $('.remainingTime,.coolingIcon,.dontCloseWarning,.sentStatistics').addClass('d-none')
                    $('.finalStatistics,.modelCloseButton').removeClass('d-none');
                    return;
                }
                $('.remainingTime').text('Cooling...')
                $('.remainingTime,.coolingIcon').addClass('d-none')
                $('.sendingIcon').removeClass('d-none')
                $.post("<?php echo e(route('admin.users.notification.all.send')); ?>", {
                    "subject": subject,
                    "_token": _token,
                    "start": start,
                    "batch": perBatch,
                    "message": message,
                    'being_sent_to':being_sent_to,
                    'user' :user,
                    'number_of_top_deposited_user' : number_of_top_deposited_user,
                    'number_of_days' : number_of_days,
                }, function(response) {
                    $('.remainingTime').removeClass('d-none')
                    $('.sendingIcon').addClass('d-none')
                    $('.coolingIcon').removeClass('d-none')
                    if (response.error) {
                        response.error.forEach(error => {
                            notify('error', error)
                        });
                    } else {
                        start += parseInt(response.total_sent);
                        $('.sent').text(start);
                        if (!parseInt(response.total_sent)) {
                            sendingStatus = false;
                            postMail();
                            return;
                        }
                        $('.sentStatistics').removeClass('d-none');
                        setTimeout(function() {
                            clearInterval(interval)
                            postMail();
                        }, coolingTime * 1000);
                        var counter = coolingTime - 1,
                            interval = setInterval(function() {
                                $('.remainingTime').text("Reloading after " + counter + " seconds");
                                counter--;
                                if (counter <= 0) clearInterval(interval);
                            }, 1000);
                    }
                });
            }

            $('.stopSending').on('click', function() {
                sendingStatus = false;
                notify('info', `<?php echo app('translator')->get('Notification sending will stop after this batch.'); ?>`);
                $('.sentStatistics').addClass('d-none')
            });

            $('select[name=being_sent_to]').on('change',function (e) {
                let methodName = $(this).val();
                methodName=methodName.toUpperCase();
                if(methodName =='SELECTEDUSERS'){
                    $('.input-append').html(`
                    <div class="form-group" id="user_list_wrapper">
                        <label class="required"><?php echo app('translator')->get('Select User'); ?></label>
                        <select name="user[]"  class="form-control" id="user_list" required multiple>
                            <option disabled><?php echo app('translator')->get('Select One'); ?></option>
                        </select>
                    </div>
                    `);

                    fetchUserList();
                    changeEmailSendingOptionHtml(true);
                    return;
                }

                if(methodName == 'TOPDEPOSITEDUSERS'){
                    $('.input-append').html(`
                    <div class="form-group">
                        <label class="required"><?php echo app('translator')->get('Number Of Top Deposited User'); ?></label>
                        <input class="form-control" type="number" name="number_of_top_deposited_user" >
                    </div>
                    `);
                    changeEmailSendingOptionHtml(true);
                    return
                }

                if(methodName == 'NOTLOGINUSERS'){
                    $('.input-append').html(`
                    <div class="form-group">
                        <label class="required"><?php echo app('translator')->get('Number Of Days'); ?></label>
                        <div class="input-group">
                            <input class="form-control" type="number" name="number_of_days" >
                            <span class="input-group-text"><?php echo app('translator')->get('Days'); ?></span>
                        </div>
                    </div>
                    `);
                    changeEmailSendingOptionHtml(true);
                    return
                }

                $('.input-append').empty();
                changeEmailSendingOptionHtml();

             });

            function fetchUserList() {
                $('.row #user_list').select2({
                ajax: {
                    url: "<?php echo e(route('admin.users.list')); ?>",
                    type: "get",
                    dataType: 'json',
                    delay: 1000,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page,
                        };
                    },
                    processResults: function (response, params) {
                        params.page = params.page || 1;
                        let data = response.users.data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.email,
                                    id: item.id
                                }
                            }),
                            pagination: {
                                more: response.more
                            }
                        };
                    },
                    cache: false,
                },
                dropdownParent: $('.input-append #user_list_wrapper')
                });
            }

            function changeEmailSendingOptionHtml(change = true) {
                if (change) {
                    $('.start-from-col').addClass('d-none');
                    $('.cooling-period-col').addClass('col-lg-6').removeClass('.col-md-4');
                    $('.per-batch-col').addClass('col-lg-6').removeClass('.col-md-4');
                    $('input[name=start_form]').attr('required',false).val(0);
                    return;
                }
                $('.start-from-col').removeClass('d-none');
                $('.cooling-period-col').removeClass('col-lg-6').addClass('col-md-4');
                $('.per-batch-col').removeClass('col-lg-6').addClass('col-md-4');
                $('input[name=start_form]').attr('required',true);
            }

        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        #user_list_wrapper{
            position: relative
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/public_html/core/resources/views/admin/users/notification_all.blade.php ENDPATH**/ ?>