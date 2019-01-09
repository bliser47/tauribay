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
use TauriBay\Realm;

class PveLadderController extends Controller
{

    public function encounter(Request $_request, $encounter_name_short)
    {

    }


    public function map(Request $_request, $_expansion_id = Defaults::EXPANSION_ID, $_map_id = Defaults::MAP_ID, $_difficulty_id = Defaults::DIFFICULTY_ID)
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
        foreach ($raidEncounters as $encounter) {
            $encounterId = $encounter["encounter_id"];
            $encounter = Encounter::where("encounter_id", "=", $encounterId)
                ->where("difficulty_id", "=", $difficultyId)
                ->whereNotIn("encounters.id", Encounter::INVALID_RAIDS)
                ->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')
                ->select(array(
                        "encounters.id as id",
                        "encounters.encounter_id as encounter_id",
                        "encounters.realm_id as realm",
                        "guilds.faction as faction",
                        "encounters.fight_time as fight_time",
                        "encounters.killtime as killtime",
                        "guilds.name as name",
                        "guilds.id as guild_id")
                )
                ->orderBy("fight_time")->first();

            if ($encounter && $encounter->realm !== null && array_key_exists($encounter->realm,Realm::REALMS)) {
                $encounterName = Encounter::ENCOUNTER_IDS[$encounter->encounter_id]["name"];
                $encounterNameShort  = array_key_exists($encounterName, Encounter::ENCOUNTER_NAME_SHORTS) ? Encounter::ENCOUNTER_NAME_SHORTS[$encounterName] : $encounterName;
                $encounters[] = array(
                    "actualId" => $encounter->id,
                    "id" => $encounter->encounter_id,
                    "realm" => Realm::REALMS_SHORT[$encounter->realm],
                    "realm_id" => $encounter->realm,
                    "realmLong" => Realm::REALMS[$encounter->realm],
                    "faction" => $encounter->faction,
                    "name" => $encounterName . ($encounterId == 1581 ? " (25)" : ""),
                    "nameShort" => $encounterNameShort,
                    "nameUrl" => Encounter::getUrlName($encounterName),
                    "guild" => $encounter->name,
                    "guild_id" => $encounter->guild_id,
                    "time" => $encounter->fight_time,
                    "date" => $encounter->killtime,
                );
            } else {
                $encounter = Encounter::where("encounter_id", "=", $encounterId)
                    ->where("difficulty_id", "=", $difficultyId)
                    ->orderBy("fight_time")->first();

                if ($encounter && $encounter->realm_id !== null && array_key_exists($encounter->realm_id,Realm::REALMS)) {
                    // Find the fastest pug?\
                    $encounterName = Encounter::ENCOUNTER_IDS[$encounter->encounter_id]["name"];
                    $encounterNameShort  = Encounter::getNameShort($encounterName);
                    $encounters[] = array(
                        "actualId" => $encounter->id,
                        "id" => $encounter->encounter_id,
                        "realm" => Realm::REALMS[$encounter->realm],
                        "realm_id" => $encounter->realm,
                        "realmLong" => Realm::REALMS_SHORT[$encounter->realm],
                        "faction" => -1,
                        "name" => $encounterName,
                        "nameShort" => $encounterNameShort ,
                        "nameUrl" => Encounter::getUrlName($encounterNameShort, true),
                        "guild" => "Random",
                        "guild_id" => 0,
                        "time" => $encounter->fight_time,
                        "date" => $encounter->killtime
                    );
                }
            }
        }

        return view("progress_times", compact("encounters"));
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