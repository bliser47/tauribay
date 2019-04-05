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

                    $cacheKey = "playerTop" . $_character_guid . http_build_query($_request->all()) . "&difficulty=" . $_difficulty_id . "?v=12";
                    $cacheValue = Cache::get($cacheKey);
                    if ( true || !$cacheValue ) {

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
                        $characterBests = MemberTop::where("character_id","=",$character->id)->where("difficulty_id",$difficultyId)
                        ->whereIn("encounter_id", Encounter::getMapEncountersIds($expansionId,$mapId))->get();


                        foreach ($raidEncounters as $raidEncounter) {
                            $encounterId = $raidEncounter["encounter_id"];
                            if (  Encounter::doubleCheckEncounterExistsOnDifficulty($encounterId, $difficultyId)) {
                                $encounters[] = $raidEncounter;
                                $scores[$encounterId] = array();
                                foreach ( $specs as $specId => $specName ) {

                                    $score = 0;
                                    $link = "";

                                    $memberBestKey = $characterBests->search(function ($item, $key) use ($encounterId, $specId) {
                                        return $item["encounter_id"] == $encounterId && $item["spec"] ==  $specId;
                                    });
                                    $memberBest = $memberBestKey !== false ? $characterBests[$memberBestKey] : null;

                                    if ( $memberBest) {
                                        $topType = EncounterMember::isHealer($specId) ? "hps" : "dps";
                                        return  Encounter::getSpecTopDps($encounterId, $difficultyId, $specId);
                                        $best = $topType == "dps" ? Encounter::getSpecTopDps($encounterId, $difficultyId, $specId) : Encounter::getSpecTopHps($encounterId,$difficultyId,$specId);
                                        $score = intval(($memberBest->$topType * 100) / $best);
                                        $encounter = $topType . "_encounter_id";
                                        $link = URL::to("/encounter/") . "/" . Encounter::getUrlName($encounterId) . "/" . $memberBest->$encounter;
                                    }
                                    $scores[$encounterId][$specId] = array(
                                        "link" => $link,
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
                        Cache::put($cacheKey, $cacheValue, 120); // 2 hours
                    }

                    return json_encode(array(
                        "view" => $cacheValue,
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