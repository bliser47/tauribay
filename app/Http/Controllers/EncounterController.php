<?php
/**
 * Created by PhpStorm.
 * User: Tamas
 * Date: 1/8/2019
 * Time: 5:14 PM
 */

namespace TauriBay\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\Exception;
use TauriBay\Defaults;
use TauriBay\EncounterMember;
use TauriBay\EncounterTop;
use TauriBay\Loot;
use TauriBay\Realm;
use TauriBay\Encounter;
use TauriBay\Tauri\Skada;
use TauriBay\Tauri\CharacterClasses;
use TauriBay\Guild;
use TauriBay\Tauri\ApiClient;
use Carbon\Carbon;
use TauriBay\Tauri;
use DB;


class EncounterController extends Controller
{
    public function index(Request $_request, $encounter_name_url)
    {

    }

    public function log(Request $_request, $encounter_id, $logid)
    {
        $encounter = Encounter::where("encounters.id", "=", $logid)
            ->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')
            ->select(array
                (
                    "encounters.id as id",
                    "encounters.encounter_id as encounter_id",
                    "encounters.realm_id as realm_id",
                    "encounters.difficulty_id as difficulty_id",
                    "guilds.name as name",
                    "encounters.killtime as killtime",
                    "encounters.fight_time as fight_time",
                    "guilds.faction as faction",
                    "guilds.id as guild_id",
                    "encounters.wipes as wipes",
                    "encounters.deaths_total as deaths_total",
                    "encounters.deaths_fight as deaths_fight",
                )
            )
            ->first();

        if ( $encounter ) {
            $members = EncounterMember::where("encounter_id", "=", $encounter->id)->get();


            foreach ($members as $member) {
                $member->total_heal = $member->heal_done + $member->absorb_done;
            }


            $membersDamage = array();
            if ($encounter->encounter_id !== 1572 || $encounter->killtime > Encounter::DURUMU_DMG_INVALID_BEFORE_TIMESTAMP) {
                $membersDamage = $members->sortByDesc("damage_done");
                foreach ($membersDamage as $member) {
                    $member->dps = Skada::calculatePS($encounter, $member, "damage_done");
                    $member->dpsNonFormatted = Skada::calculatePS($encounter, $member, "damage_done", true);
                    $member->percentageDamage = Skada::calculatePercentage($member, $membersDamage->first(), "damage_done");
                }
            }

            $membersHealing = array();
            if ($encounter->killtime > Encounter::HPS_INVALID_BEFORE_TIMESTAMP) {
                $membersHealing = $members->sortByDesc("total_heal");
                foreach ($membersHealing as $member) {
                    $member->hps = Skada::calculatePS($encounter, $member, "total_heal");
                    $member->hpsNonFormatted = Skada::calculatePS($encounter, $member, "total_heal", true);
                    $member->percentageHealing = Skada::calculatePercentage($member, $membersHealing->first(), "total_heal");
                }
            }

            $encounterData = Encounter::ENCOUNTER_IDS[$encounter->encounter_id];
            $realms = Realm::REALMS;
            $shortRealms = Realm::REALMS_SHORT;
            $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;
            $classSpecs = CharacterClasses::CLASS_SPEC_NAMES;

            $mapId = $encounterData["map_id"];
            $expansionId = Encounter::getMapExpansion($mapId);

            $loots = Loot::where("encounter_id", $encounter->id)->leftJoin("items", "loots.item_id", "=", "items.id")->get();

            return view("encounter/encounter", compact("encounter",
                "encounterData",
                "membersDamage",
                "membersDps",
                "membersHealing",
                "membersHps",
                "totalDmg",
                "characterClasses",
                "classSpecs",
                "realms",
                "shortRealms",
                "expansionId",
                "mapId",
                "loots"));
        }
    }

    public function fixMissing(Request $_request)
    {
        ini_set('max_execution_time', 0);

        $api = new Tauri\ApiClient();

        $members = EncounterMember::where("top_processed","<>",1)->take(1000)->get();
        foreach ( $members as $member )
        {
            Encounter::calculateScores($member);
            Encounter::logCharacter($member, $api);
            $member->top_processed = 1;
            $member->save();
        }
    }

    public function fixMissingEncounterMembers(Request $_request)
    {
        ini_set('max_execution_time', 0);

        $encounters = Encounter::where('members_processed','=',false)->get();
        $api = new Tauri\ApiClient();
        $result = array(
            "fixed" => array(),
            "failed" => array()
        );
        foreach ( $encounters as $encounter )
        {
            $log = $api->getRaidLog(Realm::REALMS[$encounter->realm_id], $encounter->log_id);
            if ( array_key_exists("response", $log) ) {
                $guild = Guild::where("id","=",$encounter->guild_id)->first();
                Encounter::updateEncounterMembers($log["response"], $encounter, $guild);
                $result["fixed"][] = $encounter->log_id;
            } else {
                $result["failed"][] = $encounter->log_id;
            }
        }
        return $result;
    }

    public function fixMissingEncounters(Request $_request)
    {
        ini_set('max_execution_time', 0);

        $missing = array();

        $realms = Realm::REALMS;
        foreach ( $realms as $realm_id => $realm ) {
            $page = 0;
            $pageNr = 10000;
            $last = 0;
            $missing[$realm_id] = array();
            do {
                $encounters = Encounter::where("realm_id", "=", $realm_id)->where("log_id", ">", $pageNr * $page)->where("log_id", "<", $pageNr * ($page + 1))->orderBy("log_id")->get();
                foreach ($encounters as $encounter) {
                    $id = $encounter->log_id;
                    if ($last > 0 && $id - 1 !== $last) {
                        for ($i = $last + 1; $i < $id; ++$i) {
                            $missing[$realm_id][] = $i;
                        }
                    }
                    $last = $id;
                }
                $recordsExist = count($encounters) > 0;
                ++$page;
            } while ( $recordsExist );
        }

        $api = new Tauri\ApiClient();
        $result = array(
            "fixed" => array(),
            "failed" => array(),
            "guilddata" => array(),
            "duplicate" => array()
        );
        foreach ( $missing as $realm_id => $realmMissing )
        {
            foreach ( $realmMissing as $missingLogId )
            {
                $raid = Encounter::where("log_id", '=', $missingLogId)->where("realm_id", "=", $realm_id)->first();
                if ($raid == null) {
                    $log = $api->getRaidLog(Realm::REALMS[$realm_id], $missingLogId);
                    if (array_key_exists("response", $log)) {
                        if ( array_key_exists("guilddata",$log["response"]) ) {
                            Encounter::store($api, $log["response"], $realm_id);
                            $result["fixed"][] = $realm_id . '-' . $missingLogId;
                        }
                        else {
                            $result["guilddata"][] = $realm_id . '-' . $missingLogId;
                        }
                    }
                    else {
                        $result["failed"][] = $realm_id . '-' . $missingLogId;
                    }
                }
                else {
                    $result["duplicate"][] = $realm_id . '-' . $missingLogId;
                }
            }
        }
        return $result;
    }
}