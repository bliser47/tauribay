@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default nomargin">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach ( $data as $id => $map )
                        <li class="home-main-tab {{ $id == 1098 ? "active" : "" }}" role="presentation"><a href="#map{{ $id }}" aria-controls="map{{ $id }}" role="tab" data-toggle="tab">{{ $map["name"] }}</a></li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach ( $data as $id => $map )
                        <div role="tabpanel" class="tab-pane {{ $id == 1098 ? "active" : "" }}" id="map{{ $id }}">
                            <div data-mapid="{{ $id }}" class="encounters_loading">
                                <div class="loader" style="display:block"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection