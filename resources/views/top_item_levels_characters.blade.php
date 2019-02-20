@foreach ( $characters as $nr => $character )
    <tr id="character{{$character->id}}" class="charRow">
        <td><b>{{ (($characters->currentPage()-1)*16)+$nr+1  }}</b></td>
        <td class="cellDesktop"> {{ $realmsShort[$character->realm]  }}</td>
        <td class="cellDesktop"> <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] ."/" . $character->name }}">{{ $character->name }}</a></td>
        <td class="cellMobile"><a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] ."/" . $character->name }}">{{ strlen($character->name) > 6 ? (mb_substr($character->name,0,6) . "..") : $character->name }}</a></td>
        <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png?v=2") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
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
        <td class="time cellDesktop" data-time="{{$character->updated_at}}"> {{ $character->updated_at }} </td>
    </tr>
@endforeach