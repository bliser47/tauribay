@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="bossName">
                {{ $playerTitle }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            {!! Form::open(array("method" => "get","id"=>"player-form")) !!}
            <div class="col-md-6">
                <div id="expansions-container" class="input-group col-md-12">
                    {!! Form::select('realm_url', \TauriBay\Realm::REALMS_URL_KEY, Input::get('realm_url', $realmUrl), ['required', 'id' => 'realm_url', 'class' => "control selectpicker input-large"]); !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input id="player_id" type="hidden" class="form-control" name="player_id" value="{!! Input::get('player_id',$playerId) !!}">
                    <input id="player_name" type="text" class="form-control" name="player_name" value="{!! Input::get('player_name',$playerName) !!}" placeholder="{{ __("Karakter neve") }}">
                    <span class="input-group-btn">
                    <button class="btn btn-success" name="filter" value="1" type="submit">
                        {{ __("Keresés") }}
                    </button>
                  </span>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel nomargin">
                <div id="player-response-form">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach ( $modes as $modeKey => $modeName )
                            <li id="modePanel{{ $modeKey  }}" data-mode="{{ $modeKey }}" class="modePanel home-main-tab {{ $modeKey == $modeId ? "active" : "" }}" role="presentation"><a href="#{{ $modeKey }}" aria-controls="{{ $modeKey }}"  role="tab" data-toggle="tab">{{ $modeName }}</a></li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach ( $modes as $modeKey => $modeName )
                            <div data-mode="{{ $modeKey }}" role="tabpanel" class="tab-pane {{  $modeKey == $modeId ? "active" : "" }}" id="{{ $modeKey}}">
                                <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
                                <div data-mode="{{ $modeKey }}" class="encounter-mode-loading-container"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop