    <div class="headerbackground">
    <div class="col-md-12 col-sm-12" style="background-color: #f1f1f1">
    <div class="row col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">

        <div class="col-md-4 col-sm-4">
                <img src="{!! asset('src/images/logo.jpg') !!}" class="img-responsive">
        </div>

        <div class="col-md-6 col-sm-6">
            @if(Session::has('comp_code'))

                <h1 style="color: #bf6030"><strong>{!! get_company_name() !!}</strong></h1>
            @else

                @php ($comp_code = '11001')
            <h1><strong>Spider IT Limited</strong></h1>
                {{--<img src="src/images/{!! $comp_code !!}.jpeg" class="img-responsive">--}}
            @endif
        </div>
        <div class="col-md-2 col-sm-2">
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
                                <a href="{{ url('/userIndex') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('addData-form').submit();">
                                    Add New User
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
</div>