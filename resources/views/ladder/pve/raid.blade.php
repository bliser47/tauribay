<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Boss") }}</th>
        <th class="cellDesktop">{{ __("Realm") }}</th>
        <th>{{ __("Guild") }}</th>
        <th>{{ __("Legjobb idő") }}
        <th class="cellDesktop">{{ __("Dátum") }}</th>
        <th>{{ __("Player") }}</th>
        <th>{{ __("Top DPS") }}</th>
    </tr>
    @foreach( $encounters as $encounter )
        <tr>
            <td class="cellDesktop" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') ."/" . $encounter["encounter_id"] }}">{{ \TauriBay\Encounter::getName($encounter["encounter_id"]) }}</a></td>
            <td class="cellMobile" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') ."/" . $encounter["encounter_id"] }}">{{ $encounter["encounter_id"]}}</a></td>
            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS_SHORT[intval($encounter["realm_id"])] }}</td>
            <td class="faction-{{ $encounter["faction"] }}">
                @if ( strlen($encounter["guild_name"]) )
                    <a href="{{ URL::to("/guild/" . $encounter["guild_id"]) }}"> {{ $encounter["guild_name"] }} </a>
                @else
                    Random
                @endif
            </td>
            <td><a class="guildClearTime" href="{{ URL::to("/encounter/") . "/" . $encounter["log_id"] }}">{{ $encounter["fight_time"]/1000 }}</a></td>
            <td class="cellDesktop">{{ date('M d, Y', $encounter["killtime"]) }}</td>
            <td>{{ $encounter["top_dps"]["name"] }}</td>
            <td>{{ \TauriBay\Tauri\Skada::format($encounter["top_dps"]["dps"]) }}</td>
        </tr>
    @endforeach
</table>