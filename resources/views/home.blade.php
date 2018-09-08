@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default nomargin">
                <div class="panel-heading">{{ $user->name . __(" profilja") }}</div>
                <div class="panel-body login">
                    <div class="col-md-2">
                        <div class="avatarContainer">
                             <img src="/uploads/avatars/{{ $user->avatar }}">
                        </div>
                    </div>
                    <div class="col-md-10">
                         <h4> Avatar csere </h4>
                         <form enctype="multipart/form-data" action="/profile" method="POST">
                             <div class="form-group">
                                 <div class="input-group input-file" name="avatar">
                                     <span class="input-group-btn">
                                         <button class="btn btn-default btn-choose" type="button">{{__("Tallózás")}}</button>
                                     </span>
                                     <input type="text" class="form-control" placeholder='{{__("Válassz fájlt...")}}' />
                                     <span class="input-group-btn">
                                          <button class="btn btn-primary" type="submit">{{__("Avatar csere")}}</button>
                                      </span>
                                 </div>
                             </div>
                             <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
