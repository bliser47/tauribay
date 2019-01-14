<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Nr") }}</th>
        <th>{{ __("Guild") }}</th>
        <th>{{ __("Dátum") }}</th>
        <th>{{ __("Idő") }}</th>
    </tr>
    @foreach( $encounters as $nr => $encounter )
        <tr>
            <td><b>{{ $nr+1 }}</b></td>
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
            <td class="cellDesktop">{{ date('M d, Y', $encounter->killtime) }}</td>
            <td class="cellMobile">{{ date('M d', $encounter->killtime) }}</td>
            <td><a class="guildClearTime" target="_blank" href="{{ URL::to("/encounter/") . "/" . TauriBay\Encounter::getUrlName($encounter->encounter_id) . "/" . $encounter->id }}">{{ $encounter->fight_time/1000  }}</a></td>
        </tr>
    @endforeach
</table>