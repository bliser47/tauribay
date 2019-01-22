<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Boss") }}</th>
        <th colspan="2" class="cellMobile">{{ __("Legjobb idő") }}
        <th colspan="4" class="cellDesktop">{{ __("Legjobb idő") }}
        <th class="cellDesktop" colspan="3">{{ __("Top DPS") }}</th>
        <th colspan="2" class="cellMobile">{{ __("Top DPS") }}</th>
    </tr>
    @foreach( $encounters as $encounter )
        <tr>
            <td class="cellDesktop" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId). "/" . \TauriBay\Encounter::getUrlName($encounter["encounter_id"]) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId] }}">{{ \TauriBay\Encounter::getName($encounter["encounter_id"]) }}</a></td>
            <td class="cellMobile" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId) . "/" . \TauriBay\Encounter::getUrlName($encounter["encounter_id"]) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId] }}">{{ \TauriBay\Encounter::getNameShort($encounter["encounter_id"]) }}</a></td>
            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS_SHORT[intval($encounter["realm_id"])] }}</td>
            <td class="cellDesktop faction-{{ $encounter["faction"] }}">
                @if ( strlen($encounter["guild_name"]) )
                    <a href="{{ URL::to("/guild/" . $encounter["guild_id"]) }}"> {{ $encounter["guild_name"] }} </a>
                @else
                    Random
                @endif
            </td>
            <td class="cellMobile faction-{{ $encounter["faction"] }}">
                @if ( strlen($encounter["guild_name"]) )
                    <a href="{{ URL::to("/guild/" . $encounter["guild_id"]) }}"> {{ \TauriBay\Guild::getShortName($encounter["guild_name"]) }} </a>
                @else
                    Random
                @endif
            </td>
            <td><a class="encounterKillTime" target="_blank" href="{{ URL::to("/encounter/") . "/" . \TauriBay\Encounter::getUrlName($encounter["encounter_id"]) . "/" . $encounter["id"] }}">{{ $encounter["fight_time"]/1000 }}</a></td>
            <td class="cellDesktop">{{ date('M d, Y', $encounter["killtime"]) }}</td>
            @if ( $encounter["top_dps"] )
                <td class="topDpsSpecContainer">
                    <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $encounter["top_dps"]["spec"] . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$encounter["top_dps"]["spec"]] }}"/>
                </td>
                {{--<td class="cellDesktop"><a href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$encounter["top_dps"]["realm_id"]] ."/" . $encounter["top_dps"]["name"] }}">{{ $encounter["top_dps"]["name"] }}</a></td>--}}
                <td class="cellDesktop"><a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ \TauriBay\Realm::REALMS[$encounter["top_dps"]["realm_id"]] }}&n={{ $encounter["top_dps"]["name"] }}"> {{  $encounter["top_dps"]["name"] }} </a></td>
                <td><a target="_blank" href="{{ URL::to("/encounter/") . "/" . \TauriBay\Encounter::getUrlName( $encounter["top_dps"]["encounter"]) . "/" . $encounter["top_dps"]["encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($encounter["top_dps"]["dps"]) }}</a></td>
            @else
                <td></td>
                <td class="cellDesktop"></td>
                <td></td>
            @endif
        </tr>
    @endforeach
</table>