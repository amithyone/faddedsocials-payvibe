@extends('admin.layouts.app')

@section('panel')
<div class="row justify-content-center">
    @if(request()->routeIs('admin.deposit.list') || request()->routeIs('admin.deposit.method') || request()->routeIs('admin.users.deposits') || request()->routeIs('admin.users.deposits.method'))
        <div class="col-xxl-3 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--success has-link">
                <a href="{{ route('admin.deposit.successful') }}" class="item-link"></a>
                <div class="widget-two__content">
                    <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($successful) }}</h2>
                    <p class="text-white">@lang('Successful Payment')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-xxl-3 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--6 has-link">
                <a href="{{ route('admin.deposit.pending') }}" class="item-link"></a>
                <div class="widget-two__content">
                    <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($pending) }}</h2>
                    <p class="text-white">@lang('Pending Payment')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-xxl-3 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 has-link b-radius--5 bg--pink">
                <a href="{{ route('admin.deposit.rejected') }}" class="item-link"></a>
                <div class="widget-two__content">
                    <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($rejected) }}</h2>
                    <p class="text-white">@lang('Rejected Payment')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-xxl-3 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 has-link b-radius--5 bg--dark">
                <a href="{{ route('admin.deposit.initiated') }}" class="item-link"></a>
                <div class="widget-two__content">
                    <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($initiated) }}</h2>
                    <p class="text-white">@lang('Initiated Payment')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
    @endif

    <div class="col-md-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Gateway | Transaction')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('User')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Conversion')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($deposits as $deposit)
                            @php
                                $details = $deposit->detail ? json_encode($deposit->detail) : null;
                            @endphp
                            <tr>
                                <td>
                                     <span class="fw-bold"> <a href="{{ appendQuery('method',@$deposit->gateway->alias) }}">{{ __(@$deposit->gateway->name) }}</a> </span>
                                     <br>
                                     <small> {{ $deposit->trx }} </small>
                                </td>

                                <td>
                                    {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $deposit->user->fullname ?? "Name" }}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ appendQuery('search',@$deposit->user->username) }}"><span>@</span>{{ $deposit->user->username ?? "name" }}</a>
                                    </span>
                                </td>
                                <td>
                                    {{ __($general->cur_sym) }}{{ showAmount($deposit->amount) }}
                                    <br>
                                    <strong title="@lang('Amount without charge')">
                                        {{ showAmount($deposit->amount) }} {{ __($general->cur_text) }}
                                    </strong>
                                </td>
                                <td>
    1 {{ __($general->cur_text) }} = {{ showAmount($deposit->rate) }} {{ __($deposit->method_currency) }}
    <br>
    @php
        $fixedCharge = 100;
        $percentCharge = 1.5;

        // Original final amount
        $originalFinalAmount = $deposit->final_amo;

        // Deduct the fixed charge
        $afterFixedCharge = $originalFinalAmount - $fixedCharge;
        if ($afterFixedCharge < 0) {
            $afterFixedCharge = 0;
        }

        // Deduct the percentage charge
        $finalAmountAfterDeductions = $afterFixedCharge - ($afterFixedCharge * $percentCharge / 100);
        if ($finalAmountAfterDeductions < 0) {
            $finalAmountAfterDeductions = 0;
        }
    @endphp
    <strong>
        {{ showAmount($finalAmountAfterDeductions) }} {{ __($deposit->method_currency) }}
    </strong>
</td>
                                <td>
                                    @php echo $deposit->statusBadge @endphp
                                </td>
                                <td>
                                    <a href="{{ route('admin.deposit.details', $deposit->id) }}"
                                       class="btn btn-sm btn-outline--primary ms-1">
                                        <i class="la la-desktop"></i> @lang('Details')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($deposits->hasPages())
            <div class="card-footer py-4">
                @php echo paginateLinks($deposits) @endphp
            </div>
            @endif
        </div><!-- card end -->
    </div>
</div>


<h6 class="mt-5">Referal Payment Request</h6>
<div class="col-md-12 my-3">
    <div class="card b-radius--10">
        <div class="card-body p-0">
            <div class="table-responsive--sm table-responsive">
                <table class="table table--light style--two">
                    <thead>
                    <tr>
                        <th>@lang('Username')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Bank Name')</th>
                        <th>@lang('Account Number')</th>
                        <th>@lang('Date/Time')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($referaldeposits as $data)

                        <tr>
                            <td>
                                <span class="fw-bold">{{ $data->user->fullname ?? "Name" }}</span>
                                <br>
                                <span class="small">
                                    <a href="{{ appendQuery('search',@$data->user->username) }}"><span>@</span>{{ $data->user->username ?? "name" }}</a>
                                    </span>
                            </td>

                            <td>
                                {{number_format($data->amount, 2)}}
                            </td>

                            <td>
                                {{$data->bank_name}}
                            </td>
                            <td>
                                {{$data->account_no}}
                            </td>

                            <td>
                                {{ showDateTime($data->created_at) }}<br>{{ diffForHumans($data->created_at) }}
                            </td>



                            <td>
                                <a href="approve-referal?id={{$data->id}}"
                                   class="btn btn-sm btn-outline--success ms-1">
                                    <i class="la la-user-check"></i> @lang('Approve')
                                </a>


                                <a href="decline-referal?id={{$data->id}}"
                                   class="btn btn-sm btn-outline--danger ms-1">
                                    <i class="la la-trash"></i> @lang('Decline')
                                </a>


                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
        @if ($deposits->hasPages())
            <div class="card-footer py-4">
                @php echo paginateLinks($deposits) @endphp
            </div>
        @endif
    </div><!-- card end -->
</div>


@endsection


@push('breadcrumb-plugins')
    @if(!request()->routeIs('admin.users.deposits') && !request()->routeIs('admin.users.deposits.method'))
        <x-search-form dateSearch='yes' />
    @endif
@endpush
