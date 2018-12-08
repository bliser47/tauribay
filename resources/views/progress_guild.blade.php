@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr>
                        <th>{{ __("Nr.") }}</th>
                        <th>{{ __("Realm") }}</th>
                        <th>{{ __("Guild") }}</th>
                        <th>{{ __("Frakció") }}</th>
                        <th>{{ __("Progress") }}</th>
                        <th>{{ __("Méret") }}</th>
                        <th>{{ __("Legjobb idő") }}</th>
                        <th></th>
                    </tr>
                    @foreach ( $guilds as $nr => $guild )
                        <tr class="progressRow">
                            <td> {{ $loop->index+1 }} </td>
                            <td> {{ $shortRealms[$guild->realm] }} </td>
                            <td> {{ $guild->name }} </td>
                            <td class="faction-{{ $guild->faction  }}">
                                <img src="{{ URL::asset("img/factions/small/" . ($guild->faction == 1 ? 1 : 2) . ".png") }}" alt=""/>
                            </td>
                            <td class="guildProgress"> {{ $guild->progress }}/13 </td>
                            <td> {{ $guild->difficulty_id == 5 ? 10 : 25 }} </td>
                            <td class="guildClearTime">{{ $guild->clear_time > 0 ?  $guild->clear_time : "" }}</td>
                            <td>
                                <div class="update-loader" id="updated-loader{{$guild->id}}"></div>
                                {!! Form::open(array("method" => "post","class"=>"progressupdate-form")) !!}
                                <input type="hidden" name="name" value="{{$guild->name}}">
                                <input type="hidden" name="realm" value="{{$guild->realm}}">
                                <input type="hidden" name="refreshProgress" value="1">
                                <button class="refreshProgress" name="updateProgress" value="1" type="submit"></button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop
