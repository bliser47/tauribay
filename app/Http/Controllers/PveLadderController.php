<?php
/**
 * Created by PhpStorm.
 * User: Tamas
 * Date: 1/8/2019
 * Time: 4:46 PM
 */

namespace TauriBay\Http\Controllers;

use TauriBay\Defaults;
use TauriBay\Encounter;
use Illuminate\Http\Request;
use TauriBay\EncounterMember;
use TauriBay\Guild;
use TauriBay\LadderCache;
use TauriBay\Realm;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PveLadderController extends Controller
{
    public function expansion(Request $_request, $_expansion_name_short, $_noReturn = false)
    {
        $expansionId = Encounter::convertExpansionShortNameToId($_expansion_name_short);
        $_request->request->add(array(
            "expansion_id" => $expansionId,
            "expansion_name_short" => $_expansion_name_short,
        ));
        if ( !$_noReturn ) {
            return $this->index($_request);
        }
    }

    public function map(Request $_request, $_expansion_name_short, $_map_name_short, $_noReturn = false)
    {
        $this->expansion($_request, $_expansion_name_short, true);
        $mapId = Encounter::convertMapShortNameToId($_map_name_short, $_request->get("expansion_id"));
        $_request->request->add(array(
            "map_id" => $mapId,
            "map_name_short" => $_map_name_short
        ));
        if ( !$_noReturn ) {
            return $this->index($_request);
        }
    }

    public function map_with_difficulty(Request $_request, $_expansion_name_short, $_map_name_short, $_difficulty_name_short, $_noReturn = false)
    {
        $this->map($_request, $_expansion_name_short, $_map_name_short, true);
        $difficultyId = Encounter::convertDifficultyShortNameToId($_difficulty_name_short);
        $_request->request->add(array(
            !$_noReturn ? "difficulty_id_default" : "difficulty_id" => $difficultyId,
        ));
        if ( !$_noReturn ) {
            return $this->index($_request);
        }
    }


    public function encounter(Request $_request, $_expansion_name_short, $_map_name_short, $_encounter_name_short, $_difficulty_name_short)
    {
        $this->map_with_difficulty($_request, $_expansion_name_short, $_map_name_short, $_difficulty_name_short, true);

        $encounterId = Encounter::convertEncounterShortNameToId(
            $_request->get("expansion_id"),
            $_request->get("map_id"),
            $_encounter_name_short
        );

        $_request->request->add(array(
            "encounter_id" => $encounterId,
            "encounter_name_short" => $_encounter_name_short,
        ));

        return $this->index($_request);
    }


    public function ajax(Request $_request, $_expansion_id = Defaults::EXPANSION_ID, $_map_id = Defaults::MAP_ID)
    {
        $expansionId = $_request->get("expansion_id", Defaults::EXPANSION_ID);
        $mapId = $_request->get("map_id", Defaults::MAP_ID);
        $encounterId = $_request->get("encounter_id", 0);

        if ( $encounterId > 0 )
        {
            if ( $_request->has("mode_id") )
            {
                $modeId = $_request->get("mode_id");


                $defaultDifficulty = Defaults::DIFFICULTY_ID;
                if ( !$_request->has("difficulty_id") ) {
                    $difficulties = Encounter::getMapDifficulties($expansionId, $mapId, $encounterId);
                    $found = false;
                    $first = true;
                    foreach ($difficulties as $difficulty) {
                        if ($first) {
                            $first = true;
                            $defaultDifficulty = $difficulty["id"];
                        }
                        if ($difficulty["id"] == $defaultDifficulty) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $defaultDifficulty = $first;
                    }
                }
                $difficultyId = $_request->get("difficulty_id", $defaultDifficulty);

                // Ra-den 25 HC hack
                if ( $encounterId == 1580 && $difficultyId == 6 )
                {
                    $encounterId = 1581;
                }
                else if ( $encounterId == 1082 && $difficultyId == 6 )
                {
                    $encounterId = 1083;
                }

                if ( $modeId == "dps" || $modeId == "hps" ) {

                    if ( $_request->has("mode_filter") && $_request->has("difficulty_id"))
                    {

                        $cacheKey = http_build_query($_request->all());
                        $cacheValue = Cache::get($cacheKey);
                        if ( !$cacheValue ) {

                            $members = EncounterMember::where("encounter_members.encounter", "=", $encounterId)
                                ->where("encounter_members.difficulty_id", "=", $difficultyId);

                            if ($_request->has("spec_id") && $_request->get("spec_id") > 0) {
                                $members = $members->where("encounter_members.spec", "=", $_request->get("spec_id"));
                            }
                            else if ($_request->has("class_id") && $_request->get("class_id") > 0) {
                                $members = $members->where("encounter_members.class", "=", $_request->get("class_id"));
                            }
                            if ($_request->has("role_id") && $_request->get("role_id") > 0) {
                                $members = $members->whereIn("encounter_members.spec", EncounterMember::getRoleSpecs($_request->get("role_id")));
                            }

                            //  Realm filter
                            if ($_request->has('tauri') || $_request->has('wod') || $_request->has('evermoon')) {
                                $realms = array();
                                if ($_request->has('tauri')) {
                                    array_push($realms, 0);
                                }
                                if ($_request->has('wod')) {
                                    array_push($realms, 1);
                                }
                                if ($_request->has('evermoon')) {
                                    array_push($realms, 2);
                                }
                                $members = $members->whereIn('encounter_members.realm_id', $realms);
                            }

                            // Faction filter
                            if ($_request->has('alliance') || $_request->has('horde') || $_request->has('ismeretlen')) {
                                $factions = array();
                                if ($_request->has('alliance')) {
                                    array_push($factions, 0);
                                }
                                if ($_request->has('horde')) {
                                    array_push($factions, 1);
                                }
                                if ($_request->has('ismeretlen')) {
                                    array_push($factions, 3);
                                }
                                $members = $members->whereIn('encounter_members.faction_id', $factions);
                            }

                            // Hack for fixing HPS and Durumu DPS
                            if ($modeId == "hps" || ($modeId == "dps" && $encounterId == 1572)) {
                                $members = $members->where("encounter_members.killtime", ">", 0)->where("encounter_members.killtime", ">", Encounter::DURUMU_DMG_INVALID_BEFORE_TIMESTAMP);
                            }




                            $members = $members->orderBy($modeId,"desc")->get();

                            $membersAdded = array();
                            foreach ( $members as $key => $member )
                            {
                                $memberKey = $member->realm_id . "-"  . $member->name;
                                if ( count($membersAdded) < 100 && !in_array($memberKey, $membersAdded) ) {
                                    $membersAdded[] = $memberKey;
                                    $encounter = Encounter::where("id", "=", $member->encounter_id)->first();
                                    if ($encounter->guild_id !== 0) {
                                        $guild = Guild::where("id", "=", $encounter->guild_id)->first();
                                        $member->guild_id = $encounter->guild_id;
                                        $member->guild_name = $guild->name;
                                        $member->faction = $guild->faction;
                                    }
                                }
                                else
                                {
                                    $members->forget($key);
                                }
                            }


                            $view = view("ladder/pve/ajax/members", compact(
                                "modeId",
                                "members"
                            ));

                            $cacheValue = $view->render();
                            Cache::put($cacheKey, $cacheValue, 120); // 2 hours
                        }

                        return json_encode(array(
                            "view" => $cacheValue,
                            "url" => ""
                        ));
                    }
                    else
                    {

                        $classes = EncounterMember::getClasses();
                        $classes[0] = __("Minden kaszt");
                        $classId = 0;
                        $specs = array();
                        $specs[0] = __("Minden spec");
                        $specId = 0;

                        $roles = EncounterMember::getRoles();
                        $roles[0] = __("Minden role");
                        $roleId = 0;

                        $view = view("ladder/pve/ajax/hps_dps", compact(
                            "classes",
                            "modeId",
                            "specs",
                            "classId",
                            "specId",
                            "roleId",
                            "roles"
                        ));

                        return json_encode(array(
                            "view" => $view->render(),
                            "url" => ""
                        ));
                    }
                }
                else if ( $modeId == "rescent" || $modeId == "speed")
                {
                    $encounters = Encounter::where("encounter_id", "=", $encounterId)->where("difficulty_id", "=", $difficultyId);

                    //  Realm filter
                    if ($_request->has('tauri') || $_request->has('wod') || $_request->has('evermoon')) {
                        $realms = array();
                        if ($_request->has('tauri')) {
                            array_push($realms, 0);
                        }
                        if ($_request->has('wod')) {
                            array_push($realms, 1);
                        }
                        if ($_request->has('evermoon')) {
                            array_push($realms, 2);
                        }
                        $encounters = $encounters->whereIn('realm_id', $realms);
                    }

                    // Faction filter
                    $factions = array();
                    if ($_request->has('alliance') || $_request->has('horde')) {
                        if ($_request->has('alliance')) {
                            array_push($factions, 0);
                        }
                        if ($_request->has('horde')) {
                            array_push($factions, 1);
                        }
                        if ($_request->has('ismeretlen')) {
                            array_push($factions, 3);
                        }
                    }
                    $order = $modeId == "rescent" ? "killtime" : "fight_time";
                    $order2 = $modeId == "rescent" ? "desc" : "asc";

                    $encounters = $encounters->orderBy($order, $order2)->get();

                    $guildAdded = array();
                    foreach ($encounters as $key => $encounter) {
                        if ($encounter->guild_id !== 0) {
                            if ( !in_array($encounter->guild_id, $guildAdded) || $modeId == "rescent" )
                            {
                                $guildAdded[] = $encounter->guild_id;
                                $guild = Guild::where("id", "=", $encounter->guild_id)->first();
                                $encounter->guild_name = $guild->name;
                                $encounter->faction = $guild->faction;
                                if (count($factions) && !in_array($encounter->faction, $factions)) {
                                    $encounters->forget($key);
                                }
                            }
                            else
                            {
                                $encounters->forget($key);
                            }
                        } else if (count($factions)) {
                            $encounters->forget($key);
                        }
                    }

                    $view = view("ladder/pve/ajax/rescent_speed", compact("encounters", "modeId"));

                    return json_encode(array(
                        "view" => $view->render(),
                        "url" => ""
                    ));
                }
            }
            else
            {
                $modes = array(
                    "rescent" => __("Új"),
                    "speed" => __("Speedkill"),
                    "dps" => "DPS",
                    "hps" => "HPS"
                );
                $modeId = Defaults::ENCOUNTER_SORT;
                $difficultyId = $_request->get("difficulty_id", Defaults::DIFFICULTY_ID);

                $difficulties = Encounter::getMapDifficultiesForSelect($expansionId, $mapId, $encounterId);

                $view = view("ladder/pve/ajax/encounter", compact(
                    "modes",
                    "modeId",
                    "encounterId",
                    "mapId",
                    "difficulties",
                    "difficultyId"
                ));

                return json_encode(array(
                    "view" => $view->render(),
                    "url" => URL::to("ladder/pve/" . Encounter::EXPANSION_SHORTS[$expansionId] . "/" . Encounter::getMapUrl($expansionId, $mapId) . "/" . Encounter::getUrlName($encounterId) . "/" . Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId])
                ));
            }
        }
        else {

            $expansionId = $_request->get("expansion_id", $_expansion_id);
            $mapId = $_request->get("map_id", $_map_id);
            if ( $_request->has("difficulty_id"))
            {

                $raidEncounters = array();
                $raids = Encounter::EXPANSION_RAIDS_COMPLEX["map_exp_" . $expansionId];
                foreach ($raids as $raid) {
                    if ($raid["id"] == $mapId) {
                        $raidEncounters = $raid["encounters"];
                        break;
                    }
                }

                $difficultyId = $_request->get("difficulty_id");

                $encounters = array();
                foreach ($raidEncounters as $raidEncounter) {
                    $encounterId = $raidEncounter["encounter_id"];
                    $fastestEncounterId = LadderCache::getFastestEncounterId($encounterId, $difficultyId);
                    $encounter = Encounter::where("id", "=", $fastestEncounterId)->first();
                    if ($encounter !== null) {
                        if ($encounter->guild_id !== 0) {
                            $guild = Guild::where("id", "=", $encounter->guild_id)->first();
                            $encounter->guild_name = $guild->name;
                            $encounter->faction = $guild->faction;
                        }
                        $encounter->top_dps = Encounter::getTopDps($encounterId, $difficultyId);
                        $encounters[] = $encounter;
                    }
                }

                $view = view("ladder/pve/ajax/map_difficulty", compact(
                    "encounters",
                    "expansionId",
                    "mapId",
                    "difficultyId"));

                return json_encode(array(
                    "view" => $view->render(),
                    "url" => ""
                ));
            }
            else
            {

                $defaultDifficultyIndex = 0;
                $difficulties = Encounter::getMapDifficulties($expansionId, $mapId);
                $encounters = array();
                $defaultDifficultyId = null;
                $backUpDifficultyId = null;
                foreach ($difficulties as $index => $difficulty) {
                    $difficultyId = $difficulty["id"];
                    $backUpDifficultyId = $difficultyId;
                    if ($difficultyId == 5 && !$_request->has("default_difficulty_id") || $_request->get("default_difficulty_id") == $difficultyId ) {
                        $defaultDifficultyIndex = $index;
                        $defaultDifficultyId = $difficultyId;
                    }
                }
                if ( $defaultDifficultyId == null )
                {
                    $defaultDifficultyId = $backUpDifficultyId;
                }

                $maps = Encounter::getExpansionMaps($expansionId);


                $view = view("ladder/pve/ajax/map", compact("encounters",
                    "difficulties",
                    "defaultDifficultyIndex",
                    "mapId",
                    "maps",
                    "expansionId"
                ));

                return json_encode(array(
                    "view" => $view->render(),
                    "url" => URL::to("ladder/pve/" . Encounter::EXPANSION_SHORTS[$expansionId] . "/" . Encounter::getMapUrl($expansionId, $mapId) . "/" . Encounter::SIZE_AND_DIFFICULTY_URL[$defaultDifficultyId])
                ));
            }
        }
    }

    public function index(Request $_request)
    {
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

        return view("ladder/pve/index", compact(
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
}