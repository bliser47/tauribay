@if ( isset($armoryData) )
     <script>
        {!! "var characterArmoryData = " . json_encode($armoryData) !!}
     </script>
     <div class="character-items col-md-4">
     @foreach ( $armoryData["response"]["characterItems"] as $itemNumber => $item )
        @if ( $itemNumber == 0 )
            <div class="left-items">
        @elseif ( $itemNumber == 8 )
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
     <div class="col-md-8">
            <legend> {{ __("Item level") }}</legend>
            <div class="form-group">
                 <div class="input-group col-md-12">
                        <input disabled type="number" class="form-control" value="{{ $armoryData['response']['avgitemlevel'] }}"/>
                 </div>
             </div>
             <legend> {{ __("Achievements") }}</legend>
             <div class="form-group">
                  <div class="input-group col-md-12">
                         <input disabled type="number" class="form-control" value="{{ $armoryData['response']['pts'] }}"/>
                  </div>
              </div>
     </div>
@endif