<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Nr") }}</th>
        <th class="cellMobile" colspan="2">{{ __("Player") }}</th>
        <th class="cellDesktop" colspan="4">{{ __("Player") }}</th>
        <th>{{ strtoupper($modeId) }}</th>
        <th class="cellDesktop">{{ __("Dátum") }}</th>
        <th class="cellDesktop">{{ __("iLvL") }}</th>
        <th class="cellDesktop">{{ __("Idő") }}</th>
    </tr>
    @foreach( $members as $nr => $member )
        <tr>
            <td><b>{{ (($members->currentPage()-1)*16)+$nr+1  }}</b></td>
            <td class="topDpsSpecContainer">
                <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $member["spec"] . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$member["spec"]] }}"/>
            </td>
            <td><a href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member["realm_id"]] ."/" . $member["name"] }}">{{ $member["name"] }}</a></td>
            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS_SHORT[intval($member["realm_id"])] }}</td>
            <td class="cellDesktop faction-{{ $member["faction"] }}">
                @if ( strlen($member["guild_name"]) )
                    <a href="{{ URL::to("/guild/" . $member["guild_id"]) }}"> {{ $member["guild_name"] }} </a>
                @else
                    Random
                @endif
            </td>
            <td class="cellDesktop"><a target="_blank" href="{{ URL::to("/encounter/") . "/" . \TauriBay\Encounter::getUrlName($member["encounter"]) . "/" . $member["encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($member[$modeId], true) }}</a></td>
            <td class="cellMobile"><a target="_blank" href="{{ URL::to("/encounter/") . "/" . \TauriBay\Encounter::getUrlName($member["encounter"]) . "/" . $member["encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($member[$modeId]) }}</a></td>
            <td class="cellDesktop">{{ date('M d, Y', $member["killtime"])}}</td>
            <td class="cellDesktop">{{ $member["ilvl"] }}</td>
            <td class="cellDesktop"><a target="_blank" class="encounterKillTime" href="{{ URL::to("/encounter/") . "/" . \TauriBay\Encounter::getUrlName($member["encounter"]) . "/" . $member["encounter_id"] }}">{{ $member["fight_time"]/1000 }}</a></td>
        </tr>
    @endforeach
</table>
<div class="text-center paginator">
    <div>
        {{ $members->appends(Illuminate\Support\Facades\Input::except('page')) }}
    </div>
</div>