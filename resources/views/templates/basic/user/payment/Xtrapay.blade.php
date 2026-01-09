@extends($activeTemplate.'layouts.main2')

@section('content')
<div class="container">
    <div class="row justify-content-center">
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

        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-header bg-white">
                    <h4 class="text-center mt-2">@lang('Payment Information')</h4>
                </div>
                <div class="card-body p-5">
                    <div class="alert alert-info" role="alert">
                        <p class="mb-0">
                            @lang('Please transfer exactly') 
                            <strong>{{ showAmount($data->val->amount) }} {{ __($data->val->currency) }}</strong> 
                            @lang('to the bank account below')
                        </p>
                    </div>
                    {{-- New Reference Instruction --}}
                    <div class="mt-4">
                        <div class="alert alert-warning" role="alert">
                            <p><strong>@lang('Important Transfer Instructions'):</strong> 
                                @lang('Please copy the transaction reference below and use it as the <b>narration</b> or <b>description</b> when making your transfer. Our customer support will need this reference if you report any issues about this transaction') 
                            </p>
                            <div class="d-flex align-items-center">
                                <span class="value me-2" id="reference">FADDED-{{ $data->val->reference ?? 'N/A' }}</span>
                                <button class="btn btn-sm copy-btn" onclick="copyToClipboard('reference')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded p-4 mt-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Bank Name'):</span>
                                    <span class="value" id="bankName">{{ __($data->val->bank_name ?? 'N/A') }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Account Number'):</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="accountNumber">{{ __($data->val->virtual_account ?? 'N/A') }}</span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('accountNumber')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Account Name'):</span>
                                    <span class="value">{{ __($data->val->account_name ?? 'N/A') }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Amount'):</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="amount">{{ showAmount($data->val->amount) }} {{ __($data->val->currency) }}</span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('amount')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                  <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Reference'):</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="reference">FADDED-{{ $data->val->reference ?? 'N/A' }}</span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('reference')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>

                    

                    {{-- Warning --}}
                    <div class="alert alert-warning mt-3" role="alert">
                        <ul class="mb-0">
                            <li>@lang('Please transfer the exact amount to avoid payment issues')</li>
                            <li>@lang('Use the reference as narration to help us track your payment and resolve any delays')</li>
                            <li>@lang('Your account will be credited automatically after successful transfer')</li>
                            <li>@lang('This virtual account is valid for this transaction only')</li>
                        </ul>
                    </div>

                    <a href="javascript:void(0);" 
                       id="paymentButton"
                       style="background: #20CCB4FF; border: 0px; color:#ffffff; margin-top: 0;"
                       class="btn btn-main btn-lg w-100 pill p-3">
                        I have sent
                    </a>

                    <div id="loadingIcon" style="display: none; text-align: center; margin-top: 10px;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                        <p>Checking for transaction...</p>
                    </div>

                    <p id="timerText" style="font-weight: bold; text-align: center; margin-top: 10px; font-size: 18px;">
                        Account number expires in <span id="countdown">10:00</span>
                    </p>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            let button = document.getElementById("paymentButton");
                            let buttonCountdownElement = document.getElementById("buttonCountdown");
                            let countdownElement = document.getElementById("countdown");
                            let loadingIcon = document.getElementById("loadingIcon");
                            let timeLeft = 10;
                            let accountTimeLeft = 10 * 60;

                            let accountCountdownTimer = setInterval(function() {
                                let minutes = Math.floor(accountTimeLeft / 60);
                                let seconds = accountTimeLeft % 60;
                                countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                                accountTimeLeft--;

                                if (accountTimeLeft < 0) {
                                    clearInterval(accountCountdownTimer);
                                    countdownElement.textContent = "Expired";
                                }
                            }, 1000);

                            button.addEventListener("click", function() {
                                button.disabled = true;
                                button.style.backgroundColor = "#ccc";
                                loadingIcon.style.display = "block";

                                let buttonCountdownTimer = setInterval(function() {
                                    if (timeLeft <= 0) {
                                        clearInterval(buttonCountdownTimer);
                                        buttonCountdownElement.textContent = "0";
                                        button.disabled = false;
                                        button.style.backgroundColor = "#20CCB4FF";
                                        loadingIcon.style.display = "none";
                                        return;
                                    } else {
                                        buttonCountdownElement.textContent = timeLeft;
                                        timeLeft--;
                                    }
                                }, 1000);

                                setTimeout(function() {
                                    window.location.href = "/user/deposit/new";
                                }, 30000);
                            });
                        });

                        function copyToClipboard(elementId) {
                            const text = document.getElementById(elementId).innerText;
                            if (navigator.clipboard) {
                                navigator.clipboard.writeText(text).then(() => {
                                    alert('Copied to clipboard: ' + text);
                                }).catch(err => {
                                    console.error('Clipboard error:', err);
                                });
                            } else {
                                const tempTextArea = document.createElement('textarea');
                                tempTextArea.value = text;
                                document.body.appendChild(tempTextArea);
                                tempTextArea.select();
                                document.execCommand('copy');
                                document.body.removeChild(tempTextArea);
                                alert('Copied to clipboard: ' + text);
                            }
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .value {
        font-weight: 600;
        font-size: 1.1em;
    }
    .border {
        background-color: #f8f9fa;
    }
    .alert-info {
        background-color: #e8f4f8;
        border-color: #d6e9f0;
    }
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffecb5;
    }
    .alert-primary {
        background-color: #e2e3ff;
        border-color: #c8c9ff;
    }
    .copy-btn {
        background: #20CCB4FF;
        color: white;
        border: none;
        padding: 0.3rem 0.6rem;
        border-radius: 5px;
    }
    hr {
        margin: 0.5rem 0;
        opacity: 0.15;
    }
</style>
@endpush
