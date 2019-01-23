<?php

namespace TauriBay\Http\Controllers;

use TauriBay\Encounter;
use TauriBay\EncounterMember;
use TauriBay\Guild;
use TauriBay\GuildProgress;
use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\Tauri;
use TauriBay\Tauri\CharacterClasses;
use DB;
use TauriBay\Realm;
use Carbon\Carbon;

class ProgressController extends Controller
{
    const DEFAULT_EXPANSION_ID = 4; // MoP
    const DEFAULT_MAP_ID  = 1098; // Throne of Thunder
    const DEFAULT_DIFFICULTY_ID  = 5; // 10 Heroic

    const OVERRIDE_GUILD_FIRST_KILL_TIME = array(
        5 => array(
            88 => 1534968000, // Kawaii Pandas,
            19 => 1535400000, // Insane
            28 => 1540929600, // Cradle
            66 => 1537473600, // Mythic
            17 => 1541966400, // Conclusion
            6 => 1538078400, // Bad Choice
            15 => 1541188800, // Dark Synergy
            84 => 1537214400, // Muzykanci z Gruzji
            59 => 1540843200, // Crøwd Cøntrøll
        ),
        6 => array()
    );


    public function debug(Request $_request)
    {
        $api = new Tauri\ApiClient();
        $realmId = 2;//$_request->has("data") ? $_request->get("data") : 2;

        $lastLogOnRealm = Encounter::where("realm_id","=",$realmId)->orderBy("log_id","desc")->first();

        $latestRaids = $api->getRaidLast(Realm::REALMS[$realmId], $lastLogOnRealm->log_id);
        $logs = $latestRaids["response"]["logs"];
        return $logs;
    }

    public function index(Request $_request)
    {
        $guilds = DB::table('guild_progresses')->where("guild_progresses.map_id", "=", 1098)->whereIn("guild_progresses.difficulty_id",array(5,6))->where("guild_progresses.progress", ">", 0);

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

        $sortBy = array(
            "clear" => "clear_time",
            "first_kill" => "first_kill_unix"
        );

        $orderBy = 'first_kill_unix';
        if ( $_request->has('sort') )
        {
            $orderBy = $_request->get('sort');
        }

        $guilds = $guilds->leftJoin('guilds', 'guild_progresses.guild_id', '=', 'guilds.id')->get();

        foreach ( $guilds as $guild )
        {
            if ( array_key_exists($guild->id, self::OVERRIDE_GUILD_FIRST_KILL_TIME[$guild->difficulty_id]) )
            {
                $guild->first_kill_unix = self::OVERRIDE_GUILD_FIRST_KILL_TIME[$guild->difficulty_id][$guild->id];
            }
            else if ( $guild->first_kill_unix == "" )
            {
                $guild->first_kill_unix = 100000000000 / $guild->progress;
            }
        }

        $guilds = $guilds->sortBy("first_kill_unix");


        $shortRealms = Realm::REALMS;
        $longRealms = Realm::REALMS_SHORT;

        return view("progress/index", compact("guilds", 'shortRealms', 'longRealms'));
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

    public function updateGuildProgressForNewGuilds(Request $_request)
    {
        GuildProgress::reCalculateProgressionForNewGuilds();
    }

    public function updateRaidMembers(Request $_request)
    {
        set_time_limit(0);

        $api = new Tauri\ApiClient();

        $lastMember = EncounterMember::orderBy('created_at', 'desc')->first();
        $encounters = $lastMember == null ? Encounter::all() : Encounter::where("members_processed", "=", false)->get();
        foreach ($encounters as $encounter) {
            Encounter::updateEncounterMembers($api, $encounter, Guild::where("id","=",$encounter->guild_id));
        }
    }

    public function updateRaids(Request $_request)
    {

        $api = new Tauri\ApiClient();
        $realmId = $_request->has("data") ? $_request->get("data") : rand(0,2);
        $realmName = Realm::REALMS[$realmId];


        $lastLogOnRealm = Encounter::where("realm_id","=",$realmId)->orderBy("log_id","desc")->first();

        $latestRaids =  $api->getRaidLast($realmName, $lastLogOnRealm);
        $logs = $latestRaids["response"]["logs"];
        if ( is_array($logs) ) {
            for ($i = 0; $i < count($logs); ++$i) {
                $encounter = Encounter::store($api, $logs[$i], $realmId);
                $result[] = $encounter;
                if (!$encounter["result"]) {
                    break;
                }
            }
        }
        return json_encode($result);
    }
}
