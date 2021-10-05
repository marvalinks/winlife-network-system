@extends('pages.layouts.accounts')

@section('content')
<div id="login">
            <!-- BEGIN LOGIN FORM -->
            <form id="loginform" method="POST" class="form-vertical no-padding no-margin" action="{{route('register')}}">
                @csrf
                <div class="lock">
                    <i class="icon-lock"></i>
                </div>
                <div class="control-wrap">
                    <h4>Account Registration</h4>
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-user"></i></span><input name="name" required id="input-username" type="text" placeholder="Fullname" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-envelope"></i></span><input name="email" required id="input-username" type="email" placeholder="Email Address" />
                            </div>
                        </div>
                    </div>
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

                            <div class="clearfix space5"></div>
                        </div>
                    </div>
                </div>

                <input type="submit" id="login-btn" class="btn btn-block login-btn" value="Register" />
            </form>
            <!-- END LOGIN FORM -->
        </div>
@endsection
