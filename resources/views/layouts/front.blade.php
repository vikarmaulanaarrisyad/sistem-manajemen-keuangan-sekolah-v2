<!doctype html>
<html lang="en">

<head>

    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--====== Title ======-->
    <title>{{ $sekolah->nama }}</title>

    <!--====== Favicon Icon ======-->
    {{--  <link rel="shortcut icon" href="{{ Storage::url() }}" type="image/png">  --}}
    <link rel="icon" href="{{ Storage::url($sekolah->logo ?? '') }}" type="image/*">

    <!--====== Slick css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/slick.css">

    <!--====== Animate css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/animate.css">

    <!--====== Nice Select css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/nice-select.css">

    <!--====== Nice Number css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/jquery.nice-number.min.css">

    <!--====== Magnific Popup css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/magnific-popup.css">

    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/bootstrap.min.css">

    <!--====== Fontawesome css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/font-awesome.min.css">

    <!--====== Default css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/default.css">

    <!--====== Style css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!--====== Responsive css ======-->
    <link rel="stylesheet" href="{{ asset('education') }}/css/responsive.css">
    <style>
        img.logo-header1 {
            max-width: 100% !important;
            /* Tidak lebih dari container */
            height: auto;
        }

        .header-logo-support {
            border-bottom: 1px solid #cecece;
            background-image: url('{{ Storage::url($aplikasi->logo_header) }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            /* Menyesuaikan tinggi minimum */
        }

        /* 📱 Smartphone Kecil (≤480px) */
        @media screen and (max-width: 480px) {
            .header-logo-support {
                background-size: contain !important;
                background-position: center;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                min-height: 89px !important;
                /* Beri tinggi agar terlihat */
                display: block !important;
                /* Pastikan elemen tetap tampil */
            }
        }

        /* 📱 Smartphone Kecil (≤480px) */
        @media (max-width: 480px) {
            .header-logo-support {
                min-height: 49px !important;
            }

            .pb-50 {
                padding-bottom: 50px !important;
            }
        }

        /* 📱 Smartphone Sedang (481px - 767px) */
        @media (max-width: 767px) {
            .header-logo-support {
                min-height: 49px !important;
            }

            .pb-50 {
                padding-bottom: 60px !important;
            }
        }

        /* 📱 Tablet (768px - 1024px) */
        @media (max-width: 1024px) {
            .header-logo-support {
                min-height: 49px !important;
            }

            .pb-50 {
                padding-bottom: 70px !important;
            }
        }

        /* 💻 Laptop Kecil (11-13 inch) (1025px - 1366px) */
        @media (max-width: 1366px) {
            .header-logo-support {
                min-height: 49px !important;
            }
        }

        /* 💻 Laptop Standar (14-15 inch) (1367px - 1600px) */
        @media (max-width: 1600px) {
            .header-logo-support {
                min-height: 49px !important;
            }

            .pb-50 {
                padding-bottom: 90px !important;
            }
        }

        /* 🖥️ Laptop Besar (16-17 inch) (1601px - 1920px) */
        @media (max-width: 1920px) {
            .header-logo-support {
                min-height: 49px !important;
            }

            .pb-50 {
                padding-bottom: 100px !important;
            }
        }

        /* 🖥️ Ultra-Wide Monitor (> 1920px) */
        @media (min-width: 1921px) {
            .header-logo-support {
                min-height: 49px !important;
            }

            .pb-50 {
                padding-bottom: 110px !important;
            }
        }
    </style>
    @stack('css')

</head>

<body>

    <!--====== PRELOADER PART START ======-->

    <div class="preloader">
        <div class="loader rubix-cube">
            <div class="layer layer-1"></div>
            <div class="layer layer-2"></div>
            <div class="layer layer-3 color-1"></div>
            <div class="layer layer-4"></div>
            <div class="layer layer-5"></div>
            <div class="layer layer-6"></div>
            <div class="layer layer-7"></div>
            <div class="layer layer-8"></div>
        </div>
    </div>

    <!--====== PRELOADER PART START ======-->

    <!--====== HEADER PART START ======-->

    <header id="header-part">

        @include('layouts.frontend.header-top')
        <!-- header top -->

        @include('layouts.frontend.header-logo')
        <!-- header logo support -->

        @include('layouts.frontend.navigation')

    </header>

    <!--====== HEADER PART ENDS ======-->
    @yield('content')

    <!--====== PATNAR LOGO PART ENDS ======-->

    <!--====== FOOTER PART START ======-->

    @include('layouts.frontend.footer-part')

    <!--====== FOOTER PART ENDS ======-->

    <!--====== BACK TO TP PART START ======-->

    <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!--====== BACK TO TP PART ENDS ======-->


    <!--====== jquery js ======-->
    <script src="{{ asset('education') }}/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="{{ asset('education') }}/js/vendor/jquery-1.12.4.min.js"></script>

    <!--====== Bootstrap js ======-->
    <script src="{{ asset('education') }}/js/bootstrap.min.js"></script>

    <!--====== Slick js ======-->
    <script src="{{ asset('education') }}/js/slick.min.js"></script>

    <!--====== Magnific Popup js ======-->
    <script src="{{ asset('education') }}/js/jquery.magnific-popup.min.js"></script>

    <!--====== Counter Up js ======-->
    <script src="{{ asset('education') }}/js/waypoints.min.js"></script>
    <script src="{{ asset('education') }}/js/jquery.counterup.min.js"></script>

    <!--====== Nice Select js ======-->
    <script src="{{ asset('education') }}/js/jquery.nice-select.min.js"></script>

    <!--====== Nice Number js ======-->
    <script src="{{ asset('education') }}/js/jquery.nice-number.min.js"></script>

    <!--====== Count Down js ======-->
    <script src="{{ asset('education') }}/js/jquery.countdown.min.js"></script>

    <!--====== Validator js ======-->
    <script src="{{ asset('education') }}/js/validator.min.js"></script>

    <!--====== Ajax Contact js ======-->
    <script src="{{ asset('education') }}/js/ajax-contact.js"></script>

    <!--====== Main js ======-->
    <script src="{{ asset('education') }}/js/main.js"></script>

    <!--====== Map js ======-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDC3Ip9iVC0nIxC6V14CKLQ1HZNF_65qEQ"></script>
    <script src="{{ asset('education') }}/js/map-script.js"></script>

    @stack('scripts')

</body>

</html>
