@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel panel-default nomargin">
                 <ul class="nav nav-tabs" role="tablist">
                    <li class="home-main-tab active" role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{__("Profil")}}</a></li>
                    {{--<li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">{{__("Üzenetek")}}</a></li>--}}
                    {{--<li role="presentation"><a href="#mytrades" aria-controls="mytrades" role="tab" data-toggle="tab">{{__("Hirdetéseim")}}</a></li>--}}
                    <li class="home-main-tab" role="presentation"><a href="#newtrade" aria-controls="newtrade" role="tab" data-toggle="tab">{{__("Új hirdetés")}}</a></li>
                    <li class="home-main-tab" role="presentation"><a href="#oauth" aria-controls="oauth" role="tab" data-toggle="tab">Tauri OAuth</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="profile">@include('home.profile')</div>
                    <div role="tabpanel" class="tab-pane" id="messages">@include('home.messages')</div>
                    <div role="tabpanel" class="tab-pane" id="mytrades">@include('home.mytrades')</div>
                    <div role="tabpanel" class="tab-pane" id="newtrade">@include('home.newtrade')</div>
                    <div role="tabpanel" class="tab-pane" id="oauth">@include('home.oauth')</div>
                </div>
            </div>
        </div>
    </div>
@endsection
