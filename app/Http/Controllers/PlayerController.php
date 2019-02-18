<?php
/**
 * Created by PhpStorm.
 * User: thoma
 * Date: 2/10/2019
 * Time: 9:54 AM
 */

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\Defaults;
use TauriBay\Encounter;
use TauriBay\EncounterMember;
use TauriBay\Loot;
use TauriBay\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use TauriBay\MemberTop;
use TauriBay\Realm;

class PlayerController extends Controller
{
    public function mode(Request $_request, $_realm_short, $_player_name, $modeId)
    {
        $returnView = "";
        switch($modeId)
        {
            case "recent":

                $realmId = array_search($_realm_short,Realm::REALMS_URL);
                $playerEncounters = EncounterMember::where("realm_id","=",$realmId)->where("name","=",$_player_name)->orderBy("killtime","desc")->paginate(16);
                $encounterIDs = Encounter::ENCOUNTER_IDS;

                foreach ( $playerEncounters as $encounter )
                {
                    $avgDps = MemberTop::where("encounter_id","=",$encounter->encounter)->where("difficulty_id","=",$encounter->difficulty_id)->where("spec","=",$encounter->spec)->orderBy("dps","desc")->first();
                    $avgHps = MemberTop::where("encounter_id","=",$encounter->encounter)->where("difficulty_id","=",$encounter->difficulty_id)->where("spec","=",$encounter->spec)->orderBy("hps","desc")->first();
                    $encounter->dps_score = intval(($encounter->dps * 100) / max($avgDps->dps,1));
                    $encounter->hps_score = intval(($encounter->hps * 100) / max($avgHps->hps,1));
                }

                return view("player/ajax/recent",compact("playerEncounters","encounterIDs"));

                break;

            case "hps":
            case "dps":

                break;

            default :
                $returnView = "Mode not found: " . $modeId;
                break;
        }
        return $returnView;
    }


    public function index(Request $_request, $_realm_short, $_player_name)
    {
        $realmId = array_search($_realm_short, Realm::REALMS_URL);
        $realmUrl = $_realm_short;
        $playerName = ucfirst($_player_name);
        $playerTitle = Realm::REALMS_SHORT[$realmId] . " - " . $playerName;

        $modes = array(
            "recent" => __("Új"),
            "dps" => "DPS",
            "hps" => "HPS",
        );
        $modeId = Defaults::PLAYER_MODE;

        $realms = Realm::REALMS_URL_KEY;

        return view("player/index", compact(
        "playerTitle",
            "playerName",
            "realms",
            "realmUrl",
            "modes",
            "modeId"
        ));
    }
}