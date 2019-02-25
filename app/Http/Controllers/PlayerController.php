<?php
/**
 * Created by PhpStorm.
 * User: thoma
 * Date: 2/10/2019
 * Time: 9:54 AM
 */

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\CharacterEncounters;
use TauriBay\Characters;
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
        switch($modeId)
        {
            case "recent":

                $realmId = array_search($_realm_short,Realm::REALMS_URL);
                $character = Characters::where("realm","=",$realmId)->where("name","=",$_player_name)->first();
                if ( $character !== null ) {

                    $characterClass = $character->class;

                    $canHeal = EncounterMember::canClassHeal($characterClass);

                    $encounters = CharacterEncounters::leftJoin("characters", "characters.id", "=", "character_encounters.character_id")
                        ->leftJoin("encounter_members", "character_encounters.encounter_member_id", "=", "encounter_members.id")
                        ->where("characters.realm", "=", $realmId)->where("characters.name", "=", $_player_name)->orderBy("killtime", "desc")->paginate(16);
                    $encounterIDs = Encounter::ENCOUNTER_IDS;

                    return view("player/ajax/recent", compact("encounters", "encounterIDs","canHeal"));
                }

                break;

            case "top":


            break;
        }
        return view("player/ajax/notfound");
    }


    public function search(Request $_request, $_realm_short, $_player_name)
    {
        $realmId = array_search($_realm_short, Realm::REALMS_URL);
        $playerName = ucfirst($_player_name);
        $character = Characters::where("realm","=",$realmId)->where("name","=",$playerName)->first();
        $playerTitle = __("Karakter keresése");
        $realmUrl = $_realm_short;
        $modes = array(
            "recent" => __("Új"),
            "top" => __("Top"),
        );
        $modeId = Defaults::PLAYER_MODE;
        if ( $character !== null ) {
            $playerTitle = Realm::REALMS_SHORT[$realmId] . " - " . $playerName;
        }


        $expansionId = $_request->get("expansion_id", Defaults::EXPANSION_ID);
        $mapId = $_request->get("map_id", Defaults::MAP_ID);
        $encounterId = $_request->get("encounter_id", 0);
        $difficultyId = $_request->get("difficulty_id");
        $defaultDifficultyId = $_request->get("difficulty_id_default");

        $expansions = Encounter::EXPANSIONS;
        $maps = Encounter::getExpansionMaps($expansionId);

        $difficulties = Defaults::SIZE_AND_DIFFICULTY;

        $encounters = Encounter::getMapEncounters($expansionId, $mapId);
        $encounters[0] = __("Minden boss");

        return view("player/search", compact(
        "playerTitle",
        "playerName",
        "realmUrl",
        "modes",
        "modeId",
        "expansions",
        "maps",
        "mapId",
        "expansionId",
        "difficulties",
        "encounters",
        "encounterId",
        "difficultyId",
        "defaultDifficultyId"
    ));
    }

    public function index(Request $_request)
    {

        return view("player/index");
    }
}