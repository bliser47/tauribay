@foreach ( $characters as $nr => $character )
    <tr id="character{{$character->id}}" class="rowDesktop charRow">
        <td><b>{{ (($characters->currentPage()-1)*16)+$nr+1  }}</b></td>
        <td class="time" data-time="{{$character->updated_at}}"> {{ $character->updated_at }} </td>
        <td> {{ $realmsShort[$character->realm]  }}</td>
        <td> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ $realms[$character->realm] }}&n={{ $character->name }}"> {{ $character->name }} </a></td>
        <td class="faction-{{ $character->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $character->faction . ".png") }}" alt=""/> </td>
        <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
        <td class="topItemLevel {{(!Input::has('sort') || Input::get('sort') == 'ilvl') ? 'columnActive'  : 'columnInactive'}}"> {{ $character->ilvl }}  </td>
        <td class="topAchievementPoints {{(!Input::has('sort') || Input::get('sort') == 'ilvl') ? 'columnInactive'  : 'columnActive'}}"> {{ $character->achievement_points }}  </td>
        <td>
            <div class="update-loader" id="updated-loader{{$character->id}}"></div>
            {!! Form::open(array("method" => "post","class"=>"ilvlupdate-form")) !!}
                <input type="hidden" name="name" value="{{$character->name}}">
                <input type="hidden" name="realm" value="{{$character->realm}}">
                <input type="hidden" name="refreshTop" value="1">
                <button class="refreshIlvl" name="updateCharacter" value="1" type="submit"></button>
            {!! Form::close() !!}
        </td>
    </tr>
    <tr id="character{{$character->id}}" class="rowMobile charRow factionRow faction-{{ $character->faction  }}-row">
        <td><b>{{ (($characters->currentPage()-1)*16)+$nr+1  }}</b></td>
        <td> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ $realms[$character->realm] }}&n={{ $character->name }}"> {{ $character->name }} </a></td>
        <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
        <td class="topItemLevel {{(!Input::has('sort') || Input::get('sort') == 'ilvl') ? 'columnActive'  : 'columnInactive'}}"> {{ $character->ilvl }}  </td>
        <td class="topAchievementPoints {{(!Input::has('sort') || Input::get('sort') == 'ilvl') ? 'columnInactive'  : 'columnActive'}}"> {{ $character->achievement_points }}  </td>
    </tr>
@endforeach