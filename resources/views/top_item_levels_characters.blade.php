@foreach ( $characters as $character )
    @if ( $wrapper )
        <tr id="character{{$character->id}}">
    @endif
        <td class="time" data-time="{{$character->updated_at}}"> {{ $character->updated_at }} </td>
        <td> {{ $realmsShort[$character->realm]  }}</td>
        <td> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ $realms[$character->realm] }}&n={{ $character->name }}"> {{ $character->name }} </a></td>
        <td class="faction-{{ $character->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $character->faction . ".png") }}" alt=""/> </td>
        <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
        <td> {{ $character->ilvl }}  </td>
        <td>
            <div class="update-loader" id="updated-loader{{$character->id}}"></div>
            {!! Form::open(array("method" => "post","class"=>"ilvlupdate-form")) !!}
                <input type="hidden" name="name" value="{{$character->name}}">
                <input type="hidden" name="realm" value="{{$character->realm}}">
                <button class="refreshIlvl" name="updateCharacter" value="1" type="submit"></button>
            {!! Form::close() !!}
        </td>
     @if ( $wrapper )
        </tr>
     @endif
@endforeach