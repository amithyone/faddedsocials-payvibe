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

            <!-- Step 1: Amount Input -->
            <div id="step1" class="amount-step">
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
                                <h6>Enter Amount (NGN)</h6>
                                <input type="number" id="amount-input" class="form-control" required min="2000" max="500000" placeholder="Enter amount">
                                <div id="amount-error" class="text-danger mt-2" style="display: none;">
                                    PayVibe is not available for amounts over ₦10,000. Please enter a lower amount or select another payment method.
                                </div>
                            </div>
                        </div>

                        <div class="p-3">
                            <button type="button" id="continue-btn" 
                                    style="background: #20CCB4FF; border: 0px; color: white"
                                    class="btn btn-main btn-lg w-100 pill p-3">Continue to Payment Method
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Payment Method Selection -->
            <div id="step2" class="payment-step" style="display: none;">
                <form action="{{ route('user.deposit.insert') }}" method="POST">
                    @csrf
                    <input type="hidden" name="currency" value="NGN">
                    <input type="hidden" name="id" value="0">
                    <input type="hidden" name="qty" value="1">
                    <input type="hidden" id="final-amount" name="amount" value="">
                    <input type="text" id="payment_method" name="payment" hidden>

                    <div class="dashboard-body__content">
                        <div class="dashboard-body__item-wrapper">
                            <div class="p-3">
                                <h6>Selected Amount: ₦<span id="display-amount">0</span></h6>
                                <button type="button" id="back-btn" class="btn btn-secondary btn-sm">← Back to Amount</button>
                            </div>

                            <div class="p-3">
                                <div class="card-body">
                                    <h6 class="mb-2">Select Payment Gateway</h6>
                                    <div class="payment-gateways">
                                        @foreach ($gateway_currency as $data)
                                            @if($data->method_code == 120)
                                                <!-- PayVibe option - will be conditionally shown -->
                                                <div class="form-check mb-2 gateway-option payvibe-option" data-method-code="{{ $data->method_code }}" data-currency="{{ $data->currency }}" style="display: none;">
                                                    <input class="form-check-input" type="radio" name="gateway" id="gateway_{{ $data->method_code }}" value="{{ $data->method_code }}" required>
                                                    <label class="form-check-label" for="gateway_{{ $data->method_code }}">
                                                        {{ $data->name }}
                                                    </label>
                                                </div>
                                            @else
                                                <!-- Other payment options -->
                                                <div class="form-check mb-2 gateway-option" data-method-code="{{ $data->method_code }}" data-currency="{{ $data->currency }}">
                                                    <input class="form-check-input" type="radio" name="gateway" id="gateway_{{ $data->method_code }}" value="{{ $data->method_code }}" required>
                                                    <label class="form-check-label" for="gateway_{{ $data->method_code }}">
                                                        {{ $data->name }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div id="payvibe-notice" class="alert alert-info mt-3" style="display: none;">
                                        <small><i class="fas fa-info-circle"></i> PayVibe is not available for amounts over ₦10,000. Please select another payment method.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3">
                                <button type="submit"
                                        style="background: #20CCB4FF; border: 0px; color: white"
                                        class="btn btn-main btn-lg w-100 pill p-3" id="btn-confirm">@lang('Continue')
                                </button>
                            </div>
                </form>

                <a href="https://t.me/faddedsocailsmanual"
                   class="btn btn-warning w-100 my-3"> Having Manual Payment issues? Click here to Resolve</a>
                   <a href="https://api.whatsapp.com/send/?phone=17864041871&text&type=phone_number&app_absent=0"
                   class="btn btn-warning w-100 my-3"> Having Instant payment issues? Click here to Resolve</a>
            </div>
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
                                <td data-label="@lang('Date')">
                                    {{ showDateTime($deposit->created_at) }}
                                </td>
                                <td data-label="@lang('Type')">
                                    <span class="fw-bold">{{ __($deposit->gateway->name) }}</span>
                                </td>
                                <td data-label="@lang('Amount')">
                                    <strong>{{ getAmount($deposit->amount) }} {{ __($general->cur_text) }}</strong>
                                </td>
                                <td data-label="@lang('Status')">
                                    @php
                                        $status = $deposit->status == Status::PAYMENT_SUCCESS ? 'trans' : 'trans2';
                                    @endphp
                                    <span class="badge badge--{{ $status }}">{{ __($deposit->statusText) }}</span>
                                </td>
                                <td data-label="@lang('Verify')">
                                    @if($deposit->status == Status::PAYMENT_PENDING)
                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline--success confirmationBtn"
                                           data-action="{{ route('user.deposit.confirm') }}"
                                           data-question="@lang('Are you sure to confirm this transaction?')"
                                           data-id="{{ $deposit->id }}">
                                            <i class="las la-check"></i> @lang('Confirm')
                                        </a>
                                    @else
                                        <span class="text-muted">@lang('N/A')</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    console.log('Two-step payment form script loaded');
    
    (function() {
        console.log('Initializing two-step payment form');
        
        // Wait for DOM to be ready
        function initPaymentForm() {
            console.log('DOM ready, initializing payment form');
            
            // Check if jQuery is available
            if (typeof $ === 'undefined') {
                console.log('jQuery not available, trying again in 500ms');
                setTimeout(initPaymentForm, 500);
                return;
            }
            
            console.log('jQuery available:', typeof $ !== 'undefined');
            
            var amountInput = $('#amount-input');
            var continueBtn = $('#continue-btn');
            var backBtn = $('#back-btn');
            var step1 = $('#step1');
            var step2 = $('#step2');
            var displayAmount = $('#display-amount');
            var finalAmount = $('#final-amount');
            var payvibeOption = $('.payvibe-option');
            var payvibeNotice = $('#payvibe-notice');
            var amountError = $('#amount-error');
            
            console.log('Elements found:', {
                amountInput: amountInput.length,
                continueBtn: continueBtn.length,
                step1: step1.length,
                step2: step2.length,
                payvibeOption: payvibeOption.length
            });
            
            // Continue button click
            continueBtn.on('click', function() {
                console.log('Continue button clicked');
                var amount = parseInt(amountInput.val()) || 0;
                console.log('Amount entered:', amount);
                
                if (amount < 2000) {
                    alert('Minimum amount is ₦2,000');
                    return;
                }
                
                if (amount > 500000) {
                    alert('Maximum amount is ₦500,000');
                    return;
                }
                
                // Set the amount
                displayAmount.text(amount);
                finalAmount.val(amount);
                
                // Show/hide PayVibe based on amount
                if (amount > 10000) {
                    console.log('Amount > 10000, hiding PayVibe');
                    payvibeOption.hide();
                    payvibeNotice.show();
                    amountError.hide();
                } else {
                    console.log('Amount <= 10000, showing PayVibe');
                    payvibeOption.show();
                    payvibeNotice.hide();
                    amountError.hide();
                }
                
                // Show step 2
                step1.hide();
                step2.show();
                console.log('Switched to step 2');
            });
            
            // Back button click
            backBtn.on('click', function() {
                console.log('Back button clicked');
                step2.hide();
                step1.show();
                console.log('Switched back to step 1');
            });
            
            // Set payment method based on selected gateway
            $('input[name="gateway"]').on('change', function() {
                console.log('Gateway changed to:', $(this).val());
                var methodCode = $(this).val();
                var paymentMethod = '';
                
                if (methodCode == '118') {
                    paymentMethod = 'xtrapay';
                } else if (methodCode == '107') {
                    paymentMethod = 'paystack';
                } else if (methodCode == '120') {
                    paymentMethod = 'payvibe';
                } else {
                    paymentMethod = 'enkpay';
                }
                
                $('#payment_method').val(paymentMethod);
                var selectedOption = $('.gateway-option[data-method-code="' + methodCode + '"]');
                $('input[name=currency]').val(selectedOption.data('currency'));
            });
            
            // Auto-select first available payment method
            setTimeout(function() {
                var firstRadio = $('input[name="gateway"]:visible:first');
                if (firstRadio.length > 0) {
                    firstRadio.prop('checked', true).trigger('change');
                    console.log('Auto-selected first payment method');
                }
            }, 100);
        }
        
        // Try to initialize immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPaymentForm);
        } else {
            initPaymentForm();
        }
        
        // Also try with jQuery ready as backup
        if (typeof $ !== 'undefined') {
            $(document).ready(initPaymentForm);
        }
        
    })();
</script>
@endpush

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