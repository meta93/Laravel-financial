<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Adenta Municipal Assembly</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>


<body>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h3 class="text-center login-title">Adenta Municipal Management System</h3>

            <div class="account-wall">
                <img class="profile-img" src="src/images/log_icon.png"  alt="Key Image">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{--<form class="form-signin">--}}
                <form class="form-signin" role="form" method="POST" action="{{ url('/login') }}">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {!! Form::email('email', null , array('id' => 'email', 'class' => 'col-sm-12 form-control','placeholder' => 'email',  'data-mg-required' => '')) !!}
                    {{ $errors->has('email') ? ' has-error' : '' }}
                    @if ($errors->has('email'))
                        <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                    @endif


                    {!! Form::password('password', array('class' => 'form-control','placeholder' => 'Password')) !!}
                    {{ $errors->has('password') ? ' has-error' : '' }}
                    @if ($errors->has('password'))
                        <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                    @endif

                    <button class="btn btn-lg btn-primary btn-block" type="submit">
                        Sign in</button>
                </form>

                <!--Set User Name & PassWord-->
                {{--<center>Name: admin@gmail.com--}}
                    {{--<br/>--}}
                    {{--User Password: pass123--}}
                {{--</center>--}}

            </div>
        </div>
    </div>
</div>
</body>
</html>