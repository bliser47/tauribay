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
                            <td colspan="2">{{ date('M d, Y H:m', $encounter->killtime) }}</td>
                            <td>{{ $encounter->wipes }}</td>
                            <td>{{ $encounter->deaths_total }}</td>
                            <td>{{ $encounter->deaths_fight }}</td>
                        </tr>
                    </table>
                </div>
                @if ( $expansionId == 4 )
                    <div class="bossNameImg divDesktop" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
                        <img src="{{ URL::asset("img/encounters/" . $encounter->encounter_id . ".png") }}" alt="{{ \TauriBay\Encounter::getName($encounter->encounter_id)  }}"/>
                        {{ $encounter->name ?: "Random" }} - {{ date("M d, Y") }}
                    </div>
                    <div class="bossNameImgMobile divMobile" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
                        <img src="{{ URL::asset("img/encounters/" . $encounter->encounter_id . ".png") }}" alt="{{ \TauriBay\Encounter::getName($encounter->encounter_id)  }}"/>
                        {{ $encounter->name ?: "Random" }} - {{ date("M d") }}
                    </div>
                @else
                    <div class="bossName divDesktop" style="background-image:url('{{ URL::asset("img/maps/default.jpg") }}')">
                        {{ $encounter->name ?: "Random" }} - {{ date("M d, Y") }}
                    </div>
                    <div class="bossName divMobile" style="background-image:url('{{ URL::asset("img/maps/default.jpg") }}')">
                        {{ $encounter->name?: "Random" }} - {{ date("M d") }}
                    </div>
                @endif
            </div>
            <div class="panel panel-default">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="home-main-tab active" role="presentation"><a href="#damage" aria-controls="damage" role="tab" data-toggle="tab">DPS</a></li>
                    <li class="home-main-tab" role="presentation"><a href="#healing" aria-controls="healing" role="tab" data-toggle="tab">HPS</a></li>
                    <li class="home-main-tab" role="presentation"><a href="#score" aria-controls="score" role="tab" data-toggle="tab">Score</a></li>
                    <li class="home-main-tab" role="presentation"><a href="#damage_taken" aria-controls="damage_taken" role="tab" data-toggle="tab">DMG</a></li>
                    <li class="home-main-tab" role="presentation"><a href="#loot" aria-controls="loot" role="tab" data-toggle="tab">Loot</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="damage">
                        @if ( !$dpsValid )
                            <div class="alert alert-danger nomargin">
                                {{ __("Ezek az adatok hibásak ezért nem kerülnek megjelenítésre!") }}
                            </div>
                        @endif
                        @foreach ( $membersDamage as $member )
                            <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
                                <div class="memberDataWidthContainer">
                                    <div style="width:{{ $member->percentageDamage }}%" class="memberDataWidth memberClass{{ $member->class }}"></div>
                                    <div class="memberSpec">
                                        <img src="{{ URL::asset("img/classes/specs/" . $member->spec . ".png") }}" alt="{{ $classSpecs[$member->spec] }}"/>
                                    </div>
                                    <span class="memberPosition">{{ $loop->index+1 }}.</span>
                                    <span class="memberName">
                                        <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member->realm_id] . "/" . $member["name"] . "/" . $member->guid }}">{{ $member->name }}</a>
                                    </span>
                                    <span class="memberData memberData1 divDesktop">{{ number_format($member->damage_done) }}</span>
                                    <span class="memberData memberData1 divMobile">{{ \TauriBay\Tauri\Skada::format($member->damage_done) }}</span>
                                    <span class="memberData memberData2">({{ $member->dps }})</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div role="tabpanel" class="tab-pane" id="healing">
                        @if ( !$hpsValid )
                            <div class="alert alert-danger nomargin">
                                {{ __("Figyelem! Ezek az adatok hibásak lehetnek!") }}
                            </div>
                        @endif
                        @foreach ( $membersHealing as $member )
                            <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
                                <div class="memberDataWidthContainer">
                                    <div style="width:{{ $member->percentageHealing }}%" class="memberDataWidth memberClass{{ $member->class }}"></div>
                                    <div class="memberSpec">
                                        <img src="{{ URL::asset("img/classes/specs/" . $member->spec . ".png") }}" alt="{{ $classSpecs[$member->spec] }}"/>
                                    </div>
                                    <span class="memberPosition">{{ $loop->index+1 }}.</span>
                                    <span class="memberName">
                                        <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member->realm_id] ."/" . $member->name}}">{{ $member->name }}</a>
                                    </span>
                                    <span class="memberData memberData1 divDesktop">{{ number_format($member->total_heal) }}</span>
                                    <span class="memberData memberData1 divMobile">{{ \TauriBay\Tauri\Skada::format($member->total_heal) }}</span>
                                    <span class="memberData memberData2">({{ $member->hps }})</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div role="tabpanel" class="tab-pane" id="score">
                        @foreach ( $membersScore as $member )
                            <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
                                <div class="memberDataWidthContainer">
                                    <div style="width:{{ $member->percentageScore }}%" class="memberDataWidth memberClass{{ $member->class }}"></div>
                                    <div class="memberSpec">
                                        <img src="{{ URL::asset("img/classes/specs/" . $member->spec . ".png") }}" alt="{{ $classSpecs[$member->spec] }}"/>
                                    </div>
                                    <span class="memberPosition">{{ $loop->index+1 }}.</span>
                                    <span class="memberName">
                                        <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member->realm_id] ."/" . $member["name"] . "/" . $member->guid }}">{{ $member->name }}</a>
                                    </span>
                                    <span class="memberData memberData1">{{ $member->ilvl }} iLvL</span>
                                    <span class="memberData memberData2">{{ $member->score }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div role="tabpanel" class="tab-pane" id="damage_taken">
                        @foreach ( $membersDamageTaken as $member )
                            <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
                                <div class="memberDataWidthContainer">
                                    <div style="width:{{ $member->percentageDamageTaken }}%" class="memberDataWidth memberClass{{ $member->class }}"></div>
                                    <div class="memberSpec">
                                        <img src="{{ URL::asset("img/classes/specs/" . $member->spec . ".png") }}" alt="{{ $classSpecs[$member->spec] }}"/>
                                    </div>
                                    <span class="memberPosition">{{ $loop->index+1 }}.</span>
                                    <span class="memberName">
                                        <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member->realm_id] ."/" . $member["name"] . "/" . $member->guid }}">{{ $member->name }}</a>
                                    </span>
                                    <span class="memberData memberData1 divDesktop">{{ number_format($member->damage_absorb) }} abs</span>
                                    <span class="memberData memberData1 divMobile">{{ \TauriBay\Tauri\Skada::format($member->damage_absorb) }} abs</span>
                                    <span class="memberData memberData2">{{  \TauriBay\Tauri\Skada::format($member->total_damage_taken) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div role="tabpanel" class="tab-pane" id="loot">
                        @if ( count($loots) )
                            <table class="table table-bordered table-classes">
                                <tr class="tHead">
                                    <th style="width:35px"></th>
                                    <th></th>
                                    <th></th>
                                    <th class="cellDesktop">{{ __("Név") }}</th>
                                    <th>{{ __("Típus") }}</th>
                                    <th>{{ __("iLvL") }}</th>
                                </tr>
                                @foreach ( $loots as $loot )
                                    <tr>
                                        <td class="lootItemContainer">
                                            <img class="lootItem" src="https://wow.zamimg.com/images/wow/icons/large/{{ $loot->icon }}.jpg">
                                        </td>
                                        <td>
                                            {{ \TauriBay\Item::getInventoryType($loot->inventory_type) }}
                                        </td>
                                        <td>
                                            {{ \TauriBay\Item::getSubClass($loot->inventory_type, $loot->subclass) }}
                                        </td>
                                        <td class="cellDesktop" style="white-space:nowrap;">
                                            <a class="itemToolTip gearFrame" href="http://mop-shoot.tauri.hu/?item={{ $loot->item_id }}">
                                                {{ $loot->name }}
                                            </a>
                                        </td>
                                        <td> {{ $loot->description }}</td>
                                        <td style="width:50px;">{{ $loot->ilvl }}</td>
                                    </tr>
                                @endforeach
                         </table>
                         @else
                            <div class="alert alert-warning nomargin">
                                {{ __("A visszamenőleges loot adatok jelenleg feldolgozás alatt vannak. Kérjük látogass vissza később!") }}
                            </div>
                         @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection