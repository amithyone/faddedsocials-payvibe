@extends('templates.basic.layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h5>Manual Deposit Panel Login</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first('password') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('deposit.pending.login.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">Panel Password</label>
                            <input type="password" name="password" id="password" class="form-control" required autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enter Panel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

