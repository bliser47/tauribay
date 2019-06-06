<div style="width:{{ min(100, $spec["score"]) }}%" class="memberDataWidth memberClass{{ $classId }}"></div>
<span class="divDesktop memberData memberDataLeft">{{ $spec["dps"] }}</span>
<span class="divDesktop memberData memberData2"><a href="{{ $spec["link"] ?: "#" }}">{{ $spec["score"] }}%</a></span>
<span class="divMobile memberData memberDataMiddle"><a href="{{ $spec["link"] ?: "#" }}">{{ $spec["score"] }}%</a></span>
