<div class="bossName">
    {{ \TauriBay\Encounter::getName($encounterId)  }}
</div>
<ul class="nav nav-tabs" role="tablist">
    @foreach ( $difficulties as $index => $difficulty )
        <li id="difficultyPanel{{$difficulty["id"]  }}" data-difficulty="{{ $difficulty["id"] }}" class="difficultyPanel divDesktop home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "" }}" role="presentation"><a href="#difficulty-{{ $difficulty["id"] }}" aria-controls="difficulty-{{ $difficulty["id"] }}"  role="tab" data-toggle="tab">{{ $difficulty["name"] }}</a></li>
        <li id="difficultyPanel{{$difficulty["id"]  }}" data-difficulty="{{ $difficulty["id"] }}" class="difficultyPanel divMobile home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "" }}" role="presentation"><a href="#difficulty-{{ $difficulty["id"] }}" aria-controls="difficulty-{{ $difficulty["id"] }}"  role="tab" data-toggle="tab">{{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficulty["id"]] }}</a></li>
    @endforeach
</ul>
<div class="tab-content">
    @foreach ( $difficulties as $index => $difficulty )
        <div role="tabpanel" class="tab-pane {{ $index == $defaultDifficultyIndex ? "active" : "" }}" id="difficulty-{{ $difficulty["id"] }}">
            <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
            <div data-difficulty="{{ $difficulty["id"] }}" class="encounter-difficulty-loading-container {{ $index == $defaultDifficultyIndex ? "active" : ""}}"></div>
        </div>
    @endforeach
</div>