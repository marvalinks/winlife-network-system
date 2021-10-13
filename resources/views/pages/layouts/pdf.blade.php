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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        @yield('links')
        <!-- <link rel="stylesheet" href="{{public_path('css/override.css')}}"> -->
        <style>
            .fixed-top #container {
                margin-top: 0px;
            }
            #main-content{
                /* margin-left: 0px; */
                background: white!important;
            }
        </style>

    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="fixed-top">

        <div id="container" class="row-fluid">
            <div id="main-content">

                @yield('content')
                <!-- END PAGE CONTAINER-->
            </div>
            <!-- END PAGE -->
        </div>

        <!-- BEGIN JAVASCRIPTS -->
        <!-- Load javascripts at bottom, this will reduce page load time -->
        <!-- <script src="{{public_path('backend/js/jquery-1.8.3.min.js')}}"></script>
        <script src="{{public_path('backend/assets/bootstrap/js/bootstrap.min.js')}}"></script>

        <script src="{{public_path('backend/js/scripts.js')}}"></script> -->
        <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
</html>
