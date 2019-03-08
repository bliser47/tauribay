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
use TauriBay\Characters;
use TauriBay\Defaults;
use TauriBay\EncounterMember;
use TauriBay\MemberTop;
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
                $member->total_damage_taken = $member->damage_taken + $member->damage_absorb;
                $member->score = EncounterMember::isHealer($member->spec) ? $member->hps_score : $member->dps_score;
            }


            $dpsValid = $encounter->encounter_id !== 1572 || $encounter->killtime > Encounter::DURUMU_DMG_INVALID_BEFORE_TIMESTAMP;
            $membersDamage = $members->sortByDesc("damage_done");
            foreach ($membersDamage as $member) {
                $member->dps = Skada::calculatePS($encounter, $member, "damage_done");
                $member->dpsNonFormatted = Skada::calculatePS($encounter, $member, "damage_done", true);
                $member->percentageDamage = Skada::calculatePercentage($member, $membersDamage->first(), "damage_done");
            }

            $membersDamageTaken = $members->sortByDesc("total_damage_taken");
            foreach ($membersDamageTaken as $member) {
                $member->percentageDamageTaken = Skada::calculatePercentage($member, $membersDamageTaken->first(), "total_damage_taken");
            }

            $membersScore = $members->sortByDesc("score");
            foreach ($membersScore as $member) {
                $member->percentageScore = Skada::calculatePercentage($member, $membersScore->first(), "score");
            }

            $hpsValid = $encounter->killtime > Encounter::HPS_INVALID_BEFORE_TIMESTAMP;
            $membersHealing = $members->sortByDesc("total_heal");
            foreach ($membersHealing as $member) {
                $member->hps = Skada::calculatePS($encounter, $member, "total_heal");
                $member->hpsNonFormatted = Skada::calculatePS($encounter, $member, "total_heal", true);
                $member->percentageHealing = Skada::calculatePercentage($member, $membersHealing->first(), "total_heal");
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
                "membersScore",
                "membersDamageTaken",
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
                "dpsValid",
                "hpsValid",
                "loots"));
        }
    }

    public function fix() {
        ini_set('max_execution_time', 0);
        do {
            $found = false;
            $encounters = Encounter::where("top_processed", "=", 0)->take(5000)->get();
            foreach ($encounters as $encounter) {
                $guild = Guild::where("id", "=", $encounter->guild_id)->first();
                Encounter::refreshEncounterTop($encounter, $guild);
                $found = true;
            }
        } while ( $found );
    }

    public function fix2() {
        ini_set('max_execution_time', 0);
        do
        {
            $found = false;
            $members = MemberTop::where("top_processed","=",0)->take(5000)->get();
            foreach ( $members as $member ) {
                Encounter::refreshLadderHps($member);
                Encounter::refreshLadderDps($member);
                $member->top_processed = 1;
                $member->save();
                $found = true;
            }

        } while ( $found );
    }



    public function fixFactions() {
        ini_set('max_execution_time', 0);
        $encounters = EncounterTop::where('faction_id','<',1)->get();
        foreach ( $encounters as $encounterTop ) {
            $encounter = Encounter::where("id","=",$encounterTop->fastest_encounter_id)->first();
            if ( $encounter != null ) {
                $encounterTop->faction_id = $encounter->faction_id;
                $encounterTop->save();
            }
        }
        $members = MemberTop::where('faction_id','<',1)->take(20000)->get();
        foreach ( $members as $memberTop ) {
            $encounter = Encounter::where("id","=",$memberTop->dps_encounter_id)->first();
            if ( $encounter != null ) {
                $memberTop->faction_id = $encounter->faction_id;
                $memberTop->save();
            }
        }
        $embmers = EncounterMember::where("faction_id","<",1)->get();
        foreach ( $embmers as $member ) {
            $encounter = Encounter::where("id","=",$member->encounter_id)->first();
            if ( $encounter != null ) {
                $member->faction_id = $encounter->faction_id;
                $member->save();
            }
        }
    }

    public function fixEncounterFactions(Request $_request) {
        $api = new Tauri\ApiClient();
        ini_set('max_execution_time', 0);
        $encounters = Encounter::where('faction_id','=',-1)->get();
        $nulls = array();
        foreach ( $encounters as $encounter ) {
            $members = EncounterMember::where("encounter_id","=",$encounter->id)->get();
            foreach ( $members as $member )
            {
                $factionId = $member->faction_id;
                if ( $factionId == -1 || $factionId == 0 ) {
                    $character = Characters::where("realm","=",$member->realm_id)->where("name","=",$member->name)->first();
                    if ( $character != null ) {
                        $factionId = $character->faction;
                    }
                    else {
                        $characterSheet = $api->getCharacterSheet(Realm::REALMS[$member->realm_id], $member->name);
                        if ($characterSheet && array_key_exists("response", $characterSheet)) {
                            $characterSheetResponse = $characterSheet["response"];
                            $character = new Characters;
                            $character->name = $member->name;
                            $character->ilvl = $member->ilvl;
                            $character->faction = $member->faction;
                            $character->class = $member->class;
                            $character->realm = $member->realm_id;
                            $character->achievement_points = $characterSheetResponse["pts"];
                            $character->faction = CharacterClasses::ConvertRaceToFaction($characterSheetResponse["race"]);
                            $character->guid = $characterSheetResponse["guid"];
                            $character->save();
                            $member->faction_id = $character->faction;
                            $member->save();
                            $factionId = $member->faction_id;
                        }
                        $nulls[] = $characterSheet;
                    }
                }
                if ( $factionId > 0 ) {
                    break;
                }
            }
            $encounter->faction_id = $factionId;
            $encounter->save();
        }
        return $nulls;
    }

    public function fixTopNotProcessed(Request $_request)
    {
        ini_set('max_execution_time', 0);

        $tops = MemberTop::groupBy(array(
            "encounter_id",
            "difficulty_id",
            "spec"
        ))->selectRaw("encounter_id, difficulty_id, spec, max(dps) as maxDps, max(hps) as maxHps")->get();

        $topData = array();
        foreach ( $tops as $top ) {
            $topData[$top->encounter_id] = array_key_exists($top->encounter_id, $topData) ? $topData[$top->encounter_id] : array();
            $topData[$top->encounter_id][$top->difficulty_id] = array_key_exists($top->difficulty_id, $topData[$top->encounter_id]) ? $topData[$top->encounter_id][$top->difficulty_id] : array();
            $topData[$top->encounter_id][$top->difficulty_id][$top->spec] = array_key_exists($top->spec, $topData[$top->encounter_id][$top->difficulty_id]) ? $topData[$top->encounter_id][$top->difficulty_id][$top->spec] : array(
                "dps" => $top->maxDps,
                "hps" => $top->maxHps
            );
        }

        $members = EncounterMember::where("top_processed","=",0)->orderBy("created_at","desc")->take(5000)->get();
        foreach ( $members as $member ) {

            $topMember = null;
            if ( array_key_exists($member->encounter, $topData) &&
                array_key_exists($member->difficulty_id, $topData[$member->encounter]) &&
                array_key_exists($member->spec, $topData[$member->encounter][$member->difficulty_id]) ){
                $topMember = $topData[$member->encounter][$member->difficulty_id][$member->spec];
            }
            $topDps = $topMember !== null ? $topMember["dps"] : 0;
            if ( $topDps > 0 && $member->dps < $topDps ) {
                $member->dps_score = intval(($member->dps * 100) / $topDps);
            }
            else {
                $member->dps_score = 100;
            }
            $topHps = $topMember !== null ? $topMember["hps"] : 0;
            if ( $topHps > 0 && $member->hps < $topHps ) {
                $member->hps_score = intval(($member->hps * 100) / $topHps);
            }
            else {
                $member->hps_score = 100;
            }

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
                Encounter::updateEncounterMembers($log["response"], $encounter, $guild, $api);
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

    public function fixInvalidEncounters() {

        $invalid = array();

        EncounterTop::whereIn("encounter_id",$invalid)->delete();

        foreach ( $invalid as $invalidId ) {

            $encounter = Encounter::where("id","=",$invalidId)->first();

            $guildEncounters = Encounter::where("encounter_id", $encounter->encounter_id)
                ->where("difficulty_id", $encounter->difficulty_id)->where("guild_id", $encounter->guild_id)->get();

            foreach ( $guildEncounters as $guildEncounter ) {
                $guild = Guild::where("id","=",$encounter->guild_id);
                Encounter::refreshEncounterTop($guildEncounter, $guild);
            }
        }

        $memberTops = EncounterMember::whereIn("encounter_id",$invalid)->get();
        $ret = array();
        foreach ( $memberTops as $member )
        {
            MemberTop::where("dps_encounter_id","=",$member->encounter)->delete();
            MemberTop::where("hps_encounter_id","=",$member->encounter)->delete();

            $memberEncounters = EncounterMember::where("name", $member->name)->where("realm_id", $member->realm_id)->where("encounter", $member->encounter)
                ->where("difficulty_id", $member->difficulty_id)->where("spec", $member->spec)->get();

            $ret[$member->name] = array();

            foreach ( $memberEncounters as $memberEncounter ) {
                $encounter = Encounter::where("id","=",$memberEncounter->encounter_id)->first();
                $guild = Guild::where("id","=",$encounter->guild_id)->first();
                Encounter::refreshMemberTop($memberEncounter, $guild);

                $ret[$member->name][] = $memberEncounter->id;
            }
        }
        return $ret;
    }
}