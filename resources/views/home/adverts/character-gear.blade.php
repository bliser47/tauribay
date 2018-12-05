@if ( isset($armoryData) )
     <script>
        {!! "var characterArmoryData = " . json_encode($armoryData) !!}
     </script>
     <div class="character-items col-md-4 col-sm-6 col-xs-12">
     @foreach ( $armoryData["response"]["characterItems"] as $itemNumber => $item )
        @if ( $itemNumber == 0 )
            <div class="left-items">
        @elseif ( $itemNumber == 8 )
                <div class="middle-items">
                    <p>{{ __("Az itemek tooltipjei a gemeket és enchantokat és reforgeokat nem, viszont az itemek Upgrade szintjét helyesen mutatják!") }}</p>
                    <p><i>{{ __("A közeljövőben a fentiek illetve a set itemek is helyesen lesznek feltüntetve.") }}</i></p>
                    <p><i>{{ __("Elnézést kérünk,") }}</i></p>
                    <p><i>Tauri Bay</i></p>
                </div>
            <div class="right-items">
        @elseif ( $itemNumber == 16 )
            <div class="bottom-items">
        @endif
        @if ( $itemNumber < 18 )
            <div class="gearItem rarityglow rarity{{ $item['rarity'] }}" >
            @if ( $item['entry'] > 0 )
                  <a class="itemToolTip gearFrame" href="http://mop-shoot.tauri.hu/?item={{ $item['entry'] }}" id="{{ $item['guid'] }}&amp;r={{ $armoryData['response']['realm'] }}&amp;{{ $item['queryParams'] }}">
                    <img src="https://wow.zamimg.com/images/wow/icons/large/{{ $item['icon'] }}.jpg">
                  </a>
             @endif
             @if ( $itemNumber == 7 || $itemNumber == 15 || $itemNumber == 17)
                </div>
             @endif
             </div>
         @endif
     @endforeach
     </div>
     <div class="col-md-8 col-sm-6 col-xs-12">
         <div class="col-sm-6">
            <h4> {{ __("Item level") }}</h4>
            <div class="form-group">
                <input disabled type="number" class="form-control" value="{{ $armoryData['response']['avgitemlevel'] }}"/>
             </div>
         </div>
         <div class="col-sm-6">
             <h4> {{ __("Achievements") }}</h4>
             <div class="form-group">
                 <input disabled type="number" class="form-control" value="{{ $armoryData['response']['pts'] }}"/>
             </div>
         </div>
     </div>
@endif