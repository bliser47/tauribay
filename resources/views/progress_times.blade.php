<table class="table table-bordered table-classes">
    <tr>
        <th>{{ __("Boss") }}</th>
        <th>{{ __("Realm") }}</th>
        <th>{{ __("Frakió") }}</th>
        <th>{{ __("Guild") }}</th>
        <th>{{ __("Legjobb idő") }}</th>
    </tr>
    @foreach( $encounters as $encounter )
        <tr>
            <td style="white-space:nowrap;"><a href="{{ URL::to('/progress/kills/') ."/" . $encounter["id"] }}">{{ $encounter["name"] }}</a></td>
            <td>{{ $encounter["realm"] }}</td>
            <td class="faction-{{ $encounter["faction"] }}">
                @if ( $encounter["faction"] >= 0 )
                    <img src="{{ URL::asset("img/factions/small/" . ($encounter["faction"] == 1 ? 1 : 2) . ".png") }}" alt=""/>
                @endif
            </td>
            <td>{{ $encounter["guild"] }}</td>
            <td class="guildClearTime">{{ $encounter["time"]/1000 }}</td>
        </tr>
    @endforeach
</table>