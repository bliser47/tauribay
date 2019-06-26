<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th><button style="margin:8px auto" class="refreshHeader" type="submit"></button></th>
        <th><img alt="" src="{{  URL::asset("img/award_small/1.png?v=4") }}"/></th>
        <th><img alt="" src="{{  URL::asset("img/award_small/2.png?v=4") }}"/></th>
        <th><img alt="" src="{{  URL::asset("img/award_small/3.png?v=4") }}"/></th>
    </tr>
    @foreach ( $classes as $classId => $className )
        @if ( array_key_exists($classId, $best))
            <tr>
                <td><img src="{{ URL::asset("img/classes/small/" . $classId . ".png") }}" alt="{{ $className }}"/></td>
                @foreach ( $best[$classId] as $character)
                    <td>
                        <div class="faction-{{ $character->faction }}">
                            <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] . "/" . $character->name . "/" . $character->guid }}">{{ $character->name }}</a>
                            <br/>
                        </div>
                    </td>
                @endforeach
            </tr>
        @endif
    @endforeach
</table>