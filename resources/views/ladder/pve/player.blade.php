<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th><button style="margin:3px auto" class="refreshHeader" type="submit"></button></th>
        <th><img height="21px" src="{{ URL::asset("img/roles/tank.png") }}" alt="Tank"/></th>
        <th><img height="21px" src="{{ URL::asset("img/roles/dps.png") }}" alt="DPS"/></th>
        <th><img height="21px" src="{{ URL::asset("img/roles/healer.png") }}" alt="Heal"/></th>
    </tr>
    @for ( $i = 0 ; $i < 100 ; ++$i )
        <tr>
            <td>
                @if ( $i+1 < 4 )
                    <img alt="" src="{{  URL::asset("img/award_small/" . ($i+1) . ".png?v=4") }}"/>
                @else
                    <b>{{ $i+1 }}</b>
                @endif
            </td>
            @foreach ( $roles as $roleId )
                <td class="faction-{{ $best[$roleId][$i]->faction }}">
                    <img width="24px" height="24px" alt="" src="{{  URL::asset("img/classes/small/" . $best[$roleId][$i]->class . ".png?v=4") }}"/>
                    <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$best[$roleId][$i]->realm] . "/" . $best[$roleId][$i]->name . "/" . $best[$roleId][$i]->guid }}">{{ $best[$roleId][$i]->name }}</a>
                </td>
            @endforeach
         </tr>
    @endfor
</table>