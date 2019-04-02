<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>Boss</th>
        @foreach( $specs as $specId => $specName )
            <th class="topDpsSpecContainer">
                <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $specId . ".png") }}" alt="{{ $specName}}"/> {{ $specName }}
            </th>
        @endforeach
    </tr>
    @foreach( $encounters as $encounter )
        <tr>
            <td class="cellDesktop" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId). "/" . \TauriBay\Encounter::getUrlName($encounter["encounter_id"]) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId] }}">{{ \TauriBay\Encounter::getName($encounter["encounter_id"]) }}</a></td>
            <td class="cellMobile" style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId) . "/" . \TauriBay\Encounter::getUrlName($encounter["encounter_id"]) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId] }}">{{ \TauriBay\Encounter::getNameShort($encounter["encounter_id"]) }}</a></td>
            @foreach( $specs as $specId => $specName )
                <td  class="memberDataContainer playerDataContainer">
                    <div class="memberDataWidthContainer">
                        <div style="width:{{ min(100, $scores[$encounter["encounter_id"]][$specId]["score"]) }}%" class="memberDataWidth memberClass{{ $character->class }}"></div>
                        <span class="memberData memberData2">{{ $scores[$encounter["encounter_id"]][$specId]["score"] }}%</span>
                    </div>
                </td>
            @endforeach
        </tr>
    @endforeach
</table>