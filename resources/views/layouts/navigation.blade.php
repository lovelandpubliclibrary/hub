@if (Auth::check())
    <nav class="navbar navbar-default navbar-fixed-top">
@else
    <nav class="navbar navbar-static-top">
@endif
  <div class="container-fluid">
    @if (Auth::check())
        <div class="navbar-header">

          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#app-navbar-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span> 
          </button>

            <a class="navbar-brand" href=" {{ url('/') }} "> {{ config('app.name', 'Laravel') }} </a>
            
        </div>
    
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="">Incidents</a></li>
            <li><a href="/schedule">Schedule</a></li>
            <li><a href="/helpdesk">LTI Help Desk</a></li>
            <li><a href="">Staff Training</a></li>
            <li><a href="">New Employee Checklist</a></li>
          </ul>
    @endif
      <ul class="nav navbar-nav navbar-right">
        @if (Auth::guest())
            <li><a class="default" href=" {{ route('register') }} ">Register</a></li>
        @else
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
        @endif
      </ul>
    </div>
  </div>
</nav>


@if (null)
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">

        @if (Auth::check())
            <div class="navbar-header">

                
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
            </div>
        @endif

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                @if (Auth::check())
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li><a href="">Incidents</a></li>
                        <li><a href="/schedule">Schedule</a></li>
                        <li><a href="/helpdesk">LTI Help Desk</a></li>
                        <li><a href="">Staff Training</a></li>
                        <li><a href="">New Employee Checklist</a></li>
                    </ul>
                @endif

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@endif