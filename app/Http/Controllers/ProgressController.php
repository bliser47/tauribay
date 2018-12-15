<?php

namespace TauriBay\Http\Controllers;

use TauriBay\Characters;
use TauriBay\Encounter;
use TauriBay\EncounterMember;
use TauriBay\GuildProgress;
use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\Tauri;
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
        $data = DB::table('encounters')->orderBy("created_at","desc")->paginate(16);
        $shortRealms = self::SHORT_REALM_NAMES;
        return view("progress", compact("data", 'shortRealms'));
    }

    public function killFrom(Request $_request)
    {
        if ($_request->has("log_id") && $_request->has("type") ) {

            $kill = Encounter::where("id", "=", $_request->get("log_id"))->first();

            $type = $_request->get("type");
            switch($type)
            {
                case 0:

                    return view("progress_kill_data", compact("kill"));
                    break;

                case 1:
                case 2:

                    $api = new Tauri\ApiClient();

                    $raidLog = $api->getRaidLog(self::REALM_NAMES[$kill->realm_id], $kill->log_id);
                    $members = $raidLog["response"]["members"];
                    usort($members,function($m1,$m2){
                        return $m1["dmg_done"] < $m2["dmg_done"];
                    });

                    $totalDmg = 0;
                    foreach ( $members as $key => $member )
                    {
                        $dmg = $member["dmg_done"];
                        $totalDmg += $dmg;

                        $dps = $dmg/($kill->fight_time/1000);
                        if ( $dps > 999 )
                        {
                            $x = round($dps);
                            $x_number_format = number_format($x);
                            $x_array = explode(',', $x_number_format);
                            $x_parts = array('k', 'm', 'b', 't');
                            $x_count_parts = count($x_array) - 1;
                            $x_display = $x;
                            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
                            $x_display .= $x_parts[$x_count_parts - 1];
                            $members[$key]["dps"] = $x_display;
                        }
                        else
                        {
                            $members[$key]["dps"] = $dps;
                        }
                    }
                    return view("progress_kill_data_" . $type , compact("kill", "members", "totalDmg"));


                break;

                default:
                    return "";
                    break;
            }
        }
    }

    public function killsFrom(Request $_request)
    {
        if ( $_request->has("map_id")) {

            $mapId = $_request->get("map_id");
            $encounters = array();
            foreach (Encounter::MAP_ENCOUNTERS_MERGED[$mapId] as $encounterId) {
                $encountersIds = is_array($encounterId) ? $encounterId : array($encounterId);

                foreach ( $encountersIds as $encountersId2 ) {
                    $encounter = Encounter::where("encounter_id", "=", $encountersId2)
                        ->whereIn("difficulty_id", array(5, 6))
                        ->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')
                        ->select(array("encounters.id as id","encounters.encounter_id as encounter_id","encounters.realm_id as realm","guilds.faction as faction","encounters.fight_time as fight_time","guilds.name as name"))
                        ->orderBy("fight_time")->first();

                    if ($encounter && $encounter->realm !== null) {
                        $encounters[] = array(
                            "actualId" => $encounter->id,
                            "id" => $encounter->encounter_id,
                            "realm" => self::SHORT_REALM_NAMES[$encounter->realm],
                            "faction" => $encounter->faction,
                            "name" => Encounter::ENCOUNTER_IDS[$encounter->encounter_id]["name"] . ( $encountersId2 == 1581 ? " (25)" : ""),
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


    public function kill(Request $_request, $logid)
    {
        $datas = array(
            "DPS" => array(
                "id" => 1
            ),
            "Fight" => array(
                "id" => 0
            ),
        );

        return view("progress_kill", compact("datas","logid"));
    }

    public function kills2encounter(Request $_request, $_encounter_id)
    {
        $bossName = Encounter::ENCOUNTER_IDS[$_encounter_id]["name"];
        $boss_kills = Encounter::where("encounter_id", "=", $_encounter_id)
                    ->whereIn("difficulty_id", array(5,6))
                    ->leftJoin('guilds', 'encounters.guild_id', '=', 'guilds.id')
                     ->select(array("encounters.id as id","guilds.realm as realm_id","guilds.name as name","encounters.fight_time as fight_time","guilds.faction as faction"))
                    ->orderBy("fight_time","asc")
                    ->paginate(16);

        $shortRealms = self::SHORT_REALM_NAMES;
        $longRealms = self::REALM_NAMES;

        return view("progress_kills_boss", compact("boss_kills", "shortRealms", "longRealms", "bossName"));
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
        $api = new Tauri\ApiClient();

        $encounters = Encounter::all();
        foreach ( $encounters as $encounter )
        {
            $anyMember = EncounterMember::where("encounter_id", "=", $encounter->id)->first();
            if ( $anyMember == null )
            {

                $raidLog = $api->getRaidLog(self::REALM_NAMES[$encounter->realm_id], $encounter->log_id);
                $members = $raidLog["response"]["members"];
                foreach ( $members as $memberData )
                {
                    $member = new EncounterMember;
                    $member->encounter_id = $encounter->id;

                    $memberName = $memberData["name"];
                    $character = Characters::where("realm", "=", $encounter->realm_id)->where("name", "=", $memberName)->first();
                    if ( $character == null )
                    {
                        $character = TopItemLevelsController::AddCharacter($api, $memberName, $encounter->realm_id, 0);
                    }
                    if ( $character ) {
                        $member->character_id = $character->id;
                        $member->spec = $memberData["spec"];
                        $member->damage_done = $memberData["dmg_done"];
                        $member->damage_taken = $memberData["dmg_taken"];
                        $member->damage_absorb = $memberData["dmg_absorb"];
                        $member->heal_done = $memberData["heal_done"];
                        $member->absorb_done = $memberData["absorb_done"];
                        $member->overheal = $memberData["overheal"];
                        $member->heal_taken = $memberData["heal_taken"];
                        $member->interrupts = $memberData["interrupts"];
                        $member->dispells = $memberData["dispells"];
                        $member->ilvl = $memberData["ilvl"];

                        $member->save();
                    }
                }
            }
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
            $encounter = Encounter::store(json_decode( "{" . $logs[$i]."}" , true), $realmId);
            $result[] = $encounter;
            if ( !$encounter["result"] )
            {
                break;
            }
        }
        return json_encode($result);
    }
}
