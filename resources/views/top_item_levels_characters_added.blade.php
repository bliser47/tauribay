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
            <td> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ $realms[$character->realm] }}&n={{ $character->name }}"> {{ $character->name }} </a></td>
            <td class="faction-{{ $character->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $character->faction . ".png") }}" alt=""/> </td>
            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
            <td> {{ $character->ilvl }}  </td>
            <td> {{ $character->achievement_points }}  </td>
        </tr>
    @endforeach
</table>
