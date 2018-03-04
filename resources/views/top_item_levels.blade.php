@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group trade-filter" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading nopadding" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                {{ __("Karakter hozzáadása") }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">

                            {!! Form::open(array("method" => "get","id"=>"newcharacter-form")) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr>
                        <th>{{ __("Idő") }}</th>
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Frakció") }}</th>
                        <th>{{ __("Kaszt") }}</th>
                        <th>{{ __("iLvL") }}</th>
                    </tr>
                    @foreach ( $characters as $character )
                        <tr>
                            <td class="time" data-time="{{$character->updated_at}}"> {{ $character->updated_at }}</td>
                            <td> <a target="_blank" href="https://tauriwow.com/armory#character-sheet.xml?r=%5BHU%5D%20Tauri%20WoW%20Server&n={{ $character->name }}"> {{ $character->name }} </a></td>
                            <td class="faction-{{ $character->faction  }}"> <img src="{{ URL::asset("img/factions/small/" . $character->faction . ".png") }}" alt=""/> </td>
                            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                            <td> {{ $character->ilvl }} </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $characters->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
