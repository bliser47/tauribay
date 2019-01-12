<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Nr") }}</th>
        <th class="cellMobile" colspan="2">{{ __("Player") }}</th>
        <th class="cellDesktop" colspan="4">{{ __("Player") }}</th>
        <th>{{ __("DPS") }}</th>
        <th class="cellDesktop">{{ __("iLvL") }}</th>
        <th class="cellDesktop">{{ __("Idő") }}</th>
    </tr>
    @foreach( $members as $nr => $member )
        <tr>
            <td><b>{{ (($members->currentPage()-1)*16)+$nr+1  }}</b></td>
            <td class="topDpsSpecContainer">
                <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $member["spec"] . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$member["spec"]] }}"/>
            </td>
            <td class="cellDesktop"><a href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member["realm_id"]] ."/" . $member["name"] }}">{{ $member["name"] }}</a></td>
            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS_SHORT[intval($member["realm_id"])] }}</td>
            <td class="cellDesktop faction-{{ $member["faction"] }}">
                @if ( strlen($member["guild_name"]) )
                    <a href="{{ URL::to("/guild/" . $member["guild_id"]) }}"> {{ $member["guild_name"] }} </a>
                @else
                    Random
                @endif
            </td>
            <td><a href="{{ URL::to("/encounter/") . "/" . $member["encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($member["dps"]) }}</a></td>
            <td>{{ $member["ilvl"] }}</td>
            <td class="encounterKillTime">{{ $member["fight_time"]/1000 }}</td>
        </tr>
    @endforeach
</table>