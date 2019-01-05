@extends('layouts.default')
@section('content')

    <div class="row">
        <div class="col-md-12 panel table-responsive">
            <table class="table table-bordered">
                <tr>
                    <td width="90px">
                        <a class="btn btn-default" href="{{ URL::to("/progress/guild") }}">
                            {{ __("Vissza") }}
                        </a>
                    </td>
                    <td class="bossName">
                        {{ $realm . " - " . $guild["name"]  }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default nomargin">
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th>{{ __("Boss") }}</th>
                        <th>{{ __("Nehézség") }}</th>
                        <th>{{ __("Dátum") }}</th>
                        <th>{{ __("Idő") }}</th>
                    </tr>
                    @foreach( $guildEncounters as $encounter )
                        @if ( array_key_exists($encounter->encounter_id, $encounterIDs))
                            <tr>
                                <td style="white-space:nowrap;">{{ $encounterIDs[$encounter->encounter_id]["name"] }}</td>
                                <td>{{ TauriBay\Encounter::SIZE_AND_DIFFICULTY[$encounter->difficulty_id] }}</td>
                                <td>{{ date('M d, Y', $encounter->killtime) }}</td>
                                <td><a class="guildClearTime" href="{{ URL::to("/progress/kill/") . "/" . $encounter->id }}">{{ $encounter->fight_time/1000  }}</a></td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $guildEncounters->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
