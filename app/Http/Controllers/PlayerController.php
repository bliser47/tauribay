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
use TauriBay\Loot;
use TauriBay\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use TauriBay\Realm;

class PlayerController extends Controller
{
    public function index(Request $_request, $_realm_short, $_player_name)
    {
        if ( $_request->has("mode_id") )
        {
            $modeId = $_request->get("mode_id");
            $returnView = "";
            switch($modeId)
            {
                case "recent":

                    break;

                case "best" :

                    $expansionId = $_request->get("expansion_id", Defaults::EXPANSION_ID);
                    $mapId = $_request->get("map_id", Defaults::MAP_ID);

                    $defaultDifficultyIndex = 0;
                    $difficulties = Encounter::getMapDifficulties($expansionId, $mapId);
                    $defaultDifficultyId = null;
                    $backUpDifficultyId = null;
                    foreach ($difficulties as $index => $difficulty) {
                        $difficultyId = $difficulty["id"];
                        $backUpDifficultyId = $difficultyId;
                        if ($difficultyId == 5 && !$_request->has("default_difficulty_id") || $_request->get("default_difficulty_id") == $difficultyId) {
                            $defaultDifficultyIndex = $index;
                            $defaultDifficultyId = $difficultyId;
                        }
                    }
                    if ($defaultDifficultyId == null) {
                        $defaultDifficultyId = $backUpDifficultyId;
                    }

                    $maps = Encounter::getExpansionMaps($expansionId);


                    return view("player/ajax/best", compact(
                        "difficulties",
                        "defaultDifficultyIndex",
                        "mapId",
                        "maps",
                        "expansionId",
                        "playerTitle"
                    ));

                    break;

                case "performance":

                    break;

                case "compare" :

                    break;

                default :
                    $returnView = "Mode not found: " . $modeId;
                    break;
            }
            return $returnView;
        }
        else
        {
            $realmId = array_search($_realm_short, Realm::REALMS_URL);
            $playerName = ucfirst($_player_name);
            $playerTitle = Realm::REALMS_SHORT[$realmId] . " - " . $playerName;

            $modes = array(
                "recent" => __("Új"),
                "dps" => "DPS",
                "hps" => "HPS",
            );
            $modeId = Defaults::PLAYER_MODE;

            $realms = Realm::REALMS_SHORT;

            return view("player/index", compact(
            "playerTitle",
                "playerName",
                "realms",
                "realmId",
                "modes",
                "modeId"
            ));
        }
    }
}