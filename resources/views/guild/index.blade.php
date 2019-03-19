@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="bossName">
                {{ $realm . " - " . $guild["name"]  }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            {!! Form::open(array("method" => "get","id"=>"guild-form")) !!}
            <div class="col-md-3 col-xs-6">
                <div id="expansions-container" class="input-group col-md-12">
                    {!! Form::select('expansion_id', $expansionsShort, Input::get('expansion_id', $expansionId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large"]); !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div id="expansions-container" class="input-group">
                    {!! Form::select('map_id', $mapsShort, Input::get('map_id', $mapId), ['required', 'id' => 'map', 'class' => "control selectpicker input-large"]); !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div id="expansions-container" class="input-group">
                    {!! Form::select('encounter_id', $encounters, Input::get('encounter_id', $encounterId), ['required', 'id' => 'encounter', 'class' => "control selectpicker input-large"]); !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div id="expansions-container" class="input-group">
                    {!! Form::select('difficulty_id', $difficultiesShort, Input::get('difficulty_id', $difficultyId), ['required', 'id' => 'difficulty', 'class' => "control selectpicker input-large"]); !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    </br>
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel panel-default nomargin">
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th>{{ __("Boss") }}</th>
                        <th>{{ __("Nehézség") }}</th>
                        <th>{{ __("Dátum") }}</th>
                        <th>{{ __("Idő") }}</th>
                    </tr>
                    @foreach( $guildEncounters as $encounter )
                        @if ( array_key_exists($encounter->encounter_id, $encounterIDs))
                            <tr>
                                <td class="cellMobile" style="white-space:nowrap;">{{ array_key_exists($encounterIDs[$encounter->encounter_id]["name"],TauriBay\Encounter::ENCOUNTER_NAME_SHORTS) ? TauriBay\Encounter::ENCOUNTER_NAME_SHORTS[$encounterIDs[$encounter->encounter_id]["name"]] : $encounterIDs[$encounter->encounter_id]["name"] }}</td>
                                <td class="cellDesktop" style="white-space:nowrap;">{{ $encounterIDs[$encounter->encounter_id]["name"] }}</td>
                                <td class="cellDesktop">{{ TauriBay\Encounter::SIZE_AND_DIFFICULTY[$encounter->difficulty_id] }}</td>
                                <td class="cellMobile">{{ TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$encounter->difficulty_id] }}</td>
                                <td class="cellDesktop">{{ date('M d, Y', $encounter->killtime) }}</td>
                                <td class="cellMobile">{{ date('M d', $encounter->killtime) }}</td>
                                <td><a class="guildClearTime" target="_blank" href="{{ URL::to("/encounter/") . "/" . TauriBay\Encounter::getUrlName($encounter->encounter_id) . "/" . $encounter->id }}">{{ $encounter->fight_time/1000  }}</a></td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $guildEncounters->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
