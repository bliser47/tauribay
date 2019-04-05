@foreach ( $membersDamageTaken as $member )
    <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
        <div class="memberDataWidthContainer">
            <div style="width:{{ $member->percentageDamageTaken }}%" class="memberDataWidth memberClass{{ $member->class }}"></div>
            <div class="memberSpec">
                <img src="{{ URL::asset("img/classes/specs/" . $member->spec . ".png") }}" alt="{{ $classSpecs[$member->spec] }}"/>
            </div>
            <span class="memberPosition">{{ $loop->index+1 }}.</span>
            <span class="memberName">
                                        <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member->realm_id] ."/" . $member["name"] . "/" . $member->guid }}">{{ $member->name }}</a>
                                    </span>
            <span class="memberData memberData1 divDesktop">{{ number_format($member->damage_absorb) }} abs</span>
            <span class="memberData memberData1 divMobile">{{ \TauriBay\Tauri\Skada::format($member->damage_absorb) }} abs</span>
            <span class="memberData memberData2">{{  \TauriBay\Tauri\Skada::format($member->total_damage_taken) }}</span>
        </div>
    </div>
@endforeach