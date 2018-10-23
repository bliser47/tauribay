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

    public function ArmoryRequest(Request $_request)
    {
        $curl = curl_init();

        $uri = $_SERVER["REQUEST_URI"];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://tauriwow.com/sys/mod/armory.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'ajax' => 'true',
                'dataset[i]' => '1236449605',
                'dataset[r]' => '[HU] Tauri WoW Server',
                'dataset[pcs]' => '86979:94508:95020:95037:95114:96172:96375:96398:96512:96665:96666:96667:96668:96745:96802:96872',
                'option' => 'item-tooltip/ajax'
            ),
            CURLOPT_HTTPHEADER => array(
                'Connection' => 'keep-alive',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-cache',
                'Accept' => '*/*',
                'Origin' =>  'https://tauriwow.com',
                'Content-Length' => 246,
                'X-Requested-With' => 'XMLHttpRequest',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Referer' => 'https://tauriwow.com/armory',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept-Language' => 'hu-HU,hu;q=0.9,en-US;q=0.8,en;q=0.7',
                'Cookie' => 'tauriweb_language=hu; username=USERNAME; pasw=PASSWORD; tSessionId=SESSION_ID'
            )
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
