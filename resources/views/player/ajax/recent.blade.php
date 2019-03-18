<table class="table table-classes table-transparent">
    @if ( count($encounters) )
        <tr class="tHead">
            <th>{{ __("Boss") }}</th>
            <th class="headDesktop">{{ __("Nehézség") }}</th>
            <th class="headMobile">Dif</th>
            <th>{{ __("Dátum") }}</th>
            <th>{{ __("Idő") }}</th>
            <th></th>
            <th>iLvL</th>
            <th class="headDesktop">{{ __("DPS") }}</th>
            <th class="headDesktop">{{ __("DPS Score") }}</th>
            <th class="headDesktop">{{ __("HPS") }}</th>
            <th class="headDesktop">{{ __("HPS Score") }}</th>
        </tr>
        @foreach( $encounters as $encounter )
            <tr>
                <td class="cellMobile" style="white-space:nowrap;">{{ array_key_exists($encounterIDs[$encounter->encounter]["name"],TauriBay\Encounter::ENCOUNTER_NAME_SHORTS) ? TauriBay\Encounter::ENCOUNTER_NAME_SHORTS[$encounterIDs[$encounter->encounter]["name"]] : $encounterIDs[$encounter->encounter]["name"] }}</td>
                <td class="cellDesktop" style="white-space:nowrap;">{{ $encounterIDs[$encounter->encounter]["name"] }}</td>
                <td class="cellDesktop">{{ TauriBay\Encounter::SIZE_AND_DIFFICULTY[$encounter->difficulty_id] }}</td>
                <td class="cellMobile">{{ TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$encounter->difficulty_id] }}</td>
                <td class="cellDesktop">{{ date('M d, Y', $encounter->killtime) }}</td>
                <td class="cellMobile">{{ date('M d', $encounter->killtime) }}</td>
                <td class="cellDesktop"><a class="guildClearTime" target="_blank" href="{{ URL::to("/encounter/") . "/" . TauriBay\Encounter::getUrlName($encounter->encounter) . "/" . $encounter->encounter_id }}">{{ $encounter->fight_time/1000  }}</a></td>
                <td class="cellMobile"><a class="guildClearTimeMobile" target="_blank" href="{{ URL::to("/encounter/") . "/" . TauriBay\Encounter::getUrlName($encounter->encounter) . "/" . $encounter->encounter_id }}">{{ $encounter->fight_time/1000  }}</a></td>
                <td class="divDesktop topDpsSpecContainer">
                    <img class="topDpsSpec" src="{{ URL::asset("img/classes/specs/" . $encounter->spec . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$encounter->spec] }}"/>
                </td>
                <td class="divMobile topDpsSpecContainer">
                    <img class="topDpsSpec mobile" src="{{ URL::asset("img/classes/specs/" . $encounter->spec . ".png") }}" alt="{{ \TauriBay\Tauri\CharacterClasses::CLASS_SPEC_NAMES[$encounter->spec] }}"/>
                </td>
                <td>{{ $encounter->ilvl }}</td>
                <td class="cellDesktop">{{ \TauriBay\Tauri\Skada::format($encounter->dps) }}</td>
                <td class="cellDesktop memberDataContainer playerDataContainer">
                    <div class="memberDataWidthContainer">
                        <div style="width:{{ min(100,$encounter->dps_score) }}%" class="memberDataWidth memberClass{{ $encounter->class }}"></div>
                        <span class="memberData memberData2">{{ $encounter->dps_score }}%</span>
                    </div>
                </td>
                <td class="cellDesktop">{{ \TauriBay\Tauri\Skada::format($encounter->hps) }}</td>
                <td class="cellDesktop memberDataContainer playerDataContainer">
                    <div class="memberDataWidthContainer">
                        <div style="width:{{ min(100,$encounter->hps_score) }}%" class="memberDataWidth memberClass{{ $encounter->class }}"></div>
                        <span class="memberData memberData2">{{ $encounter->hps_score }}%</span>
                    </div>
                </td>
            </tr>
            <tr class="rowMobile">
                @if ( $canHeal )
                    <td colspan="3" class="memberDataContainer playerDataContainer">
                        <div class="memberDataWidthContainer">
                            <div style="width:{{ min(100,$encounter->dps_score) }}%" class="memberDataWidth memberClass{{ $encounter->class }}"></div>
                            <span class="memberData memberDataLeft"><b>DPS</b> {{ \TauriBay\Tauri\Skada::format($encounter->dps) }}</span>
                            <span class="memberData memberData2">{{ $encounter->dps_score }}%</span>
                        </div>
                    </td>
                    <td colspan="3" class="memberDataContainer playerDataContainer">
                        <div class="memberDataWidthContainer">
                            <div style="width:{{ min(100,$encounter->hps_score) }}%" class="memberDataWidth memberClass{{ $encounter->class }}"></div>
                            <span class="memberData memberDataLeft"><b>HPS</b> {{ \TauriBay\Tauri\Skada::format($encounter->hps) }}</span>
                            <span class="memberData memberData2">{{ $encounter->hps_score }}%</span>
                        </div>
                    </td>
                @else
                    <td colspan="6" class="memberDataContainer playerDataContainer">
                        <div class="memberDataWidthContainer">
                            <div style="width:{{ min(100,$encounter->dps_score) }}%" class="memberDataWidth memberClass{{ $encounter->class }}"></div>
                            <span class="memberData memberDataLeft"><b>DPS</b> {{ \TauriBay\Tauri\Skada::format($encounter->dps) }}</span>
                            <span class="memberData memberData2">{{ $encounter->dps_score }}%</span>
                        </div>
                    </td>
                @endif
            </tr>
            <tr class="rowMobile spacer"><td colspan="6"></td></tr>
        @endforeach
    @else
        <div class="alert alert-warning nomargin">
            {{ __("A visszamenőleges karakter bossfight adatok jelenleg feldolgozás alatt vannak. Kérjük látogass vissza később!") }}
        </div>
    @endif
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
