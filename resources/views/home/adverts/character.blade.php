<div class="panel-body">
    {!! Form::open(array("method" => "get","id"=>"new-character-advert")) !!}


    <div id="ad-intent-section" class="form-group col-md-6">
        <legend> {{ __("Szándék") }}</legend>
        <div class="form-group">
            <div class="input-group col-md-12">
                {!! Form::select('intent', $adIntents, null, ['required', 'id' => 'ad-intent', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz szándékot")]); !!}
            </div>
        </div>
    </div>

     <div id="ad-realm-section" class="form-group col-md-6 disabled-ad-section">
         <legend> {{ __("Realm") }} </legend>
         <div id="trade-realm" class="form-group ad-realm inactive-ad-realm">
            <div class="input-group col-md-12">
                {!! Form::select('realm', $realms, null, ['required', 'id' => 'ad-realm', 'class' => "control selectpicker input-large", 'placeholder' =>  __("Válassz realmet")]); !!}
            </div>
         </div>
         <div id="buy-realm" class="form-group ad-realm inactive-ad-realm">
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
                 <div class="checkbox checkbox-inline checkbox-all-realms">
                     {!! Form::checkbox('realmall',3,Input::get('realmall'),array("id"=>"realm-all","class"=>"realm")) !!}
                     <label for="realm-all"> {{ __("Mind") }} </label>
                 </div>
             </div>
         </div>
    </div>
    <div id="ad-character-section" class="form-group col-md-12 disabled-ad-section">
        <legend> {{ __("Karakter") }}</legend>
        <h5> {{ __("Add meg a karakter nevét: ") }} </h5>
        <div class="input-group col-md-12">
            <input disabled required="required" id="ad-character" type="text" class="form-control" name="characterName" value="{!! Input::get('characterName') !!}" placeholder="{{ __("Karakter neve") }}">
            <span class="input-group-btn">
                <button class="btn btn-success" name="filter" value="1" type="submit">
                    {{ __("Betöltés Armoryról") }}
                </button>
            </span>
        </div>
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    {!! Form::close() !!}
    <div id="ad-gear-section" class="form-group col-md-12 disabled-ad-section">
        <legend> {{ __("Gear") }}</legend>
        <div id="character-gear-container">
            @include('home.adverts.character-gear')
        </div>
    </div>
</div>