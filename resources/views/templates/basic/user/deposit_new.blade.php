@extends($activeTemplate.'layouts.main2')
@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @if ($errors->any())
                <div class="alert alert-danger my-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger mt-2">
                    {{ session()->get('error') }}
                </div>
            @endif

            <form action="{{ route('user.deposit.insert') }}" method="POST">
                @csrf
                <input type="hidden" name="currency" value="NGN">
                <input type="hidden" name="id" value="0">
                <input type="hidden" name="qty" value="1">

                <div class="dashboard-body__content">
                    <div class="dashboard-body__item-wrapper">
                        <div class="p-3">
                            <p class="mt-3 p-3">Top up your wallet easily</p>
                            <a style="background: #20CCB4FF; border: 0px"
                               href="https://streamable.com/stp3r2"
                               class="btn btn-dark btn-sm w-20 p-2">Learn how to fund your wallet</a>
                               <a style="background: #20CCB4FF; border: 0px"
                               href="https://faddedlinks.blogspot.com/2025/05/useful-links.html"
                               class="btn btn-warning btn-sm w-20 p-2">Having Account issues Click here</a>
                        </div>

                        <div class="p-3">
                            <div class="card-body">
                                <label for="amount-input" class="form-label">Enter Amount (NGN)</label>
                                <input type="number" name="amount" id="amount-input" class="form-control" required min="2000" max="500000" aria-describedby="amount-help">
                                <div id="amount-help" class="form-text">Minimum: ₦2,000 | Maximum: ₦500,000</div>
                                <input type="text" id="payment_method" name="payment" hidden>
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="card-body">
                                <label for="gateway" class="form-label">Select Payment Gateway</label>
                                <select name="gateway" id="gateway" class="form-control" required aria-describedby="gateway-help">
                                    <option value="">Select Payment Method</option>
                                    @foreach ($gateway_currency as $data)
                                        @if($data->method_code == 118 || $data->method_code == 120 || $data->method_code == 1000)
                                            <option value="{{ $data->method_code }}" data-currency="{{ $data->currency }}">
                                                {{ $data->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="gateway-help" class="form-text">Choose payment method</div>
                                <div id="payvibe-notice" class="alert alert-info mt-3" style="display: none;" role="alert" aria-live="polite">
                                    <small><i class="fas fa-info-circle" aria-hidden="true"></i> PayVibe is disabled for amounts over ₦10,000. Please select another payment method.</small>
                                </div>
                            </div>
                        </div>

                        <div class="p-3">
                            <button type="submit"
                                    style="background: #20CCB4FF; border: 0px; color: white"
                                    class="btn btn-main btn-lg w-100 pill p-3" id="btn-confirm">@lang('Continue')
                            </button>
                        </div>

                        <script>
                            $(document).ready(function() {
                                console.log('PayVibe disable filter loaded');
                                console.log('jQuery version:', $.fn.jquery);

                                function togglePayVibe() {
                                    var amount = parseInt($('input[name="amount"]').val()) || 0;
                                    var payvibeOption = $('#gateway option[value="120"]');
                                    var payvibeNotice = $('#payvibe-notice');
                                    var gatewaySelect = $('#gateway');

                                    console.log('Amount:', amount);
                                    console.log('PayVibe option found:', payvibeOption.length > 0);
                                    console.log('Current gateway value:', gatewaySelect.val());

                                    if (amount > 10000) {
                                        payvibeOption.prop('disabled', true);
                                        payvibeNotice.show();
                                        console.log('Disabled PayVibe');
                                        
                                        // If PayVibe is currently selected, clear the selection
                                        if (gatewaySelect.val() == '120') {
                                            gatewaySelect.val('');
                                            console.log('Cleared PayVibe selection');
                                        }
                                    } else {
                                        payvibeOption.prop('disabled', false);
                                        payvibeNotice.hide();
                                        console.log('Enabled PayVibe');
                                    }
                                }

                                // Set payment method based on selected gateway
                                $('#gateway').on('change', function() {
                                    console.log('Gateway changed to:', $(this).val());
                                    var methodCode = $(this).val();
                                    var paymentMethod = '';
                                    
                                    if (methodCode == '118') {
                                        paymentMethod = 'xtrapay';
                                    } else if (methodCode == '120') {
                                        paymentMethod = 'payvibe';
                                    } else if (methodCode == '1000') {
                                        paymentMethod = 'manual';
                                    }
                                    
                                    $('#payment_method').val(paymentMethod);
                                    var selectedOption = $(this).find('option:selected');
                                    $('input[name=currency]').val(selectedOption.data('currency'));
                                });

                                // Monitor amount input
                                $('input[name="amount"]').on('input', function() {
                                    console.log('Amount changed:', $(this).val());
                                    togglePayVibe();
                                });

                                // Initial run
                                console.log('Running initial togglePayVibe');
                                togglePayVibe();
                            });
                        </script>
            </form>

            <a href="https://t.me/faddedsocailsmanual"
               class="btn btn-warning w-100 my-3"> Having Manual Payment issues? Click here to Resolve</a>
               <a href="https://api.whatsapp.com/send/?phone=17864041871&text&type=phone_number&app_absent=0"
               class="btn btn-warning w-100 my-3"> Having Instant payment issues? Click here to Resolve</a>
        </div>
    </div>

    <div class="col-xl-12 col-sm-12 p-2">
        <div class="dashboard-widget">
            <h5 class="mt-4 mb-4">@lang('Latest Payments History')</h5>

            <div class="dashboard-body__item">
                <div class="table-responsive">
                    <table class="table style-two">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Verify</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($deposits as $deposit)
                            <tr>
                                <td>
                                    {{ diffForHumans($deposit->created_at) }}
                                </td>
                                <td>
                                    @if($deposit->method_code == 1000)
                                        <p class="mb-0 text-small">Manual</p>
                                    @elseif($deposit->method_code == 107)
                                        <p class="mb-0">Paystack</p>
                                    @elseif($deposit->method_code == 118)
                                        <p class="mb-0">XtraPay</p>
                                    @elseif($deposit->method_code == 120)
                                        <p class="mb-0">PayVibe</p>
                                    @else
                                        <p class="mb-0">Referral</p>
                                    @endif
                                </td>
                                <td>
                                    <p>{{number_format($deposit->amount, 2)}}</p>
                                </td>
                                <td>
                                    @if($deposit->status == 1)
                                        <a href="#" class="btn btn-success btn-sm">Completed</a>
                                    @elseif($deposit->status == 2)
                                        <a href="#" class="btn btn-warning btn-sm">Pending</a>
                                    @elseif($deposit->status == 3)
                                        <a href="#" class="btn btn-danger btn-sm">Rejected</a>
                                    @else
                                        <a href="#" class="btn btn-warning btn-sm">Pending</a>
                                    @endif
                                </td>
                                <td>
                                @if($deposit->method_code == 118 && $deposit->status == 0)
                                <a href="/user/xtrapay/verify/{{ $deposit->trx }}"
                                       class="btn btn-primary btn-sm">
                                        Verify
                                    </a>
                                @endif
                            </td>
                            </tr>
                        @empty
                            <div class="card">
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
                        @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-start my-3">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination common-pagination mt-0">
                                <li class="page-item"> {{ paginateLinks($deposits) }}</li>
                                <li class="page-item active"></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row p-3">
            <div class="col-12">
            </div>
            <div class="col-md-12">
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" role="dialog" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="maintenanceModalLabel">Maintenance Mode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                The site is currently in maintenance mode. You may experience delays in top-up transactions.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/67de576c5a8f99190f7211c2/1imu8b0nm';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->