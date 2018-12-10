@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default nomargin">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach ( $datas as $name => $data )
                        <li class="home-main-tab {{ $loop->index == 0 ? "active" : "" }}" role="presentation"><a href="#fightDataType{{ $data["id"] }}" aria-controls="fightDataType{{ $name }}" role="tab" data-toggle="tab">{{ $name }}</a></li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach ( $datas as $name => $data )
                        <div role="tabpanel" class="tab-pane {{ $loop->index == 0 ? "active" : "" }}" id="fightDataType{{ $data["id"] }}">
                            <div data-logid="{{ $logid }}" data-type="{{ $data["id"] }}" class="encounter_loading">
                                <div class="loader" style="display:block"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection