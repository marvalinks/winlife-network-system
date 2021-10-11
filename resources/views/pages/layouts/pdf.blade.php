<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>{{env('APP_NAME')}} Dashboard</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link href="/backend/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/backend/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link href="/backend/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link href="/backend/css/style.css" rel="stylesheet" />
        <link href="/backend/css/style_responsive.css" rel="stylesheet" />
        <link href="/backend/css/style_default.css" rel="stylesheet" id="style_color" />

        <link href="/backend/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="/backend/assets/uniform/css/uniform.default.css" />
        <link href="/backend/assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
        <link href="/backend/assets/jqvmap/jqvmap/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />

        @yield('links')
        <link rel="stylesheet" href="/css/override.css">
        <style>
            .fixed-top #container {
                margin-top: 0px;
            }
            #main-content{
                margin-left: 0px;
                background: white!important;
            }
        </style>

    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="fixed-top">

        <div id="container" class="row-fluid">
            <div id="main-content">
                <!-- BEGIN PAGE CONTAINER-->

                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                    <div class="alert alert-{{$msg}}">
                        <button class="close" data-dismiss="alert">×</button>
                        {{ Session::get('alert-' . $msg) }}
                    </div>
                    @endif
                @endforeach

                @yield('content')
                <!-- END PAGE CONTAINER-->
            </div>
            <!-- END PAGE -->
        </div>

        <!-- BEGIN JAVASCRIPTS -->
        <!-- Load javascripts at bottom, this will reduce page load time -->
        <script src="/backend/js/jquery-1.8.3.min.js"></script>
        <script src="/backend/assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="/backend/assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="/backend/assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
        <script src="/backend/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="/backend/js/jquery.blockui.js"></script>
        <script src="/backend/js/jquery.cookie.js"></script>
        <!-- ie8 fixes -->
        <!--[if lt IE 9]>
            <script src="/backend/js/excanvas.js"></script>
            <script src="/backend/js/respond.js"></script>
        <![endif]-->
        <script src="/backend/assets/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
        <script src="/backend/assets/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
        <script src="/backend/assets/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
        <script src="/backend/assets/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
        <script src="/backend/assets/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
        <script src="/backend/assets/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
        <script src="/backend/assets/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
        <script src="/backend/assets/jquery-knob/js/jquery.knob.js"></script>
        <script src="/backend/assets/flot/jquery.flot.js"></script>
        <script src="/backend/assets/flot/jquery.flot.resize.js"></script>

        <script src="/backend/assets/flot/jquery.flot.pie.js"></script>
        <script src="/backend/assets/flot/jquery.flot.stack.js"></script>
        <script src="/backend/assets/flot/jquery.flot.crosshair.js"></script>

        <script src="/backend/js/jquery.peity.min.js"></script>
        <script type="text/javascript" src="/backend/assets/uniform/jquery.uniform.min.js"></script>

        <script src="/backend/js/scripts.js"></script>
        <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
</html>
