@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            @foreach ( $data as $id => $map )
                <div class="panel-group trade-filter progressKillAccordion" id="accordion{{ $id }}" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading nopadding" role="tab" id="heading{{ $map["name"] }}">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion{{ $id }}" href="#throneOfThunderTimes{{ $id }}" aria-expanded="false" aria-controls="collapseTwo">
                                    {{ $map["name"] }}
                                </a>
                            </h4>
                        </div>
                        <div id="throneOfThunderTimes{{ $id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $map["name"] }}">
                            <div class="panel table-responsive">
                                <table class="table table-bordered table-classes">
                                    <tr>
                                        <th>{{ __("Realm") }}</th>
                                        <th>{{ __("Frakió") }}</th>
                                        <th>{{ __("Boss") }}</th>
                                        <th>{{ __("Guild") }}</th>
                                        <th>{{ __("Legjobb idő") }}</th>
                                    </tr>
                                    @foreach( $map["encounters"] as $encounter )
                                    <tr>
                                        <td>{{ $encounter["realm"] }}</td>
                                        <td class="faction-{{ $encounter["faction"] }}">
                                            @if ( $encounter["faction"] >= 0 )
                                            <img src="{{ URL::asset("img/factions/small/" . ($encounter["faction"] == 1 ? 1 : 2) . ".png") }}" alt=""/>
                                            @endif
                                        </td>
                                        <td><a>{{ $encounter["name"] }}</a></td>
                                        <td>{{ $encounter["guild"] }}</td>
                                        <td class="guildClearTime">{{ $encounter["time"]/1000 }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
