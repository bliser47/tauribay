<?php

namespace TauriBay\Http\Controllers;

use TauriBay\Characters;
use TauriBay\Encounter;
use TauriBay\EncounterMember;
use TauriBay\GuildProgress;
use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\Tauri;
use TauriBay\Tauri\CharacterClasses;
use DB;
use Carbon\Carbon;

class ProgressController extends Controller
{
    const REALM_NAMES = array(
        0 => "[HU] Tauri WoW Server",
        1 => "[HU] Warriors of Darkness",
        2 => "[EN] Evermoon"
    );

    const SHORT_REALM_NAMES = array(
        0 => "Tauri",
        1 => "WoD",
        2 => "Evermoon"
    );

    const INVALID_RAIDS = array(43718);

    public function debug(Request $_request)
    {
        $api = new Tauri\ApiClient();
        $realmId = 2;//$_request->has("data") ? $_request->get("data") : 2;
        $realmName = self::REALM_NAMES[$realmId];

        $raidLog = $api->getRaidLog(self::REALM_NAMES[0], 1135);


        /*
        $latestRaids = $api->getRaidLast($realmName);

        $start = "\"response\":{\"logs\":[{";
        $startPos = strpos ($latestRaids,$start);
        $end = "]}}";
        $endPos = strrpos($latestRaids,$end);
        $delimiter = "},{";

        $logs = substr($latestRaids,$startPos+strlen($start),$endPos);
        $logs = explode($delimiter,$logs);

        return "{".$logs[0]."}";
        */

        return json_encode($raidLog);
    }

    public function index(Request $_request)
    {
        $data = DB::table('encounters')->orderBy("fight_time", "desc")->paginate(16);
        $shortRealms = self::SHORT_REALM_NAMES;
        return view("progress", compact("data", 'shortRealms'));
    }


    public function killsFrom(Request $_request)
    {
        if ($_request->has("map_id")) {

            $mapId = $_request->get("map_id");
            $encounters = array();
            foreach (Encounter::MAP_ENCOUNTERS_MERGED[$mapId] as $encounterId) {
                $encountersIds = is_array($encounterId) ? $encounterId : array($encounterId);

                foreach ($encountersIds as $encountersId2) {
                    $encounter = Encounter::where("encounter_id", "=", $encountersId2)
                        ->whereIn("difficulty_id", array(5, 6))
                        ->whereNotIn("encounters.id", self::INVALID_RAIDS)
                        ->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')
                        ->select(array("encounters.id as id", "encounters.encounter_id as encounter_id", "encounters.realm_id as realm", "guilds.faction as faction", "encounters.fight_time as fight_time", "guilds.name as name"))
                        ->orderBy("fight_time")->first();

                    if ($encounter && $encounter->realm !== null) {
                        $encounters[] = array(
                            "actualId" => $encounter->id,
                            "id" => $encounter->encounter_id,
                            "realm" => self::SHORT_REALM_NAMES[$encounter->realm],
                            "realmLong" => self::REALM_NAMES[$encounter->realm],
                            "faction" => $encounter->faction,
                            "name" => Encounter::ENCOUNTER_IDS[$encounter->encounter_id]["name"] . ($encountersId2 == 1581 ? " (25)" : ""),
                            "guild" => $encounter->name,
                            "time" => $encounter->fight_time
                        );
                    } else {
                        $encounter = Encounter::where("encounter_id", "=", $encountersId2)
                            ->whereIn("difficulty_id", array(5, 6))
                            ->orderBy("fight_time")->first();
                        if ($encounter && $encounter->realm_id !== null) {
                            // Find the fastest pug?
                            $encounters[] = array(
                                "actualId" => $encounter->id,
                                "id" => $encounter->encounter_id,
                                "realm" => self::SHORT_REALM_NAMES[$encounter->realm],
                                "realmLong" => self::REALM_NAMES[$encounter->realm],
                                "faction" => -1,
                                "name" => Encounter::ENCOUNTER_IDS[$encounter->encounter_id]["name"],
                                "guild" => "Random",
                                "time" => $encounter->fight_time
                            );
                        }
                    }
                }
            }

            return view("progress_times", compact("encounters"));
        }
        return "";
    }


    public static function calculatePS($encounter, $member, $key, $noFormat = false)
    {
        $ps = $member[$key]/($encounter->fight_time/1000);
        if ( $noFormat )
        {
            return $ps;
        }
        if ( $ps > 999 )
        {
            $x = round($ps);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];
            return $x_display;
        }
        else
        {
            return number_format($ps);
        }
    }

    public static function calculatePercentage($member,$firstMember, $key)
    {
        return $member->$key * 100 / max(array($firstMember->$key, 1));
    }


    public function kill(Request $_request, $logid)
    {
        $encounter = Encounter::where("encounters.id", "=", $logid)
            ->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')
            ->select(array
                (
                    "encounters.id as id",
                    "encounters.encounter_id as encounter_id",
                    "encounters.realm_id as realm_id",
                    "guilds.name as name",
                    "encounters.fight_time as fight_time",
                    "guilds.faction as faction",
                    "encounters.wipes as wipes",
                    "encounters.deaths_total as deaths_total",
                    "encounters.deaths_fight as deaths_fight",
                )
            )
            ->first();

        $members = EncounterMember::where("encounter_id", "=", $encounter->id)->get();

        $membersDamage = $members->sortByDesc("damage_done");



        foreach ( $members as $member )
        {
            $member->total_heal = $member->heal_done + $member->damage_absorb;
        }

        $membersHealing = $members->sortByDesc("total_heal");

        foreach ( $membersDamage as $member ) {
            $member->dps = self::calculatePS($encounter, $member, "damage_done");
            $member->dpsNonFormatted = self::calculatePS($encounter, $member, "damage_done", true);
            $member->percentageDamage = self::calculatePercentage($member, $membersDamage->first(), "damage_done");
        }

        foreach ( $membersHealing as $member ) {
            $member->hps = self::calculatePS($encounter, $member, "total_heal");
            $member->hpsNonFormatted = self::calculatePS($encounter, $member, "total_heal", true);
            $member->percentageHealing = self::calculatePercentage($member, $membersHealing->first(), "total_heal");
        }

        $encounteData = Encounter::ENCOUNTER_IDS[$encounter->encounter_id];
        $realms = self::REALM_NAMES;
        $shortRealms = self::SHORT_REALM_NAMES;
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;
        $classSpecs = CharacterClasses::CLASS_SPEC_NAMES;

        return view("progress_kill" , compact("encounter","encounteData", "membersDamage", "membersDps", "membersHealing", "membersHps","totalDmg","characterClasses","classSpecs","realms","shortRealms"));
    }

    public function kills2encounter(Request $_request, $_encounter_id)
    {
        $bossName = Encounter::ENCOUNTER_IDS[$_encounter_id]["name"];
        $boss_kills = Encounter::where("encounter_id", "=", $_encounter_id)
                    ->whereIn("difficulty_id", array(5,6))
                    ->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')
                     ->select(array("encounters.id as id","encounters.realm_id as realm_id","guilds.name as name","encounters.fight_time as fight_time","guilds.faction as faction"))
                    ->orderBy("fight_time","asc")
                    ->paginate(16);

        $shortRealms = self::SHORT_REALM_NAMES;
        $longRealms = self::REALM_NAMES;

        return view("progress_kills_boss", compact("boss_kills", "shortRealms", "longRealms", "bossName"));
    }

    public function damage(Request $request)
    {
        return view("progress_damage");
    }

    public function kills2(Request $_request)
    {
        $maps = Encounter::MAPS;
        return view("progress_kills2", compact("maps"));
    }

    public function guild(Request $_request)
    {
        $guilds = DB::table('guild_progresses')
            ->where("guild_progresses.map_id", "=", 1098)->whereIn("guild_progresses.difficulty_id",array(5,6))
            ->where("guild_progresses.progress", ">", 0);

        if ( $_request->has("filter") ) {

            // 1. Faction filter
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
                $guilds = $guilds->whereIn('faction', $factions);
            }

            // 2. Realm filter
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
                $guilds = $guilds->whereIn('realm', $realms);
            }

            // 2. Size filter
            if ($_request->has('difficulty10') || $_request->has('difficulty25') ) {
                $difficulties = array();
                if ($_request->has('difficulty10')) {
                    array_push($difficulties, 5);
                }
                if ($_request->has('difficulty25')) {
                    array_push($difficulties, 6);
                }
                $guilds = $guilds->whereIn('difficulty_id', $difficulties);
            }
        }

        $guilds = $guilds->leftJoin('guilds', 'guild_progresses.guild_id', '=', 'guilds.id')
            ->orderBy("guild_progresses.progress", "desc")
            ->orderBy("guild_progresses.clear_time")->get();


        $shortRealms = self::SHORT_REALM_NAMES;
        $longRealms = self::REALM_NAMES;
        return view("progress_guild", compact("guilds", 'shortRealms', 'longRealms'));
    }


    public function updateGuildProgress(Request $_request)
    {
        if ( $_request->has("name") && $_request->has("realm") )
        {
            return response()->json(GuildProgress::reCalculateProgressionFromNameAndRealm($_request->get("name"),$_request->get("realm")));
        }
        return "";
    }

    public function updateGuildProgressAll(Request $_request)
    {
        GuildProgress::reCalculateProgressionForAll();
    }

    public function updateRaidMembers(Request $_request)
    {
        set_time_limit(0);

        $api = new Tauri\ApiClient();

        $lastMember = EncounterMember::orderBy('created_at', 'desc')->first();
        $encounters = $lastMember == null ? Encounter::all() : Encounter::where("members_processed", "=", false)->get();
        foreach ($encounters as $encounter) {
            Encounter::updateEncounterMembers($api, $encounter);
        }
    }

    public function updateRaids(Request $_request)
    {
        $api = new Tauri\ApiClient();
        $realmId = $_request->has("data") ? $_request->get("data") : rand(0,2);
        $realmName = self::REALM_NAMES[$realmId];

        $latestRaids = $api->getRaidLast($realmName);

        $start = "\"response\":{\"logs\":[{";
        $startPos = strpos ($latestRaids,$start);
        $end = "]}}";
        $endPos = strrpos($latestRaids,$end);
        $delimiter = "},{";

        $logs = substr($latestRaids,$startPos+strlen($start),$endPos);
        $logs = explode($delimiter,$logs);

        $result = array();
        for ( $i = 0 ; $i < count($logs) ; ++$i)
        {
            $encounter = Encounter::store($api, json_decode( "{" . $logs[$i]."}" , true), $realmId);
            $result[] = $encounter;
            if ( !$encounter["result"] )
            {
                break;
            }
        }
        return json_encode($result);
    }
}
