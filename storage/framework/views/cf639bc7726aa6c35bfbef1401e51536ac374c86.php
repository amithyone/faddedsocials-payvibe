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
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/style2.css">

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
                                <a href="/user/dashboard"><h6 class="mb-0"><?php echo e(Auth::user()->email); ?></h6></a>

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
                            <a href="https://linktr.ee/faddedsocials" class="pc-link">
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
                            <a href="https://faddedsms.com/us" class="pc-link">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-sms"></use>
                                </svg>
                            </span>
                                <span class="pc-mtext"> Verify SMS</span>
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
                            <a href="/user/profile" class="pc-link">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-user-bold"></use>
                                </svg>
                            </span>
                                <span class="pc-mtext"> My Profile</span>
                            </a>

                        </li>


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




<?php echo $__env->yieldContent('content'); ?>

<style>
    .float {
        position: fixed; /* Position it fixed to ensure it's always in view */
        bottom: 20px; /* Adjust the distance from the bottom */
        left: 200px; /* Adjust the distance from the right */
        background-color: rgba(255, 0, 0, 0);
        border: none; /* Remove default border */
        padding: 0; /* Remove padding */
        z-index: 1000; /* Make sure it's above other elements */
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out; /* Smooth transition for floating effect */
    }

    /* Floating effect */
    .float:hover {
        transform: translateY(-10px); /* Move it up when hovered */
    }

    /* Optional: Add a smooth continuous floating effect */
    @keyframes float {
        0% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0); }
    }

    .float {
        animation: float 5s ease-in-out infinite; /* Apply the floating animation */
    }

</style>

<a href="https://whatsapp.com/channel/0029VbAcPAVFMqrbCK1l0T1X" class="float" target="_blank">
    <svg width="126" height="40" viewBox="0 0 126 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="109" cy="15" rx="17" ry="15" fill="#D9D9D9"/>
        <path d="M110.043 22.465H109.723C106.702 22.465 104.138 21.6831 102.032 20.1192C99.9256 18.5554 98.8724 16.652 98.8724 14.4089C98.8724 12.1658 99.9256 10.2624 102.032 8.69853C104.138 7.1347 106.702 6.35278 109.723 6.35278C111.234 6.35278 112.644 6.56193 113.953 6.98021C115.262 7.3985 116.411 7.97506 117.4 8.7099C118.389 9.44475 119.165 10.2977 119.728 11.2689C120.292 12.2401 120.574 13.2867 120.575 14.4089C120.575 16.5256 119.771 18.4922 118.164 20.3088C116.557 22.1254 114.531 23.547 112.085 24.5738C111.872 24.6528 111.66 24.6964 111.447 24.7046C111.234 24.7128 111.043 24.6771 110.872 24.5975C110.702 24.5179 110.553 24.4152 110.426 24.2894C110.298 24.1637 110.223 24.0136 110.202 23.8393L110.043 22.465ZM112.915 21.8489C114.426 20.9011 115.655 19.7916 116.602 18.5203C117.549 17.249 118.022 15.8786 118.021 14.4089C118.021 12.6871 117.218 11.23 115.611 10.0377C114.004 8.84543 112.042 8.24897 109.723 8.24834C107.405 8.2477 105.443 8.84417 103.836 10.0377C102.229 11.2313 101.426 12.6884 101.426 14.4089C101.426 16.1294 102.229 17.5868 103.836 18.781C105.443 19.9752 107.405 20.5713 109.723 20.5694H112.915V21.8489ZM109.692 19.598C110.053 19.598 110.362 19.5032 110.617 19.3136C110.872 19.1241 111 18.895 111 18.6265C111 18.3579 110.872 18.1289 110.617 17.9393C110.362 17.7498 110.053 17.655 109.692 17.655C109.33 17.655 109.021 17.7498 108.766 17.9393C108.511 18.1289 108.383 18.3579 108.383 18.6265C108.383 18.895 108.511 19.1241 108.766 19.3136C109.021 19.5032 109.33 19.598 109.692 19.598ZM106.915 12.3949C107.149 12.4738 107.383 12.4779 107.617 12.4072C107.851 12.3364 108.043 12.2217 108.192 12.0631C108.383 11.8736 108.606 11.7273 108.862 11.6243C109.117 11.5213 109.404 11.4701 109.723 11.4708C110.234 11.4708 110.649 11.5772 110.968 11.7902C111.287 12.0031 111.447 12.2758 111.447 12.6081C111.447 12.8135 111.367 13.0188 111.208 13.2242C111.049 13.4295 110.767 13.6823 110.362 13.9824C109.83 14.3299 109.436 14.6578 109.181 14.9662C108.926 15.2745 108.798 15.5863 108.798 15.9016C108.798 16.0912 108.889 16.2533 109.07 16.3878C109.251 16.5224 109.469 16.5894 109.723 16.5888C109.978 16.5881 110.191 16.5171 110.362 16.3755C110.533 16.234 110.66 16.0681 110.745 15.8779C110.851 15.6094 111.043 15.3646 111.319 15.1434C111.596 14.9223 111.851 14.7248 112.085 14.551C112.532 14.2193 112.867 13.8876 113.091 13.5559C113.315 13.2242 113.426 12.8924 113.426 12.5607C113.426 11.8341 113.09 11.2496 112.42 10.8073C111.749 10.365 110.85 10.1439 109.723 10.1439C109.043 10.1439 108.415 10.2665 107.84 10.5116C107.266 10.7568 106.798 11.0923 106.436 11.5182C106.309 11.6919 106.292 11.8619 106.388 12.0281C106.483 12.1942 106.659 12.3165 106.915 12.3949Z" fill="#01C69C"/>
        <path d="M8.3 23.18C6.74 23.18 5.42667 22.8933 4.36 22.32C3.30667 21.7333 2.51333 20.9 1.98 19.82C1.44667 18.74 1.18 17.4533 1.18 15.96C1.18 14.4667 1.46 13.18 2.02 12.1C2.59333 11.02 3.40667 10.1867 4.46 9.6C5.52667 9.01333 6.80667 8.72 8.3 8.72C9.32667 8.72 10.22 8.86667 10.98 9.16C11.74 9.44 12.42 9.85333 13.02 10.4L12.28 12.02C11.8533 11.66 11.44 11.3733 11.04 11.16C10.64 10.9467 10.2267 10.7933 9.8 10.7C9.37333 10.5933 8.87333 10.54 8.3 10.54C6.68667 10.54 5.46 11.0133 4.62 11.96C3.78 12.9067 3.36 14.2467 3.36 15.98C3.36 17.74 3.76 19.08 4.56 20C5.37333 20.92 6.64667 21.38 8.38 21.38C8.98 21.38 9.57333 21.3267 10.16 21.22C10.7467 21.1 11.3133 20.9267 11.86 20.7L11.46 21.68V17.06H7.98V15.5H13.2V22.16C12.5867 22.4667 11.8333 22.7133 10.94 22.9C10.06 23.0867 9.18 23.18 8.3 23.18ZM21.647 23.18C20.0204 23.18 18.7404 22.72 17.807 21.8C16.8737 20.88 16.407 19.6067 16.407 17.98C16.407 16.9267 16.607 16.0067 17.007 15.22C17.4204 14.4333 17.987 13.8267 18.707 13.4C19.4404 12.96 20.287 12.74 21.247 12.74C22.1937 12.74 22.987 12.94 23.627 13.34C24.267 13.74 24.7537 14.3067 25.087 15.04C25.4204 15.76 25.587 16.6133 25.587 17.6V18.24H17.987V17.08H24.207L23.867 17.34C23.867 16.34 23.6404 15.5667 23.187 15.02C22.747 14.46 22.107 14.18 21.267 14.18C20.3337 14.18 19.6137 14.5067 19.107 15.16C18.6004 15.8 18.347 16.6867 18.347 17.82V18.02C18.347 19.2067 18.6337 20.1 19.207 20.7C19.7937 21.3 20.6204 21.6 21.687 21.6C22.2737 21.6 22.8204 21.52 23.327 21.36C23.847 21.1867 24.3404 20.9067 24.807 20.52L25.467 21.9C25.0004 22.3133 24.4337 22.6333 23.767 22.86C23.1004 23.0733 22.3937 23.18 21.647 23.18ZM32.6055 23.18C31.5121 23.18 30.6855 22.8867 30.1255 22.3C29.5655 21.7 29.2855 20.8067 29.2855 19.62V14.48H27.3255V12.92H29.2855V10.26L31.3055 9.72V12.92H34.1055V14.48H31.3055V19.44C31.3055 20.1867 31.4388 20.72 31.7055 21.04C31.9721 21.36 32.3588 21.52 32.8655 21.52C33.1188 21.52 33.3388 21.5 33.5255 21.46C33.7255 21.4067 33.9055 21.3467 34.0655 21.28V22.92C33.8655 23 33.6321 23.06 33.3655 23.1C33.0988 23.1533 32.8455 23.18 32.6055 23.18ZM43.0631 23V8.9H45.1431V14.98H53.0631V8.9H55.1431V23H53.0631V16.72H45.1431V23H43.0631ZM63.7338 23.18C62.1071 23.18 60.8271 22.72 59.8938 21.8C58.9604 20.88 58.4938 19.6067 58.4938 17.98C58.4938 16.9267 58.6938 16.0067 59.0938 15.22C59.5071 14.4333 60.0738 13.8267 60.7938 13.4C61.5271 12.96 62.3738 12.74 63.3338 12.74C64.2804 12.74 65.0738 12.94 65.7138 13.34C66.3538 13.74 66.8404 14.3067 67.1738 15.04C67.5071 15.76 67.6738 16.6133 67.6738 17.6V18.24H60.0738V17.08H66.2938L65.9538 17.34C65.9538 16.34 65.7271 15.5667 65.2738 15.02C64.8338 14.46 64.1938 14.18 63.3538 14.18C62.4204 14.18 61.7004 14.5067 61.1938 15.16C60.6871 15.8 60.4338 16.6867 60.4338 17.82V18.02C60.4338 19.2067 60.7204 20.1 61.2938 20.7C61.8804 21.3 62.7071 21.6 63.7738 21.6C64.3604 21.6 64.9071 21.52 65.4138 21.36C65.9338 21.1867 66.4271 20.9067 66.8938 20.52L67.5538 21.9C67.0871 22.3133 66.5204 22.6333 65.8538 22.86C65.1871 23.0733 64.4804 23.18 63.7338 23.18ZM70.7917 23V8.28H72.8117V23H70.7917ZM76.5722 27.32V15.34C76.5722 14.9533 76.5522 14.56 76.5122 14.16C76.4855 13.7467 76.4522 13.3333 76.4122 12.92H78.3722L78.5722 15.2H78.3522C78.5655 14.44 78.9922 13.84 79.6322 13.4C80.2855 12.96 81.0589 12.74 81.9522 12.74C82.8455 12.74 83.6255 12.9533 84.2922 13.38C84.9589 13.7933 85.4722 14.3867 85.8322 15.16C86.2055 15.9333 86.3922 16.8667 86.3922 17.96C86.3922 19.04 86.2055 19.9733 85.8322 20.76C85.4722 21.5467 84.9589 22.1467 84.2922 22.56C83.6255 22.9733 82.8455 23.18 81.9522 23.18C81.0722 23.18 80.3055 22.9667 79.6522 22.54C79.0122 22.1 78.5855 21.5067 78.3722 20.76H78.5922V27.32H76.5722ZM81.4522 21.6C82.3322 21.6 83.0322 21.2933 83.5522 20.68C84.0855 20.0667 84.3522 19.16 84.3522 17.96C84.3522 16.76 84.0855 15.86 83.5522 15.26C83.0322 14.6467 82.3322 14.34 81.4522 14.34C80.5722 14.34 79.8655 14.6467 79.3322 15.26C78.8122 15.86 78.5522 16.76 78.5522 17.96C78.5522 19.16 78.8122 20.0667 79.3322 20.68C79.8655 21.2933 80.5722 21.6 81.4522 21.6Z" fill="#01C69C"/>
    </svg>

</a>

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



<?php /**PATH /home/wolrdhome/public_html/core/resources/views/templates/basic/layouts/main2.blade.php ENDPATH**/ ?>