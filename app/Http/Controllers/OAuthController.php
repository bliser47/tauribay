<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class OAuthController extends Controller
{

    public function debug(Request $_request)
    {
        $user = Auth::user();
        if ( $user )
        {
            if ( strlen($user->tauri_oauth_access_token) ) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://oauth.tauriwow.com/oauth/v2/token",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_HTTPHEADER, array(
                        "Authorization: Bearer " . $user->tauri_oauth_access_token
                    ),
                    CURLOPT_POSTFIELDS => array(
                        "client_id" => env('TAURI_OAUTH_CLIENT'),
                        "client_secret" => env("TAURI_OAUTH_SECRET")
                    )
                ));

                $response = curl_exec($curl);
                curl_close($curl);

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
                        "redirect_uri" => "https://tauribay.hu/oauth",
                        "code" => $code
                    ))
                );

                $response = curl_exec($curl);
                curl_close($curl);


                $responseJson = json_decode($response,true);

                return $responseJson;

                if ( isset($responseJson["access_token"]) ) {
                    $user->tauri_oauth_access_token = $responseJson["access_token"];
                    $user->tauri_oauth_refresh_token = $responseJson["refresh_token"];
                    $user->save();
                }
                else
                {
                    return var_dump($response);
                }
            }
            return redirect('/home');
        }
        else {
            return redirect('/login');
        }
    }
}