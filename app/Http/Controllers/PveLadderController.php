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

class PveLadderController extends Controller
{
    public function expansion(Request $_request, $_expansion_name_short, $_noReturn = false)
    {
        $expansionId = Encounter::convertExpansionShortNameToId($_expansion_name_short);
        $_request->request->add(array(
            "expansion_id" => $expansionId
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
            "map_id" => $mapId
        ));
        if ( !$_noReturn ) {
            return $this->index($_request);
        }
    }


    public function encounter(Request $_request, $_expansion_name_short, $_map_name_short, $_encounter_name_short, $_difficulty_name_short)
    {
        $this->map($_request, $_expansion_name_short, $_map_name_short, true);
        $encounterId = Encounter::convertEncounterShortNameToId(
            $_request->get("expansion_id"),
            $_request->get("map_id"),
            $_encounter_name_short
        );
        $difficultyId = Encounter::convertDifficultyShortNameToId($_difficulty_name_short);
        $_request->request->add(array(
            "encounter_id" => $encounterId,
            "difficulty_id" => $difficultyId
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
            if ( $_request->get("mode_id") )
            {
                $modeId = $_request->get("mode_id");

                $difficulties = Encounter::getMapDifficulties($expansionId, $mapId, $encounterId);
                $defaultDifficulty = Defaults::DIFFICULTY_ID;
                $found = false;
                $first = true;
                foreach ( $difficulties as $difficulty )
                {
                    if ( $first )
                    {
                        $first = true;
                        $defaultDifficulty = $difficulty["id"];
                    }
                    if ( $difficulty["id"] == $defaultDifficulty )
                    {
                        $found = true;
                        break;
                    }
                }
                if ( !$found )
                {
                    $defaultDifficulty = $first;
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

                    if ( $_request->has("difficulty_id"))
                    {
                        $members = EncounterMember::where("encounter", "=", $encounterId)
                            ->where("difficulty_id", "=", $difficultyId);

                        if ( $_request->has("spec_id") && $_request->get("spec_id") > 0 )
                        {
                            $members = $members->where("spec", "=", $_request->get("spec_id"));
                        }
                        else if ( $_request->has("class_id") && $_request->get("class_id") > 0 )
                        {
                            $members = $members->where("class", "=", $_request->get("class_id"));
                        }
                        else if ( $_request->has("role_id") && $_request->get("role_id") > 0 )
                        {
                            $members = $members->whereIn("spec", EncounterMember::getRoleSpecs($_request->get("role_id")));
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
                            $members = $members->whereIn('realm_id', $realms);
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
                            $members = $members->whereIn('faction_id', $factions);
                        }

                        // Hack for fixing HPS and Durumu DPS
                        if ( $modeId == "hps" || ($modeId == "dps" && $encounterId == 1572) )
                        {
                            $members = $members->where("killtime",">",0)->where("killtime", ">", Encounter::DURUMU_DMG_INVALID_BEFORE_TIMESTAMP);
                        }
                        $members = $members->groupBy('realm_id', 'name')->orderBy($modeId,"desc")->take(50)->get();

                        foreach ( $members as $member )
                        {
                            $encounter = Encounter::where("id", "=", $member->encounter_id)->first();
                            if ( $encounter->guild_id !== 0 ) {
                                $guild = Guild::where("id", "=", $encounter->guild_id)->first();
                                $member->guild_id = $encounter->guild_id;
                                $member->guild_name = $guild->name;
                                $member->faction = $guild->faction;
                            }
                        }

                        return view("ladder/pve/ajax/members", compact(
                            "modeId",
                            "members"
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

                        return view("ladder/pve/ajax/hps_dps", compact(
                            "classes",
                            "modeId",
                            "specs",
                            "classId",
                            "specId",
                            "roleId",
                            "roles"
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


                    foreach ($encounters as $key => $encounter) {
                        if ($encounter->guild_id !== 0) {
                            $guild = Guild::where("id", "=", $encounter->guild_id)->first();
                            $encounter->guild_name = $guild->name;
                            $encounter->faction = $guild->faction;
                            if (count($factions) && !in_array($encounter->faction, $factions)) {
                                $encounters->forget($key);
                            }
                        } else if (count($factions)) {
                            $encounters->forget($key);
                        }
                    }

                    $encounters = $encounters->take(20);

                    return view("ladder/pve/ajax/rescent_speed", compact("encounters"));
                }
                else
                {
                    return view("ladder/pve/ajax/encounters");
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
                $difficultyId = $_request->get("difficulty_id_for_filter", Defaults::DIFFICULTY_ID);
                $difficulties = Encounter::getMapDifficultiesForSelect($expansionId, $mapId, $encounterId);

                return view("ladder/pve/ajax/encounter", compact(
                    "modes",
                    "modeId",
                    "encounterId",
                    "mapId",
                    "difficulties",
                    "difficultyId"
                ));
            }
        }
        else {

            $expansionId = $_request->get("expansion_id", $_expansion_id);
            $mapId = $_request->get("map_id", $_map_id);
            if ( $_request->has("difficulty_id") )
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

                return view("ladder/pve/ajax/map_difficulty", compact(
                    "encounters",
                    "expansionId",
                    "mapId",
                    "difficultyId"));
            }
            else
            {
                $defaultDifficultyIndex = 0;
                $difficulties = Encounter::getMapDifficulties($expansionId, $mapId);
                $encounters = array();
                foreach ($difficulties as $index => $difficulty) {
                    $difficultyId = $difficulty["id"];
                    if ($difficultyId == 5) {
                        $defaultDifficultyIndex = $index;
                    }
                }

                $maps = Encounter::EXPANSION_RAIDS[$expansionId];


                return view("ladder/pve/ajax/map", compact("encounters",
                    "difficulties",
                    "defaultDifficultyIndex",
                    "mapId",
                    "maps",
                    "expansionId",
                    "encounterId"
                ));
            }
        }
    }

    public function index(Request $_request)
    {
        $expansionId = $_request->get("expansion_id", Defaults::EXPANSION_ID);
        $mapId = $_request->get("map_id", Defaults::MAP_ID);
        $encounterId = $_request->get("encounter_id", 0);
        $difficultyId = $_request->get("difficulty_id", Defaults::DIFFICULTY_ID);

        $expansions = Encounter::EXPANSIONS;
        $maps = Encounter::EXPANSION_RAIDS[$expansionId];

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
            "difficultyId"
        ));
    }
}