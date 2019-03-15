<?php

namespace TauriBay\Http\Controllers;

use TauriBay\CharacterEncounters;
use TauriBay\Defaults;
use TauriBay\Encounter;
use TauriBay\EncounterMember;
use TauriBay\EncounterTop;
use TauriBay\Faction;
use TauriBay\Guild;
use TauriBay\GuildProgress;
use Illuminate\Http\Request;
use TauriBay\Http\Requests;
use TauriBay\LadderCache;
use TauriBay\MemberTop;
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
        3 => array(),
        4 => array(),
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
        ini_set('max_execution_time', 0);

        EncounterTop::whereIn("fastest_encounter_id",Encounter::INVALID_RAIDS)->delete();
        LadderCache::whereIn("fastest_encounter", Encounter::INVALID_RAIDS)->delete();

        foreach ( Encounter::INVALID_RAIDS as $invalidId ) {

            $encounter = Encounter::where("id","=",$invalidId)->first();

            $guildEncounters = Encounter::where("encounter_id", $encounter->encounter_id)->whereNotIn("encounter_id",Encounter::INVALID_RAIDS)
                ->where("difficulty_id", $encounter->difficulty_id)->where("guild_id", $encounter->guild_id)->get();

            foreach ( $guildEncounters as $guildEncounter ) {
                $guild = Guild::where("id","=",$encounter->guild_id)->first();
                Encounter::refreshEncounterTop($guildEncounter, $guild);
            }
        }

        /*
        $ids = Encounter::getMapEncountersIds(2, 615);
        $data = array();
        foreach ( $ids as $encounter_id ) {
            foreach ( Encounter::SIZE_AND_DIFFICULTY as $difficulty_id => $difficultyName ) {
                LadderCache::calculateFastestEncounter($encounter_id, $difficulty_id, array(0,1,2), array(1,2));
                LadderCache::calculateTopDps($encounter_id, $difficulty_id, array(0,1,2), array(1,2));
            }
        }
        return $data;
        */
        /*
        $enc = CharacterEncounters::groupBy(array("character_id","encounter_member_id"))->havingRaw("count(*) > 1")->selectRaw("min(id) as id")->get();
        $remove = array();
        foreach ( $enc as $e) {
            $remove[] = $e->id;
        }
        CharacterEncounters::whereIn("id",$remove)->delete();
        */
        /*
        $invalid = [
            92775
        ];

        EncounterTop::whereIn("fastest_encounter_id",$invalid)->delete();

        foreach ( $invalid as $invalidId ) {

            $encounter = Encounter::where("id","=",$invalidId)->first();

            $guildEncounters = Encounter::where("encounter_id", $encounter->encounter_id)
                ->where("difficulty_id", $encounter->difficulty_id)->where("guild_id", $encounter->guild_id)->get();

            foreach ( $guildEncounters as $guildEncounter ) {
                $guild = Guild::where("id","=",$encounter->guild_id)->first();
                Encounter::refreshEncounterTop($guildEncounter, $guild);
            }
        }

        $memberTops = EncounterMember::whereIn("encounter_id",$invalid)->get();
        $ret = array();
        foreach ( $memberTops as $member )
        {
            MemberTop::where("dps_encounter_id","=",$member->encounter_id)->delete();
            MemberTop::where("hps_encounter_id","=",$member->encounter_id)->delete();

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
        */

        /*
        $api = new Tauri\ApiClient();
        $realmId = 2;//$_request->has("data") ? $_request->get("data") : 2;

        $lastLogOnRealm = Encounter::where("realm_id","=",$realmId)->orderBy("log_id","desc")->first();


        $log = $api->getCharacterSheet(Realm::REALMS[0], "Blizer", 26877838);

        return $log;


        $items = $data["response"];
        foreach ( $items as $key => $item )
        {
            return $key;
        }
        return $items;
        */
    }

    public function index(Request $_request)
    {
        $isHeroic = true;
        if ($_request->has('difficulty')) {
            $isHeroic = $_request->get("difficulty") == "heroic";
        }

        $difficulties = array();
        if ($_request->has('difficulty10')) {
            array_push($difficulties, $isHeroic ? 5 : 3);
        }
        if ($_request->has('difficulty25')) {
            array_push($difficulties, $isHeroic ? 6 : 4);
        }
        if ( count($difficulties) == 0 ) {
            $difficulties = $isHeroic ? array(5,6) : array(3,4);
        }

        $guilds = DB::table('guild_progresses')->where("guild_progresses.map_id", "=", 1098)->
        whereIn("guild_progresses.difficulty_id",$difficulties)->where("progress",">",0);

        if ( $_request->has("filter") ) {

            $factions = array();
            if ($_request->has('alliance')) {
                array_push($factions, Faction::ALLIANCE);
            }
            if ($_request->has('horde')) {
                array_push($factions, Faction::HORDE);
            }
            if ($_request->has('ismeretlen')) {
                array_push($factions, Faction::NEUTRAL);
            }
            if ( count($factions) > 0 ) {
                $guilds = $guilds->whereIn('faction', $factions);
            }

            $realms = array();
            if ($_request->has('tauri')) {
                array_push($realms, Realm::TAURI);
            }
            if ($_request->has('wod')) {
                array_push($realms, Realm::WOD);
            }
            if ($_request->has('evermoon')) {
                array_push($realms, Realm::EVERMOON);
            }
            if ( count($realms) > 0 ) {
                $guilds = $guilds->whereIn('realm', $realms);
            }
        }

        $sortBy = array(
            "first_kill_unix" => __("Első kill"),
            "clear_time" => __("Legjobb idő")
        );

        $difficulty = array(
            "normal" => "Normal",
            "heroic" => "Heroic"
        );

        $orderByValue = 'first_kill_unix';
        if ( $_request->has('sort') )
        {
            $orderByValue = $_request->get('sort');
        }

        $guilds = $guilds->leftJoin('guilds', 'guild_progresses.guild_id', '=', 'guilds.id')->get();

        foreach ( $guilds as $index => $guild )
        {
            if ( array_key_exists($guild->id, self::OVERRIDE_GUILD_FIRST_KILL_TIME[$guild->difficulty_id]) )
            {
                $guild->first_kill_unix = self::OVERRIDE_GUILD_FIRST_KILL_TIME[$guild->difficulty_id][$guild->id];
            }
            else if ( $guild->first_kill_unix == "" )
            {
                $guild->first_kill_unix = $guild->progress > 0 ? 100000000000 / $guild->progress : 100000000000 * ($index+1);
            }
            $guild->clear_time = $guild->clear_time > 0 ? $guild->clear_time :  ($guild->progress > 0 ? 1/$guild->progress * 100000000000 : 100000000000  * ($index+1));
        }

        $guilds = $guilds->sortBy($orderByValue);

        $shortRealms = Realm::REALMS;
        $longRealms = Realm::REALMS_SHORT;


        return view("progress/index", compact("guilds", 'shortRealms', 'longRealms', 'sortBy', 'difficulty', 'isHeroic'));
    }


    public function updateGuildProgress(Request $_request)
    {
        if ( $_request->has("name") && $_request->has("realm") && $_request->has("difficulty_id"))
        {
            return response()->json(GuildProgress::reCalculateProgressionFromNameAndRealm($_request->get("name"),$_request->get("realm"), $_request->get("difficulty_id")));
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
        ini_set('max_execution_time', 0);

        $api = new Tauri\ApiClient();
        $realmId = $_request->has("data") ? $_request->get("data") : rand(0,2);
        $realmName = Realm::REALMS[$realmId];

        $lastLogOnRealm = Encounter::where("realm_id","=",$realmId)->orderBy("log_id","desc")->first();

        $latestRaids =  $api->getRaidLast($realmName, $lastLogOnRealm->log_id);
        $logs = $latestRaids["response"]["logs"];
        $result = array();
        if ( is_array($logs) ) {
            for ($i = 0; $i < count($logs); ++$i) {
                if ( strlen($logs[$i]["log_id"]) > 0 ) {
                    $raid = Encounter::where("log_id", '=', $logs[$i]["log_id"])->where("realm_id", "=", $realmId)->first();
                    if ($raid == null) {
                        $logs[$i] = $api->getRaidLog($realmName, $logs[$i]["log_id"]);
                        if (array_key_exists("response", $logs[$i])) {
                            $encounter = Encounter::store($api, $logs[$i]["response"], $realmId);
                            $result[$logs[$i]["response"]["log_id"]] = $encounter;
                        }
                    }
                    else
                    {
                        $result["error"] = "Raid (log_id: " . $raid->log_id . ") already exists";
                    }
                }
                else
                {
                    $result["error"] = "Log_id missing: ";
                }
            }
        }
        return json_encode($result);
    }
}
