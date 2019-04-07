<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>Boss</th>
        @foreach( $specs as $specId => $specName )
            <td class="cellDesktop topDpsSpecContainer">
                <img class="topDpsSpec mobile" src="{{ URL::asset("img/classes/specs/" . $specId . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$specId] }}"/>
                {{ $specName }}
            </td>
            <td class="cellMobile topDpsSpecContainer">
                <img class="topDpsSpec mobile" src="{{ URL::asset("img/classes/specs/" . $specId . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$specId] }}"/>
            </td>
        @endforeach
    </tr>
    @foreach( $encounters as $encounter )
        <tr>
            <td style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId) . "/" . \TauriBay\Encounter::getUrlName($encounter["encounter_id"]) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId] }}">{{ \TauriBay\Encounter::getNameShort($encounter["encounter_id"]) }}</a></td>
            @foreach( $specs as $specId => $specName )
                <td class="memberDataContainer playerDataContainer">
                    <table class="table">
                        <tr>
                            <td class="autoWidth">
                                <div class="memberDataWidthContainer memberDataWidthContainer{{ $specId }}">
                                    @if ( is_array($scores[$encounter["encounter_id"]][$specId]["cache"]) && array_key_exists("score",$scores[$encounter["encounter_id"]][$specId]["cache"]) )
                                        <div style="width:{{ min(100, $scores[$encounter["encounter_id"]][$specId]["cache"]["score"]) }}%" class="memberDataWidth memberClass{{ $character->class }}"></div>
                                        <span class="memberData memberDataMiddle"><a href="{{ $scores[$encounter["encounter_id"]][$specId]["cache"]["link"] ?: "#" }}">{{ $scores[$encounter["encounter_id"]][$specId]["cache"]["score"] }}%</a></span>
                                    @endif
                                </div>
                            </td>
                            <td class="refreshSpecTopCell">
                                {!! Form::open(array("method" => "get","class"=>"refreshSpecTop" . (!is_array($scores[$encounter["encounter_id"]][$specId]["cache"]) ? " autoLoad" : ""),"url"=>$scores[$encounter["encounter_id"]][$specId]["load"])) !!}
                                <button class="refreshProgress" type="submit"></button>
                                <div class="update-loader" id="updated-loader{{$encounter["encounter_id"]."-".$specId}}"></div>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    </table>
                </td>
            @endforeach
        </tr>
    @endforeach
</table>