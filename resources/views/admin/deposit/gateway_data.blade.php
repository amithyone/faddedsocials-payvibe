@php
    // List of keys to ignore from displaying
    $ignoreKeys = [
        'sessionId',
        'settlementId',
        'sourceAccountNumber',
        'sourceAccountName'
    ];
@endphp

<div class="row">
    @foreach($details as $k => $val)
        @if(in_array($k, $ignoreKeys))
            {{-- Skip displaying this key --}}
            @continue
        @endif

        <div class="col-md-12 mb-4">
            @if(is_object($val) || is_array($val))
                <h6>{{ keyToTitle($k) }}</h6>
                <hr>
                <div class="ms-3">
                    @include('admin.deposit.gateway_data', ['details' => $val])
                </div>
            @else
                <h6>{{ keyToTitle($k) }}</h6>
                @if (($k === 'amount' || $k === 'settledAmount') && is_numeric($val))
                    @php
                        $fixedCharge = 100;
                        $percentCharge = 1.5;
                        $afterFixedCharge = $val - $fixedCharge;
                        if ($afterFixedCharge < 0) {
                            $afterFixedCharge = 0;
                        }
                        $finalAmount = $afterFixedCharge - ($afterFixedCharge * ($percentCharge / 100));
                        if ($finalAmount < 0) {
                            $finalAmount = 0;
                        }
                    @endphp
                    <p>
                        {{ number_format($finalAmount, 2) }}
                    </p>
                @else
                    <p>{{ $val }}</p>
                @endif
            @endif
        </div>
    @endforeach
</div>
