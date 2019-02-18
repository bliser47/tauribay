@extends('layouts.default')

@section('content')
    <div class="bossName" style="background-image:url('{{ URL::asset("img/maps/default.jpg") }}')">
        <span> {{ $playerTitle }}</span>
    </div>
    <div id="player-form-response">
        <ul class="nav nav-tabs" role="tablist">
            @foreach ( $modes as $modeKey => $modeName )
                <li id="modePanel{{ $modeKey  }}" data-mode="{{ $modeKey }}" class="modePanel home-main-tab {{ $modeKey == $modeId ? "active" : "" }}" role="presentation"><a href="#{{ $modeKey }}" aria-controls="{{ $modeKey }}"  role="tab" data-toggle="tab">{{ $modeName }}</a></li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach ( $modes as $modeKey => $modeName )
                <div data-mode="{{ $modeKey }}" role="tabpanel" class="tab-pane {{  $modeKey == $modeId ? "active" : "" }}" id="{{ $modeKey}}">
                    <div class="encounters_loading"><div class="loader" style="display:block"></div></div>
                    <div data-mode="{{ $modeKey }}" class="encounter-mode-loading-container"></div>
                </div>
            @endforeach
        </div>
    </div>
@endsection