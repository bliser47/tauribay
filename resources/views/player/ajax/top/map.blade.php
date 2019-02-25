@if ( $expansionId == 4 )
    <div class="bossName" style="background-image:url('{{ URL::asset("img/maps/" . $mapId . ".jpg") }}')">
@else
    <div class="bossName" style="background-image:url('{{ URL::asset("img/maps/default.jpg") }}')">
    @endif
    <span> {{ \TauriBay\Encounter::getMapName($expansionId, $mapId) }}</span>
</div>
<ul class="nav nav-tabs" role="tablist">
    @foreach ( $difficulties as $index => $difficulty )
        <li data-url="{{ URL::to("/ladder/pve/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] ."/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$difficulty["id"]]) }}" class="map-difficulty-tab divDesktop home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "unLoaded" }}" role="presentation"><a href="#difficulty-{{ $index }}" aria-controls="difficulty-{{ $index}}" role="tab" data-toggle="tab">{{ $difficulty["name"] }}</a></li>
        <li data-url="{{ URL::to("/ladder/pve/" . \TauriBay\Encounter::EXPANSION_SHORTS[$expansionId] ."/" . \TauriBay\Encounter::getMapUrl($expansionId, $mapId) . "/" . \TauriBay\Encounter::SIZE_AND_DIFFICULTY_URL[$difficulty["id"]]) }}" class="map-difficulty-tab divMobile home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "unLoaded" }}" role="presentation"><a href="#difficulty-{{ $index }}" aria-controls="difficulty-{{ $index}}" role="tab" data-toggle="tab">{{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficulty["id"]] }}</a></li>
    @endforeach
</ul>
<div class="tab-content">
    @foreach ( $difficulties as $index => $difficulty )
        <div role="tabpanel" class="map-difficulty tab-pane {{ $index == $defaultDifficultyIndex ? "active" : "" }}" id="difficulty-{{ $index }}">
            <div data-difficulty="{{  $difficulty["id"] }}" class="encounters_loading ajax-map-difficulty"><div class="loader" style="display:block"></div></div>
        </div>
    @endforeach
</div>