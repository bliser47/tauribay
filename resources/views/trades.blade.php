@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th>{{ __("Idő") }}</th>
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Frakció") }}</th>
                        <th>{{ __("Hirdetés") }}</th>
                    </tr>
                    @foreach ( $trades as $trade )
                         <tr>
                            <td class="time" data-time="{{$trade->created_at}}"> {{ $trade->created_at }}</td>
                            <td> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r=%5BHU%5D%20Tauri%20WoW%20Server&n={{ $trade->name }}"> {{ $trade->name }} </a></td>
                            <td class="faction-{{ $trade->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $trade->faction . ".png") }}" alt=""/> </td>
                            <td class="text-left"> {{ $trade->text }} </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $trades->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
