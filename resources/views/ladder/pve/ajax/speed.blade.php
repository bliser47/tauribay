<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Nr") }}</th>
        <th>{{ __("Guild") }}</th>
        <th>{{ __("Dátum") }}</th>
        <th>{{ __("Idő") }}</th>
    </tr>
    @foreach( $encounters as $index=> $encounter )
        <tr>
            <td>
                @if ( ((($encounters->currentPage()-1)*10)+$index)  < 3 )
                    <img alt="" src="{{  URL::asset("img/award_small/" . ($index+1)  . ".png?v=4") }}"/>
                @else
                    <b>{{ (($encounters->currentPage()-1)*10)+($index+1)  }}</b>
                @endif
            </td>
            <td class="cellDesktop faction-{{ $encounter["faction_id"] }}">
                @if ( strlen($encounter["name"]) )
                    <a href="{{ URL::to("/guild/" . $encounter["guild_id"]) }}"> {{ $encounter["name"] }} </a>
                @else
                    <span class="random">Random ({{ \TauriBay\Realm::getShortNameFromID($encounter["realm_id"]) }})</span>
                @endif
            </td>
            <td class="cellMobile faction-{{ $encounter["faction_id"] }}">
                @if ( strlen($encounter["name"]) )
                    <a href="{{ URL::to("/guild/" . $encounter["guild_id"]) }}"> {{ \TauriBay\Guild::getShortName($encounter["name"]) }} </a>
                @else
                    <span class="random">Random ({{ \TauriBay\Realm::getShortestNameFromID($encounter["realm_id"]) }})</span>
                @endif
            </td>
            <td class="cellDesktop">{{ date('M d, Y', $encounter->fastest_encounter_date) }}</td>
            <td class="cellMobile">{{ date('M d', $encounter->fastest_encounter_date) }}</td>
            <td><a class="guildClearTime" target="_blank" href="{{ URL::to("/encounter/") . "/" . TauriBay\Encounter::getUrlName($encounter->encounter_id) . "/" . $encounter->fastest_encounter_id }}">{{ $encounter->fastest_encounter_time/1000  }}</a></td>
        </tr>
    @endforeach
</table>
<div class="text-center paginator">
    <div class="divDesktop">
        {{ $encounters->appends(Illuminate\Support\Facades\Input::except('page')) }}
    </div>
    <div class="divMobile">
        <ul class="pagination pagination-sm">
            @if ($encounters->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $encounters->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            @if($encounters->currentPage() > 3)
                <li class="page-item hidden-xs"><a class="page-link" href="{{ $encounters->url(1) }}">1</a></li>
            @endif

            @if($encounters->currentPage() > 4)
                <li class="page-item disabled hidden-xs"><span class="page-link">...</span></li>
            @endif

            @foreach(range(1, $encounters->lastPage()) as $i)
                @if($i >= $encounters->currentPage() - 2 && $i <= $encounters->currentPage() + 2)
                    @if ($i == $encounters->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                    @else
                        <li class="page-item" ><a class="page-link" href="{{ $encounters->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endforeach

            @if($encounters->currentPage() < $encounters->lastPage() - 3)
                <li class="page-item disabled hidden-xs"><span class="page-link">...</span></li>
            @endif

            @if($encounters->currentPage() < $encounters->lastPage() - 2)
                <li class="page-item hidden-xs"><a class="page-link" href="{{ $encounters->url($encounters->lastPage()) }}">{{ $encounters->lastPage() }}</a></li>
            @endif

            @if ($encounters->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $encounters->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            @endif
        </ul>
    </div>
</div>
