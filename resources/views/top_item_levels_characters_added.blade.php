<table class="table table-bordered table-classes">
     <tr>
          <th>{{ __("Realm") }}</th>
          <th>{{ __("Név") }}</th>
          <th>{{ __("Frakció") }}</th>
          <th>{{ __("Kaszt") }}</th>
          <th>{{ __("iLvL") }}</th>
          <th>{{ __("Achi") }}</th>
     </tr>
    @foreach ( $characters as $character )
        <tr id="character{{$character->id}}">
            <td> {{ $realmsShort[$character->realm]  }}</td>

            <td> <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm_id] ."/" . $character->name}}">{{ $character->name }}</a></td>
            <td class="faction-{{ $character->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $character->faction . ".png") }}" alt=""/> </td>
            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png?v=2") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
            <td> {{ $character->ilvl }}  </td>
            <td> {{ $character->achievement_points }}  </td>
        </tr>
    @endforeach
</table>
