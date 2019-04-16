@foreach ( $members as $character )
    <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
        <div class="memberDataWidthContainer">
            <div style="width:{{ $character->scorePercentage }}%" class="memberDataWidth memberClass{{ $character->class }}"></div>
            <div class="memberSpec">
                <img src="{{ URL::asset("img/classes/specs/" . $character->spec . ".png") }}" alt="{{ $classSpecs[$character->spec] }}"/>
            </div>
            <span class="memberPosition">{{ $loop->index+1 }}.</span>
            <span class="memberName">
                <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] . "/" . $character->name . "/" . $character->guid }}">{{ $character->name }}</a>
            </span>
            <span class="memberData memberData2">{{ \TauriBay\Tauri\Skada::format($character->totalMode) }}%</span>
        </div>
    </div>
@endforeach