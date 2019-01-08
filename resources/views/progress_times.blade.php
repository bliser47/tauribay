<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Boss") }}</th>
        <th class="cellDesktop">{{ __("Realm") }}</th>
        <th>{{ __("Guild") }}</th>
        <th>{{ __("Legjobb idő") }}
        <th class="cellDesktop">{{ __("Dátum") }}</th>
    </tr>
    @foreach( $encounters as $encounter )
        <tr>
            <td class="cellDesktop" style="white-space:nowrap;"><a href="{{ URL::to('/progress/kills/') ."/" . $encounter["id"] }}">{{ $encounter["name"] }}</a></td>
            <td class="cellMobile" style="white-space:nowrap;"><a href="{{ URL::to('/progress/kills/') ."/" . $encounter["id"] }}">{{ $encounter["nameShort"] }}</a></td>
            <td class="cellDesktop">{{ $encounter["realm"] }}</td>
            <td class="faction-{{ $encounter["faction"] }}">
                @if ( strlen($encounter["guild"]) )
                    <a href="{{ URL::to("progress/guild/" . $encounter["realm_id"]. "/" . $encounter["guild_id"]) }}"> {{ $encounter["guild"] }} </a>
                @else
                    Random
                @endif
            </td>
            <td><a class="guildClearTime" href="{{ URL::to("/progress/kill/") . "/" . $encounter["actualId"] }}">{{ $encounter["time"]/1000 }}</a></td>
            <td class="cellDesktop">{{ date('M d, Y', $encounter["date"]) }}</td>
        </tr>
    @endforeach
</table>