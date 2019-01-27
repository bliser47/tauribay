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
use TauriBay\MemberTop;
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

        $_request->session()->put('dps-roleId', 0);
        $_request->session()->put('dps-classId', 0);
        $_request->session()->put('dps-specId', 0);

        $_request->session()->put('hps-roleId', 0);
        $_request->session()->put('hps-classId', 0);
        $_request->session()->put('hps-specId', 0);


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
                        if ( true ||  !$cacheValue ) {

                            $members = MemberTop::where("member_tops.encounter_id", "=", $encounterId)
                                ->where("member_tops.difficulty_id", "=", $difficultyId)->where($modeId,">",0);

                            if ($_request->has("spec_id") && $_request->get("spec_id") > 0) {
                                $members = $members->where("member_tops.spec", "=", $_request->get("spec_id"));
                                $_request->session()->put($modeId.'-specId', $_request->get("spec_id"));
                                $_request->session()->put($modeId.'-classId', $_request->get("class_id"));
                            }
                            else if ($_request->has("class_id") && $_request->get("class_id") > 0) {
                                $members = $members->where("member_tops.class", "=", $_request->get("class_id"));
                                $_request->session()->put($modeId.'-classId', $_request->get("class_id"));
                                $_request->session()->put($modeId.'-specId', 0);
                            }
                            if ($_request->has("role_id") && $_request->get("role_id") > 0) {
                                $members = $members->whereIn("member_tops.spec", EncounterMember::getRoleSpecs($_request->get("role_id")));
                                $_request->session()->put($modeId.'-roleId', $_request->get("role_id"));
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
                                $members = $members->whereIn('member_tops.realm_id', $realms);
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
                                $members = $members->whereIn('member_tops.faction_id', $factions);
                            }

                            $members = $members->orderBy($modeId,"desc");
                            $members = $members->leftJoin("encounters", "encounters.id" , "=", "member_tops." . $modeId . "_encounter_id")
                                ->select(array(
                                    "member_tops.name as name",
                                    "member_tops.class as class",
                                    "member_tops.spec as spec",
                                    "member_tops.dps as dps",
                                    "member_tops.hps as hps",
                                    "member_tops.faction_id as faction_id",
                                    "encounters.guild_id as guild_id",
                                    "encounters.id as encounter_id",
                                    "encounters.encounter_id as encounter",
                                    "member_tops.realm_id as realm_id",
                                    "encounters.fight_time as fight_time",
                                    "encounters.killtime as killtime",
                                    "member_tops.dps_ilvl as dps_ilvl",
                                    "member_tops.hps_ilvl as hps_ilvl"
                                ));
                            $members = $members->paginate(10);

                            foreach ( $members as $member )
                            {
                                if ($member->guild_id !== 0) {
                                    $guild = Guild::where("id", "=", $member->guild_id)->first();
                                    if ( $guild !== null ) {
                                        $member->guild_name = $guild->name;
                                        $member->faction = $guild->faction;
                                    }
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
                        $roles = EncounterMember::getRoles();
                        $roles[0] = __("Minden role");
                        $roleId = $_request->session()->get($modeId."-roleId", 0);

                        $classes = $roleId == 0 ? EncounterMember::getClasses() : EncounterMember::getRoleClasses($roleId);
                        $classes[0] = __("Minden kaszt");
                        $classId = $_request->session()->get($modeId."-classId", 0);

                        $specId = $_request->session()->get($modeId."-specId", 0);
                        $specs = $classId == 0 ? array() : ($roleId == 0 ? EncounterMember::getSpecs($classId) : EncounterMember::getRoleClassSpecs($roleId, $classId));
                        $specs[0] = __("Minden spec");


                        $view = view("ladder/pve/ajax/hps_dps", compact(
                            "modeId",
                            "classes",
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

                $difficulties = Encounter::getMapDifficultiesShortForSelect($expansionId, $mapId, $encounterId);
                $encounters = Encounter::getMapEncountersShort($expansionId, $mapId);

                $view = view("ladder/pve/ajax/encounter", compact(
                    "modes",
                    "modeId",
                    "encounters",
                    "encounterId",
                    "mapId",
                    "difficulties",
                    "difficultyId",
                    "expansionId"
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

                $cacheKey = http_build_query($_request->all());
                $cacheValue = Cache::get($cacheKey);
                if (  !$cacheValue ) {

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

                $cacheKey = http_build_query($_request->all());
                $cacheValue = Cache::get($cacheKey);
                $cacheUrlValue = Cache::get($cacheKey."URL");
                if (  !$cacheValue || !$cacheUrlValue ) {
                    $defaultDifficultyIndex = 0;
                    $difficulties = Encounter::getMapDifficulties($expansionId, $mapId);
                    $encounters = array();
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


                    $view = view("ladder/pve/ajax/map", compact("encounters",
                        "difficulties",
                        "defaultDifficultyIndex",
                        "mapId",
                        "maps",
                        "expansionId"
                    ));

                    $cacheValue = $view->render();
                    Cache::put($cacheKey, $cacheValue, 1200); // 20 hours

                    $cacheUrlValue = URL::to("ladder/pve/" . Encounter::EXPANSION_SHORTS[$expansionId] . "/" . Encounter::getMapUrl($expansionId, $mapId) . "/" . Encounter::SIZE_AND_DIFFICULTY_URL[$defaultDifficultyId]);
                    Cache::put($cacheKey . "URL", $cacheUrlValue, 1200);
                }

                return json_encode(array(
                    "view" => $cacheValue,
                    "url" => $cacheUrlValue
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