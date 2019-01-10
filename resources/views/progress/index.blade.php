@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group trade-filter" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading nopadding" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                {{ __("Szűrés") }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            {!! Form::open(array("method" => "get","id"=>"top-filter-form")) !!}
                            <div class="form-group col-md-4">
                                <legend> {{ __("Realm") }} </legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-realm">
                                        {!! Form::checkbox('tauri',2,Input::get('tauri'),array("id"=>"realm-tauri","class"=>"realm")) !!}
                                        <label for="realm-tauri"> Tauri </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-realm">
                                        {!! Form::checkbox('wod',1,Input::get('wod'),array("id"=>"realm-wod","class"=>"realm")) !!}
                                        <label for="realm-wod"> WoD </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-realm">
                                        {!! Form::checkbox('evermoon',1,Input::get('evermoon'),array("id"=>"realm-evermoon","class"=>"realm")) !!}
                                        <label for="realm-evermoon"> Evermoon </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <legend> {{ __("Frakció") }} </legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-alliance checkbox-white-tick checkbox-faction">
                                        {!! Form::checkbox('alliance',2,Input::get('alliance'),array("id"=>"faction-alliance","class"=>"faction")) !!}
                                        <label for="faction-alliance"> Alliance </label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-horde checkbox-white-tick checkbox-faction">
                                        {!! Form::checkbox('horde',1,Input::get('horde'),array("id"=>"faction-horde","class"=>"faction")) !!}
                                        <label for="faction-horde"> Horde </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <legend> {{ __("Méret") }} </legend>
                                <div class="input-group">
                                    <div class="checkbox checkbox-inline checkbox-10man checkbox-difficulty">
                                        {!! Form::checkbox('difficulty10',2,Input::get('difficulty10'),array("id"=>"difficulty-10","class"=>"difficulty")) !!}
                                        <label for="difficulty-10">10 man</label>
                                    </div>
                                    <div class="checkbox checkbox-inline checkbox-25man checkbox-difficulty">
                                        {!! Form::checkbox('difficulty25',1,Input::get('difficulty25'),array("id"=>"difficulty-25","class"=>"difficulty")) !!}
                                        <label for="difficulty-25">25 man</label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block btn-success" name="filter" value="1" type="submit">
                                {{ __("Szűrés") }}
                            </button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel table-responsive">
                <table class="table table-bordered table-classes">
                    <tr class="tHead">
                        <th class="headDesktop">{{ __("Nr.") }}</th>
                        <th class="headDesktop">{{ __("Realm") }}</th>
                        <th>{{ __("Guild") }}</th>
                        <th class="cellDesktop">{{ __("Progress") }}</th>
                        <th class="cellMobile">{{ __("Prog.") }}</th>
                        <th class="cellDesktop">{{ __("Méret") }}</th>
                        <th class="cellDesktop">{{ __("Legjobb idő") }}</th>
                        <th>{{ __("Első kill") }}</th>
                        <th class="cellDesktop"></th>
                    </tr>
                    @foreach ( $guilds as $nr => $guild )
                        <tr class="progressRow">
                            <td class="cellDesktop"> {{ $loop->index+1 }} </td>
                            <td class="cellDesktop"> {{ $shortRealms[$guild->realm] }} </td>
                            <td class="cellDesktop faction-{{ $guild->faction  }}">  <a href="{{ URL::to("guild/" . $guild->id) }}"> {{  $guild->name  }} </a>
                            <td class="cellMobile faction-{{ $guild->faction  }}">  <a href="{{ URL::to("guild/" . $guild->id) }}"> {{ strlen($guild->name) > 9 ? mb_substr($guild->name,0,9) . ".." : $guild->name  }} </a>
                            </td>
                            <td class="guildProgress"> {{ $guild->progress }}/13 </td>
                            <td class="cellDesktop"> {{ $guild->difficulty_id == 5 ? 10 : 25 }} </td>
                            <td class="cellDesktop guildClearTime">{{ $guild->clear_time > 0 ?  $guild->clear_time : "" }}</td>
                            <td class="firstKillTime">{{ $guild->first_kill_unix <= time() ? date('M d, Y', $guild->first_kill_unix) : "" }}</td>
                            <td class="updateLoaderContainer cellDesktop">
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