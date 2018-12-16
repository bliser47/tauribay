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
                        <th>{{ __("Időtartam") }}</th>
                        <th>{{ __("Wipe-ok") }}</th>
                        <th>{{ __("Összes halál") }}</th>
                        <th>{{ __("Halálok") }}</th>
                    </tr>
                    <tr>
                        <td>{{ $shortRealms[$encounter->realm_id]  }}</td>
                        <td style="white-space:nowrap;"><a href="{{ URL::to("progress/kills/" . $encounter->encounter_id) }}">{{ $encounteData["name"]  }}</a></td>
                        <td>
                            @if ( strlen($encounter->name) )
                                <a target="_blank" href="https://tauriwow.com/armory#guild-info.xml?r={{ $realms[$encounter->realm_id] }}&gn={{ $encounter->name }}">{{ $encounter->name }}</a>
                            @else
                                Random
                            @endif
                        </td>
                        <td class="guildClearTime">{{ $encounter->fight_time/1000 }}</td>
                        <td>{{ $encounter->wipes }}</td>
                        <td>{{ $encounter->deaths_total }}</td>
                        <td>{{ $encounter->deaths_fight }}</td>
                    </tr>
                </table>
            </div>
            <div class="panel panel-default nomargin">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="home-main-tab active" role="presentation"><a href="#fightDataDamage" aria-controls="fightDataDamage" role="tab" data-toggle="tab">Damage</a></li>
                    <li class="home-main-tab disabled" role="presentation"><a>Healing</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="fightDataDamage">
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
                    </div>
                    {{--
                    <div role="tabpanel" class="tab-pane" id="fightDataHealing">
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
                                    </span>
                                    <span class="memberData memberData1">{{ number_format($member->total_heal) }}</span>
                                    <span class="memberData memberData2">({{ $member->hps }})</span>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
@endsection