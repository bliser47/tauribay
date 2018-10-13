<div class="panel-body login">
    <div class="col-md-2">
        <div class="avatarContainer">
             <img src="/uploads/avatars/{{ $user->avatar }}">
        </div>
    </div>
    <div class="col-md-10">
         <h4>{{__("Avatar csere")}}</h4>
         <form enctype="multipart/form-data" class="change-avatar-form" action="/profile/avatar" method="POST">
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
                 <div class="change-form-errors">
                     <div id="avatar-error-container"></div>
                 </div>
             </div>
             <input type="hidden" name="_token" value="{{ csrf_token() }}">
         </form>
          <h4>{{__("Jelszó csere")}}</h4>
          <form enctype="multipart/form-data" class="change-password-form" action="/profile/password" method="POST">
                <div class="input-group">
                    <label for="new_password" generated="true" class="error"></label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="{{ __("Új jelszó") }}">
                    <span id="change-password-splitter" class="input-group-addon">-</span>
                    <label for="new_password_confirm" generated="true" class="error"></label>
                    <input type="password" class="form-control" name="password_confirmation" placeholder="{{ __("Új jelszó újra") }}">
                    <span class="input-group-btn">
                      <button class="btn btn-block btn-danger" name="changepassword" value="1" type="submit">
                          {{ __("Jelszó csere") }}
                      </button>
                   </span>
                </div>
                <div class="change-form-errors">
                  <div id="password-error-container">
                       @if(session("passwordSuccess"))
                           <span class="help-block flashit"> {{ __("Jelszó frissítve!") }} </span>
                       @endif
                       @if(isset($passwordErrors))
                          @foreach ($passwordErrors->get('password') as $error)
                            <span class="help-block flashit">{{ __($error) }}</span>
                          @endforeach
                       @endif
                  </div>
                  <div id="password_confirmation-error-container">
                        @if(isset($passwordErrors))
                          @foreach ($passwordErrors->get('password_confirmation') as $error)
                            <span class="help-block flashit">{{ __($error) }}</span>
                          @endforeach
                       @endif
                  </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
          </form>
    </div>
</div>