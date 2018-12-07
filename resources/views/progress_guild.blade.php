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
                        <th>{{ __("Progress") }}</th>
                        <th></th>
                    </tr>
                    @foreach ( $data as $key => $d )
                        <tr>
                            <td> {{ $key+1 }} </td>
                            <td> {{ $shortRealms[$d->realm] }} </td>
                            <td> {{ $d->name }} </td>
                            <td> {{ TauriBay\GuildProgress::getProgression($d->id) }} </td>
                            <td>
                                <div class="update-loader" id="updated-loader{{$d->id}}"></div>
                                {!! Form::open(array("method" => "post","class"=>"ilvlupdate-form")) !!}
                                <input type="hidden" name="name" value="{{$d->name}}">
                                <input type="hidden" name="realm" value="{{$d->realm}}">
                                <input type="hidden" name="refreshProgress" value="1">
                                <button class="refreshProgress" name="updateProgress" value="1" type="submit"></button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="text-center">
                <div>
                    {{ $data->appends(Illuminate\Support\Facades\Input::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@stop
