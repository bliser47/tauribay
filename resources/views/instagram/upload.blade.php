@if (Auth::guest())
    <div class="col-md-12">
        @include('auth.login_content')
    </div>
@else
    <div class="panel-body">
        <p>
            {{ __("Mai feltöltött képek: ") . $photosToday . "/1" }}
        </p>
        @if ( $photosToday < \TauriBay\Http\Controllers\InstagramController::MAX_PHOTOS )
            <form enctype="multipart/form-data" class="change-avatar-form" action="/insta" method="POST">
                <div class="form-group nomargin">
                    <div class="input-group input-file" name="photo">
                         <span class="input-group-btn">
                             <button class="btn btn-default btn-choose" type="button">{{__("Tallózás")}}</button>
                         </span>
                        <input type="text" class="form-control" placeholder='{{__("Válassz fájlt...")}}' />
                        <span class="input-group-btn">
                              <button class="btn btn-primary" type="submit">{{__("Feltöltés")}}</button>
                        </span>
                    </div>
                    <div class="change-form-errors">
                        <div id="avatar-error-container"></div>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        @endif
    </div>
@endif
