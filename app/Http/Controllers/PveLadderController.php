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

    public function encounter(Request $_request, $encounter_name_short)
    {

    }


    public function filter(Request $_request, $_expansion_id = Defaults::EXPANSION_ID, $_map_id = Defaults::MAP_ID, $_difficulty_id = Defaults::DIFFICULTY_ID)
    {
        $expansionId = $_request->get("expansion_id", $_expansion_id);
        $mapId = $_request->get("map_id", $_map_id);
        $difficultyId = $_request->get("difficulty_id", $_difficulty_id);

        $raidEncounters = array();
        $raids = Encounter::EXPANSION_RAIDS_COMPLEX["map_exp_" . $expansionId];
        foreach ( $raids as $raid )
        {
            if ( $raid["id"] == $mapId )
            {
                $raidEncounters = $raid["encounters"];
                break;
            }
        }
        $encounters = array();
        foreach ( $raidEncounters as $raidEncounter )
        {
            $encounterId = $raidEncounter["encounter_id"];
            $fastestEncounterId = LadderCache::getFastestEncounterId($encounterId, $difficultyId);
            $encounter = Encounter::where("id", "=", $fastestEncounterId)->first();
            if ( $encounter !== null ) {
                if ($encounter->guild_id !== 0) {
                    $guild = Guild::where("id","=",$encounter->guild_id)->first();
                    $encounter->guild_name = $guild->name;
                    $encounter->faction = $guild->faction;
                }
                $encounter->top_dps = Encounter::getTopDps($encounterId, $difficultyId);
                $encounters[] = $encounter;
            }
        }

        return view("ladder/pve/raid", compact("encounters"));
    }

    public function index(Request $_request)
    {
        $expansionId = $_request->get("expansion_id", Defaults::EXPANSION_ID);
        $mapId = $_request->get("map_id", Defaults::MAP_ID);
        $difficultyId = $_request->get("difficulty_id", Defaults::DIFFICULTY_ID);
        $encounterId = $_request->get("encounter_id", 0);

        $expansions = Encounter::EXPANSIONS;
        $maps = Encounter::EXPANSION_RAIDS[$expansionId];
        $difficulties = Defaults::SIZE_AND_DIFFICULTY;
        $encounters = Encounter::ENCOUNTERS_DEFAULT;

        $encounters[0] = __("Minden boss");

        return view("ladder/pve/index", compact(
            "expansions",
            "maps",
            "mapId",
            "expansionId",
            "difficulties",
            "difficultyId",
            "encounters",
            "encounterId"
        ));
    }
}