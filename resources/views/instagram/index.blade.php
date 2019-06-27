@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-nopadding">
            <div class="panel panel-default nomargin">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation"><a href="#photos" aria-controls="character" role="tab" data-toggle="tab"><i class="fa fa-photo"></i> {{__("Fotók")}}</a></li>
                    <li role="presentation"><a href="#upload" aria-controls="character" role="tab" data-toggle="tab"><i class="fa fa-upload"></i> {{__("Feltöltés")}}</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="photos">@include('instagram.photos')</div>
                    <div role="tabpanel" class="tab-pane" id="upload">@include('instagram.upload')</div>
                </div>
            </div>
        </div>
    </div>
@stop