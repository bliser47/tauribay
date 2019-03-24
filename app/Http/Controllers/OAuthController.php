<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use TauriBay\AuthorizedCharacter;
use TauriBay\Characters;
use TauriBay\Realm;
use TauriBay\Tauri\ApiClient;
use TauriBay\Tauri\CharacterClasses;
use TauriBay\User;

class OAuthController extends Controller
{

    public static function refreshToken($user) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://oauth.tauriwow.com/oauth/v2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => array(
                "grant_type" => "refresh_token",
                "client_id" => env('TAURI_OAUTH_CLIENT'),
                "client_secret" => env("TAURI_OAUTH_SECRET"),
                "refresh_token" => $user->tauri_oauth_refresh_token
            ))
        );
        $response = curl_exec($curl);
        curl_close($curl);

        $responseJson = json_decode($response,true);

        if ( isset($responseJson["access_token"]) ) {
            $user->tauri_oauth_access_token = $responseJson["access_token"];
            $user->save();
        }
    }

    public function debug(Request $_request)
    {
        $user = Auth::user();
        if ( $user )
        {
            if ( strlen($user->tauri_oauth_access_token) ) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://oauth.tauriwow.com/api/v1/publicuserdata?access_token=" . $user->tauri_oauth_access_token,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
                ));

                $response = curl_exec($curl);
                curl_close($curl);

                $result = json_decode($response,true);
                if ( array_key_exists("error", $result) && $result["error_description"] == "The access token provided has expired." ) {
                   self::refreshToken($user);
                    return $this->debug($_request);
                }
                return $response;
            }
            return "User not authenticated";
        }
        return "User not logged in";
    }

    public function auth(Request $_request)
    {
        $user = Auth::user();
        if ( $user ) {
            if ($_request->has("code")) {
                $code = $_request->get("code");

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://oauth.tauriwow.com/oauth/v2/token",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_POSTFIELDS => array(
                        "grant_type" => "authorization_code",
                        "client_id" => env('TAURI_OAUTH_CLIENT'),
                        "client_secret" => env("TAURI_OAUTH_SECRET"),
                        "redirect_uri" => env('APP_URL') . "/oauth",
                        "code" => $code
                    ))
                );

                $response = curl_exec($curl);
                curl_close($curl);


                $responseJson = json_decode($response,true);

                if ( isset($responseJson["access_token"]) ) {
                    $user->tauri_oauth_access_token = $responseJson["access_token"];
                    $user->tauri_oauth_refresh_token = $responseJson["refresh_token"];
                    $user->save();
                }
            }
            return redirect('/home#oauth');
        }
        else {
            return redirect('/login');
        }
    }

    public function char(Request $_request) {
        $user = Auth::user();
        if ( $user )
        {
            if ( strlen($user->tauri_oauth_access_token) ) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://oauth.tauriwow.com/api/v1/publicuserdata?access_token=" . $user->tauri_oauth_access_token,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
                ));

                $response = curl_exec($curl);
                curl_close($curl);

                $result = json_decode($response,true);
                if ( array_key_exists("error", $result) && $result["error_description"] == "The access token provided has expired." ) {
                    //self::refreshToken($user);
                    return redirect('/home#oauth');
                }

                //$userId = $response["user_id"];
                $charData = explode("-",$result["user_name"]);
                $charName = $charData[0];
                $charRealm = $charData[1];

                $realmId = array_search($charRealm, Realm::REALMS_SHORT);
                if ( array_key_exists($realmId, Realm::REALMS) ) {
                    $character = Characters::where("realm","=",$realmId)->where("name","=",$charName)->
                    orderBy("guid","desc")->first();
                    if ( !$character ) {
                        $character = new Characters;
                        $character->realm = $realmId;
                        $character->name = $charName;

                        $api = new ApiClient();
                        $characterSheet = $api->getCharacterSheet(Realm::REALMS[$character->realm], $character->name);
                        if ($characterSheet && array_key_exists("response", $characterSheet)) {
                            $characterSheetResponse = $characterSheet["response"];
                            $character->achievement_points = $characterSheetResponse["pts"];
                            $character->faction = CharacterClasses::ConvertRaceToFaction($characterSheetResponse["race"]);
                            $character->class = $characterSheetResponse["class"];
                            $character->ilvl = $characterSheetResponse["avgitemlevel"];
                            $character->guid = $characterSheetResponse["guid"];
                            $character->save();
                        }
                    }
                    if ( $character->guid ) {
                        $authorizedCharacter = AuthorizedCharacter::where("user_id","=",$user->id)->where("character_id","=",$character->id)->first();
                        if ( !$authorizedCharacter ) {
                            $authorizedCharacter = new AuthorizedCharacter;
                            $authorizedCharacter->user_id = $user->id;
                            $authorizedCharacter->character_id = $character->id;
                        }
                        $authorizedCharacter->save();
                    }
                }
            }
        }
        return redirect('/home#oauth');
    }
}