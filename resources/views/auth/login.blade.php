@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default nomargin">
                <div class="panel-heading">Bejelentkezés</div>
                <div class="panel-body login nopadding-bottom">
                    <div class="login-left"></div>
                    <div class="login-right"></div>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail cím</label>

                            <div class="col-md-4">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Jelszó</label>

                            <div class="col-md-4">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-2 col-md-offset-4">
                                <div class="checkbox">
                                    <input id="remember" type="checkbox" name="remember">
                                    <label for="remember">Emlékezz rám!</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Elfelejtett jelszó?</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                <button type="submit" class="btn btn-block btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Bejelentkezés
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




