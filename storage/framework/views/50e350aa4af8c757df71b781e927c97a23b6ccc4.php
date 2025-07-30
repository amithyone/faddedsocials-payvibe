<!DOCTYPE html>
<html lang="en"><!-- [Head] start -->
<head><title>FADDED | LOGSTORE</title><!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
          content="Fadded Log strore">
    <meta name="keywords"
          content="Fadded logstore">
    <meta name="author" content="Phoenixcoded"><!-- [Favicon] icon -->
    <link rel="icon" href="<?php echo e(url('')); ?>/assets/assets2/images/favicon.svg" type="image/x-icon">
    <!-- [Page specific CSS] start -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/plugins/datepicker-bs5.min.css">
    <!-- [Page specific CSS] end -->
    <!-- [Font] Family -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/fonts/inter/inter.css" id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/fonts/tabler-icons.min.css">
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/fonts/feather.css">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/fonts/fontawesome.css">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/fonts/material.css"><!-- [Template CSS Files] -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/style-preset.css">

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>



    


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>



    <style>
        .carousel {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .carousel-images {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 100%;
        }

        .carousel-images a {
            flex: 0 0 100%;
            text-align: center;
        }

        .carousel-images img {
            width: 100%;
            height: auto;
        }

        .carousel-buttons {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .carousel-buttons button {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .carousel-buttons button:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>

</head><!-- [Head] end --><!-- [Body] Start -->
<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
      data-pc-theme_contrast="" data-pc-theme="light"><!-- [ Pre-loader ] start -->
<div class="page-loader">
    <div class="bar"></div>
</div><!-- [ Pre-loader ] End --><!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">


        <?php if(auth()->guard()->check()): ?>
            <div class="navbar-content">
                <div class="card pc-user-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/user/avatar-1.jpg" alt="user-image"
                                     class="user-avtar wid-45 rounded-circle">
                            </div>

                            <div class="flex-grow-1 ms-3 me-2">
                                <a href="/user/dashboard"><h6 class="mb-0"><?php echo e(Auth::user()->username); ?></h6></a>
                                <small>Customer</small>
                            </div>

                        </div>
                    </div>
                    <ul class="pc-navbar">
                        <li class="pc-item">
                            <a href="/products" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon"><use xlink:href="#custom-home"></use>
                                </svg> </span>
                                <span class="pc-mtext">Dashboard</span>
                            </a>
                        </li>


                        <li class="pc-item">
                            <a href="/user/deposit/new" class="pc-link">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-direct-inbox"></use>
                                </svg>
                            </span>
                                <span class="pc-mtext">Fund Wallet</span>
                            </a>

                        </li>

                        <li class="pc-item">
                            <a href="/user/orders" class="pc-link">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-bag"></use>
                                </svg>
                            </span>
                                <span class="pc-mtext">My Orders</span>
                            </a>

                        </li>


                        <li class="pc-item">
                            <a href="/user/refer" class="pc-link">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-note-1"></use>
                                </svg>
                            </span>
                                <span class="pc-mtext">Referral</span>
                            </a>

                        </li>


                        <li class="pc-item">
                            <a href="https://t.me/faddedsocialsteam" class="pc-link">
                            <span class="pc-micon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_346_411)">
                                <path
                                    d="M11.944 0C8.77112 0.014807 5.73324 1.28562 3.4949 3.53446C1.25656 5.78329 -3.4549e-05 8.82708 7.12435e-10 12C7.12441e-10 15.1826 1.26428 18.2348 3.51472 20.4853C5.76516 22.7357 8.8174 24 12 24C15.1826 24 18.2348 22.7357 20.4853 20.4853C22.7357 18.2348 24 15.1826 24 12C24 8.8174 22.7357 5.76516 20.4853 3.51472C18.2348 1.26428 15.1826 0 12 0L11.944 0ZM16.906 7.224C17.006 7.222 17.227 7.247 17.371 7.364C17.4672 7.44672 17.5283 7.5629 17.542 7.689C17.558 7.782 17.578 7.995 17.562 8.161C17.382 10.059 16.6 14.663 16.202 16.788C16.034 17.688 15.703 17.989 15.382 18.018C14.686 18.083 14.157 17.558 13.482 17.116C12.426 16.423 11.829 15.992 10.804 15.316C9.619 14.536 10.387 14.106 11.062 13.406C11.239 13.222 14.309 10.429 14.369 10.176C14.376 10.144 14.383 10.026 14.313 9.964C14.243 9.902 14.139 9.923 14.064 9.94C13.958 9.96467 12.271 11.0797 9.003 13.285C8.523 13.615 8.089 13.775 7.701 13.765C7.273 13.757 6.449 13.524 5.836 13.325C5.084 13.08 4.487 12.951 4.539 12.536C4.56567 12.32 4.86333 12.099 5.432 11.873C8.93 10.349 11.2627 9.34433 12.43 8.859C15.762 7.473 16.455 7.232 16.906 7.224Z"
                                    fill="#919295"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_346_411">
                                <rect width="24" height="24" fill="white"/>
                                </clipPath>
                                </defs>
                                </svg>

                            </span>
                                <span class="pc-mtext">Support</span>
                            </a>

                        </li>


                        <li class="pc-item">
                            <a href="/user/rules" class="pc-link">
                            <span class="pc-micon">
                                <svg width="24" height="24" viewBox="0 0 48 48" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                <mask id="mask0_39_2" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="3"
                                      width="48" height="42">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M24 5L2 43H46L24 5Z" fill="white"
                                      stroke="white" stroke-width="4" stroke-linejoin="round"/>
                                <path d="M24 35V36M24 19L24.008 29" stroke="black" stroke-width="4"
                                      stroke-linecap="round"/>
                                </mask>
                                <g mask="url(#mask0_39_2)">
                                <path d="M0 0H48V48H0V0Z" fill="#878787"/>
                                </g>
                                </svg>


                            </span>
                                <span class="pc-mtext">Rules</span>
                            </a>

                        </li>



                        <li class="pc-item">
                            <a href="/user/termofuse" class="pc-link">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-document-filter"></use>
                                </svg>
                            </span>
                                <span class="pc-mtext"> Terms Of Use</span>
                            </a>

                        </li>





                        <li class="pc-item">
                            <a href="https://chat.whatsapp.com/CuSck16moiWFH8njS7pE7C" class="pc-link">
                            <span class="pc-micon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                <mask id="mask0_346_414" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0"
                                      y="0" width="20" height="20">
                                <path d="M0.5 0.5H19.5V19.5H0.5V0.5Z" fill="white"/>
                                </mask>
                                <g mask="url(#mask0_346_414)">
                                <path
                                    d="M11.79 0.640019L11.22 0.560019C9.50693 0.312676 7.75885 0.557374 6.17954 1.26558C4.60023 1.97379 3.25476 3.11634 2.3 4.56002C1.28416 5.94 0.678585 7.57876 0.552979 9.28771C0.427373 10.9967 0.786836 12.7063 1.59 14.22C1.6722 14.3717 1.72337 14.5383 1.74054 14.71C1.75771 14.8817 1.74053 15.055 1.69 15.22C1.28 16.63 0.9 18.05 0.5 19.54L1 19.39C2.35 19.03 3.7 18.67 5.05 18.34C5.33494 18.2808 5.63112 18.3087 5.9 18.42C7.1112 19.0111 8.43482 19.3363 9.78205 19.3738C11.1293 19.4113 12.4689 19.1601 13.7111 18.6372C14.9533 18.1144 16.0692 17.3318 16.9841 16.3421C17.899 15.3525 18.5915 14.1785 19.0153 12.8992C19.4392 11.6198 19.5844 10.2646 19.4414 8.92442C19.2983 7.58429 18.8703 6.29026 18.1859 5.12917C17.5016 3.96809 16.5769 2.96681 15.4737 2.19254C14.3706 1.41827 13.1146 0.888923 11.79 0.640019ZM14.31 13.76C13.9466 14.0854 13.5034 14.3087 13.0256 14.407C12.5478 14.5054 12.0524 14.4754 11.59 14.32C9.49456 13.73 7.67661 12.4152 6.46 10.61C5.99528 9.97154 5.6217 9.27149 5.35 8.53002C5.20285 8.09979 5.17632 7.63749 5.27327 7.19325C5.37023 6.74902 5.58698 6.33981 5.9 6.01002C6.05239 5.81553 6.25981 5.67145 6.49526 5.59654C6.7307 5.52162 6.98325 5.51935 7.22 5.59002C7.42 5.64002 7.56 5.93002 7.74 6.15002C7.886 6.56302 8.057 6.96702 8.25 7.36002C8.39642 7.56053 8.45758 7.81082 8.42011 8.05626C8.38263 8.30169 8.24958 8.52234 8.05 8.67002C7.6 9.07002 7.67 9.40002 7.99 9.85002C8.69745 10.8692 9.6736 11.6723 10.81 12.17C11.13 12.31 11.37 12.34 11.58 12.01C11.67 11.88 11.79 11.77 11.89 11.65C12.47 10.92 12.29 10.93 13.21 11.33C13.503 11.453 13.787 11.597 14.06 11.76C14.33 11.92 14.74 12.09 14.8 12.33C14.8577 12.5904 14.8425 12.8616 14.7561 13.1139C14.6696 13.3662 14.5153 13.5898 14.31 13.76Z"
                                    fill="#919295"/>
                                </g>
                                </svg>


                            </span>
                                <span class="pc-mtext">Join our community</span>
                            </a>

                        </li>


                        <hr class="text-muted">


                        <li class="pc-item">
                            <a href="/user/change-password" class="pc-link">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-shopping-bag"></use>
                                </svg>
                            </span>
                                <span class="pc-mtext">Change Password</span>
                            </a>

                        </li>

                        <li class="pc-item">
                            <a href="/user/logout" class="pc-link">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-logout"></use>
                                </svg>
                            </span>
                                <span class="pc-mtext">Log Out</span>
                            </a>

                        </li>

                    </ul>
                </div>
                <?php else: ?>

                    <div class="navbar-content">
                        <div class="card pc-user-card">


                            <div class="card">
                                <div class="card-body">
                                    <a style="background: #20CCB4FF; border: 0px" href="/user/login"
                                       class="btn btn-dark btn-lg w-100 my-">Login</a>

                                    <a href="/user/register" class="btn btn-dark btn-lg w-100 my-3">Register</a>
                                </div>


                            </div>

                        </div>

                    </div>

                <?php endif; ?>
            </div>
</nav><!-- [ Sidebar Menu ] end --><!-- [ Header Topbar ] start -->


<header class="pc-header">
    <div class="header-wrapper"><!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled"><!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0"
                       id="sidebar-hide">
                        <i class="ti ti-menu-2">
                        </i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="##" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2">

                        </i>
                    </a>

                </li>

                <img src="<?php echo e(url('')); ?>/assets/assets2/images/logo-dark.svg">

            </ul>

        </div><!-- [Mobile Media Block end] -->

        <?php if(auth()->guard()->check()): ?>
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a href="/user/deposit/new"
                           class="btn btn-dark btn-sm">NGN <?php echo e(number_format(Auth::user()->balance, 2)); ?></a>
                    </li>
                </ul>
            </div>
        <?php else: ?>

            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a href="/user/login" class="btn btn-dark btn-sm"><i class="fa fa-lock"></i></a>
                    </li>
                </ul>
            </div>

        <?php endif; ?>
    </div>
</header>


<div class="offcanvas pc-announcement-offcanvas offcanvas-end" tabindex="-1" id="announcement"
     aria-labelledby="announcementLabel">
    <div class="offcanvas-header"><h5 class="offcanvas-title" id="announcementLabel">What's new announcement?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body"><p class="text-span">Today</p>
        <div class="card mb-3">
            <div class="card-body">
                <div class="align-items-center d-flex flex-wrap gap-2 mb-3">
                    <div class="badge bg-light-success f-12">Big News</div>
                    <p class="mb-0 text-muted">2 min ago</p><span class="badge dot bg-warning"></span></div>
                <h5 class="mb-3">Able Pro is Redesigned</h5>
                <p class="text-muted">Able Pro is completely renowed with high aesthetics User Interface.</p><img
                    src="<?php echo e(url('')); ?>/assets/assets2/images/layout/img-announcement-1.png" alt="img"
                    class="img-fluid mb-3">
                <div class="row">
                    <div class="col-12">
                        <div class="d-grid"><a class="btn btn-outline-secondary" href="https://1.envato.market/zNkqj6"
                                               target="_blank">Check Now</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <div class="align-items-center d-flex flex-wrap gap-2 mb-3">
                    <div class="badge bg-light-warning f-12">Offer</div>
                    <p class="mb-0 text-muted">2 hour ago</p><span class="badge dot bg-warning"></span></div>
                <h5 class="mb-3">Able Pro is in best offer price</h5>
                <p class="text-muted">Download Able Pro exclusive on themeforest with best price.</p><a
                    href="https://1.envato.market/zNkqj6" target="_blank"><img
                        src="<?php echo e(url('')); ?>/assets/assets2/images/layout/img-announcement-2.png" alt="img"
                        class="img-fluid"></a></div>
        </div>
        <p class="text-span mt-4">Yesterday</p>
        <div class="card mb-3">
            <div class="card-body">
                <div class="align-items-center d-flex flex-wrap gap-2 mb-3">
                    <div class="badge bg-light-primary f-12">Blog</div>
                    <p class="mb-0 text-muted">12 hour ago</p><span class="badge dot bg-warning"></span></div>
                <h5 class="mb-3">Featured Dashboard Template</h5>
                <p class="text-muted">Do you know Able Pro is one of the featured dashboard template selected by
                    Themeforest team.?</p><img src="<?php echo e(url('')); ?>/assets/assets2/images/layout/img-announcement-3.png"
                                               alt="img"
                                               class="img-fluid"></div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <div class="align-items-center d-flex flex-wrap gap-2 mb-3">
                    <div class="badge bg-light-primary f-12">Announcement</div>
                    <p class="mb-0 text-muted">12 hour ago</p><span class="badge dot bg-warning"></span></div>
                <h5 class="mb-3">Buy Once - Get Free Updated lifetime</h5>
                <p class="text-muted">Get the lifetime free updates once you purchase the Able Pro.</p><img
                    src="<?php echo e(url('')); ?>/assets/assets2/images/layout/img-announcement-4.png" alt="img" class="img-fluid">
            </div>
        </div>
    </div>
</div><!-- [ Header ] end --><!-- [ Main Content ] start -->

<?php echo $__env->yieldContent('content'); ?>


<footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
        <div class="text-center">
            <h6 class="">2024 | FADDED LOGS STORE</h6>
        </div>
    </div>
</footer><!-- Required Js -->
<script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/popper.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/simplebar.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/bootstrap.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/fonts/custom-font.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/pcoded.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/dz.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/feather.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/pages/ac-slider.js"></script>
<script>layout_change('false');</script>

<script>layout_theme_contrast_change('false');</script>
<script>change_box_container('false');</script>
<script>layout_caption_change('true');</script>
<script>layout_rtl_change('false');</script>
<script>preset_change('preset-1');</script>
<script>main_layout_change('vertical');</script><!-- [Page Specific JS] start --><!-- bootstrap-datepicker -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/datepicker-full.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/apexcharts.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/peity-vanilla.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/pages/course-dashboard.js"></script><!-- [Page Specific JS] end -->
<!-- [ Main Content ] end --><!-- Required Js -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/popper.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/simplebar.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/bootstrap.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/fonts/custom-font.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/pcoded.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets2/js/plugins/feather.min.js"></script>
<script>
    layout_change("false");
</script>
<script>
    layout_theme_contrast_change("false");
</script>
<script>
    change_box_container("false");
</script>
<script>
    layout_caption_change("true");
</script>
<script>
    layout_rtl_change("false");
</script>
<script>
    preset_change("preset-1");
</script>
<script>
    main_layout_change("vertical");
</script>


<div class="pct-c-btn"><a href="##" data-bs-toggle="offcanvas"
                          data-bs-target="#offcanvas_pc_layout"><i class="ph-duotone ph-gear-six"></i></a></div>
<div class="offcanvas border-0 pct-offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout">
    <div class="offcanvas-header"><h5 class="offcanvas-title">Settings</h5>
        <button type="button" class="btn btn-icon btn-link-danger ms-auto" data-bs-dismiss="offcanvas"
                aria-label="Close"><i class="ti ti-x"></i></button>
    </div>
    <div class="pct-body customizer-body">
        <div class="offcanvas-body py-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="pc-dark"><h6 class="mb-1">Theme Mode</h6>
                        <p class="text-muted text-sm">Choose light or dark mode or Auto</p>
                        <div class="row theme-color theme-layout">
                            <div class="col-4">
                                <div class="d-grid">
                                    <button class="preset-btn btn active" data-value="true"
                                            onclick="layout_change('light');" data-bs-toggle="tooltip" title="Light">
                                        <svg class="pc-icon text-warning">
                                            <use xlink:href="#custom-sun-1"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="false" onclick="layout_change('dark');"
                                            data-bs-toggle="tooltip" title="Dark">
                                        <svg class="pc-icon">
                                            <use xlink:href="#custom-moon"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="default"
                                            onclick="layout_change_default();" data-bs-toggle="tooltip"
                                            title="Automatically sets the theme based on user's operating system's color scheme.">
                                        <span class="pc-lay-icon d-flex align-items-center justify-content-center"><i
                                                class="ph-duotone ph-cpu"></i></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item"><h6 class="mb-1">Theme Contrast</h6>
                    <p class="text-muted text-sm">Choose theme contrast</p>
                    <div class="row theme-contrast">
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn" data-value="true"
                                        onclick="layout_theme_contrast_change('true');" data-bs-toggle="tooltip"
                                        title="True">
                                    <svg class="pc-icon">
                                        <use xlink:href="#custom-mask"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn active" data-value="false"
                                        onclick="layout_theme_contrast_change('false');" data-bs-toggle="tooltip"
                                        title="False">
                                    <svg class="pc-icon">
                                        <use xlink:href="#custom-mask-1-outline"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item"><h6 class="mb-1">Custom Theme</h6>
                    <p class="text-muted text-sm">Choose your primary theme color</p>
                    <div class="theme-color preset-color"><a href="#" data-bs-toggle="tooltip"
                                                             title="Blue" class="active" data-value="preset-1"><i
                                class="ti ti-checks"></i></a> <a href="#" data-bs-toggle="tooltip"
                                                                 title="Indigo" data-value="preset-2"><i
                                class="ti ti-checks"></i></a> <a href="#" data-bs-toggle="tooltip"
                                                                 title="Purple" data-value="preset-3"><i
                                class="ti ti-checks"></i></a> <a href="#" data-bs-toggle="tooltip"
                                                                 title="Pink" data-value="preset-4"><i
                                class="ti ti-checks"></i></a> <a href="#" data-bs-toggle="tooltip"
                                                                 title="Red" data-value="preset-5"><i
                                class="ti ti-checks"></i></a> <a href="#" data-bs-toggle="tooltip"
                                                                 title="Orange" data-value="preset-6"><i
                                class="ti ti-checks"></i></a> <a href="#" data-bs-toggle="tooltip"
                                                                 title="Yellow" data-value="preset-7"><i
                                class="ti ti-checks"></i></a> <a href="#" data-bs-toggle="tooltip"
                                                                 title="Green" data-value="preset-8"><i
                                class="ti ti-checks"></i></a> <a href="#" data-bs-toggle="tooltip"
                                                                 title="Teal" data-value="preset-9"><i
                                class="ti ti-checks"></i></a> <a href="#" data-bs-toggle="tooltip"
                                                                 title="Cyan" data-value="preset-10"><i
                                class="ti ti-checks"></i></a></div>
                </li>
                <li class="list-group-item"><h6 class="mb-1">Theme layout</h6>
                    <p class="text-muted text-sm">Choose your layout</p>
                    <div class="theme-main-layout d-flex align-center gap-1 w-100"><a href="#"
                                                                                      data-bs-toggle="tooltip"
                                                                                      title="Vertical" class="active"
                                                                                      data-value="vertical"><img
                                src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/caption-on.svg" alt="img"
                                class="img-fluid"> </a><a
                            href="#" data-bs-toggle="tooltip" title="Horizontal"
                            data-value="horizontal"><img
                                src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/horizontal.svg" alt="img"
                                class="img-fluid"> </a><a href="#"
                                                          data-bs-toggle="tooltip"
                                                          title="Color Header"
                                                          data-value="color-header"><img
                                src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/color-header.svg" alt="img"
                                class="img-fluid"> </a><a
                            href="#" data-bs-toggle="tooltip" title="Compact"
                            data-value="compact"><img src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/compact.svg"
                                                      alt="img"
                                                      class="img-fluid"> </a><a href="#"
                                                                                data-bs-toggle="tooltip" title="Tab"
                                                                                data-value="tab"><img
                                src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/tab.svg" alt="img" class="img-fluid"></a>
                    </div>
                </li>
                <li class="list-group-item"><h6 class="mb-1">Sidebar Caption</h6>
                    <p class="text-muted text-sm">Sidebar Caption Hide/Show</p>
                    <div class="row theme-color theme-nav-caption">
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn-img btn active" data-value="true"
                                        onclick="layout_caption_change('true');" data-bs-toggle="tooltip"
                                        title="Caption Show"><img
                                        src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/caption-on.svg"
                                        alt="img" class="img-fluid"></button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn-img btn" data-value="false"
                                        onclick="layout_caption_change('false');" data-bs-toggle="tooltip"
                                        title="Caption Hide"><img
                                        src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/caption-off.svg"
                                        alt="img" class="img-fluid"></button>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="pc-rtl"><h6 class="mb-1">Theme Layout</h6>
                        <p class="text-muted text-sm">LTR/RTL</p>
                        <div class="row theme-color theme-direction">
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn-img btn active" data-value="false"
                                            onclick="layout_rtl_change('false');" data-bs-toggle="tooltip" title="LTR">
                                        <img src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/ltr.svg" alt="img"
                                             class="img-fluid">
                                    </button>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn-img btn" data-value="true"
                                            onclick="layout_rtl_change('true');" data-bs-toggle="tooltip" title="RTL">
                                        <img src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/rtl.svg" alt="img"
                                             class="img-fluid">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item pc-box-width">
                    <div class="pc-container-width"><h6 class="mb-1">Layout Width</h6>
                        <p class="text-muted text-sm">Choose Full or Container Layout</p>
                        <div class="row theme-color theme-container">
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn-img btn active" data-value="false"
                                            onclick="change_box_container('false')" data-bs-toggle="tooltip"
                                            title="Full Width"><img
                                            src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/full.svg" alt="img"
                                            class="img-fluid"></button>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn-img btn" data-value="true"
                                            onclick="change_box_container('true')" data-bs-toggle="tooltip"
                                            title="Fixed Width"><img
                                            src="<?php echo e(url('')); ?>/assets/assets2/images/customizer/fixed.svg"
                                            alt="img" class="img-fluid"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-grid">
                        <button class="btn btn-light-danger" id="layoutreset">Reset Layout</button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
</body><!-- [Body] end --></html>



<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/fadded/core/resources/views/templates/basic/layouts/main2.blade.php ENDPATH**/ ?>