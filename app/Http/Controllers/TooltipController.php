<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\Tauri;

class TooltipController extends Controller
{
    public function Request(Request $_request)
    {
        $curl = curl_init();

        $uri = $_SERVER["REQUEST_URI"];
        $params = explode('?', $uri)[1];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://mop-shoot.tauri.hu/ajax/ajax.php?" . $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            	// Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}
