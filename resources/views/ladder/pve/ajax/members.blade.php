<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Nr") }}</th>
        <th class="cellMobile" colspan="2">{{ __("Player") }}</th>
        <th class="cellDesktop" colspan="4">{{ __("Player") }}</th>
        <th>{{ strtoupper($modeId) }}</th>
        <th class="cellDesktop">{{ __("Dátum") }}</th>
        <th class="cellDesktop">{{ __("iLvL") }}</th>
        <th class="cellDesktop">{{ __("Idő") }}</th>
    </tr>
    @php $nr = 1 @endphp
    @foreach( $members as $member )
        <tr>
            <td>
                @if (  $nr < 4 )
                    <img alt="" src="{{  URL::asset("img/award_small/" . $nr . ".png?v=4") }}"/>
                @else
                    <b>{{ $nr }}</b>
                @endif
            </td>
            <td class="topDpsSpecContainer">
                <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $member["spec"] . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$member["spec"]] }}"/>
            </td>
            {{--<td><a href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$member["realm_id"]] ."/" . $member["name"] }}">{{ $member["name"] }}</a></td>--}}
            <td><a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r={{ \TauriBay\Realm::REALMS[$member["realm_id"]] }}&n={{ $member["name"] }}">{{ $member["name"] }}</a></td>
            <td class="cellDesktop">{{ \TauriBay\Realm::REALMS_SHORT[intval($member["realm_id"])] }}</td>
            <td class="cellDesktop faction-{{ $member["faction"] }}">
                @if ( strlen($member["guild_name"]) )
                    <a href="{{ URL::to("/guild/" . $member["guild_id"]) }}"> {{ $member["guild_name"] }} </a>
                @else
                    Random
                @endif
            </td>
            <td class="cellDesktop"><a target="_blank" href="{{ URL::to("/encounter/") . "/" . \TauriBay\Encounter::getUrlName($member["encounter"]) . "/" . $member["encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($member[$modeId], true) }}</a></td>
            <td class="cellMobile"><a target="_blank" href="{{ URL::to("/encounter/") . "/" . \TauriBay\Encounter::getUrlName($member["encounter"]) . "/" . $member["encounter_id"] }}">{{  \TauriBay\Tauri\Skada::format($member[$modeId]) }}</a></td>
            <td class="cellDesktop">{{ date('M d, Y', $member["killtime"])}}</td>
            <td class="cellDesktop">{{ $member["ilvl"] }}</td>
            <td class="cellDesktop"><a target="_blank" class="encounterKillTime" href="{{ URL::to("/encounter/") . "/" . \TauriBay\Encounter::getUrlName($member["encounter"]) . "/" . $member["encounter_id"] }}">{{ $member["fight_time"]/1000 }}</a></td>
        </tr>
        @php ++$nr @endphp
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