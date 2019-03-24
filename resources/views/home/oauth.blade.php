<div class="panel-body login">
    <table class="col-md-12">
        <tr>
            <td class="col-md-6">
                <b>1) </b>
                <a href="https://oauth.tauriwow.com/oauth/v2/auth?client_id=5_2i4bajinnracokc00480cogowgwkgwowk8os8k8ogogcw88g80&redirect_uri={{ env('APP_URL') }}/oauth&response_type=code&scope=publicuserdata">
                    {{ strlen($user->tauri_oauth_access_token) ? __("Másik account autentikációja") :  __("Account autentikáció") }}
                </a>
            </td>
            <td class="col-md-6">
                <b>2) </b>
                <a href="{{ URL::to("/oauth/character") }}">{{ __("Jelenlegi kiválasztott karakter autentikációja") }}</a>
                <a target="_blank" href="http://devil.tauri.hu/~kimbatt/bugreport.png">
                    <i class="tauribay far fa-question-circle"></i>
                </a>
            </td>
        </tr>
    </table>
</div>
<table class="table table-bordered table-classes">
    <tr class="tHead">
        <th>{{ __("Realm") }}</th>
        <th>{{ __("Név") }}</th>
        <th>{{ __("Kaszt") }}</th>
        <th>{{ __("Élettartam") }}</th>
    </tr>
    @foreach ( $authorizedCharacters as $character )
        <tr>
            <td>{{ \TauriBay\Realm::REALMS_SHORT[$character->realm] }}</td>
            <td><a href="{{ URL::to("/player/" . \TauriBay\Realm::REALMS_URL[$character->realm] . "/" . $character->name . "/" . $character->id ) }}">{{ $character->name }}</a></td>
            <td class="class-{{ $character->class  }}"> <img src="{{ URL::asset("img/classes/small/" . $character->class . ".png") }}" alt="{{ $characterClasses[$character->class] }}"/> </td>
            <td>{{ $authorizationLifeTimeInHours - \Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($character->updated_at)) . " " .  __("óra") }}</td>
        </tr>
    @endforeach
</table>
