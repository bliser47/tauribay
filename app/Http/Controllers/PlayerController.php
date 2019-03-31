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
use TauriBay\Tauri\CharacterClasses;

class PlayerController extends Controller
{
    public function mode(Request $_request, $_realm_short, $_player_name, $_character_guid, $_mode_id)
    {
        switch($_mode_id)
        {
            case "recent":
                $character = Characters::where("guid","=",$_character_guid)->first();
                if ( $character !== null ) {

                    $characterClass = $character->class;

                    $canHeal = EncounterMember::canClassHeal($characterClass);

                    $encounters = CharacterEncounters::where("character_id","=",$character->id)
                        ->rightJoin("encounter_members", "character_encounters.encounter_member_id", "=", "encounter_members.id")
                        ->orderBy("killtime", "desc")->paginate(16);

                    $encounterIDs = Encounter::ENCOUNTER_IDS;

                    return view("player/ajax/recent", compact("encounters", "encounterIDs","canHeal"));
                }

                break;

            case "top":


            break;
        }
        return view("player/ajax/notfound");
    }


    public function player(Request $_request, $_realm_short, $_player_name, $guid) {

        $character = Characters::where("guid","=",$guid)->first();
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;

        $modes = array(
            "recent" => __("Új"),
            //"top" => __("Top"),
        );
        $modeId = Defaults::PLAYER_MODE;

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

        return view("player/player", compact(
            "character",
            "characterClasses",
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
            "defaultDifficultyId"));
    }

    public function search(Request $_request, $_realm_url, $search)
    {
        $realmId = array_search($_realm_url, Realm::REALMS_URL);
        $characters = Characters::where("realm","=",$realmId)
            ->whereRaw("LOWER(name) LIKE \"%" . strtolower($search) . "%\"")->get();
        $playerTitle = __("Karakter keresése");
        $playerName = $search;
        $realmUrl = $_realm_url;

        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;

        return view("player/search", compact(
            "characters", "search", "playerName", "playerTitle","realmUrl","characterClasses"));
    }

    public function index(Request $_request)
    {
        return view("player/index");
    }
}