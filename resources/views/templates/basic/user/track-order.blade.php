@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
    <meta name="theme-color" content="#2196f3">
    <meta name="author" content="DexignZone" />
    <meta name="keywords" content="" />
    <meta name="robots" content="" />
    <meta name="description" content="FADDED"/>
    <meta property="og:title" content="FADDED" />
    <meta property="og:description" content="FADDED" />
    <meta property="og:image" content="https://makaanlelo.com/tf_products_007/foodia/xhtml/social-image.png"/>
    <meta name="format-detection" content="telephone=no">

    <!-- Favicons Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{url('')}}/assets/assets/images/favicon.png" />

    <!-- Title -->
    <title>FADDED</title>


    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="{{url('')}}/assets/assets/css/style.css">
    <link rel="stylesheet" href="{{url('')}}/assets/assets/vendor/swiper/swiper-bundle.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&family=Roboto+Slab:wght@100;300;500;600;800&display=swap" rel="stylesheet">

</head>
<body class="bg-white">
<div class="page-wraper">

    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <!-- Preloader end-->

    <!-- Header -->
    <header class="header">
        <div class="main-bar">
            <div class="container">
                <div class="header-content">
                    <div class="left-content">
                        <a href="javascript:void(0);" class="back-btn">
                            <svg width="18" height="18" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.03033 0.46967C9.2966 0.735936 9.3208 1.1526 9.10295 1.44621L9.03033 1.53033L2.561 8L9.03033 14.4697C9.2966 14.7359 9.3208 15.1526 9.10295 15.4462L9.03033 15.5303C8.76406 15.7966 8.3474 15.8208 8.05379 15.6029L7.96967 15.5303L0.96967 8.53033C0.703403 8.26406 0.679197 7.8474 0.897052 7.55379L0.96967 7.46967L7.96967 0.46967C8.26256 0.176777 8.73744 0.176777 9.03033 0.46967Z" fill="#a19fa8"/>
                            </svg>
                        </a>
                    </div>
                    <h5 class="mb-0 ms-2 text-nowrap">Track Order</h5>
                    <div class="mid-content">
                    </div>
                    <div class="right-content">
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->


    <!-- Page Content -->
    <div class="page-content">
        <div class="container bottom-content">
            <div class="location-area">
                <div class="d-flex align-items-center">

                    <div class="ms-3">
                        <h6 class="title">Order ID</h6>
                        <span class="font-14 font-w600">{{$order->id}}</span>
                    </div>
                </div>

                <div class="text-end">
                    <div class="d-flex align-items-center">
                        <i class="me-2 fa-solid fa-clock"></i>
                        <small class="font-w600">Estimated Arrival Date/Time</small>
                    </div>
                    <span class="font-w900 font-14 text-dark d-block">15-20 min</span>
                </div>
            </div>
            <a href="tracking-order.html" class="short-map">
                <div class="zone">
                    <p class="mb-0 text-dark">Estimated Time</p>
                    <div class="time">5-10 min</div>
                </div>
{{--                <img src="{{url('')}}/assets/assets/images/map2.png" alt="">--}}
            </a>

            <div class="d-flex align-items-center">

                <div class="ms-3">
                    <h6 class="title">Order Details</h6>
                    <span class="font-14 font-w600">{{$order->gift_item}}</span>
                </div>
            </div>


            <div class="order-status">
                <h6 class="title">Order Status</h6>
                <ul class="dz-timeline style-1">

                    @forelse($order_track as $data)

                        <li class="timeline-item active">
                            <h6 class="timeline-tilte">{{$data->note}}</h6>
                            @php
                                $dateTime = Carbon::parse($data->created_at);
                            @endphp
                            <p class="timeline-date">{{ $dateTime->format('M j, Y - g:ia') }}
                            </p>
                        </li>

                    @empty





                    @endforelse


                </ul>
            </div>
            <div class="footer fixed ">

                <div class="container">
                    <div class="footer-btn d-flex align-items-center">
                        @if($order_status != 4)
                        <a href="user/confirm-delivery" class="btn btn-dark btn-block">CONFIRM DELIVERY</a>
                        @else

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Content End-->

    <!-- Theme Color Settings -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom">
        <div class="offcanvas-body small">
            <ul class="theme-color-settings">
                <li>
                    <input class="filled-in" id="primary_color_8" name="theme_color" type="radio" value="color-primary" />
                    <label for="primary_color_8"></label>
                    <span>Default</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_2" name="theme_color" type="radio" value="color-green" />
                    <label for="primary_color_2"></label>
                    <span>Green</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_3" name="theme_color" type="radio" value="color-blue" />
                    <label for="primary_color_3"></label>
                    <span>Blue</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_4" name="theme_color" type="radio" value="color-pink" />
                    <label for="primary_color_4"></label>
                    <span>Pink</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_5" name="theme_color" type="radio" value="color-yellow" />
                    <label for="primary_color_5"></label>
                    <span>Yellow</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_6" name="theme_color" type="radio" value="color-orange" />
                    <label for="primary_color_6"></label>
                    <span>Orange</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_7" name="theme_color" type="radio" value="color-purple" />
                    <label for="primary_color_7"></label>
                    <span>Purple</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_1" name="theme_color" type="radio" value="color-red" />
                    <label for="primary_color_1"></label>
                    <span>Red</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_9" name="theme_color" type="radio" value="color-lightblue" />
                    <label for="primary_color_9"></label>
                    <span>Lightblue</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_10" name="theme_color" type="radio" value="color-teal" />
                    <label for="primary_color_10"></label>
                    <span>Teal</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_11" name="theme_color" type="radio" value="color-lime" />
                    <label for="primary_color_11"></label>
                    <span>Lime</span>
                </li>
                <li>
                    <input class="filled-in" id="primary_color_12" name="theme_color" type="radio" value="color-deeporange" />
                    <label for="primary_color_12"></label>
                    <span>Deeporange</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- Theme Color Settings End -->
</div>
<!--**********************************
    Scripts
***********************************-->
<script src="{{url('')}}/assets/assets/js/jquery.js"></script>
<script src="{{url('')}}/assets/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{url('')}}/assets/assets/js/dz.carousel.js"></script><!-- Swiper -->
<script src="{{url('')}}/assets/assets/vendor/swiper/swiper-bundle.min.js"></script><!-- Swiper -->
<script src="{{url('')}}/assets/assets/js/settings.js"></script>
<script src="{{url('')}}/assets/assets/js/custom.js"></script>
</body>
</html>
