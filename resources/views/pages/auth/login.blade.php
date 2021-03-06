@extends('pages.layouts.accounts')

@section('content')
<div id="login">
            <!-- BEGIN LOGIN FORM -->
            <form id="loginform" method="POST" class="form-vertical no-padding no-margin" action="{{route('login')}}">
                @csrf
                <div class="lock">
                    <i class="icon-lock"></i>
                </div>
                <div class="control-wrap">
                    <h4>User Login</h4>
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-user"></i></span><input name="username" required id="input-username" type="text" placeholder="Username" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-key"></i></span><input name="password" required id="input-password" type="password" placeholder="Password" />
                            </div>
                            <div class="mtop10">
                                <div class="block-hint pull-left small"><input type="checkbox" id="" /> Remember Me</div>
                                <div class="block-hint pull-right">
                                    <a href="javascript:;" class="" id="forget-password">Forgot Password?</a>
                                </div>
                            </div>

                            <div class="clearfix space5"></div>
                        </div>
                    </div>
                </div>

                <input type="submit" id="login-btn" class="btn btn-block login-btn" value="Login" />
            </form>
            <!-- END LOGIN FORM -->
            <!-- BEGIN FORGOT PASSWORD FORM -->
            <form id="forgotform" class="form-vertical no-padding no-margin hide" action="index.html">
                <p class="center">Enter your e-mail address below to reset your password.</p>
                <div class="control-group">
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-envelope"></i></span><input id="input-email" type="text" placeholder="Email" />
                        </div>
                    </div>
                    <div class="space20"></div>
                </div>
                <input type="button" id="forget-btn" class="btn btn-block login-btn" value="Submit" />
            </form>
            <!-- END FORGOT PASSWORD FORM -->
        </div>
@endsection
