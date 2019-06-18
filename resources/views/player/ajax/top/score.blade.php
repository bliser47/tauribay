<div class="alert alert-info nomargin">
    {{ __("Az adatok ezen az oldalon Class alapon vannak összehasonlítva. Erre kivételt tesznek a Druidok és Shamanok akiknél Spec alapon van összehasonlítve a DPS-ek.") }}
</div>
<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>Boss</th>
        <th colspan="2">Personal</th>
        <th colspan="3">Best</th>
    </tr>
    @foreach( $encounters as $encounterId => $encounter )
        @if ( array_key_exists("best",$scores[$encounterId]) )
            <tr>
                <td style="white-space:nowrap;"><a href="{{ URL::to('/ladder/pve/') . "/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] . "/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId) . "/" . \TauriBay\Encounter::getUrlName($encounterId) }}">{{ \TauriBay\Encounter::getNameShort($encounterId) }}</a></td>
                <td class="topDpsSpecContainer">
                    <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $scores[$encounterId]["encounter_" . $scores[$encounterId]["best"]]["spec"] . ".png") }}" alt="{{ $classSpecs[$scores[$encounterId]["encounter_" . $scores[$encounterId]["best"]]["spec"]] }}"/>
                </td>
                <td class="memberDataContainer playerDataContainer">
                    <div class="memberDataWidthContainer memberDataWidthContainer{{  $scores[$encounterId]["encounter_" . $scores[$encounterId]["best"]]["spec"] }}">
                        <div style="width:{{ min(100, $scores[$encounterId][$scores[$encounterId]["best"]]) }}%" class="memberDataWidth memberClass{{ $scores[$encounterId]["encounter_" . $scores[$encounterId]["best"]]["class"]}}"></div>
                        <span class="memberData memberDataMiddle"><a href="{{ URL::to("/encounter/") . "/" . TauriBay\Encounter::getUrlName($encounterId) ."/" . $scores[$encounterId]["encounter_" . $scores[$encounterId]["best"]][$scores[$encounterId]["best"] . "_encounter_id"] }}">{{ number_format($scores[$encounterId][$scores[$encounterId]["best"]],2) }}%</a></span>
                    </div>
                </td>
                <td class="topDpsSpecContainer">
                    <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $scores[$encounterId]["top_" . $scores[$encounterId]["best"]]["spec"] . ".png") }}" alt="{{ $classSpecs[$scores[$encounterId]["encounter_" . $scores[$encounterId]["best"]]["spec"]] }}"/>
                </td>
                <td>
                    <a href="{{ URL::to("/player/" . \TauriBay\Realm::REALMS_URL[$scores[$encounterId]["top_" . $scores[$encounterId]["best"]]["realm_id"]] . "/" . $scores[$encounterId]["top_" . $scores[$encounterId]["best"]]["name"] . "/" . $scores[$encounterId]["top_" . $scores[$encounterId]["best"]]["guid"]) }}">{{ $scores[$encounterId]["top_" . $scores[$encounterId]["best"]]["name"] }}</a>
                </td>
                <td>
                    <a href="{{ URL::to("/encounter/") . "/" . TauriBay\Encounter::getUrlName($encounterId) . "/" . $scores[$encounterId]["top_" . $scores[$encounterId]["best"]][$scores[$encounterId]["best"] . "_encounter_id"] }}"><b>{{ \TauriBay\Tauri\Skada::format($scores[$encounterId]["top_" . $scores[$encounterId]["best"]][$scores[$encounterId]["best"]]) }}</b></a>
                </td>
            </tr>
        @endif
    @endforeach
</table>