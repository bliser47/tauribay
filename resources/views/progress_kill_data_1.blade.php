<div class="table table-bordered table-classes">
    <div class="dataBackground"></div>
    @foreach ( $members as $member )
        <div class="{{ $loop->index == 0 ? "memberDataContainerFirst" : "" }} memberDataContainer">
            <div class="memberDataWidthContainer">
                <div class="memberDataWidth memberClass{{ $member->class }}" data-current="{{ $member->damage_done }}" data-total="{{ $totalDmg }}"></div>
                <span class="memberName">{{ ($loop->index+1) . ". " . $member->name  }}</span>
                <span class="memberData">{{  $member->dps }}</span>
            </div>
        </div>
    @endforeach
</div>