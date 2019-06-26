<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th><button style="margin:3px auto" class="refreshHeader" type="submit"></button></th>
        <th><img height="21px" src="{{ URL::asset("img/roles/tank.png") }}" alt="Tank"/></th>
        <th><img height="21px" src="{{ URL::asset("img/roles/dps.png") }}" alt="DPS"/></th>
        <th><img height="21px" src="{{ URL::asset("img/roles/healer.png") }}" alt="Heal"/></th>
    </tr>
    @foreach ( $classes as $classId => $className )
        @if ( array_key_exists($classId, $best))
            <tr>
                <td><img src="{{ URL::asset("img/classes/small/" . $classId . ".png") }}" alt="{{ $className }}"/></td>
                @foreach ( $roles as $roleId)
                    <td>
                        <div class="playerRolesContainer">
                        @if ( array_key_exists($roleId, $best[$classId]))
                            @foreach ( $best[$classId][$roleId] as $index => $character )
                                <div class="playerRoleContainer faction-{{ $character->faction }}">
                                    <img width="16px" height="16px" alt="" src="{{  URL::asset("img/award_small/" . ($index+1) . ".png?v=4") }}"/>
                                    <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] . "/" . $character->name . "/" . $character->guid }}">{{ $character->name }}</a>
                                    <br/>
                                </div>
                            @endforeach
                        @endif
                        </div>
                    </td>
                @endforeach
            </tr>
        @endif
    @endforeach
</table>