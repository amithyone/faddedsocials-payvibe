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

            <form action="change-password" method="POST">
                @csrf

                <div class="dashboard-body__content">
                    <div class="dashboard-body__item-wrapper">

                        <div class="p-3">

                            <h6 class="mt-3 p-3">Update your password</h6>

                        </div>


                        <div class="p-3">
                            <div class="card-body">
                                <h6>Enter new password</h6>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                        </div>

                        <div class="p-3">
                            <div class="card-body">
                                <h6>Confirm password</h6>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <div class="p-3">

                            <button type="submit"
                                    style="background: #20CCB4FF; border: 0px; color: white"
                                    class="btn btn-main btn-lg w-100 pill p-3" id="btn-confirm">@lang('Reset Password')

                        </div>


            </form>

        </div>
    </div>


@endsection
