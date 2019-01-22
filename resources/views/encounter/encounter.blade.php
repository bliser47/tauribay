@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <table class="table table-bordered table-classes">
                    <tr>
                        <th>Realm</th>
                        <th>Boss</th>
                        <th>Guild</th>
                        <th>{{ __("Dátum") }}</th>
                        <th>{{ __("Időtartam") }}</th>
                        <th class="cellDesktop">{{ __("Wipe-ok") }}</th>
                        <th class="cellDesktop">{{ __("Összes halál") }}</th>
                        <th class="cellDesktop">{{ __("Halálok") }}</th>
                    </tr>
                    <tr>
                        <td>{{ $shortRealms[$encounter->realm_id]  }}</td>
                        <td class="cellDesktop" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId). "/" . \TauriBay\Encounter::getUrlName($encounter->encounter_id) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$encounter->difficulty_id] }}">{{ $encounterData["name"]  }}</a></td>
                        <td class="cellMobile" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId). "/" . \TauriBay\Encounter::getUrlName($encounter->encounter_id) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$encounter->difficulty_id] }}">{{ \TauriBay\Encounter::getNameShort($encounter->encounter_id)  }}</a></td>
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
                        <td class="cellDesktop">{{ date('M d, Y', $encounter->killtime) }}</td>
                        <td class="cellMobile">{{ date('M d', $encounter->killtime) }}</td>
                        <td class="guildClearTime">{{ $encounter->fight_time/1000 }}</td>
                        <td class="cellDesktop">{{ $encounter->wipes }}</td>
                        <td class="cellDesktop">{{ $encounter->deaths_total }}</td>
                        <td class="cellDesktop">{{ $encounter->deaths_fight }}</td>
                    </tr>
                </table>
            </div>
            <div class="panel panel-default">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="home-main-tab active" role="presentation"><a href="#fightDataDamage" aria-controls="fightDataDamage" role="tab" data-toggle="tab">Damage</a></li>
                    <li class="home-main-tab" role="presentation"><a href="#fightDataHealing" aria-controls="fightDataHealingg" role="tab" data-toggle="tab">Healing</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="fightDataDamage">
                        @if ( count($membersDamage) )
                            @foreach ( $membersDamage as $member )
                                <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
                                    <div class="memberDataWidthContainer">
                                        <div style="width:{{ $member->percentageDamage }}%" class="memberDataWidth memberClass{{ $member->class }}"></div>
                                        <div class="memberSpec">
                                            <img src="{{ URL::asset("img/classes/specs/" . $member->spec . ".png") }}" alt="{{ $classSpecs[$member->spec] }}"/>
                                        </div>
                                        <span class="memberPosition">{{ $loop->index+1 }}.</span>
                                        <span class="memberName">
                                            <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ $realms[$member->realm_id] }}&n={{ $member->name }}"> {{ $member->name }} </a>
                                        </span>
                                        <span class="memberData memberData1">{{ number_format($member->damage_done) }}</span>
                                        <span class="memberData memberData2">({{ $member->dps }})</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-danger nomargin">
                                {{ __("Ezek az adatok hibásak ezért nem kerülnek megjelenítésre!") }}
                            </div>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="fightDataHealing">
                        @if ( count($membersHealing) )
                            @foreach ( $membersHealing as $member )
                                <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
                                    <div class="memberDataWidthContainer">
                                        <div style="width:{{ $member->percentageHealing }}%" class="memberDataWidth memberClass{{ $member->class }}"></div>
                                        <div class="memberSpec">
                                            <img src="{{ URL::asset("img/classes/specs/" . $member->spec . ".png") }}" alt="{{ $classSpecs[$member->spec] }}"/>
                                        </div>
                                        <span class="memberPosition">{{ $loop->index+1 }}.</span>
                                        <span class="memberName">
                                            <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ $realms[$member->realm_id] }}&n={{ $member->name }}"> {{ $member->name }} </a>
                                            {{--<a href="{{ URL::to("progress/guild/" . $member->realm_id . "/" . $member->name) }}"> {{ $member->name }} </a> --}}
                                        </span>
                                        <span class="memberData memberData1">{{ number_format($member->total_heal) }}</span>
                                        <span class="memberData memberData2">({{ $member->hps }})</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-danger nomargin">
                                {{ __("Ezek az adatok hibásak ezért nem kerülnek megjelenítésre!") }}
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection