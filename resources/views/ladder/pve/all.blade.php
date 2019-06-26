<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th><button style="margin:3px auto" class="refreshHeader" type="submit"></button></th>
        <th>Realm</th>
        <th>Class</th>
        <th>HEADER_PLAYER</th>
        <th>Score</th>
    </tr>
    @foreach ( $best as $i => $character )
        <tr>
            <td>
                @if ( $i+1 < 4 )
                    <img alt="" src="{{  URL::asset("img/award_small/" . ($i+1) . ".png?v=4") }}"/>
                @else
                    <b>{{ $i+1 }}</b>
                @endif
            </td>
            <td>
                {{ \TauriBay\Realm::REALMS_SHORT[$character->realm] }}
            </td>
            <td>
                <img alt="" src="{{  URL::asset("img/classes/small/" . $character->class . ".png?v=4") }}"/>
            </td>
            <td class="faction-{{ $character->faction }}">
                <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] . "/" . $character->name . "/" . $character->guid }}">{{ $character->name }}</a>
            </td>
            <td class="topScore">
                {{ $character->$columnName }}
            </td>
        </tr>
    @endforeach
</table>