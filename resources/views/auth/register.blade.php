@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default nomargin">
                <div class="panel-heading">Regisztráció</div>
                <div class="panel-body register">
                    <div class="register-left"></div>
                    <div class="register-right"></div>
                    <form class="form-horizontal register-form" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nicknév</label>
                            <div class="col-md-4">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" autocomplete="off">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail cím</label>

                            <div class="col-md-4">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autocomplete="off">

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
                                <input id="password" type="password" class="form-control" name="password" autocomplete="off">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Jelszó újra</label>

                            <div class="col-md-4">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="off">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                {!! Recaptcha::render() !!}
                            </div>
                        </div>

                        <div class="form-group nomargin">
                            <div class="col-md-4 col-md-offset-4">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-btn fa-user"></i> Regisztráció
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
