    <div class="panel">
        <div class="panel-heading text-center">
            <img src="{{ URL::asset('images/lpl_logo.png') }}" alt="Loveland Public Library Logo" class="text-center">
            <div class="h2 text-info">
                LPL Staff Repository
            </div>
        </div><!-- .panel-heading -->

        <div class="panel-body">
            <form class="form text-center" role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label sr-only">
                        E-Mail Address
                    </label>

                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="control-label sr-only">
                        Password
                    </label>

                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                    <button type="submit" class="btn btn-default">
                        Login
                    </button>

                    <div>
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>
                    </div>
                </div>
            </form>
        </div><!-- .panel-body -->
    </div><!-- .panel -->
