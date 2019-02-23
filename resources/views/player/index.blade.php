@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="bossName">
                {{ __("Karakter keresése") }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            {!! Form::open(array("method" => "get","id"=>"player-form")) !!}
            <div class="col-md-6">
                <div id="expansions-container" class="input-group col-md-12">
                    {!! Form::select('realm_url', \TauriBay\Realm::REALMS_URL_KEY, Input::get('realm_url'), ['required', 'id' => 'realm_url', 'class' => "control selectpicker input-large"]); !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input id="player_name" type="text" class="form-control" name="player_name" value="{!! Input::get('player_name') !!}" placeholder="{{ __("Karakter neve") }}">
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
@stop