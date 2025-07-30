<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fadded Social | Welcome">
    <!-- Title-->
    <title>Fadded Social | Welcome</title>
    <!-- Favicon-->
    <link rel="shortcut icon" href="<?php echo e(url('')); ?>/assets/front/img/core-img/favicon.ico" type="image/x-icon">
    <!-- All CSS Stylesheet-->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/front/css/all-css-libraries.css">
    <!-- Core Stylesheet-->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/front/style.css">
</head>
<body>
<!-- Preloader-->
<div class="preloader" id="preloader">
    <div class="spinner-grow text-light" role="status"><span class="visually-hidden">Loading...</span></div>
</div>
<!-- Header Area-->
<header class="header-area">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Navbar Brand --><a class="navbar-brand" href="index.html"><img src="<?php echo e(url('')); ?>/assets/assets2/images/logo-white.svg"></a>
            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#saasboxNav" aria-controls="saasboxNav" aria-expanded="false" aria-label="Toggle navigation"><i class="bi bi-grid"></i></button>
            <!-- Navbar Nav -->
            <div class="collapse navbar-collapse" id="saasboxNav">

                <?php if(auth()->guard()->check()): ?>
                    <div class="ms-auto mb-3 mb-lg-0"><a style="background: #f9f9fc; color: #4be0bb" class="btn  btn-sm" href="/products">Dashboard</a></div>

                <?php else: ?>
                    <div class="ms-auto mb-3 mb-lg-0"><a style="background: #4be0bb; color: #f9f9fc" class="btn  btn-sm" href="/user/login">Log In</a></div>
                <?php endif; ?>
                <!-- Login Button -->
            </div>
        </div>
    </nav>
</header>
<!-- Welcome Area -->
<div class="welcome-area bg-gradient">
    <!-- Background Shape -->
    <div class="background-shape">
        <div class="circle1 wow fadeInRightBig" data-wow-duration="4000ms"></div>
        <div class="circle2 wow fadeInRightBig" data-wow-duration="4000ms"></div>
        <div class="circle3 wow fadeInRightBig" data-wow-duration="4000ms"></div>
        <div class="circle4 wow fadeInRightBig" data-wow-duration="4000ms"></div>
    </div>
    <!-- Background Animation -->
    <div class="background-animation">
        <div class="item1"></div>
        <div class="item2"></div>
        <div class="item3"></div>
        <div class="item4"></div>
        <div class="item5"></div>
    </div>
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-between">
            <!-- Welcome Content -->
            <div class="col-12 col-sm-10 col-md-6">
                <div class="welcome-content">
                    <ul class="mb-0 ps-1 d-flex align-items-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="200ms">
                        <li>Best</li>
                        <li>Long Lasting</li>
                        <li>Strong</li>
                    </ul>
                    <h2 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="300ms">Marketplace <br> for Unique Accounts</h2>
                    <p class="mb-4 wow fadeInUp" data-wow-duration="1s" data-wow-delay="400ms">One stop shop for all social accounts.</p>
                    <div class="hero-btn-group wow fadeInUp" data-wow-duration="1s" data-wow-delay="500ms">
                        <?php if(auth()->guard()->check()): ?>
                            <a class="btn btn-warning mt-3 me-3" href="/products">Shop now</a>
                        <?php else: ?>
                            <a class="btn btn-warning mt-3 me-3" href="/user/register">Register</a>
                            <a class="btn btn-light mt-3" href="/user/login">Login</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <!-- Welcome Thumb -->
            <div class="col-12 col-sm-9 col-md-6">
                <div class="welcome-thumb ms-xl-5 wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s"><img src="<?php echo e(url('')); ?>/assets/front/img/illustrator/hero-3.png" alt=""></div>
            </div>
        </div>
    </div>
</div>
<div class="mb-120 d-block"></div>
<!-- About Area -->
<div class="about-area">
    <div class="container">
        <div class="row align-items-center justify-content-between g-5">
            <!-- About Content Area -->
            <div class="col-12 col-lg-7">
                <div class="about-content">
                    <div class="row g-4 g-xl-5">
                        <!-- Single About Area -->
                        <div class="col-12 col-sm-6">
                            <div class="card about-card">
                                <div class="card-body">
                                    <div class="icon">
                                        <svg class="bi bi-app-indicator" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1H5.5z"></path>
                                            <path d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
                                        </svg>
                                    </div>
                                    <h5 class="mb-3">Professional service</h5>
                                    <p class="mb-0">Our team is always ready to assist you with selection, answer your questions, and provide you with all the necessary information about each of our items.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Single About Area -->
                        <div class="col-12 col-sm-6">
                            <div class="card about-card">
                                <div class="card-body">
                                    <div class="icon bg-success">
                                        <svg class="bi bi-lightbulb" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13a.5.5 0 0 1 0 1 .5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1 0-1 .5.5 0 0 1 0-1 .5.5 0 0 1-.46-.302l-.761-1.77a1.964 1.964 0 0 0-.453-.618A5.984 5.984 0 0 1 2 6zm6-5a5 5 0 0 0-3.479 8.592c.263.254.514.564.676.941L5.83 12h4.342l.632-1.467c.162-.377.413-.687.676-.941A5 5 0 0 0 8 1z"></path>
                                        </svg>
                                    </div>
                                    <h5 class="mb-3">Wide range of options</h5>
                                    <p class="mb-0">Regardless of your needs, we offer a variety of accounts with different numbers of followers and account ages. You can choose what best suits your goals.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Single About Area -->
                        <div class="col-12 col-sm-6">
                            <div class="card about-card">
                                <div class="card-body">
                                    <div class="icon bg-warning">
                                        <svg class="bi bi-bar-chart" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5v12h-2V2h2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z"></path>
                                        </svg>
                                    </div>
                                    <h5 class="mb-3">What sets us apart?</h5>
                                    <p class="mb-0">Quality and verified accounts: We guarantee that every account offered in our store has undergone thorough validation. We are confident in the quality of what we offer and ensure the highest quality of our accounts.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Single About Area -->
                        <div class="col-12 col-sm-6">
                            <div class="card about-card border-0 bg-primary">
                                <div class="card-body">
                                    <div class="icon bg-info">
                                        <svg class="bi bi-basket" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"></path>
                                        </svg>
                                    </div>
                                    <h5 class="mb-3 text-white">Secure Transactions</h5>
                                    <p class="mb-0 text-white-50">Shop with confidence knowing that our platform prioritizes security. Your transactions are protected, and we facilitate a secure environment for both buyers and sellers.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- About Text Area -->
            <div class="col-12 col-sm-10 col-md-9 col-lg-5">
                <div class="section-heading mb-0 ms-xl-5">
                    <h6>At Fadded Socials</h6>
                    <h2>We focus on quality socials accounts.</h2>
                    <p>At Log marketplace, we specialize in offering a wide selection of accounts that can be useful for various purposes, whether it's for marketing, brand promotion, newsletters, and much more.</p>
                    <div class="about-btn-group"><a class="btn btn-warning mt-5" href="/products">Shop now</a></div>
            </div>
        </div>
    </div>
</div>
<div class="mb-120 d-block"></div>



<!-- Features Area -->
<div class="feature-area bg-primary pt-120 pb-120">
    <!-- Background Shape -->
    <div class="background-shape wow fadeInLeftBig" data-wow-duration="4000ms"></div>
    <!-- Curve Top Shape-->
    <div class="curve-shape-top"></div>
    <!-- Curve Bottom Shape-->
    <div class="curve-shape-bottom"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-9 col-lg-8">
                <div class="section-heading text-center white">
                    <h2>Explore our solutions</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row g-4 justify-content-center">
            <!-- Single Feature Area -->
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                <div class="card feature-card">
                    <div class="card-body d-flex align-items-center"><i class="bg-primary bi bi-facebook"></i>
                        <div class="fea-text">
                            <h6>Facebook</h6><span>Quality Facebook service</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single Feature Area -->
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                <div class="card feature-card">
                    <div class="card-body d-flex align-items-center"><i class="bg-success bi bi-instagram"></i>
                        <div class="fea-text">
                            <h6>Instagram</h6><span>Quality Instagram service</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single Feature Area -->
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                <div class="card feature-card">
                    <div class="card-body d-flex align-items-center"><i class="bg-info bi bi-tiktok"></i>
                        <div class="fea-text">
                            <h6>TikTok</h6><span>Quality Tiktok service</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single Feature Area -->
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                <div class="card feature-card">
                    <div class="card-body d-flex align-items-center"><i class="bg-danger bi bi-twitter"></i>
                        <div class="fea-text">
                            <h6>Twitter</h6><span>Quality Twitter service</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single Feature Area -->
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                <div class="card feature-card">
                    <div class="card-body d-flex align-items-center"><i class="bg-warning bi bi-snapchat"></i>
                        <div class="fea-text">
                            <h6>Snapchat</h6><span>Quality Snapchat service</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single Feature Area -->
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                <div class="card feature-card">
                    <div class="card-body d-flex align-items-center"><i class="bg-success bi bi-twitch"></i>
                        <div class="fea-text">
                            <h6>Twitch</h6><span>Quality Twitch service</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single Feature Area -->

            <div class="col-12 col-sm-10 col-md-12 col-lg-12">
                    <div class="d-flex justify-content-center">
                        <a href="/products" class="btn btn-warning ">

                            Shop Now
                        </a>
                    </div>
            </div>

            <!-- Single Feature Area -->

            <!-- Single Feature Area -->

        </div>
    </div>
</div>


<!-- Footer Area-->
<footer class="footer-area">

    <div class="container">
        <div class="copywrite-wrapper mt-5 rounded-3 d-lg-flex align-items-lg-center justify-content-lg-center">
            <div class="copywrite-text text-center text-lg-start mb-3 mb-lg-0 me-lg-4">
                <p class="mb-0">2024 &copy; All rights reserved by <a href="#" target="_blank">Fadedsocials</a></p>
            </div>

        </div>
    </div>
</footer>
<!-- Scroll To Top  -->
<div id="scrollTopButton"><i class="bi bi-arrow-up-short"></i></div>
<div class="mb-120 d-block"></div>
<!-- Cookie Alert Area-->
<div class="cookiealert">
    <p>This site uses cookies. We use cookies to ensure you get the best experience on our website. For details, please check our <a href="#" target="_blank"> Privacy Policy.</a></p>
    <button class="btn btn-primary btn-sm acceptcookies" type="button" aria-label="Close">I agree &amp; Close</button>
</div>
<!-- All JavaScript Files-->
<script src="<?php echo e(url('')); ?>/assets/front/js/all-js-libraries.js"></script>
<script src="<?php echo e(url('')); ?>/assets/front/js/active.js"></script>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/fadded/core/resources/views/home.blade.php ENDPATH**/ ?>