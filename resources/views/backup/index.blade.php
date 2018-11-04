<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>RFID PRODUCTION MONITORING SYSTEM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <!-- jQuery -->
    <script type="text/javascript" src="../src/smartmenus-1.0.0/libs/jquery/jquery.js"></script>

    <!-- SmartMenus jQuery plugin -->
    <script type="text/javascript" src="../src/smartmenus-1.0.0/jquery.smartmenus.js"></script>

    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- SmartMenus jQuery init -->
    <script type="text/javascript">
        $(function() {
            $('#main-menu').smartmenus({
                subMenusSubOffsetX: 1,
                subMenusSubOffsetY: -8
            });

        });
    </script>



    <!-- SmartMenus core CSS (required) -->
    <link href="../src/smartmenus-1.0.0/css/sm-core-css.css" rel="stylesheet" type="text/css" />

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />



    <!-- "sm-blue" menu theme (optional, you can use your own CSS, too) -->
    <link href="../src/smartmenus-1.0.0/css/sm-blue/sm-blue.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
        @media (min-width: 768px) {
            #main-menu > li {
                float: none;
                display: table-cell;
                width: 1%;
                text-align: center;
            }
        }
    </style>


    <style>
        html,body{
            background-color: #8eb4cb;
            height:100%;
        }

        .fullpage{
            min-height: 77vh;
            height:100%;
            background-color:#FFFFFF;
        }

    </style>
</head>
<body>



<div class="container-full">

    <div class="row">
        <div class="col-md-12">
            <img src="src/images/banar_cool.jpeg">
            <a>SSSSSSSSSSSSSSSSSSSSSSSSS</a>
        </div>
    </div>




    <div class="row">
        <nav id="main-nav" role="navigation">
            {{--<div class="container-fluid">--}}
            <ul id="main-menu" class="sm sm-blue">
                <li><a href="{{ url('home') }}">Home</a></li>
                <li><a href="http://www.smartmenus.org/about/">Authorization</a>
                    <ul>
                        <li><a href="{{ url('/registerIndex') }}">Add Edit User Info</a></li>
                        <li><a href="http://www.smartmenus.org/about/themes/">Set User Prevellage</a></li>
                        <li><a href="http://vadikom.com/about/#vasil-dinkov">Change User Password</a></li>
                        {{--<li><a href="http://www.smartmenus.org/about/vadikom/">The company</a>--}}
                        {{--<ul>--}}
                        {{--<li><a href="http://vadikom.com/about/">About Vadikom</a></li>--}}
                        {{--<li><a href="http://vadikom.com/projects/">Projects</a></li>--}}
                        {{--<li><a href="http://vadikom.com/services/">Services</a></li>--}}
                        {{--<li><a href="http://www.smartmenus.org/about/vadikom/privacy-policy/">Privacy policy</a></li>--}}
                        {{--</ul>--}}
                        </li>
                    </ul>
                </li>
                <li><a href="">Basic Settings</a>
                    <ul>
                        <li><a href="{{ url('/readerLocationIndex') }}">Reader Location</a></li>
                        <li><a href="{!! url('/regNewTagsIndex') !!}">Register New Tags</a></li>
                        <li><a href="{!! url('/regTagsBundleIndex') !!}">Register Tag With Bundle</a></li>
                        <li><a href="{!! url('/editBundleQtyIndex') !!}">Edit Bundle Quantity</a></li>
                        <li><a href="{!! url('/modalTestIndex') !!}">Modal Test</a></li>
                        {{--<li><a href="http://www.smartmenus.org/about/vadikom/">The company</a>--}}
                        {{--<ul>--}}
                        {{--<li><a href="http://vadikom.com/about/">About Vadikom</a></li>--}}
                        {{--<li><a href="http://vadikom.com/projects/">Projects</a></li>--}}
                        {{--<li><a href="http://vadikom.com/services/">Services</a></li>--}}
                        {{--<li><a href="http://www.smartmenus.org/about/vadikom/privacy-policy/">Privacy policy</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                    </ul>
                </li>
                {{--<li><a href="http://www.smartmenus.org/download/">Download</a></li>--}}
                {{--<li><a href="http://www.smartmenus.org/support/">Support</a>--}}
                {{--<ul>--}}
                {{--<li><a href="http://www.smartmenus.org/support/premium-support/">Premium support</a></li>--}}
                {{--<li><a href="http://www.smartmenus.org/support/forums/">Forums</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li><a href="http://www.smartmenus.org/docs/">Docs</a></li>--}}
                <li><a href="#">Report</a>
                    <ul>
                        <li><a href="prodCounterIndex">Production Monitor</a></li>
                        {{--<li><a href="#">Dummy item</a></li>--}}
                        {{--<li><a href="#" class="disabled">Disabled menu item</a></li>--}}
                        {{--<li><a href="#">Dummy item</a></li>--}}
                    </ul>
                </li>
                {{--<ul class="nav navbar-nav navbar-right">--}}
            <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    {{--<li><a href="{{ url('/register') }}">Register</a></li>--}}
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->email }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif

            </ul>
            {{--</div>--}}
        </nav>
    </div>




    <div class="row fullpage">
        <div class="col-md-12">

        </div>
    </div>


    <div class="row" style="background-color: #3092c0; height: 50px; padding-top: 10px; text-align: center">
        <div class="col-md-12">
            <p style="color: #ffffff">Something</p>
        </div>
    </div>

</div>

</body>
</html>