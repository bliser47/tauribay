<div id="difficulty-form-response">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ( $modes as $modeKey => $modeName )
            <li id="modePanel{{ $modeKey  }}-{{ $difficultyId }}" data-mode="{{ $modeKey }}" class="modePanel map-difficulty-mode-tab-{{ $difficultyId }} home-main-tab {{ $modeKey == $modeId ? "active loaded" : "" }}" role="presentation"><a href="#{{ $modeKey }}-{{ $difficultyId }}" aria-controls="{{ $modeKey }}"  role="tab" data-toggle="tab">{{ $modeName }}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach ( $modes as $modeKey => $modeName )
            <div data-mode="{{ $modeKey }}" role="tabpanel" class="map-difficulty-mode-{{ $difficultyId }} tab-pane {{  $modeKey == $modeId ? "active loaded" : "" }}" id="{{ $modeKey }}-{{ $difficultyId }}">
                <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
                <div data-mode="{{ $modeKey }}" class="difficulty-mode-loading-container-{{ $difficultyId }}"></div>
            </div>
        @endforeach
    </div>
</div>