<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Mobilekit Mobile UI Kit</title>
    @stack('css_vendor')
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('mobile-template') }}/assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('mobile-template') }}/assets/img/icon/192x192.png">
    <link rel="stylesheet" href="{{ asset('mobile-template') }}/assets/css/style.css">
    <link rel="manifest" href="__manifest.json">

    <!-- SweetAler2 -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/plugins/sweetalert2/sweetalert2.min.css') }}">

    @stack('css')
</head>

<body style="background-color:#e9ecef;">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    @yield('header')

    <!-- App Capsule -->
    @yield('content')
    <!-- * App Capsule -->

    <!-- App Bottom Menu -->
    @include('partials.appBottomMenu')
    <!-- * App Bottom Menu -->


    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="{{ asset('mobile-template') }}/assets/js/lib/jquery-3.4.1.min.js"></script>
    <!-- jQuery -->
    <script src="{{ asset('/AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('mobile-template') }}/assets/js/lib/popper.min.js"></script>
    <script src="{{ asset('mobile-template') }}/assets/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('mobile-template') }}/assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- jQuery Circle Progress -->
    <script src="{{ asset('mobile-template') }}/assets/js/plugins/jquery-circle-progress/circle-progress.min.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <!-- Base Js File -->
    <script src="{{ asset('mobile-template') }}/assets/js/base.js"></script>
    <!-- sweetalert2 -->
    <script src="{{ asset('/AdminLTE/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('/js/custom.js') }}"></script>
    @stack('scripts_vendor')

    <script>
        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("chartdiv", am4charts.PieChart3D);
            chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

            chart.legend = new am4charts.Legend();

            chart.data = [{
                    country: "Hadir",
                    litres: 501.9
                },
                {
                    country: "Sakit",
                    litres: 301.9
                },
                {
                    country: "Izin",
                    litres: 201.1
                },
                {
                    country: "Terlambat",
                    litres: 165.8
                },
            ];



            var series = chart.series.push(new am4charts.PieSeries3D());
            series.dataFields.value = "litres";
            series.dataFields.category = "country";
            series.alignLabels = false;
            series.labels.template.text = "{value.percent.formatNumber('#.0')}%";
            series.labels.template.radius = am4core.percent(-40);
            series.labels.template.fill = am4core.color("white");
            series.colors.list = [
                am4core.color("#1171ba"),
                am4core.color("#fca903"),
                am4core.color("#37db63"),
                am4core.color("#ba113b"),
            ];
        }); // end am4core.ready()
    </script>

    @stack('scripts')

</body>

</html>
