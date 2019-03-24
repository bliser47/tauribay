@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="bossName">
                {{ \TauriBay\Realm::REALMS_SHORT[$character->realm] . " - " . $playerName }}
            </div>
            <table class="table table-bordered table-classes">
                <tr>
                    <th>{{ __("Kaszt") }}</th>
                    <th>{{ __("Frakció") }}</th>
                    <th>iLvL</th>
                    <th>Achi</th>
                    <th>Tauri Armory</th>
                </tr>
                <tr>
                    <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                    <td class="cellDesktop faction-{{ $character->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $character->faction . ".png") }}" alt=""/> </td>
                    <td>{{ $character->ilvl }}</td>
                    <td>{{ $character->achievement_points }}</td>
                    <td><a target="_blank" href="{{ URL::to("https://tauriwow.com/armory#character-sheet.xml?r=" . \TauriBay\Realm::REALMS[$character->realm] . "&n=" . $playerName) }}">{{ __("Armory megtekíntése") }}</a></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel nomargin">
                <div id="player-response-form">
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
            </div>
        </div>
    </div>
@stop