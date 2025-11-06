@extends('layouts.app')

@section('content')
<div class="login">
    <div class="panel panel-default" style="background: rgba(255, 255, 255, 0.7)!important;">
        <div class="panel-heading" style="background-color: rgba(244,67,54,1)!important;"> {{ config('app.name', 'IDEIA CMS') }}</div>

        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-3 control-label">E-Mail</label>

                    <div class="col-md-8">
                        <input id="email" type="email" class="form-control form-field" name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-3 control-label">Password</label>

                    <div class="col-md-8">
                        <input id="password" type="password" class="form-control form-field" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Lembrar de mim
                                </label>
                            </div>
                        </div>
                       <!--  <div class="col-md-6">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                Forgot Your Password?
                            </a>
                        </div> -->
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                        <button type="submit" class="btn btn-success">
                            Entrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
