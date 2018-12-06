<?php

namespace TauriBay\Http\Controllers;

use TauriBay\Encounter;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\Tauri;

class ProgressController extends Controller
{
    const REALM_NAMES = array(
        0 => "[HU] Tauri WoW Server",
        1 => "[HU] Warriors of Darkness",
        2 => "[EN] Evermoon"
    );

    const ENCOUNTER_IDS = array(
        "Jin'rokh the Breaker" => 1577
        /*
        "Horridon" => 1575,
        "Council of Elders" => 1570,
        "Tortos" => 1565,
        "Megaera" => 1578,
        "Ji-Kun" => 1573,
        "Durumu the Forgotten" => 1572,
        "Primordius" => 1574,
        "Dark Animus" => 1576,
        "Iron Qon" => 1559,
        "Twin Consorts" => 1560,
        "Lei Shen" => 1579,
        "Ra-den" => 1580
        */
    );

    public function index(Request $_request)
    {
        $api = new Tauri\ApiClient();
        $maps = $api->getRaidMaps("[HU] Tauri WoW Server");
        //$maps = $api->getRaidGuildRank("[HU] Tauri WoW Server", 1580, 5);

        $data = array();
        foreach ( self::REALM_NAMES as $realmName )
        {
            $data[$realmName] = array();
            foreach ( self::ENCOUNTER_IDS as $encounterName => $encounterId )
            {
                $data[$realmName][$encounterName] = $api->getRaidGuildRank($realmName,$encounterId,5);
            }
        }


        return view("progress", compact("data"));
    }


    public function updateRaids(Request $_request)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 'on');

        $api = new Tauri\ApiClient();
        $realmId = $_request->has("data") ? $_request->get("data") : 0;
        $realmName = self::REALM_NAMES[$realmId];
        $latestRaids = $api->getRaidLast($realmName);
        return $latestRaids["response"]["logs"][0];


        foreach ( $latestRaids["response"]["logs"] as $raid )
        {
            //Encounter::store($raid, $realmId);
        }
    }
}
