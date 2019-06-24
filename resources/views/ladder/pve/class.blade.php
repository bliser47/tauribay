<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Kaszt") }}</th>
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
                    @if ( array_key_exists($roleId, $best[$classId]))
                            <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$best[$classId][$roleId]->realm] . "/" . $best[$classId][$roleId]->name . "/" . $best[$classId][$roleId]->guid }}">{{ $best[$classId][$roleId]->name }}</a>
                    @endif
                    </td>
                @endforeach
            </tr>
        @endif
    @endforeach
</table>