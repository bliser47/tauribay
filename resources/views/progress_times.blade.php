<table class="table table-bordered table-classes">
    <tr>
        <th>{{ __("Boss") }}</th>
        <th>{{ __("Realm") }}</th>
        <th>{{ __("Guild") }}</th>
        <th>{{ __("Legjobb idő") }}</th>
    </tr>
    @foreach( $encounters as $encounter )
        <tr>
            <td style="white-space:nowrap;"><a href="{{ URL::to('/progress/kills/') ."/" . $encounter["id"] }}">{{ $encounter["name"] }}</a></td>
            <td>{{ $encounter["realm"] }}</td>
            <td class="faction-{{ $encounter["faction"] }}">
                @if ( strlen($encounter["guild"]) )
                    <a target="_blank" href="https://tauriwow.com/armory#guild-info.xml?r={{ $encounter["realmLong"] }}&gn={{ $encounter["guild"]}}">{{ $encounter["guild"] }}</a>
                @else
                    Random
                @endif
            </td>
            <td><a class="guildClearTime" href="{{ URL::to("/progress/kill/") . "/" . $encounter["actualId"] }}">{{ $encounter["time"]/1000 }}</a></td>
        </tr>
    @endforeach
</table>