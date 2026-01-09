@extends($activeTemplate.'layouts.main2')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if (isset($errors) && $errors->any())
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
                    <h4 class="text-center mt-2">@lang('PayVibe Payment Information')</h4>
                </div>
                <div class="card-body p-5">
                    <div class="alert alert-info" role="alert">
                        <p class="mb-0">
                            @lang('Please transfer exactly') 
                            <strong>{{ showAmount(is_array($data->val) ? $data->val['amount'] : $data->val->amount) }} {{ __(is_array($data->val) ? $data->val['currency'] : $data->val->currency) }}</strong> 
                            @lang('to the virtual account below')
                        </p>
                    </div>

                    {{-- Important Instructions --}}
                    <div class="mt-4">
                        <div class="alert alert-warning" role="alert">
                            <p><strong>@lang('Important Transfer Instructions'):</strong> 
                                @lang('Please copy the transaction reference below and use it as the <b>narration</b> or <b>description</b> when making your transfer. Our customer support will need this reference if you report any issues about this transaction') 
                            </p>
                            <div class="d-flex align-items-center">
                                <span class="value me-2" id="reference">FADDED-{{ is_array($data->val) ? ($data->val['reference'] ?? 'N/A') : ($data->val->reference ?? 'N/A') }}</span>
                                <button class="btn btn-sm copy-btn" onclick="copyToClipboard('reference')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Account Details - Visible on Main Page --}}
                    <div class="border rounded p-4 mt-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Bank Name'):</span>
                                    <span class="value" id="bankName">{{ __(is_array($data->val) ? ($data->val['bank_name'] ?? 'N/A') : ($data->val->bank_name ?? 'N/A')) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Account Number'):</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="accountNumber">{{ __(is_array($data->val) ? ($data->val['virtual_account'] ?? 'N/A') : ($data->val->virtual_account ?? 'N/A')) }}</span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('accountNumber')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Account Name'):</span>
                                    <span class="value">{{ __(is_array($data->val) ? ($data->val['account_name'] ?? 'N/A') : ($data->val->account_name ?? 'N/A')) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Amount'):</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="amount">{{ showAmount(is_array($data->val) ? $data->val['amount'] : $data->val->amount) }} {{ __(is_array($data->val) ? $data->val['currency'] : $data->val->currency) }}</span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('amount')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="font-weight-bold">@lang('Reference'):</span>
                                    <div class="d-flex align-items-center">
                                        <span class="value me-2" id="reference2">FADDED-{{ is_array($data->val) ? ($data->val['reference'] ?? 'N/A') : ($data->val->reference ?? 'N/A') }}</span>
                                        <button class="btn btn-sm copy-btn" onclick="copyToClipboard('reference2')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Help Button --}}
                    <button type="button" 
                            class="btn btn-outline-primary btn-lg w-100 pill p-3 mt-4" 
                            data-toggle="modal" 
                            data-target="#payvibeAccountModal">
                        <i class="fas fa-question-circle me-2"></i>
                        @lang('Need Help? View Transfer Instructions')
                    </button>

                    {{-- Warning --}}
                    <div class="alert alert-warning mt-3" role="alert">
                        <ul class="mb-0">
                            <li>@lang('Please transfer the exact amount to avoid payment issues')</li>
                            <li>@lang('Use the reference as narration to help us track your payment')</li>
                            <li>@lang('Your account will be credited automatically after successful transfer')</li>
                            <li>@lang('This virtual account is valid for this transaction only')</li>
                        </ul>
                    </div>

                    <a href="javascript:void(0);" 
                       id="paymentButton"
                       style="background: #00be9c; border: 0px; color:#ffffff; margin-top: 0;"
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PayVibe Help Modal -->
<div class="modal fade" id="payvibeAccountModal" tabindex="-1" role="dialog" aria-labelledby="payvibeAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payvibeAccountModalLabel">
                    <i class="fas fa-question-circle me-2"></i>
                    @lang('PayVibe Transfer Instructions')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Account Details Summary --}}
                <div class="alert alert-info" role="alert">
                    <h6><i class="fas fa-info-circle me-2"></i>@lang('Account Details Summary'):</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>@lang('Bank'):</strong> {{ __(is_array($data->val) ? ($data->val['bank_name'] ?? 'N/A') : ($data->val->bank_name ?? 'N/A')) }}<br>
                            <strong>@lang('Account Number'):</strong> {{ __(is_array($data->val) ? ($data->val['virtual_account'] ?? 'N/A') : ($data->val->virtual_account ?? 'N/A')) }}<br>
                            <strong>@lang('Account Name'):</strong> {{ __(is_array($data->val) ? ($data->val['account_name'] ?? 'N/A') : ($data->val->account_name ?? 'N/A')) }}
                        </div>
                        <div class="col-md-6">
                            <strong>@lang('Amount'):</strong> {{ showAmount(is_array($data->val) ? $data->val['amount'] : $data->val->amount) }} {{ __(is_array($data->val) ? $data->val['currency'] : $data->val->currency) }}<br>
                            <strong>@lang('Reference'):</strong> FADDED-{{ is_array($data->val) ? ($data->val['reference'] ?? 'N/A') : ($data->val->reference ?? 'N/A') }}
                        </div>
                    </div>
                </div>

                {{-- Step-by-Step Instructions --}}
                <div class="alert alert-primary mt-3" role="alert">
                    <h6><i class="fas fa-list-ol me-2"></i>@lang('How to Transfer'):</h6>
                    <ol class="mb-0">
                        <li>@lang('Open your bank app or visit your bank')</li>
                        <li>@lang('Select "Transfer" or "Send Money"')</li>
                        <li>@lang('Enter the account number above')</li>
                        <li>@lang('Enter the exact amount')</li>
                        <li>@lang('Use the reference as narration/description')</li>
                        <li>@lang('Complete the transfer')</li>
                    </ol>
                </div>

                {{-- Important Notes --}}
                <div class="alert alert-warning mt-3" role="alert">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>@lang('Important Notes'):</h6>
                    <ul class="mb-0">
                        <li>@lang('Transfer the exact amount to avoid issues')</li>
                        <li>@lang('Use the reference as narration for tracking')</li>
                        <li>@lang('Your account will be credited automatically')</li>
                        <li>@lang('This virtual account is valid for this transaction only')</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                <button type="button" class="btn btn-primary" onclick="copyAllDetails()">
                    <i class="fas fa-copy me-2"></i>@lang('Copy All Details')
                </button>
            </div>
        </div>
    </div>
</div>

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
                    button.style.backgroundColor = "#00be9c";
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
                showToast('Copied to clipboard: ' + text, 'success');
            }).catch(err => {
                console.error('Clipboard error:', err);
                showToast('Failed to copy to clipboard', 'error');
            });
        } else {
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = text;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);
            showToast('Copied to clipboard: ' + text, 'success');
        }
    }

    function copyAllDetails() {
        const accountNumber = document.getElementById('accountNumber').innerText;
        const bankName = document.getElementById('bankName').innerText;
        const accountName = document.querySelector('#payvibeAccountModal .alert-info .col-md-6:first-child').innerText.split('Account Name:')[1].trim();
        const amount = document.getElementById('amount').innerText;
        const reference = document.getElementById('reference').innerText;

        const allDetails = `Bank: ${bankName}\nAccount Number: ${accountNumber}\nAccount Name: ${accountName}\nAmount: ${amount}\nReference: ${reference}`;

        if (navigator.clipboard) {
            navigator.clipboard.writeText(allDetails).then(() => {
                showToast('All details copied to clipboard!', 'success');
            }).catch(err => {
                console.error('Clipboard error:', err);
                showToast('Failed to copy details', 'error');
            });
        } else {
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = allDetails;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);
            showToast('All details copied to clipboard!', 'success');
        }
    }

    function showToast(message, type) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        `;
        
        if (type === 'success') {
            toast.style.backgroundColor = '#28a745';
        } else {
            toast.style.backgroundColor = '#dc3545';
        }
        
        toast.textContent = message;
        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
</script>

<style>
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>
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
        background: #00be9c;
        color: white;
        border: none;
        padding: 0.3rem 0.6rem;
        border-radius: 5px;
    }
    hr {
        margin: 0.5rem 0;
        opacity: 0.15;
    }
    .modal-lg {
        max-width: 600px;
    }
    .modal-content {
        border-radius: 15px;
    }
    .modal-header {
        background: #00be9c;
        color: white;
        border-radius: 15px 15px 0 0;
    }
    .modal-header .close {
        color: white;
        opacity: 0.8;
    }
    .modal-header .close:hover {
        opacity: 1;
    }
</style>
@endpush 