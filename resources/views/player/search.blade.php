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
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Kaszt") }}</th>
                        <th>GUID</th>
                        <th></th>
                    </tr>
                    @foreach ( $characters as $character )
                        <tr>
                            <td>{{ $character->name }}</td>
                            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                            <td>{{ $character->guid  }}</td>
                            <td><a href="{{ URL::to("/player/" . $realmUrl . "/" . $character->name . "/" . $character->id ) }}">{{ __("Kiválaszt") }}</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop