@extends('layouts.default')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="bossName">
                {{ $realm . " - " . $guild["name"]  }}
            </div>
            {{--
            <div class="panel panel-default">
                <div class="panel-heading nopadding" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {{ __("Szűrés") }}
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        {!! Form::open(array("method" => "get","id"=>"pve-ladder-form")) !!}
                        <div class="form-group col-sm-4 col-sm-nopadding">
                            <legend> {{ __("Kieg") }} </legend>
                            <div id="expansions-container" class="input-group col-md-12">
                                {!! Form::select('expansion_id', $expansions, Input::get('expansion_id', $expansionId), ['required', 'id' => 'expansion', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz kieget")]); !!}
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-sm-nopadding">
                            <legend> {{ __("Raid") }} </legend>
                            <div id="maps-container" class="input-group col-md-12">
                                {!! Form::select('map_id', $maps,  Input::get('map_id', $mapId), ['required', 'id' => 'map', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz raidet")]); !!}
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-sm-nopadding">
                            <legend> {{ __("Nehézség") }} </legend>
                            <div id="difficulty-container" class="input-group col-md-12">
                                {!! Form::select('difficulty_id', $difficulties,  Input::get('difficulty_id', $difficultyId), ['required', 'id' => 'size', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz nehézséget")]); !!}
                            </div>
                        </div>
                        <div class="form-group col-md-12 nopadding nomargin">
                            <button class="btn btn-block btn-success" name="filter" value="1" type="submit">
                                {{ __("Keresés") }}
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        --}}
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
@stop
