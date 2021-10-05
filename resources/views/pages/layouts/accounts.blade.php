<!DOCTYPE html>
<!--
Template Name: Admin Lab Dashboard build with Bootstrap v2.3.1
Template Version: 1.3
Author: Mosaddek Hossain
Website: http://thevectorlab.net/
-->

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>Login page</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link href="/backend/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/backend/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link href="/backend/css/style.css" rel="stylesheet" />
        <link href="/backend/css/style_responsive.css" rel="stylesheet" />
        <link href="/backend/css/style_default.css" rel="stylesheet" id="style_color" />
        <style>
            #logo {
                width: 431px;
            }
            #logo h2{
                color: white;
                position: relative;
                bottom: 15px;
            }
        </style>
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body id="login-body">
        <div class="login-header">
            <!-- BEGIN LOGO -->
            <div id="logo" class="center">
                <!-- <img src="/backend/img/logo.png" alt="logo" class="center" /> -->
                <h2>Winlife Network System</h2>
            </div>
            <!-- END LOGO -->
        </div>

        <!-- BEGIN LOGIN -->
        @yield('content')
        <!-- END LOGIN -->
        <!-- BEGIN COPYRIGHT -->
        <div id="login-copyright">
            2021 &copy; {{env('APP_NAME')}}
        </div>
        <!-- END COPYRIGHT -->
        <!-- BEGIN JAVASCRIPTS -->
        <script src="/backend/js/jquery-1.8.3.min.js"></script>
        <script src="/backend/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="/backend/js/jquery.blockui.js"></script>
        <script src="/backend/js/scripts.js"></script>
        <script>
            jQuery(document).ready(function () {
                App.initLogin();
            });
        </script>
        <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
</html>
