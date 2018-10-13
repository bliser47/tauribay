@if ( isset($armoryData) )
     <div class="character-items">
     @foreach ( $armoryData["response"]["characterItems"] as $itemNumber => $item )
        @if ( $itemNumber == 0 )
            <div class="left-items">
        @elseif ( $itemNumber == 8 )
            <div class="right-items">
        @elseif ( $itemNumber == 16 )
            <div class="bottom-items">
        @endif
        <div class="gearItem rarityglow rarity{{ $item['rarity'] }}" >
        @if ( $item['entry'] > 0 )
              <img src="http://mop-static.tauri.hu/images/icons/large/{{ $item['icon'] }}.png">
              <a class="itemToolTip gearFrame" href="http://mop-shoot.tauri.hu/?item={{ $item['entry'] }}" id="{{ $item['guid'] }}&amp;r={{ $armoryData['response']['realm'] }}&amp;{{ $item['queryParams'] }}">
                  <span class="upgradeBox"></span>
              </a>
         @endif
         @if ( $itemNumber == 7 || $itemNumber == 15 || $itemNumber == 18)
            </div>
         @endif
         </div>
     @endforeach
     </div>
@endif