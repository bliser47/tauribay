<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Boss") }}</th>
        <th>{{ __("Nehézség") }}</th>
        <th>{{ __("Dátum") }}</th>
        <th>{{ __("Idő") }}</th>
        <th></th>
        <th>{{ __("DPS") }}</th>
        <th>{{ __("DPS Score") }}</th>
        <th>{{ __("HPS") }}</th>
        <th>{{ __("HPS Score") }}</th>
    </tr>
    @foreach( $playerEncounters as $encounter )
        @if ( array_key_exists($encounter->encounter, $encounterIDs))
            <tr>
                <td class="cellMobile" style="white-space:nowrap;">{{ array_key_exists($encounterIDs[$encounter->encounter]["name"],TauriBay\Encounter::ENCOUNTER_NAME_SHORTS) ? TauriBay\Encounter::ENCOUNTER_NAME_SHORTS[$encounterIDs[$encounter->encounter]["name"]] : $encounterIDs[$encounter->encounter]["name"] }}</td>
                <td class="cellDesktop" style="white-space:nowrap;">{{ $encounterIDs[$encounter->encounter]["name"] }}</td>
                <td class="cellDesktop">{{ TauriBay\Encounter::SIZE_AND_DIFFICULTY[$encounter->difficulty_id] }}</td>
                <td class="cellMobile">{{ TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$encounter->difficulty_id] }}</td>
                <td class="cellDesktop">{{ date('M d, Y', $encounter->killtime) }}</td>
                <td class="cellMobile">{{ date('M d', $encounter->killtime) }}</td>
                <td><a class="guildClearTime" target="_blank" href="{{ URL::to("/encounter/") . "/" . TauriBay\Encounter::getUrlName($encounter->encounter) . "/" . $encounter->encounter_id }}">{{ $encounter->fight_time  }}</a></td>
                <td class="topDpsSpecContainer">
                    <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $encounter->spec . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$encounter->spec] }}"/>
                </td>
                <td>{{ \TauriBay\Tauri\Skada::format($encounter->dps) }}</td>
                <td class="memberDataContainer playerDataContainer">
                    <div class="memberDataWidthContainer">
                        <div style="width:{{ $encounter->dps_score }}%" class="memberDataWidth memberClass{{ $encounter->class }}"></div>
                        <span class="memberData memberData2">{{ $encounter->dps_score }}%</span>
                    </div>
                </td>
                <td>{{ \TauriBay\Tauri\Skada::format($encounter->hps) }}</td>
                <td class="memberDataContainer playerDataContainer">
                    <div class="memberDataWidthContainer">
                        <div style="width:{{ $encounter->hps_score }}%" class="memberDataWidth memberClass{{ $encounter->class }}"></div>
                        <span class="memberData memberData2">{{ $encounter->hps_score }}%</span>
                    </div>
                </td>
            </tr>
        @endif
    @endforeach
</table>