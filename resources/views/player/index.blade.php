@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="bossName">
                {{ $playerTitle }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(array("method" => "get","id"=>"player-form")) !!}
            <div class="col-xs-6">
                <div id="expansions-container" class="input-group col-md-12">
                    {!! Form::select('realm_id', $realms, Input::get('realm_id', $realmId), ['required', 'id' => 'realm', 'class' => "control selectpicker input-large"]); !!}
                </div>
            </div>
            <div class="col-xs-6">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{!! Input::get('name',$playerName) !!}" placeholder="{{ __("Karakter neve") }}">
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
        <div class="col-md-12">
            <div class="panel panel-default nomargin">
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