<?php

namespace TauriBay\Http\Controllers;

use TauriBay\Defaults;
use TauriBay\Encounter;
use Illuminate\Http\Request;
use TauriBay\EncounterMember;
use TauriBay\EncounterTop;
use TauriBay\Faction;
use TauriBay\Guild;
use TauriBay\LadderCache;
use TauriBay\MemberTop;
use TauriBay\Realm;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use TauriBay\Loot;
use TauriBay\Tauri\CharacterClasses;
use TauriBay\Tauri\Skada;

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

        $_request->session()->put('modeId', Defaults::ENCOUNTER_SORT);


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
                        if (!$_request->has("refresh_cache") )
                        {
                            $cacheKey = http_build_query($_request->all()) . "?v=3";
                            $cacheValue = Cache::get($cacheKey);
                            $cacheUrlValue = Cache::get($cacheKey."URL");
                        } else {
                            $cacheValue = "";
                            $cacheUrlValue = "";
                            unset($_request['refresh_cache']);
                            $cacheKey = http_build_query($_request->all()) . "?v=3";
                        }
                        if ( !$cacheValue ) {

                            $top100LowLimitCacheKey = http_build_query($_request->all()) . "_low_limit";
                            $top100LowLimitCache = Cache::get($top100LowLimitCacheKey);
                            if ( !$top100LowLimitCache ) {
                                $top100LowLimitCache = 0;
                            }

                            $members = MemberTop::where("member_tops.encounter_id", "=", $encounterId)
                                ->where("member_tops.difficulty_id", "=", $difficultyId)->where($modeId,">",$top100LowLimitCache);

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
                            if ( count($realms) > 0 && count($realms) < count(Realm::REALMS) ) {
                                $members = $members->whereIn('member_tops.realm_id', $realms);
                            }

                            $factions = array();
                            if ($_request->has('alliance')) {
                                array_push($factions, Faction::ALLIANCE);
                            }
                            if ($_request->has('horde')) {
                                array_push($factions, Faction::HORDE);
                            }
                            if ( count($factions) == 1 ) {
                                $members = $members->whereIn('member_tops.faction_id', $factions);
                            }

                            $members = $members->orderBy($modeId,"desc");
                            $members = $members->limit(100)->select(
                                "member_tops.name",
                                "member_tops.spec",
                                "member_tops.realm_id",
                                "member_tops.guid",
                                "member_tops.faction_id",
                                "member_tops." . $modeId,
                                "member_tops." . $modeId . "_ilvl as ilvl",
                                "member_tops." . $modeId . "_encounter_id as encounter_id",
                                "member_tops." . $modeId . "_encounter_killtime as encounter_killtime",
                                "member_tops." . $modeId . "_encounter_fight_time as encounter_fight_time"
                            )->get();

                            if ( $members->count() > 99 ) {
                                Cache::put($top100LowLimitCacheKey, $members[99]->$modeId);
                            }

                            $encounterName = Encounter::getUrlName($encounterId);

                            $view = view("ladder/pve/ajax/members", compact(
                                "encounterName",
                                "modeId",
                                "members"
                            ));

                            $cacheValue = $view->render();
                            Cache::put($cacheKey, $cacheValue, 10); // 10 minutes

                            $subQuery = "/?" . http_build_query(array(
                                    "tauri" => $_request->get("tauri"),
                                    "wod" => $_request->get("wod"),
                                    "evermoon" => $_request->get("evermoon"),
                                    "alliance" => $_request->get("alliance"),
                                    "horde" => $_request->get("horde"),
                                    "role" => $_request->get("role"),
                                    "class" => $_request->get("class"),
                                    "spec" => $_request->get("spec"),
                                ));

                            $cacheUrlValue = URL::to("ladder/pve/" . Encounter::EXPANSION_SHORTS[$expansionId] . "/" . Encounter::getMapUrl($expansionId, $mapId) . "/" .
                                    Encounter::getUrlName($encounterId) . "/" . Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId]) . $subQuery;
                            Cache::put($cacheKey . "URL", $cacheUrlValue, 10); // 10 minutes
                        }

                        return json_encode(array(
                            "view" => $cacheValue,
                            "url" => $cacheUrlValue
                        ));
                    }
                    else
                    {
                        if ( $_request->has("role") || $_request->has("class") || $_request->has("spec") ) {
                            $roleId = $_request->has("role") && $_request->get("role") != null ? $_request->get("role") : 0;
                            $classId = $_request->has("class") && $_request->get("class") != null ? $_request->get("class") : 0;
                            $specId = $_request->has("spec") && $_request->get("spec") != null ? $_request->get("spec") :  0;
                        } else {
                            $roleId = $_request->session()->get($modeId . "-roleId", 0);
                            $classId = $_request->session()->get($modeId . "-classId", 0);
                            $specId = $_request->session()->get($modeId . "-specId", 0);
                        }

                        $cacheKey = http_build_query($_request->all()) . "_" . Lang::locale() . "_" . $roleId . "-" . $classId . "-" . $specId . "?v=2";
                        $cacheValue = Cache::get($cacheKey);
                        $cacheUrlValue = Cache::get($cacheKey."URL");
                        if (  !$cacheValue ) {
                            $roles = EncounterMember::getRoles();
                            $roles[0] = __("Minden role");

                            $rolesShort = EncounterMember::getRolesShort();
                            $rolesShort[0] = __("Minden");

                            $classes = $roleId == 0 ? EncounterMember::getClasses() : EncounterMember::getRoleClasses($roleId);
                            $classes[0] = __("Minden kaszt");

                            $classesShort = $roleId == 0 ? EncounterMember::getClassesShort() : EncounterMember::getRoleClassesShort($roleId);
                            $classesShort[0] = __("Minden");

                            $specs = $classId == 0 ? array() : ($roleId == 0 ? EncounterMember::getSpecs($classId) : EncounterMember::getRoleClassSpecs($roleId, $classId));
                            $specs[0] = __("Minden spec");

                            $specsShort = $classId == 0 ? array() : ($roleId == 0 ? EncounterMember::getSpecsShort($classId) : EncounterMember::getRoleClassSpecsShort($roleId, $classId));
                            $specsShort[0] = __("Minden");


                            $view = view("ladder/pve/ajax/hps_dps", compact(
                                "modeId",
                                "classes",
                                "classesShort",
                                "specs",
                                "specsShort",
                                "classId",
                                "specId",
                                "roleId",
                                "roles",
                                "rolesShort"
                            ));

                            $cacheValue = $view->render();
                            Cache::put($cacheKey, $cacheValue, 1440); // 1 day

                            $subQuery = "/?" . http_build_query(array(
                                    "tauri" => $_request->get("tauri"),
                                    "wod" => $_request->get("wod"),
                                    "evermoon" => $_request->get("evermoon"),
                                    "alliance" => $_request->get("alliance"),
                                    "horde" => $_request->get("horde"),
                                    "role" => $_request->get("role"),
                                    "class" => $_request->get("class"),
                                    "spec" => $_request->get("spec"),
                                ));

                            $cacheUrlValue = URL::to("ladder/pve/" . Encounter::EXPANSION_SHORTS[$expansionId] . "/" . Encounter::getMapUrl($expansionId, $mapId) . "/" .
                                    Encounter::getUrlName($encounterId) . "/" . Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId]) . $subQuery;
                            Cache::put($cacheKey . "URL", $cacheUrlValue, 1200);
                        }

                        return json_encode(array(
                            "view" => $cacheValue,
                            "url" => $cacheUrlValue
                        ));
                    }
                }
                else if ( $modeId == "recent" )
                {
                    $cacheKey = http_build_query($_request->all()) . "_" . Lang::locale();
                    $cacheValue = Cache::get($cacheKey);
                    $cacheUrlValue = Cache::get($cacheKey."URL");
                    if (  !$cacheValue ) {

                        $encounters = Encounter::where("encounter_id", "=", $encounterId)->where("difficulty_id", "=", $difficultyId);

                        $realms = array();
                        if ($_request->has('tauri')) {
                            array_push($realms, Realm::TAURI);
                        }
                        if ($_request->has('wod')) {
                            array_push($realms, Realm::WOD);
                        }
                        if ($_request->has('evermoon')) {
                            array_push($realms, Realm::EVERMOON);
                        }
                        if ( count($realms) > 0 && count($realms) < count(Realm::REALMS) ) {
                            $encounters = $encounters->whereIn('realm_id', $realms);
                        }

                        $factions = array();
                        if ($_request->has('alliance')) {
                            array_push($factions, Faction::ALLIANCE);
                        }
                        if ($_request->has('horde')) {
                            array_push($factions, Faction::HORDE);
                        }
                        if ( count($factions) == 1 ) {
                            $encounters = $encounters->whereIn('faction_id', $factions);
                        }

                        if ( $_request->has("max_players") && !empty($_request->get("max_players")) && $_request->get("max_players") < Encounter::DIFFICULTY_SIZE[$difficultyId] ) {
                            $encounters = $encounters->where("member_count","<=",$_request->get("max_players"));
                        }

                        $encounters = $encounters->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')->select('encounters.*', 'guilds.name', 'guilds.faction');
                        $encounters = $encounters->orderBy("killtime", "desc");
                        $encounters = $encounters->paginate(10);

                        $subQuery = "/?" . http_build_query(array(
                                "tauri" => $_request->get("tauri"),
                                "wod" => $_request->get("wod"),
                                "evermoon" => $_request->get("evermoon"),
                                "alliance" => $_request->get("alliance"),
                                "max_players" => $_request->get("max_players")
                            ));


                        $view = view("ladder/pve/ajax/recent", compact("encounters", "modeId"));

                        $cacheValue = $view->render();
                        Cache::put($cacheKey, $cacheValue, 10); // 10 mintues

                        $cacheUrlValue = URL::to("ladder/pve/" . Encounter::EXPANSION_SHORTS[$expansionId] . "/" . Encounter::getMapUrl($expansionId, $mapId) . "/" .
                                Encounter::getUrlName($encounterId) . "/" . Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId]) . $subQuery;
                        Cache::put($cacheKey . "URL", $cacheUrlValue, 10); // 10 minutes
                    }
                    return json_encode(array(
                        "view" => $cacheValue,
                        "url" => $cacheUrlValue
                    ));
                }
                else if ( $modeId == "speed" )
                {
                    if (!$_request->has("refresh_cache") )
                    {
                        $cacheKey = http_build_query($_request->all()) . "_" . Lang::locale() . "?v=9";
                        $cacheValue = Cache::get($cacheKey);
                        $cacheUrlValue = Cache::get($cacheKey."URL");
                    } else {
                        $cacheValue = "";
                        $cacheUrlValue = "";
                        unset($_request['refresh_cache']);
                        $cacheKey = http_build_query($_request->all()) . "_" . Lang::locale() . "?v=9";
                    }
                    if (  !$cacheValue ) {

                        $encounters = EncounterTop::where("encounter_id", "=", $encounterId)->where("difficulty_id", "=", $difficultyId);

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

                        $encounters = $encounters->leftJoin('guilds', 'encounter_tops.guild_id', '=', 'guilds.id')->select('encounter_tops.*', 'guilds.name', 'guilds.faction');

                        // Faction filter
                        $factions = array();
                        if ($_request->has('alliance') || $_request->has('horde')) {
                            if ($_request->has('alliance')) {
                                array_push($factions, Faction::ALLIANCE);
                            }
                            if ($_request->has('horde')) {
                                array_push($factions, Faction::HORDE);
                            }
                            $encounters->whereIn('faction_id', $factions);
                        }

                        $encounters = $encounters->orderBy("fastest_encounter_time", "asc");
                        $encounters = $encounters->paginate(10);

                        $view = view("ladder/pve/ajax/speed", compact("encounters", "modeId"));

                        $cacheValue = $view->render();
                        Cache::put($cacheKey, $cacheValue, 60); // 1 hour

                        $subQuery = "/?" . http_build_query(array(
                                "tauri" => $_request->get("tauri"),
                                "wod" => $_request->get("wod"),
                                "evermoon" => $_request->get("evermoon"),
                                "alliance" => $_request->get("alliance")
                            ));

                        $cacheUrlValue = URL::to("ladder/pve/" . Encounter::EXPANSION_SHORTS[$expansionId] . "/" . Encounter::getMapUrl($expansionId, $mapId) . "/" .
                                Encounter::getUrlName($encounterId) . "/" . Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId]) . $subQuery;
                        Cache::put($cacheKey . "URL", $cacheUrlValue, 60); // 1 hour
                    }
                    return json_encode(array(
                        "view" => $cacheValue,
                        "url" => $cacheUrlValue
                    ));
                }
                else if ( $modeId == "loot" ) {
                    $cacheKey = http_build_query($_request->all()) . "_" . Lang::locale() . "?v=7";
                    $cacheValue = Cache::get($cacheKey);
                    if (  !$cacheValue ) {


                        $items = Loot::leftJoin("encounters", "encounters.id", "=", "loots.encounter_id")
                            ->where("encounters.encounter_id", "=", $encounterId)->where("encounters.difficulty_id", "=", $difficultyId)
                            ->leftJoin("items", "loots.item_id", "=", "items.id")
                            ->groupBy("items.item_id")->select(
                                DB::raw('items.name as name, items.item_id, count(items.item_id) as num, items.icon as icon, items.subclass as subclass, items.inventory_type as inventory_type, items.description as description, items.ilvl as ilvl')
                            )->orderBy("num","DESC")->get();



                        $itemsTotal = Encounter::where("encounters.encounter_id", "=", $encounterId)
                            ->where("encounters.difficulty_id", "=", $difficultyId)->count();


                        $view = view("ladder/pve/ajax/loot", compact("items", "itemsTotal"));

                        $cacheValue = $view->render();
                        Cache::put($cacheKey, $cacheValue, 1440); // 1 day
                    }
                    return json_encode(array(
                        "view" => $cacheValue,
                        "url" => ""
                    ));
                }
            }
            else
            {
                $cacheKey = http_build_query($_request->all()) . "_" . Lang::locale() . "_" . $_request->fullUrl() . "?v=6";
                $cacheValue = Cache::get($cacheKey);
                $cacheUrlValue = Cache::get($cacheKey."URL");
                if (  !$cacheValue || !$cacheUrlValue ) {
                    $modes = array(
                        "recent" => __("Új"),
                        "speed" => "Speed",
                        "dps" => "DPS",
                        "hps" => "HPS",
                        "loot" => "Loot"
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

                    $cacheValue = $view->render();
                    Cache::put($cacheKey, $cacheValue, 1440); // 1 d

                    $cacheUrlValue = URL::to("ladder/pve/" . Encounter::EXPANSION_SHORTS[$expansionId] . "/" . Encounter::getMapUrl($expansionId, $mapId) . "/" . Encounter::getUrlName($encounterId) . "/" . Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId]);
                    Cache::put($cacheKey . "URL", $cacheUrlValue, 1200);
                }

                return json_encode(array(
                    "view" => $cacheValue,
                    "url" => $cacheUrlValue
                ));
            }
        }
        else {
            $expansionId = $_request->get("expansion_id", $_expansion_id);
            $mapId = $_request->get("map_id", $_map_id);
            if ( $_request->has("difficulty_id"))
            {
                $difficultyId = $_request->get("difficulty_id");
                if ( $_request->has("mode_id") ) {
                    $cacheKey = http_build_query($_request->all()) . "?v=21";
                    $cacheValue = Cache::get($cacheKey);
                    $cacheUrlValue = Cache::get($cacheKey."URL");
                    if (  !$cacheValue ) {

                        //  Realm filter
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
                        if ( count($realms) == 0 ) {
                            $realms = Realm::getAllRealmIds();
                        }

                        // Faction filter
                        $factions = array();
                        if ($_request->has('alliance')) {
                            array_push($factions, Faction::ALLIANCE);
                        }
                        if ($_request->has('horde')) {
                            array_push($factions, Faction::HORDE);
                        }
                        if ( count($factions) == 0 ) {
                            $factions = Faction::getAllFactionIds();
                        }

                        switch($_request->get("mode_id"))
                        {
                            case "ladder":
                                $raidEncounters = array();
                                $raids = Encounter::EXPANSION_RAIDS_COMPLEX["map_exp_" . $expansionId];
                                foreach ($raids as $raid) {
                                    if ($raid["id"] == $mapId) {
                                        $raidEncounters = $raid["encounters"];
                                        break;
                                    }
                                }

                                $encounters = array();
                                foreach ($raidEncounters as $raidEncounter) {
                                    $encounterId = $raidEncounter["encounter_id"];
                                    $fastestEncounter = Encounter::getFastest($encounterId, $difficultyId, $realms, $factions);
                                    $added = false;
                                    if ( $fastestEncounter !== null ) {
                                        $encounter = Encounter::where("id", "=", $fastestEncounter->fastest_encounter)->first();
                                        if ($encounter !== null) {
                                            if ($encounter->guild_id !== 0) {
                                                $guild = Guild::where("id", "=", $encounter->guild_id)->first();
                                                $encounter->guild_name = $guild->name;
                                                $encounter->faction = $guild->faction;
                                            }
                                            $topDps = Encounter::getTopDps($encounterId, $difficultyId, $realms, $factions);
                                            if ( $topDps != null && $topDps->id > 0 ) {
                                                $encounter->top_dps = $topDps;
                                                $added = true;
                                            }
                                            $topHps = Encounter::getTopHps($encounterId, $difficultyId, $realms, $factions);
                                            if ( $topHps != null && $topHps->id > 0 ) {
                                                $encounter->top_hps = $topHps;
                                                $added = true;
                                            }
                                            if ( $added ) {
                                                $encounters[] = $encounter;
                                            }
                                        }
                                    }
                                    if ( !$added && Encounter::doubleCheckEncounterExistsOnDifficulty($encounterId, $difficultyId)) {
                                        $encounters[] = $raidEncounter;
                                    }
                                }

                                if ( count($encounters) > 0 ) {
                                    $view = view("ladder/pve/ajax/difficulty/ladder", compact(
                                        "encounters",
                                        "expansionId",
                                        "mapId",
                                        "difficultyId"));
                                    $view = $view->render();
                                } else {
                                    $view = "";
                                }


                                $realmFactionQuery = "/?" . http_build_query(array(
                                        "tauri" => $_request->get("tauri"),
                                        "wod" => $_request->get("wod"),
                                        "evermoon" => $_request->get("evermoon"),
                                        "alliance" => $_request->get("alliance"),
                                        "horde" => $_request->get("horde")
                                    ));

                                $cacheValue = $view;
                                $cacheUrlValue = URL::to("ladder/pve/" . Encounter::EXPANSION_SHORTS[$expansionId] . "/" .
                                        Encounter::getMapUrl($expansionId, $mapId) . "/" . Encounter::SIZE_AND_DIFFICULTY_URL[$difficultyId]) . $realmFactionQuery;
                                Cache::put($cacheKey, $cacheValue, 120); // 2 hours
                                Cache::put($cacheKey . "URL", $cacheUrlValue, 120);
                                break;

                            case "allstars-dps":
                                $mapEncounters = Encounter::getMapEncountersIds($expansionId, $mapId);
                                $members = MemberTop::whereIn("encounter_id",$mapEncounters)->where("difficulty_id","=",$difficultyId)
                                    ->whereIn("realm_id",$realms)->whereIn("faction_id", $factions)
                                    ->groupBy(array("realm_id","name","spec"))
                                    ->selectRaw("member_tops.realm_id as realm, member_tops.name as name, SUM(member_tops.dps) as totalDps, MAX(member_tops.guid) as guid, member_tops.spec as spec, member_tops.class as class")
                                    ->orderBy("totalDps","desc")
                                    ->take(100)->get();
                                foreach ( $members as $member ) {
                                    $member->scorePercentage = Skada::calculatePercentage($member,$members->first(),"totalDps");
                                }
                                $classSpecs = CharacterClasses::CLASS_SPEC_NAMES;

                                $view = view("ladder/pve/ajax/difficulty/allstars", compact(
                                    "members","classSpecs"));
                                $view = $view->render();
                                $cacheValue = $view;

                                break;
                            case "allstars-hps":
                                $mapEncounters = Encounter::getMapEncountersIds($expansionId, $mapId);
                                $members = MemberTop::whereIn("encounter_id",$mapEncounters)->where("difficulty_id","=",$difficultyId)
                                    ->whereIn("realm_id",$realms)->whereIn("faction_id", $factions)
                                    ->groupBy(array("realm_id","name","spec"))
                                    ->selectRaw("member_tops.realm_id as realm, member_tops.name as name, SUM(member_tops.hps) as totalHps, MAX(member_tops.guid) as guid, member_tops.spec as spec, member_tops.class as class")
                                    ->orderBy("totalHps","desc")
                                    ->take(100)->get();
                                foreach ( $members as $member ) {
                                    $member->scorePercentage = Skada::calculatePercentage($member,$members->first(),"totalHps");
                                }
                                $classSpecs = CharacterClasses::CLASS_SPEC_NAMES;

                                $view = view("ladder/pve/ajax/difficulty/allstars", compact(
                                    "members","classSpecs"));
                                $view = $view->render();
                                $cacheValue = $view;

                                break;
                        }
                    }

                    return json_encode(array(
                        "view" => $cacheValue,
                        "url" => $cacheUrlValue
                    ));
                } else {
                    $cacheKey = http_build_query($_request->all()) . "_" . $_request->fullUrl() . "?v=6";
                    $cacheValue = Cache::get($cacheKey);
                    $cacheUrlValue = Cache::get($cacheKey."URL");
                    if (  !$cacheValue || !$cacheUrlValue ) {
                        $modes = array(
                            "ladder" => "Ladder",
                            "allstars-dps" => "DPS gods",
                            "allstars-hps" => "HPS gods"
                        );
                        $modeId = Defaults::DIFFICULTY_MODE;

                        $view = view("ladder/pve/ajax/map_difficulty", compact(
                            "difficultyId",
                            "modes",
                            "modeId"
                        ));

                        $cacheValue = $view->render();
                        Cache::put($cacheKey, $cacheValue, 1440); // 1 d
                    }

                    return json_encode(array(
                        "view" => $cacheValue,
                        "url" => ""
                    ));
                }
            }
            else
            {
                $cacheKey = http_build_query($_request->all()) . "_" . Lang::locale() . "_" . $_request->fullUrl() . "?v=20";
                $cacheValue = Cache::get($cacheKey);
                $cacheUrlValue = Cache::get($cacheKey."URL");
                if (  !$cacheValue || !$cacheUrlValue ) {
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

                    $view = view("ladder/pve/ajax/map", compact(
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