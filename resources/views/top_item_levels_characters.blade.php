@foreach ( $characters as $nr => $character )
    <tr id="character{{$character->id}}" class="charRow">
        <td><b>{{ (($characters->currentPage()-1)*16)+$nr+1  }}</b></td>
        <td class="cellDesktop faction-{{ $character->faction }}"> {{ $realmsShort[$character->realm]  }}</td>
        <td class="cellDesktop faction-{{ $character->faction }}"> <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] ."/" . $character->name . "/" . $character->guid }}">{{ $character->name }}</a></td>
        <td class="cellMobile"><a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] ."/" . $character->name . "/" . $character->guid }}">{{ strlen($character->name) > 6 ? (mb_substr($character->name,0,6) . "..") : $character->name }}</a></td>
        <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png?v=2") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
        <td class="topItemLevel {{(!Input::has('sort') || Input::get('sort') == 'ilvl') ? 'columnActive'  : 'columnInactive'}}"> {{ $character->ilvl }}  </td>
        <td class="topAchievementPoints {{(Input::get('sort') == 'achievement_points') ? 'columnActive'  : 'columnInactive'}}"> {{ $character->achievement_points }}  </td>
        <td class="topScore {{(Input::get('sort') == 'score') ? 'columnActive'  : 'columnInactive'}}"> {{ number_format($character->score,2) }}  </td>
        <td>
            <div class="update-loader" id="updated-loader{{$character->id}}"></div>
            {!! Form::open(array("method" => "post","class"=>"ilvlupdate-form")) !!}
                <input type="hidden" name="name" value="{{$character->name}}">
                <input type="hidden" name="guid" value="{{$character->guid}}">
                <input type="hidden" name="realm" value="{{$character->realm}}">
                <input type="hidden" name="refreshTop" value="1">
                <button class="refreshIlvl" name="updateCharacter" value="1" type="submit"></button>
            {!! Form::close() !!}
        </td>
        <td class="time cellDesktop topUpdateAt" data-time="{{$character->updated_at}}"> {{ $character->updated_at }} </td>
    </tr>
@endforeach