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
        @livewireStyles
        @yield('links')
        <link rel="stylesheet" href="/css/override.css">

    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="fixed-top">
        <!-- BEGIN HEADER -->
        <div id="header" class="navbar navbar-inverse navbar-fixed-top">
            <!-- BEGIN TOP NAVIGATION BAR -->
            <div class="navbar-inner">
                <div class="container-fluid">
                    <!-- BEGIN LOGO -->
                    <a class="brand" href="#">
                        {{env('APP_NAME')}}
                    </a>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="arrow"></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->

                    <!-- END  NOTIFICATION -->
                    <div class="top-nav">
                        <ul class="nav pull-right top-menu">
                            <!-- END SUPPORT -->
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <li class="dropdown">
                                <a href="##" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="/backend/img/avatar1_small.jpg" alt="" />
                                    <span class="username">{{auth()->user()->name}}</span>
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="divider"></li>
                                    <li>
                                        <a href="{{route('logout')}}"><i class="icon-key"></i> Log Out</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                        </ul>
                        <!-- END TOP NAVIGATION MENU -->
                    </div>
                </div>
            </div>
            <!-- END TOP NAVIGATION BAR -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN CONTAINER -->
        <div id="container" class="row-fluid">
            <!-- BEGIN SIDEBAR -->
            @include('pages.fragments.sidebar')
            <!-- END SIDEBAR -->
            <!-- BEGIN PAGE -->
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
                <div class="row-fluid">
                    <div class="btn-group pull-right" style="margin-right: 10px;">
                        @if (auth()->user()->roleid == 1)
                            <a href="{{ route('chain.data') }}" class="btn green">reload all agents statistics</a>
                        @endif
                        <a href="{{ url()->previous() }}" class="btn green">go back <i class="icon-back"></i></a>
                    </div>
                </div>
                @yield('content')
                <!-- END PAGE CONTAINER-->
            </div>
            <!-- END PAGE -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div id="footer">
            2021 &copy; {{env('APP_NAME')}}
            <div class="span pull-right">
                <span class="go-top"><i class="icon-arrow-up"></i></span>
            </div>
        </div>
        <!-- END FOOTER -->
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
        @livewireScripts
        @yield('scripts')
        <script src="/backend/js/scripts.js"></script>
        <script>
            jQuery(document).ready(function () {
                // initiate layout and plugins
                App.setMainPage(true);
                App.init();
            });
        </script>
        <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
</html>
