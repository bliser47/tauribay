<ul class="nav nav-tabs" role="tablist">
    @foreach ( $difficulties as $index => $difficulty )
        <li class="divDesktop home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "" }}" role="presentation"><a href="#difficulty-{{ $index }}" aria-controls="difficulty-{{ $index}}" role="tab" data-toggle="tab">{{ $difficulty["name"] }}</a></li>
        <li class="divMobile home-main-tab {{ $index == $defaultDifficultyIndex ? "active" : "" }}" role="presentation"><a href="#difficulty-{{ $index }}" aria-controls="difficulty-{{ $index}}" role="tab" data-toggle="tab">{{ \TauriBay\Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficulty["id"]] }}</a></li>
    @endforeach
</ul>
<div class="tab-content">
    @foreach ( $difficulties as $index => $difficulty )
        <div role="tabpanel" class="tab-pane {{ $index == $defaultDifficultyIndex ? "active" : "" }}" id="difficulty-{{ $index }}">
            <table class="table table-bordered table-classes">
                <tr class="tHead">

                </tr>
                @foreach( $encounters[$difficulty["id"]] as $encounter )
                    <tr>

                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach
</div>