@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel">
                {!! Form::open(array("method" => "post","class"=>"gdkp-apply-form")) !!}
                <div class="input-group col-md-12">
                    {!! Form::select('character_id', $characters, null, ['required', 'id' => 'authorized_characters', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz karaktert")]); !!}
                </div>
                <button class="btn btn-block btn-success" name="filter" value="1" type="submit">
                    {{ __("Jelentkezés") }}
                </button>
                {!! Form::close() !!}
                <br/>
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th>{{ __("Név") }}</th>
                        <th>{{ __("Kaszt") }}</th>
                        <th>Score</th>
                        <th>Állapot</th>
                    </tr>
                    @foreach ( $applied as $character )
                        <tr id="character{{$character->id}}">
                            <td> <a target="_blank" href="{{ URL::to("/player/") . "/" . \TauriBay\Realm::REALMS_URL[$character->realm] ."/" . $character->name . "/" . $character->guid}}">{{ $character->name }}</a></td>
                            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png?v=2") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
                            <td> {{ $character->score }}  </td>
                            <td></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop