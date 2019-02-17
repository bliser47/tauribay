<?php
/**
 * Created by PhpStorm.
 * User: Tamas
 * Date: 1/8/2019
 * Time: 5:15 PM
 */

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use TauriBay\Guild;
use TauriBay\Realm;
use TauriBay\Encounter;
use TauriBay\Defaults;

class GuildController extends Controller
{
    public function index(Request $_request, $_guild_id)
    {
        $guild = Guild::where("id", "=", $_guild_id)->first();
        $realm = Realm::REALMS_SHORT[$guild->realm];

        $encounterIDs = Encounter::ENCOUNTER_IDS;


        $expansionId = $_request->get("expansion_id", Defaults::EXPANSION_ID);
        $mapId = $_request->get("map_id", Defaults::MAP_ID);
        $difficultyId = $_request->get("difficulty_id", 0);
        $encounterId = $_request->get("encounter_id", 0);

        $expansions = Encounter::EXPANSIONS;
        $expansionsShort = Encounter::EXPANSIONS_SHORT;
        $maps = Encounter::getExpansionMaps($expansionId);
        $mapsShort = Encounter::getExpansionMapsShort($expansionId);
        if ( !array_key_exists($mapId, $maps))
        {
            $mapId = array_keys($maps)[0];
        }

        $mapEncounters = Encounter::getMapEncountersIds($expansionId, $mapId);
        if ( !in_array($encounterId, $mapEncounters) )
        {
            $encounterId = 0;
        }

        $encounters = Encounter::getMapEncountersShort($expansionId, $mapId);
        $encounters[0] = __("Minden boss");


        if ( $encounterId == 0 ) {
            $encounterIds = $mapEncounters;
        }
        else
        {
            $encounterIds = array($encounterId);
        }

        $difficulties = Encounter::getMapDifficultiesForSelect($expansionId, $mapId, $encounterId);
        $difficulties[0] = __("Minden");

        $difficultiesShort = Encounter::getMapDifficultiesShortForSelect($expansionId, $mapId, $encounterId);
        $difficultiesShort[0] = __("Minden");

        $guildEncounters = $encounter = Encounter::where("guild_id", "=", $_guild_id)
            ->whereNotIn("encounters.id", Encounter::INVALID_RAIDS)
            ->whereIn("encounters.encounter_id", $encounterIds);

        if ( $difficultyId > 0 )
        {
            $guildEncounters = $guildEncounters->where("encounters.difficulty_id", $difficultyId);
        }
        $guildEncounters = $guildEncounters
            ->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')
            ->select(array(
                    "encounters.id as id",
                    "encounters.encounter_id as encounter_id",
                    "encounters.realm_id as realm",
                    "encounters.difficulty_id as difficulty_id",
                    "guilds.faction as faction",
                    "encounters.fight_time as fight_time",
                    "encounters.killtime as killtime",
                    "guilds.id as guild_id")
            )
            ->orderBy("killtime", "desc")->paginate(16);

        return view("guild/index", compact
        (
            "guildEncounters",
            "guild",
            "realm"
            ,"encounterIDs",
            "encounters", "expansionsShort", "encounterId",
            "expansionId", "mapId","difficultyId",
            "expansions", "maps", "mapsShort", "difficulties", "difficultiesShort"
        ));
    }
}