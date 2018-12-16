<div class="table table-bordered table-classes">
    @foreach ( $members as $member )
        <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
            <div class="memberDataWidthContainer">
                <div class="memberDataWidth memberClass{{ $member->class }}" data-current="{{ $member->damage_done }}" data-total="{{ $totalDmg }}"></div>
                <div class="memberSpec">
                    <img src="{{ URL::asset("img/classes/specs/" . $member->spec . ".png") }}" alt="{{ $classSpecs[$member->spec] }}"/>
                </div>
                <span class="memberPosition">{{ $loop->index+1 }}.</span>
                <span class="memberName">
                    <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ $realms[$member->realm_id] }}&n={{ $member->name }}"> {{ $member->name }} </a>
                </span>
                <span class="memberData">{{ $member->dps }}</span>
            </div>
        </div>
    @endforeach
</div>