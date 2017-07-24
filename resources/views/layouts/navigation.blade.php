<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container-fluid">
        {{-- Content that is displayed at all times in the navbar --}}
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span> 
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/lpl_logo.png') }}" alt="Logo">
                {{ config('app.name') }}
            </a>
        </div>

        {{-- Content that is displayed above sm breakpoint --}}
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ route('incidents') }}">
                        Incidents
                    </a>
                </li>
                <li>
                    <a href="{{ route('schedule') }}">
                        Scheduler
                    </a>
                </li>
                <li>
                    <a href="{{ route('cityemail') }}">
                        City Email
                    </a>
                </li>
            </ul>

            {{-- User-specific navigation links always displayed on the right-hand side of the navbar --}}
            <div class="navbar-nav navbar-right">
                {{ Auth::user()->name }}
            </div>
        </div>

    </div><!-- .container-fluid -->
</nav>