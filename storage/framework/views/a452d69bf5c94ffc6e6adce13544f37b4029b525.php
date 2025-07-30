<?php $__env->startSection('content'); ?>
    <div class="pc-container">
        <div class="pc-content">


            <div class="col-xl-12 col-sm-12">


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


                <div class="d-flex justify-content-center">

                    <p>Earn money when people you refer Buy our Products! T&Cs
                        apply</p>

                </div>

                <div class="col-xl-12 col-sm-12">

                    <div class="row p-4">
                        <div class="col-12">
                            <div class="card border-0  p-4">

                                <h6 class="text-center">Referral Link</h6>
                                <div class="row">

                                    <div class="col-10">
                                        <input type="text" id="copy"
                                               class="d-flex justify-content-start text-bold w-100"
                                               style="font-size: 12px; width: 230px; border: none"
                                               value="<?php echo e(Auth::user()->refer); ?>">
                                        <span id="message"></span>
                                    </div>

                                    <div class="col-2">
                                        <i onclick="copyToClipboard()"
                                           class="d-flex justify-content-end fa fa-copy"> </i>

                                    </div>

                                </div>

                                <script>

                                    function copyToClipboard() {
                                        var textToCopy = document.getElementById("copy");
                                        var copyMessage = document.getElementById("message");

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


                            </div>
                        </div>
                    </div>
                </div>

                <div class="row p-4">
                    <div class="col-6">
                        <div class="card border-0  p-4">
                            <h6 class="text-center">Reward</h6>
                            <strong class="text-center">NGN <?php echo e(number_format($earned)); ?></strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0  p-4">
                            <h6 class="text-center">Withdrawal</h6>
                            <strong class="text-center">NGN <?php echo e(number_format($withdrawal)); ?></strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0  p-4">
                            <h6 class="text-center">Total Click</h6>
                            <strong class="text-center"><?php echo e(number_format(Auth::user()->click_count)); ?></strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0  p-4">
                            <h6 class="text-center">Total Sign Up</h6>
                            <strong class="text-center"><?php echo e(number_format(Auth::user()->sign_up_count)); ?></strong>
                        </div>
                    </div>
                </div>


                <div class="col-xl-12 col-sm-12">

                    <div class="row p-4">
                        <div class="col-12">
                            <div class="card border-0  p-4">

                                <h6 class="text-center">Referral Wallet</h6>
                                <div class="row">

                                    <div class="col-12 text-center my-3">
                                        <h4>NGN <?php echo e(number_format(Auth::user()->ref_wallet, 2)); ?></h4>

                                    </div>


                                    <div class="col-12 my-3 d-flex justify-content-center">
                                        <a href="#" data-toggle="modal"
                                           style="background: linear-gradient(279deg, #020202 -6.58%, #000000 121.69%); color: white"
                                           data-target="#exampleModal" class="btn btn-dark btn-lg w-100">Cash out
                                            reward</a>
                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal -->
                <div class="modal fade p-5 my-3" id="exampleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-3">


                                <div class="card-body p-4">

                                    <form action="/user/send-funds" method="POST">
                                        <?php echo csrf_field(); ?>

                                        <h6 class="d-flex justify-content-center">Enter Amount</h6>
                                        <input type="number" maxlength="" required name="amount"
                                               class="form-control"
                                               style="background: rgb(220,220,220);border-radius: 10px;">

                                        <button
                                            style="background: linear-gradient(279deg, #01227a -6.58%, #3886fd 121.69%);"
                                            class="btn text-white w-100 my-3 mt-2" type="submit">
                                            Cash Out
                                        </button>

                                        <a href="/user/refer"
                                           style="background: linear-gradient(279deg, #020202 -6.58%, #000000 121.69%); color: white"
                                           class="btn btn-black w-100 my-3">
                                            Back
                                        </a>


                                    </form>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-xl-12 col-sm-12 p-2">
                    <div class="card border-0  p-4">
                        <div class="dashboard-widget">
                            <h5 class="mt-4 mb-4"><?php echo app('translator')->get('Referral History'); ?></h5>

                            <div class="dashboard-body__item">
                                <div class="table-responsive">
                                    <table class="table style-two">
                                        <thead class="text-dark">
                                        <tr>
                                            <th class="text-dark">Commission Earned</th>
                                            <th class="text-dark"> Status</th>

                                        </tr>
                                        </thead>
                                        <?php $__empty_1 = true; $__currentLoopData = $referal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>


                                            <tr class="text-small">

                                                <td class="text-dark">
                                                    <?php echo e(number_format($data->amount, 2)); ?>

                                                </td>

                                                <td>
                                                    <?php if($data->status == 1): ?>
                                                        <span class="btn btn-success">Commission Earned</span>
                                                    <?php elseif($data->status == 4): ?>
                                                        <span
                                                            class="btn btn-warning">Commission Withdrawal</span>
                                                    <?php elseif($data->status == 5): ?>
                                                        <span
                                                            class="btn btn-warning">Waiting for approval</span>
                                                    <?php elseif($data->status == 6): ?>
                                                        <span
                                                            class="btn btn-success">Approved</span>
                                                    <?php elseif($data->status == 7): ?>
                                                        <span
                                                            class="btn btn-danger">Declined</span>
                                                    <?php endif; ?>
                                                </td>



                                            </tr>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="card border-0">
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

                                            </thead>

                                            <tbody>
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


            <div class="d-flex justify-content-center">

                <nav aria-label="Page navigation example">
                    <ul class="pagination common-pagination mt-0">
                        <li class="page-item"> <?php echo e(paginateLinks($referal)); ?></li>
                        <li class="page-item active"></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch-jsonp/1.3.0/fetch-jsonp.min.js"></script>

    <script>
        window.onload = function () {
            document.getElementById('inputField').disabled = true;
        };

        function updateForm2() {
            document.getElementById('inputField').disabled = false;
        }


        function limitInputLength() {
            const inputValue = document.getElementById('inputField').value;
            if (inputValue.length > 10) {
                document.getElementById('inputField').value = inputValue.slice(0, 10);
            }
        }

        function updateForm3() {
            const selectValue = document.getElementById('selectOption').value;
            const inputValue = document.getElementById('inputField').value;

            if (inputValue.length === 10) {
                document.getElementById('loadingIndicator').style.display = 'block';
                const proxyUrl = `/proxy?callback=handleResponse&bank_code=${selectValue}&account_number=${inputValue}`;

                // Use fetch to make the request via the Laravel proxy
                fetch(proxyUrl)
                    .then(response => response.json())
                    .then(data => {

                        console.log(data.status);
                        if (data.status === true) {
                            document.getElementById('result').value = data.customer_name;
                        } else {
                            document.getElementById('result').value = JSON.stringify(data.message, null, 2);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    })
                    .finally(() => {
                        document.getElementById('loadingIndicator').style.display = 'none';
                    });
            }
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate.'layouts.main2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/fadded/public_html/core/resources/views/templates/basic/user/referal.blade.php ENDPATH**/ ?>