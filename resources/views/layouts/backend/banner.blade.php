<nav id="myNavbar" class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation" style="background-color: #f1f1f1">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="container">

        <div class="row">
            <div class="col-md-3">
                <img src="src/images/logo.jpg" class="img-responsive">
            </div>
            <div class="col-md-6">
                @if(Session::has('comp_code'))

                    <h1 style="color: #bf6030"><strong>{!! get_company_name() !!}</strong></h1>
                @else

                    @php ($comp_code = '11001')
                    <h1><strong>Spider IT Limited</strong></h1>
                    <img src="src/images/{!! $comp_code !!}.jpeg" class="img-responsive">
                @endif
            </div>
            <div class="col-md-3">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}"><strong>LOGIN</strong></a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <strong>{{ Auth::user()->name }}</strong>    <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>


    </div>
</nav>