<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->
<head>
    <title>FADDED | REGISTER</title>
    <!-- [Meta] -->
    <meta charset="utf-8"/>
    <meta
        name="viewport"
        content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui"
    />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta
        name="description"
        content="FADDED REGISTRATION"
    />
    <meta
        name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard"
    />
    <meta name="author" content="Phoenixcoded"/>
    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ url('') }}/assets/assets2/images/favicon.svg" type="image/x-icon"/>
    <!-- [Font] Family -->
    <link
        rel="stylesheet"
        href="{{ url('') }}/assets/assets2/fonts/inter/inter.css"
        id="main-font-link"
    />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/fonts/tabler-icons.min.css"/>
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/fonts/feather.css"/>
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/fonts/fontawesome.css"/>
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/fonts/material.css"/>
    <!-- [Template CSS Files] -->
    <link
        rel="stylesheet"
        href="{{ url('') }}/assets/assets2/css/style.css"
        id="main-style-link"
    />
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/css/style-preset.css"/>
</head
><!-- [Head] end --><!-- [Body] Start -->
<body
    data-pc-preset="preset-1"
    data-pc-sidebar-caption="true"
    data-pc-layout="vertical"
    data-pc-direction="ltr"
    data-pc-theme_contrast=""
    data-pc-theme="light"
>
<!-- [ Pre-loader ] start -->
<div class="page-loader">
    <div class="bar"></div>
</div>
<!-- [ Pre-loader ] End -->
<div class="auth-main">
    <div class="auth-wrapper v2">
        <div class="auth-sidecontent">
            <img
                src="{{ url('') }}/assets/assets2/images/authentication/img-auth-sideimg.jpg"
                alt="images"
                class="img-fluid img-auth-side"
            />
        </div>
        <div class="auth-form">
            <div class="card my-5">
                <div class="card-body">


                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session()->has('message'))
                        <div class="alert pill alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert pill alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif


                    <div class="text-center">
                        <a href="/"
                        ><img src="{{ url('') }}/assets/assets2/images/logo-dark.svg" alt="img"
                            /></a>

                        <h5 class="text-center f-w-500 mb-3">
                            Sign up your account
                        </h5>


                        <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                            @csrf

                            <input type="text" name="code" value="{{$ref_code ?? "null"}}" hidden >


                            <div class="mb-3">
                                <input
                                    required type="text" name="username" value="{{ old('username') }}"
                                    class="form-control"
                                    placeholder="Enter username"
                                />
                            </div>


                            <div class="mb-3">
                                <input
                                    required type="email" name="email" value="{{ old('email') }}"
                                    id="email"
                                    type="email"
                                    class="form-control"
                                    placeholder="Enter your Email Address"
                                />
                            </div>

                            <div class="mb-3">
                                <input
                                    required type="password" name="password"
                                    class="form-control" id="your-password"
                                    placeholder="Enter password"
                                />
                            </div>


                            <div class="mb-3">
                                <input
                                    required type="password"
                                    class="form-control" id="your-password"
                                    type="password" name="password_confirmation"
                                    placeholder="Confirm your password"
                                />
                            </div>


                            <div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input
                                        class="form-check-input input-primary"
                                        name="agree"  required value="1"
                                        type="checkbox"
                                        id="customCheckc1"
                                        checked=""
                                    />
                                    <label class="form-check-label text-muted" for="customCheckc1"
                                    >I agree to all the Terms & Condition</label
                                    >
                                </div>
                            </div>


                            <div class="d-grid mt-4">
                                <button style="background: #20CCB4FF; border: 0px" type="submit" class="btn btn-primary">Register</button>
                            </div>




                        </form>

                            <div class="d-flex justify-content-between align-items-end mt-4">
                                <h6 class="f-w-500 mb-0">Already have an Account?</h6>
                                <a href="/user/login" class="link-primary">Login here</a>
                            </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end --><!-- Required Js -->
    <script src="{{ url('') }}/assets/assets2/js/plugins/popper.min.js"></script>
    <script src="{{ url('') }}/assets/assets2/js/plugins/simplebar.min.js"></script>
    <script src="{{ url('') }}/assets/assets2/js/plugins/bootstrap.min.js"></script>
    <script src="{{ url('') }}/assets/assets2/js/fonts/custom-font.js"></script>
    <script src="{{ url('') }}/assets/assets2/js/pcoded.js"></script>
    <script src="{{ url('') }}/assets/assets2/js/plugins/feather.min.js"></script>
</div>
</body>
<!-- [Body] end -->
</html>

