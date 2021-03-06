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
use TauriBay\LadderCache;
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

    public function mode(Request $_request, $encounter_id, $logid, $mode) {
        $classSpecs = CharacterClasses::CLASS_SPEC_NAMES;
        $encounter = Encounter::where("encounters.id", "=", $logid)->first();
        switch($mode) {

            case "damage":
                $members = EncounterMember::where("encounter_id", "=", $encounter->id)->get();
                $dpsValid = $encounter->encounter_id !== 1572 || $encounter->killtime > Encounter::DURUMU_DMG_INVALID_BEFORE_TIMESTAMP;
                $membersDamage = $members->sortByDesc("damage_done");
                foreach ($membersDamage as $member) {
                    $member->dps = Skada::calculatePS($encounter, $member, "damage_done");
                    $member->dpsNonFormatted = Skada::calculatePS($encounter, $member, "damage_done", true);
                    $member->percentageDamage = Skada::calculatePercentage($member, $membersDamage->first(), "damage_done");
                }
                return view("encounter/ajax/dps", compact("membersDamage", "dpsValid", "classSpecs"));
                break;

            case "healing":
                $members = EncounterMember::where("encounter_id", "=", $encounter->id)->get();
                foreach ($members as $member) {
                    $member->total_heal = $member->heal_done + $member->absorb_done;
                }
                $hpsValid = $encounter->killtime > Encounter::HPS_INVALID_BEFORE_TIMESTAMP;
                $membersHealing = $members->sortByDesc("total_heal");
                foreach ($membersHealing as $member) {
                    $member->hps = Skada::calculatePS($encounter, $member, "total_heal");
                    $member->hpsNonFormatted = Skada::calculatePS($encounter, $member, "total_heal", true);
                    $member->percentageHealing = Skada::calculatePercentage($member, $membersHealing->first(), "total_heal");
                }
                return view("encounter/ajax/hps", compact("membersHealing", "hpsValid", "classSpecs"));
            break;


            case "damage_taken" :
                $members = EncounterMember::where("encounter_id", "=", $encounter->id)->get();
                foreach ($members as $member) {
                    $member->total_damage_taken = $member->damage_taken + $member->damage_absorb;
                }
                $membersDamageTaken = $members->sortByDesc("total_damage_taken");
                foreach ($membersDamageTaken as $member) {
                    $member->percentageDamageTaken = Skada::calculatePercentage($member, $membersDamageTaken->first(), "total_damage_taken");
                }
                return view("encounter/ajax/damage_taken", compact("membersDamageTaken", "classSpecs"));
            break;

            case "score":
                $members = EncounterMember::where("encounter_id", "=", $encounter->id)->get();
                foreach ($members as $member) {
                    $member->score = EncounterMember::isHealer($member->spec) ? $member->hps_score : $member->dps_score;
                }
                $membersScore = $members->sortByDesc("score");
                foreach ($membersScore as $member) {
                    $member->percentageScore = Skada::calculatePercentage($member, $membersScore->first(), "score");
                }
                return view("encounter/ajax/score", compact("membersScore", "classSpecs"));
            break;

            case "loot" :
                $loots = Loot::where("encounter_id", $encounter->id)->leftJoin("items", "loots.item_id", "=", "items.id")->get();
                return view("encounter/ajax/loot", compact("loots"));
            break;
        }
        return "";
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
            $encounterData = Encounter::ENCOUNTER_IDS[$encounter->encounter_id];
            $realms = Realm::REALMS;
            $shortRealms = Realm::REALMS_SHORT;
            $mapId = $encounterData["map_id"];
            $expansionId = Encounter::getMapExpansion($mapId);
            $isInvalid = Encounter::isInvalid($logid);
            return view("encounter/encounter", compact("encounter",
                "isInvalid",
                "encounterData",
                "realms",
                "shortRealms",
                "expansionId",
                "mapId"));
        }
    }

    public function fix() {
        ini_set('max_execution_time', 0);
        $api = new Tauri\ApiClient();
        do {
            $found = false;
            $encounters = Encounter::where("top_processed", "=", 0)->take(5000)->get();
            foreach ($encounters as $encounter) {
                $found = true;
                $log = $api->getRaidLog(Realm::REALMS[$encounter->realm_id], $encounter->log_id);
                if ( array_key_exists("response", $log) ) {
                    $guild = Guild::where("id","=",$encounter->guild_id)->first();
                    Encounter::updateEncounterMembers($log["response"], $encounter, $guild, $api);
                    $result["fixed"][] = $encounter->log_id;
                } else {
                    $result["failed"][] = $encounter->log_id;
                }
                $encounter->top_processed = 1;
                $encounter->save();
            }
        } while ( $found );
    }

    public function fix3() {
        ini_set('max_execution_time', 0);
        do {
            $found = false;
            $memberTop = MemberTop::where("top_processed", "=", 0)->take(5000)->get();
            foreach ($memberTop as $top) {
                if ( $top->dps_encounter_id > 0 ) {
                    $encounterDPS = Encounter::where("id","=",$top->dps_encounter_id)->first();
                    $top->dps_encounter_fight_time = $encounterDPS->fight_time;
                    $top->dps_encounter_killtime = $encounterDPS->killtime;
                    $found = true;
                }
                if ( $top->hps_encounter_id > 0 ) {
                    $encounterHPS = Encounter::where("id","=",$top->hps_encounter_id)->first();
                    $top->hps_encounter_fight_time = $encounterHPS->fight_time;
                    $top->hps_encounter_killtime = $encounterHPS->killtime;
                    $found = true;
                }
                $top->top_processed = 1;
                $top->save();
            }

        } while ( $found );
    }

    public function fix2() {
        ini_set('max_execution_time', 0);
        do {
            $found = false;
            $memberTop = MemberTop::where("top_processed", "=", 1)->whereNull("dps_encounter_fight_time")
                ->whereNull("hps_encounter_fight_time")->get();
            foreach ($memberTop as $top) {
                if ( $top->dps_encounter_id > 0 ) {
                    $encounterDPS = Encounter::where("id","=",$top->dps_encounter_id)->first();
                    $top->dps_encounter_fight_time = $encounterDPS->fight_time;
                    $top->dps_encounter_killtime = $encounterDPS->killtime;
                    $found = true;
                }
                if ( $top->hps_encounter_id > 0 ) {
                    $encounterHPS = Encounter::where("id","=",$top->hps_encounter_id)->first();
                    $top->hps_encounter_fight_time = $encounterHPS->fight_time;
                    $top->hps_encounter_killtime = $encounterHPS->killtime;
                    $found = true;
                }
                $top->top_processed = 1;
                $top->save();
            }

        } while ( $found );
    }


    public function fixLaddersFromEncounterTops() {
        ini_set('max_execution_time', 0);
        $encounters = EncounterTop::get();
        foreach ( $encounters as $encounterTop ) {
            $encounter = Encounter::where("id","=",$encounterTop->fastest_encounter_id)->first();
            Encounter::refreshLadderSpeedKill($encounter);
        }
    }

    public function fixLaddersFromMemberTops() {
        ini_set('max_execution_time', 0);
        do {
            $found = false;
            $memberTops = MemberTop::where("top_processed","=",0)->take(5000)->get();
            foreach ($memberTops as $top) {
                Encounter::refreshLadderDps($top);
                Encounter::refreshLadderHps($top);
                $top->top_processed = 1;
                $top->save();
                $found = true;
            }
        } while ( $found );
    }

    public function fixCharacters() {
        $api = new Tauri\ApiClient();
        //ini_set('max_execution_time', 0);
        $dps = MemberTop::join('encounter_members', function($join)
            {
                $join->on('member_tops.dps_encounter_id', '=', 'encounter_members.encounter_id');
                $join->on('member_tops.faction_id', '=', 'encounter_members.faction_id');
                $join->on('member_tops.realm_id', '=', 'encounter_members.realm_id');
                $join->on('member_tops.class', '=', 'encounter_members.class');
                $join->on('member_tops.spec', '=', 'encounter_members.spec');
                $join->on('member_tops.dps', '=', 'encounter_members.dps');
                $join->on('member_tops.hps', '=', 'encounter_members.hps');
                $join->on('member_tops.name', '=', 'encounter_members.name');
                $join->on('member_tops.guid', '<>', 'encounter_members.guid');
            })->where("member_tops.dps",">",0)->where("member_tops.encounter_id",">",1500)->select(array(
                "member_tops.guid as guid",
                "member_tops.id as oldId",
                "encounter_members.guid as newGuid"
        ))->get();

        return $dps;

        foreach ( $dps as $d ) {
            $memberTop = MemberTop::where("id","=",$d->oldId)->first();
            $memberTop->guid = $d->newGuid;
            $memberTop->save();
        }
    }

    public function fixEncounterTop(Request $_request) {
        ini_set('max_execution_time', 0);
        do {
            $found = false;
            $encounters = Encounter::where("top_processed","=",0)->take(5000)->get();
            foreach ($encounters as $encounter) {
                $found = true;
                $guild = Guild::where("id","=",$encounter->guild_id)->first();
                Encounter::refreshEncounterTop($encounter, $guild);
                $encounter->top_processed = 1;
                $encounter->save();
            }
        } while ( $found );
    }

    public function fixGuildEncounterFactions(Request $_request) {
        ini_set('max_execution_time', 0);
        $encounters = Encounter::where('guild_id', '>', 0)->get();
        foreach ($encounters as $encounter) {
            $guild = Guild::where("id","=",$encounter->guild_id)->first();
            $encounter->faction_id = $guild->faction;
            $encounter->save();
        }
    }

    public function fixRandomEncounterFactions(Request $_request) {
        ini_set('max_execution_time', 0);
        do {
            $found = false;
            $encounters = Encounter::where('top_processed', '=', 0)->take(5000)->get();
            foreach ($encounters as $encounter) {
                $found = true;
                $factions = array(0, 0, 0);
                $members = EncounterMember::where("encounter_id", "=", $encounter->id)->get();
                foreach ($members as $member) {
                    if ( $member->faction_id > 0 ) {
                        $factions[$member->faction_id]++;
                    }
                }
                $faction = array_search(max($factions), $factions);;
                if ($encounter->faction_id != $faction) {
                    $encounter->faction_id = $faction;
                }
                $encounter->top_processed = 1;
                $encounter->save();
            }
        } while ( $found );
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