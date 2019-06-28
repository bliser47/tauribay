@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel panel-default">
                <div class="panel-heading nopadding" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {{ __("Info") }}
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <table class="table table-bordered table-classes">
                        <tr class="tHead">
                            <th>Realm</th>
                            <th>Boss</th>
                            <th>Diff</th>
                            <th>Guild</th>
                            <th>{{ __("Idő") }}</th>
                        </tr>
                        <tr>
                            <td>{{ \TauriBay\Realm::REALMS_SHORTEST[$encounter->realm_id]  }}</td>
                            <td class="cellDesktop" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId). "/" . \TauriBay\Encounter::getUrlName($encounter->encounter_id) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$encounter->difficulty_id] }}">{{ $encounterData["name"]  }}</a></td>
                            <td class="cellMobile" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId). "/" . \TauriBay\Encounter::getUrlName($encounter->encounter_id) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$encounter->difficulty_id] }}">{{ \TauriBay\Encounter::getNameShort($encounter->encounter_id)  }}</a></td>
                            <td>{{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$encounter->difficulty_id] }}</td>
                            <td class="cellDesktop faction-{{ $encounter->faction }}">
                                @if ( strlen($encounter->name) )
                                    <a href="{{ URL::to("/guild/" . $encounter->guild_id) }}"> {{ $encounter->name }} </a>
                                @else
                                    Random
                                @endif
                            </td>
                            <td class="cellMobile faction-{{ $encounter->faction }}">
                                @if ( strlen($encounter->name) )
                                    <a href="{{ URL::to("/guild/" . $encounter->guild_id) }}"> {{ \TauriBay\Guild::getShortName($encounter->name) }} </a>
                                @else
                                    Random
                                @endif
                            </td>
                            <td class="guildClearTime cellDesktop">{{ $encounter->fight_time/1000 }}</td>
                            <td class="guildClearTimeMobile cellMobile">{{ $encounter->fight_time/1000 }}</td>
                        </tr>
                        <tr class="tHead">
                            <th colspan="2">{{ __("Dátum") }}</th>
                            <th>{{ __("Wipe") }}</th>
                            <th>{{ __("Összes halál") }}</th>
                            <th>{{ __("Halálok") }}</th>
                        </tr>
                        <tr>
                            <td colspan="2">{{ date('M d, Y H:i', $encounter->killtime) }}</td>
                            <td>{{ $encounter->wipes }}</td>
                            <td>{{ $encounter->deaths_total }}</td>
                            <td>{{ $encounter->deaths_fight }}</td>
                        </tr>
                    </table>
                </div>
                @if ( $expansionId == 4 )
                    <div class="bossNameImg divDesktop" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
                        <img src="{{ URL::asset("img/encounters/" . $encounter->encounter_id . ".png") }}" alt="{{ \TauriBay\Encounter::getName($encounter->encounter_id)  }}"/>
                        {{ $encounter->name ?: "Random" }} - {{ date("M d, Y", $encounter->killtime) }}
                    </div>
                    <div class="bossNameImgMobile divMobile" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
                        <img src="{{ URL::asset("img/encounters/" . $encounter->encounter_id . ".png") }}" alt="{{ \TauriBay\Encounter::getName($encounter->encounter_id)  }}"/>
                        {{ $encounter->name ?: "Random" }} - {{ date("M d", $encounter->killtime) }}
                    </div>
                @else
                    <div class="bossName divDesktop" style="background-image:url('{{ URL::asset("img/maps/default.jpg") }}')">
                        {{ $encounter->name ?: "Random" }} - {{ date("M d, Y", $encounter->killtime) }}
                    </div>
                    <div class="bossName divMobile" style="background-image:url('{{ URL::asset("img/maps/default.jpg") }}')">
                        {{ $encounter->name?: "Random" }} - {{ date("M d", $encounter->killtime) }}
                    </div>
                @endif
            </div>
            @if ( $isInvalid )
                <div class="alert alert-danger nomargin">
                    {{ __("Figyelem! Ezek az adatok bizonyos bugok miatt nem kerülnek beszámításra a Toplista rendszerbe!") }}
                </div>
            @endif
            <div class="panel panel-default">
                <ul id="encounter-tabs" class="nav nav-tabs" role="tablist">
                    <li data-id="{{ $encounter->id }}" data-mode="damage" class="home-main-tab active" role="presentation"><a href="#damage" aria-controls="damage" role="tab" data-toggle="tab">DPS</a></li>
                    <li data-id="{{ $encounter->id }}" data-mode="healing" class="home-main-tab" role="presentation"><a href="#healing" aria-controls="healing" role="tab" data-toggle="tab">HPS</a></li>
                    <li data-id="{{ $encounter->id }}" data-mode="score" class="home-main-tab" role="presentation"><a href="#score" aria-controls="score" role="tab" data-toggle="tab">Score</a></li>
                    <li data-id="{{ $encounter->id }}" data-mode="damage_taken" class="home-main-tab" role="presentation"><a href="#damage_taken" aria-controls="damage_taken" role="tab" data-toggle="tab">DMG</a></li>
                    <li data-id="{{ $encounter->id }}" data-mode="loot" class="home-main-tab" role="presentation"><a href="#loot" aria-controls="loot" role="tab" data-toggle="tab">Loot</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="damage"></div>
                    <div role="tabpanel" class="tab-pane" id="healing"></div>
                    <div role="tabpanel" class="tab-pane" id="score"></div>
                    <div role="tabpanel" class="tab-pane" id="damage_taken"></div>
                    <div role="tabpanel" class="tab-pane" id="loot"></div>
                </div>
            </div>
        </div>
    </div>
@endsection