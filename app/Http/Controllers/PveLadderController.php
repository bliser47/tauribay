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


    public function encounter(Request $_request, $_expansion_name_short, $_map_name_short, $encounter_name_short)
    {
        $this->map($_request, $_expansion_name_short, $_map_name_short, true);
        $encounterId = Encounter::convertEncounterShortNameToId(
            $_request->get("expansion_id"),
            $_request->get("map_id"),
            $encounter_name_short
        );
        $_request->request->add(array(
            "encounter_id" => $encounterId
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
            if ( $_request->has("difficulty_id")) {
                $difficultyId = $_request->get("difficulty_id");


                // Ra-den 25 HC hack
                if ( $encounterId == 1580 && $difficultyId == 6 )
                {
                    $encounterId = 1581;
                }
                else if ( $encounterId == 1082 && $difficultyId == 6 )
                {
                    $encounterId = 1083;
                }

                $members = EncounterMember::where("encounter", "=", $encounterId)
                    ->where("difficulty_id", "=", $difficultyId)
                    ->orderBy("dps","desc")->paginate(10);

                foreach ( $members as $member )
                {
                    $encounter = Encounter::where("id", "=", $member->encounter_id)->first();
                    if ( $encounter->guild_id !== 0 )
                    {
                        $guild = Guild::where("id", "=", $encounter->guild_id)->first();
                        $member->guild_id = $encounter->guild_id;
                        $member->guild_name = $guild->name;
                        $member->faction = $guild->faction;
                    }
                }
                return view("ladder/pve/ajax/members", compact(
                    "members",
                    "mapId"
                ));
            }
            else
            {

                $sorting = array(
                    "rescent" => __("Legutóbbi"),
                    "speed" => __("Legjobb idő"),
                    "dps" => "DPS",
                    "hps" => "HPS"
                );
                $sortingId = "dps";
                $difficultyId = 5;
                $difficulties = Encounter::getMapDifficultiesForSelect($expansionId, $mapId, $encounterId);

                $classes = array();
                $classes[0] = __("Minden kaszt");
                $classId = 0;
                $specs = array();
                $specs[0] = __("Minden spec");
                $specId = 0;

                return view("ladder/pve/ajax/encounter", compact(
                    "sorting",
                    "sortingId",
                    "encounterId",
                    "mapId",
                    "difficulties",
                    "difficultyId",
                    "classes",
                    "specs",
                    "classId",
                    "specId"
                ));
            }
        }
        else {
            $expansionId = $_request->get("expansion_id", $_expansion_id);
            $mapId = $_request->get("map_id", $_map_id);

            $raidEncounters = array();
            $raids = Encounter::EXPANSION_RAIDS_COMPLEX["map_exp_" . $expansionId];
            foreach ($raids as $raid) {
                if ($raid["id"] == $mapId) {
                    $raidEncounters = $raid["encounters"];
                    break;
                }
            }
            $defaultDifficultyIndex = 0;
            $difficulties = Encounter::getMapDifficulties($expansionId, $mapId);
            $encounters = array();
            foreach ($difficulties as $index => $difficulty) {
                $difficultyId = $difficulty["id"];
                if ($difficultyId == 5) {
                    $defaultDifficultyIndex = $index;
                }
                $encounters[$difficultyId] = array();
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
                        $encounters[$difficultyId][] = $encounter;
                    }
                }
            }
            return view("ladder/pve/ajax/map", compact("encounters",
                "difficulties",
                "defaultDifficultyIndex",
                "mapId",
                "expansionId",
                "encounterId"
            ));
        }
    }

    public function index(Request $_request)
    {
        $expansionId = $_request->get("expansion_id", Defaults::EXPANSION_ID);
        $mapId = $_request->get("map_id", Defaults::MAP_ID);
        $encounterId = $_request->get("encounter_id", 0);

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
            "encounterId"
        ));
    }
}