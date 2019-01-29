<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Nr") }}</th>
        <th>{{ __("Guild") }}</th>
        <th>{{ __("Dátum") }}</th>
        <th>{{ __("Idő") }}</th>
    </tr>
    @php $nr = 1 @endphp
    @foreach( $encounters as $index => $encounter )
        <tr>
            <td><b>{{ (($encounters->currentPage()-1)*10)+$nr  }}</b></td>
            <td class="cellDesktop faction-{{ $encounter["faction"] }}">
                @if ( strlen($encounter["guild_name"]) )
                    <a href="{{ URL::to("/guild/" . $encounter["guild_id"]) }}"> {{ $encounter["guild_name"] }} </a>
                @else
                    Random
                @endif
            </td>
            <td class="cellMobile faction-{{ $encounter["faction"] }}">
                @if ( strlen($encounter["guild_name"]) )
                    <a href="{{ URL::to("/guild/" . $encounter["guild_id"]) }}"> {{ \TauriBay\Guild::getShortName($encounter["guild_name"]) }} </a>
                @else
                    Random
                @endif
            </td>
            <td class="cellDesktop">{{ date('M d, Y', $encounter->killtime) }}</td>
            <td class="cellMobile">{{ date('M d', $encounter->killtime) }}</td>
            <td><a class="guildClearTime" target="_blank" href="{{ URL::to("/encounter/") . "/" . TauriBay\Encounter::getUrlName($encounter->encounter_id) . "/" . $encounter->id }}">{{ $encounter->fight_time/1000  }}</a></td>
        </tr>
        @php ++$nr @endphp
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
