<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Nr") }}</th>
        <th class="cellMobile" colspan="2">{{ __("Player") }}</th>
        <th class="cellDesktop" colspan="3">{{ __("Player") }}</th>
        <th>{{ strtoupper($modeId) }}</th>
        <th class="cellDesktop">{{ __("Dátum") }}</th>
        <th class="cellDesktop">{{ __("iLvL") }}</th>
        <th class="cellDesktop">{{ __("Idő") }}</th>
    </tr>
    @foreach( $members as $index => $member )
        <tr>
            <td>
                @if (  $index < 3 )
                    <img alt="" src="{{  URL::asset("img/award_small/" . ($index+1) . ".png?v=4") }}"/>
                @else
                    <b>{{ $index+1 }}</b>
                @endif
                {{--
                @if (  (($members->currentPage()-1)*10)+$index < 3 )
                    <img alt="" src="{{  URL::asset("img/award_small/" . ($index+1) . ".png?v=4") }}"/>
                @else
                    <b>{{ (($members->currentPage()-1)*10)+$index+1  }}</b>
                @endif
                --}}
            </td>
            <td class="topDpsSpecContainer">
                <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $member["spec"] . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$member["spec"]] }}"/>
            </td>
            <td><a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member["realm_id"]] ."/" . $member["name"] }}">{{ $member["name"] }}</a></td>
            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS_SHORT[intval($member["realm_id"])] }}</td>
            <td class="cellDesktop"><a target="_blank" href="{{ URL::to("/encounter/") . "/" . $encounterName . "/". $member[$modeId."_encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($member[$modeId], true) }}</a></td>
            <td class="cellMobile"><a target="_blank" href="{{ URL::to("/encounter/") . "/" . $encounterName . "/". $member[$modeId."_encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($member[$modeId]) }}</a></td>
            <td class="cellDesktop">{{ date('M d, Y', $member[$modeId."_encounter_killtime"]) }}</td>
            <td class="cellDesktop">{{ $member[$modeId."_ilvl"] }}</td>
            <td class="cellDesktop"><a target="_blank" class="encounterKillTime" href="{{ URL::to("/encounter/") . "/" . $encounterName . "/" . $member[$modeId."_encounter_id"] }}">{{ $member[$modeId."_encounter_fight_time"] / 1000 }}</a></td>
        </tr>
    @endforeach
</table>
{{--
<div class="text-center paginator">
    <div class="divDesktop">
        {{ $members->appends(Illuminate\Support\Facades\Input::except('page')) }}
    </div>
    <div class="divMobile">
        <ul class="pagination pagination-sm">
            @if ($members->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $members->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            @if($members->currentPage() > 3)
                <li class="page-item hidden-xs"><a class="page-link" href="{{ $members->url(1) }}">1</a></li>
            @endif

            @if($members->currentPage() > 4)
                <li class="page-item disabled hidden-xs"><span class="page-link">...</span></li>
            @endif

            @foreach(range(1, $members->lastPage()) as $i)
                @if($i >= $members->currentPage() - 2 && $i <= $members->currentPage() + 2)
                    @if ($i == $members->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                    @else
                        <li class="page-item" ><a class="page-link" href="{{ $members->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endforeach

            @if($members->currentPage() < $members->lastPage() - 3)
                <li class="page-item disabled hidden-xs"><span class="page-link">...</span></li>
            @endif

            @if($members->currentPage() < $members->lastPage() - 2)
                <li class="page-item hidden-xs"><a class="page-link" href="{{ $members->url($members->lastPage()) }}">{{ $members->lastPage() }}</a></li>
            @endif

            @if ($members->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $members->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            @endif
        </ul>
    </div>
</div>
--}}