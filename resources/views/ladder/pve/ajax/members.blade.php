
<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>
            <button class="refreshHeader" type="submit"></button>
        </th>
        <th class="cellMobile" colspan="2">HEADER_PLAYER</th>
        <th class="cellDesktop" colspan="3">Player</th>
        <th>{{ strtoupper($modeId) }}</th>
        <th class="cellDesktop">HEADER_DATE</th>
        <th class="cellDesktop">iLvL</th>
        <th class="cellDesktop">HEADER_TIME</th>
    </tr>
    @foreach( $members as $index => $member )
        <tr>
            <td>
                @if (  $index < 3 )
                    <img alt="" src="{{  URL::asset("img/award_small/" . ($index+1) . ".png?v=4") }}"/>
                @else
                    <b>{{ $index+1 }}</b>
                @endif
            </td>
            <td class="topDpsSpecContainer">
                <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $member["spec"] . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$member["spec"]] }}"/>
            </td>
            <td class="faction-{{ $member["faction_id"] }}"><a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member["realm_id"]] ."/" . $member["name"] ."/" . $member["guid"]}}">{{ $member["name"] }}</a></td>
            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS_SHORT[intval($member["realm_id"])] }}</td>
            <td class="cellDesktop"><a target="_blank" href="{{ URL::to("/encounter/") . "/" . $encounterName . "/". $member["encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($member[$modeId], true) }}</a></td>
            <td class="cellMobile"><a target="_blank" href="{{ URL::to("/encounter/") . "/" . $encounterName . "/". $member["encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($member[$modeId]) }}</a></td>
            <td class="cellDesktop">{{ date('M d, Y', $member["encounter_killtime"]) }}</td>
            <td class="cellDesktop">{{ $member["ilvl"] }}</td>
            <td class="cellDesktop"><a target="_blank" class="encounterKillTime" href="{{ URL::to("/encounter/") . "/" . $encounterName . "/" . $member["encounter_id"] }}">{{ $member["encounter_fight_time"] / 1000 }}</a></td>
        </tr>
    @endforeach
</table>