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
    public function difficulty(Request $_request, $_realm_short, $_player_name, $_character_guid, $_mode_id, $_difficulty_id) {
        $character = Characters::where("guid","=",$_character_guid)->first();
        if ( $character !== null ) {

            $specs = EncounterMember::getSpecsShort($character->class);


            switch ($_mode_id) {
                case "top":

                    $expansionId = Defaults::EXPANSION_ID;
                    $mapId = Defaults::MAP_ID;

                    $cacheKey = "playerTop" . $_character_guid . http_build_query($_request->all()) . "&difficulty=" . $_difficulty_id . "?v=5";
                    $cacheValue = Cache::get($cacheKey);
                    $cacheUrlValue = Cache::get($cacheKey."URL");
                    if (  !$cacheValue ) {

                        $raidEncounters = array();
                        $raids = Encounter::EXPANSION_RAIDS_COMPLEX["map_exp_" . $expansionId];
                        foreach ($raids as $raid) {
                            if ($raid["id"] == $mapId) {
                                $raidEncounters = $raid["encounters"];
                                break;
                            }
                        }

                        $difficultyId = $_difficulty_id;

                        $scores = array();
                        $encounters = array();
                        foreach ($raidEncounters as $raidEncounter) {
                            $encounterId = $raidEncounter["encounter_id"];
                            if (  Encounter::doubleCheckEncounterExistsOnDifficulty($encounterId, $difficultyId)) {
                                $encounters[] = $raidEncounter;
                                $scores[$encounterId] = array();
                                foreach ( $specs as $specId => $specName ) {
                                    $topType = EncounterMember::isHealer($specId) ? "hps" : "dps";
                                    $memberBest =  MemberTop::where("encounter_id","=",$encounterId)->where("realm_id","=",$character->realm)->
                                        where("difficulty_id","=",$difficultyId)->where("name","=",$character->name)->where("spec","=",$specId)
                                        ->first();
                                    $score = 0;
                                    if ( $memberBest ) {
                                        $typeBest = MemberTop::where("encounter_id","=",$encounterId)
                                            ->where("difficulty_id","=",$difficultyId)->where("spec","=",$specId)->orderBy($topType,"desc")->first();
                                        $score = intval(($memberBest->$topType * 100) / $typeBest->$topType);
                                    }
                                    $scores[$encounterId][$specId] = array(
                                        "type" => $memberBest ? date() : "",
                                        "typeName" => strtoupper($topType),
                                        "score" => $score
                                    );
                                }
                            }
                        }

                        if ( count($encounters) > 0 ) {
                            $view = view("player/ajax/top/difficulty", compact(
                                "encounters",
                                "character",
                                "scores",
                                "specs",
                                "expansionId",
                                "mapId",
                                "difficultyId"));
                            $view = $view->render();
                        } else {
                            $view = "";
                        }

                        $cacheValue = $view;
                        $cacheUrlValue = URL::to("player/" . $_realm_short . "/" . $_player_name . "/" . $_character_guid);
                        Cache::put($cacheKey, $cacheValue, 120); // 2 hours
                        Cache::put($cacheKey . "URL", $cacheUrlValue, 120);
                    }

                    return json_encode(array(
                        "view" => $cacheValue,
                        "url" => $cacheUrlValue
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
                        ->rightJoin("encounter_members", "character_encounters.encounter_member_id", "=", "encounter_members.id")
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
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;

        $modes = array(
            "recent" => __("Új"),
            "top" => __("Top (Real)")
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