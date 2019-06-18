@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="bossName">
                <img src="{{ URL::asset("img/factions/small/" . $character->faction . ".png") }}" alt=""/> {{ \TauriBay\Realm::REALMS_SHORT[$character->realm] . " - " . $character->name }}
            </div>
            <table class="table table-bordered table-classes nomargin">
                <tr>
                    <th>{{ __("Kaszt") }}</th>
                    <th>iLvL</th>
                    <th>Achi</th>
                    <th>Score</th>
                    <th>Tauri Armory</th>
                </tr>
                <tr>
                    <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                    <td>{{ $character->ilvl }}</td>
                    <td>{{ $character->achievement_points }}</td>
                    <td>{{ $character->score }}</td>
                    <td><a target="_blank" href="{{ URL::to("https://tauriwow.com/armory#character-sheet.xml?r=" . \TauriBay\Realm::REALMS[$character->realm] . "&n=" . $character->name) }}">{{ __("Armory megtekintése") }}</a></td>
                </tr>
            </table>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel panel-default">
                <div class="panel-heading nopadding" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {{ __("Válassz instát!") }}
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        {!! Form::open(array("method" => "get","id"=>"pve-player-form")) !!}
                        <div class="hidden">
                            {!! Form::checkbox('tauri',1,Input::get('tauri')) !!}
                            {!! Form::checkbox('wod',1,Input::get('wod')) !!}
                            {!! Form::checkbox('evermoon',1,Input::get('evermoon')) !!}
                            {!! Form::checkbox('alliance',1,Input::get('alliance')) !!}
                            {!! Form::checkbox('horde',1,Input::get('horde')) !!}
                        </div>
                        @if ( $difficultyId )
                            <input type="hidden" name="difficulty_id" value="{{ $difficultyId }}"/>
                        @endif
                        @if ( $defaultDifficultyId )
                            <input type="hidden" name="default_difficulty_id" value="{{ $defaultDifficultyId }}"/>
                        @endif
                        <input id="realm_url" type="hidden" class="form-control" name="player_id" value="{{ $realmUrl }}">
                        <input id="player_guid" type="hidden" class="form-control" name="player_id" value="{{ $character->guid }}">
                        <input id="player_name" type="hidden" class="form-control" name="player_name" value="{{ $character->name }}">
                        <div class="col-sm-4 col-sm-nopadding col-sm-margin">
                            <div id="expansions-container" class="input-group col-md-12">
                                {!! Form::select('expansion_id', $expansions, Input::get('expansion_id', $expansionId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large"]); !!}
                            </div>
                        </div>
                        <div class="col-sm-4 col-sm-nopadding col-sm-margin">
                            <div id="maps-container" class="input-group col-md-12">
                                {!! Form::select('map_id', $maps,  Input::get('map_id', $mapId), ['required', 'id' => 'map', 'class' => "control selectpicker input-large"]); !!}
                            </div>
                        </div>
                        <div class="col-sm-4 nomargin col-sm-nopadding">
                            <button class="btn btn-block btn-success" name="filter" value="1" type="submit">
                                {{ __("Keresés") }}
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="panel table-responsive">
                <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
                <div id="map-loading-container"></div>
            </div>
        </div>
    </div>
@stop