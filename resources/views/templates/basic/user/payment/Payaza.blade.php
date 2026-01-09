@extends($activeTemplate.'layouts.app')

@section('app')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Payaza Payment')</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group text-center">
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('You have to pay '):
                                    <strong>{{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('Reference'):
                                    <strong>{{$deposit->trx}}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="button" class="btn btn--primary w-100" id="payButton">
                                @lang('Pay Now')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://js.payaza.africa/v1/inline.min.js"></script>
<script>
    'use strict';
    
    document.getElementById('payButton').addEventListener('click', function() {
        try {
            const payaza = new PayazaCheckout({
                key: "{{ $key }}",
                reference: "{{ $ref }}",
                amount: {{ $amount }},
                currency: "{{ $currency }}",
                email: "{{ $email }}",
                onClose: function() {
                    console.log('Payment window closed');
                },
                onSuccess: function(response) {
                    console.log('Payment successful:', response);
                    window.location.href = "{{ route('user.deposit.history') }}";
                },
                onError: function(error) {
                    console.error('Payment error:', error);
                    alert('Payment failed. Please try again.');
                }
            });

            payaza.init();

        } catch (error) {
            console.error('Error initializing payment:', error);
            alert('Error initializing payment. Please check the console for details.');
        }
    });
</script>
@endpush
