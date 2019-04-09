<?php

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\CharacterEncounters;
use TauriBay\Characters;
use TauriBay\Defaults;
use TauriBay\Encounter;
use TauriBay\EncounterMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use TauriBay\MemberTop;
use TauriBay\Realm;
use TauriBay\Tauri\CharacterClasses;

class PlayerController extends Controller
{

    public static function getSpecTop($guid, $encounterId, $difficultyId, $specId, $calculate) {

        $specBest = MemberTop::where("guid","=",$guid)->where("encounter_id", "=", $encounterId)->where("difficulty_id",$difficultyId)->where("spec","=",$specId)->first();
        if ( $specBest && $calculate ) {
            $topType = EncounterMember::isHealer($specId) ? "hps" : "dps";
            $encounter = $topType . "_encounter_id";
            if ( $specBest->$encounter > 0 ) {
                $best = $topType == "dps" ? Encounter::getSpecTopDps($encounterId, $difficultyId, $specId) : Encounter::getSpecTopHps($encounterId,$difficultyId,$specId);
                return array(
                    "link" => URL::to("/encounter/") . "/" . Encounter::getUrlName($encounterId) . "/" . $specBest->$encounter,
                    "score" => intval(($specBest->$topType * 100) / $best)
                );
            }
        }
        return array();
    }

    public function spec(Request $_request, $_realm_short, $_player_name, $_character_guid, $_mode_id, $_difficulty_id, $_encounter_id, $_spec_id) {
        $spec = self::getSpecTop($_character_guid, $_encounter_id, $_difficulty_id, $_spec_id, true);
        if ( is_array($spec) && array_key_exists("score",$spec)) {
            $classId = EncounterMember::getSpecClass($_spec_id);
            return view("player/ajax/top/difficulty_spec",compact("spec","classId"));
        }
        return "";
    }

    public function difficulty(Request $_request, $_realm_short, $_player_name, $_character_guid, $_mode_id, $_difficulty_id) {
        $character = Characters::where("guid","=",$_character_guid)->first();
        if ( $character !== null ) {

            $specs = EncounterMember::getSpecsShort($character->class);
            switch ($_mode_id) {
                case "top":

                    $expansionId = Defaults::EXPANSION_ID;
                    $mapId = Defaults::MAP_ID;

                    $raidEncounters = array();
                    $raids = Encounter::EXPANSION_RAIDS_COMPLEX["map_exp_" . $expansionId];
                    foreach ($raids as $raid) {
                        if ($raid["id"] == $mapId) {
                            $raidEncounters = $raid["encounters"];
                            break;
                        }
                    }

                    $difficultyId = $_difficulty_id;
                    $encounters = array();
                    foreach ($raidEncounters as $raidEncounter) {
                        $encounterId = $raidEncounter["encounter_id"];
                        if (  Encounter::doubleCheckEncounterExistsOnDifficulty($encounterId, $difficultyId)) {
                            $encounters[] = $raidEncounter;
                            $scores[$encounterId] = array();
                            foreach ( $specs as $specId => $specName ) {
                                $scores[$encounterId][$specId] = array(
                                    "load" => URL::to("/player/". $_realm_short . "/" . $_player_name . "/" . $_character_guid . "/top/" . $difficultyId . "/" . $encounterId . "/" . $specId),
                                    "cache" => self::getSpecTop($_character_guid, $encounterId, $difficultyId, $specId, false)
                                );
                            }
                        }
                    }

                    if ( count($encounters) > 0 ) {
                        $view = view("player/ajax/top/difficulty", compact(
                            "encounters",
                            "scores",
                            "character",
                            "specs",
                            "expansionId",
                            "mapId",
                            "difficultyId"));
                        $view = $view->render();
                    } else {
                        $view = "";
                    }

                    return json_encode(array(
                        "view" => $view,
                        "url" => ""
                    ));
                break;
            }
        }
        return "";
    }

    public function mode(Request $_request, $_realm_short, $_player_name, $_character_guid, $_mode_id)
    {
        $character = Characters::where("guid","=",$_character_guid)->first();
        if ( $character !== null ) {
            switch ($_mode_id) {
                case "recent":

                    $characterClass = $character->class;

                    $canHeal = EncounterMember::canClassHeal($characterClass);

                    $encounters = CharacterEncounters::where("character_id", "=", $character->id)
                        ->leftJoin("encounter_members", "character_encounters.encounter_member_id", "=", "encounter_members.id")
                        ->orderBy("killtime", "desc")->paginate(16);

                    $encounterIDs = Encounter::ENCOUNTER_IDS;

                    return view("player/ajax/recent", compact("encounters", "encounterIDs", "canHeal"));

                    break;

                case "top":
                    $expansionId = Defaults::EXPANSION_ID;
                    $mapId = Defaults::MAP_ID;
                    $difficulties = Encounter::getMapDifficulties($expansionId, $mapId);

                    $defaultDifficultyIndex = 0;
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


                    return view("player/ajax/top/map", compact("difficulties","mapId","expansionId","defaultDifficultyIndex"));
                break;
            }
        }
        return view("player/ajax/notfound");
    }


    public function player(Request $_request, $_realm_short, $_player_name, $guid) {

        $character = Characters::where("guid","=",$guid)->first();
        if ( $character ) {
            $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;

            $modes = array(
                "recent" => __("Új"),
                "top" => "Top"
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
            $realmUrl = $_realm_short;

            return view("player/player", compact(
                "character",
                "realmUrl",
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
        return redirect('/player');
    }

    public function index(Request $_request)
    {
        $playerName = $_request->get("player_name");
        if ( strlen($playerName) > 0 ) {
            $characters = Characters::whereRaw("LOWER(name) LIKE \"%" . strtolower($playerName) . "%\"")->get();
        } else {
            $playerName = __("Nem talált karakter");
            $characters = array();
        }
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;

        return view("player/search", compact(
            "characters", "playerName","characterClasses"));
    }
}