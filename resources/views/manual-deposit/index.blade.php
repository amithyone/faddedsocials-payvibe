@extends('templates.basic.layouts.master')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Pending Deposits - Manual Approval</h4>
        <form method="POST" action="{{ route('deposit.pending.logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
        </form>
    </div>

    @if(session('notify'))
        @foreach(session('notify') as $msg)
            <div class="alert alert-{{ $msg[0] === 'success' ? 'success' : 'danger' }}">
                {{ $msg[1] }}
            </div>
        @endforeach
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('deposit.pending.index') }}" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Search by Email, Account Number or Transaction / Deposit ID</label>
                    <input type="text"
                           name="search"
                           value="{{ $search ?? '' }}"
                           class="form-control"
                           placeholder="user@example.com, account number or trx / deposit ID">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    <a href="{{ route('deposit.pending.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Gateway</th>
                            <th>Amount</th>
                            <th>Account Number</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $deposit)
                            <tr>
                                <td>{{ $deposit->id }}</td>
                                <td>{{ $deposit->user->fullname ?? $deposit->user->username ?? 'N/A' }}</td>
                                <td>{{ $deposit->user->email ?? 'N/A' }}</td>
                                <td>{{ $deposit->gateway->name ?? 'N/A' }}</td>
                                <td>{{ showAmount($deposit->amount) }} {{ __($deposit->method_currency) }}</td>
                                <td>{{ $deposit->account_number ?? 'N/A' }}</td>
                                <td>{{ showDateTime($deposit->created_at) }}</td>
                                <td>
                                    <form method="POST"
                                          action="{{ route('deposit.pending.approve', $deposit->id) }}"
                                          onsubmit="return confirm('Approve and credit this deposit?');">
                                        @csrf
                                        <input type="hidden" name="search" value="{{ $search ?? '' }}">
                                        <button type="submit" class="btn btn-sm btn-success">Approve &amp; Credit</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    No pending deposits found for this search.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $deposits->links() }}
        </div>
    </div>
</div>
@endsection

