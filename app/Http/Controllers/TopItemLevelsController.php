<?php

namespace TauriBay\Http\Controllers;


use TauriBay\Characters;
use Illuminate\Http\Request;
use TauriBay\EncounterMember;
use TauriBay\Guild;
use TauriBay\MemberTop;
use TauriBay\Realm;
use TauriBay\Trader;
use TauriBay\Tauri;
use TauriBay\Tauri\CharacterClasses;
use Carbon\Carbon;
use TauriBay\Encounter;
use TauriBay\Defaults;
use DB;
use Auth;

class TopItemLevelsController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $_request)
    {
        $characters = Characters::GetTopItemLevels($_request)->paginate(16);
        $realms = Realm::REALMS;
        $realmsShort = Realm::REALMS_SHORT;

        $characterFactions = array("Ismeretlen", "Horde", "Alliance");
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;
        $wrapper = true;

        $sortBy = array(
            "ilvl" => "iLvL",
            "achievement_points" => "Achi",
            "score" => "Score"
        );

        return view('top_item_levels')->with(compact('characters','realms', 'realmsShort', 'characterFactions', 'characterClasses','sortBy','wrapper'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public static function AddCharacter($_api, $_name, $_realmId, $_subMinutes, $_guid)
    {
        $character = Characters::where("name",'=',$_name)->where('realm','=',$_realmId);
        if ( $_guid ) {
            $character = $character->where("guid","=",$_guid);
        } else {
            $character = $character->orderBy("guid","desc");
        }
        $character = $character->first();
        if ( $character === null || Carbon::parse($character->updated_at) < Carbon::now()->subMinutes($_subMinutes) )
        {
            $characterSheet = $_api->getCharacterSheet(Realm::REALMS[$_realmId], $_name);
            if ($characterSheet && array_key_exists("response", $characterSheet)) {
                $characterSheetResponse = $characterSheet["response"];
                $characterItemLevel = $characterSheetResponse["avgitemlevel"];
                if ($character === null) {
                    $character = new Characters;
                    $character->name = ucfirst(strtolower($_name));
                    $character->ilvl = $characterItemLevel;
                    $character->created_at = Carbon::now();
                }
                else
                {
                    if ( $characterItemLevel > $character->ilvl )
                    {
                        $character->ilvl = $characterItemLevel;
                    }
                }

                $guild = Guild::getOrCreate(array(
                    "name" => $characterSheetResponse["guildName"],
                    "faction" => $character->faction
                ), $character->realm);
                if ( $guild ) {
                    $character->guild_id = $guild->id;
                }

                $character->score = self::getCharacterLiveScore($character,[5,6], null, true);
                $character->score_10n = self::getCharacterLiveScore($character, [3], null, false);
                $character->score_25n = self::getCharacterLiveScore($character, [4], null, false);
                $character->score_10hc = self::getCharacterLiveScore($character, [5], null, false);
                $character->score_25hc = self::getCharacterLiveScore($character, [6], null, false);

                $canHeal = EncounterMember::canClassHeal($character->class);
                $canTank = EncounterMember::canClassTank($character->class);
                if ( !$canTank && !$canTank ) {
                    $character->score10n_dps = $character->score_10n;
                    $character->score25n_dps = $character->score_25n;
                    $character->score10hc_dps = $character->score_10hc;
                    $character->score25hc_dps =  $character->score_25hc;
                }
                else
                {
                    $character->score10n_dps = self::getCharacterLiveScore($character, [3], EncounterMember::ROLE_DPS, false);
                    $character->score25n_dps = self::getCharacterLiveScore($character, [4], EncounterMember::ROLE_DPS, false);
                    $character->score10hc_dps = self::getCharacterLiveScore($character, [5], EncounterMember::ROLE_DPS, false);
                    $character->score25hc_dps = self::getCharacterLiveScore($character, [6], EncounterMember::ROLE_DPS, false);

                    if ( $canHeal) {
                        $character->score10n_healer = self::getCharacterLiveScore($character, [3], EncounterMember::ROLE_HEAL, false);
                        $character->score25n_healer = self::getCharacterLiveScore($character, [4], EncounterMember::ROLE_HEAL, false);
                        $character->score10hc_healer = self::getCharacterLiveScore($character, [5], EncounterMember::ROLE_HEAL, false);
                        $character->score25hc_healer = self::getCharacterLiveScore($character, [6], EncounterMember::ROLE_HEAL, false);
                    }
                    if ( $canTank ) {
                        $character->score10n_tank = self::getCharacterLiveScore($character, [3], EncounterMember::ROLE_TANK, false);
                        $character->score25n_tank = self::getCharacterLiveScore($character, [4], EncounterMember::ROLE_TANK, false);
                        $character->score10hc_tank = self::getCharacterLiveScore($character, [5], EncounterMember::ROLE_TANK, false);
                        $character->score25hc_tank = self::getCharacterLiveScore($character, [6], EncounterMember::ROLE_TANK, false);
                    }
                }

                $character->faction = CharacterClasses::ConvertRaceToFaction($characterSheetResponse["race"]);
                //$character->class = $characterSheetResponse["class"];
                $character->realm = $_realmId;
                $character->achievement_points = $characterSheetResponse["pts"];
                $character->save();
                return $character;
            }
            else if ( $character )
            {
                //$character->delete();
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $_request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $_request)
    {
        $realmId = $_request->get('realm');
        $guid = $_request->get("guid");
        $characters = array();
        if ( !is_null($realmId) ) {
            $realms = Realm::REALMS;
            if (array_key_exists($realmId, $realms)) {
                $characterName = ucfirst(strtolower($_request->get('name')));
                $guildName = $_request->get('guildName');
                $api = new Tauri\ApiClient();
                if (strlen($characterName)) {
                    $char = TopItemLevelsController::AddCharacter($api, $characterName,$realmId, 0, $guid);
                    if ( $char )
                    {
                        array_push($characters, $char);
                    }
                }
                if ( strlen($guildName) )
                {
                    $roster = $api->getGuildRoster($realms[$realmId], $guildName);
                    if ( $roster && array_key_exists("response", $roster)) {
                        $members = $roster['response']['guildList'];
                        $api = new Tauri\ApiClient();
                        foreach ( $members as $member )
                        {
                            $char = TopItemLevelsController::AddCharacter($api,$member["name"],$realmId, 14400, $guid);
                            if ( $char )
                            {
                                array_push($characters, $char);
                            }
                        }
                    }
                }
            }
            else
            {
                return "Realm doesn't exist";
            }
        }
        else
        {
            return "Realm is null";
        }
        $realmsShort = Realm::REALMS_SHORT;
        $realms = Realm::REALMS;
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;
        if ( $_request->has('fromAdd') )
        {
            return view('top_item_levels_characters_added')->with(compact('characters','realmsShort','realms','characterClasses'));
        }
        else if ( $_request->has('refreshTop') )
        {
            return response()->json([
                'characters' => $characters
            ]);
        }
        else
        {
            return view('top_item_levels_characters')->with(compact('characters','realmsShort','realms','characterClasses'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \TauriBay\Characters  $characters
     * @return \Illuminate\Http\Response
     */
    public function show(Characters $characters)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \TauriBay\Characters  $characters
     * @return \Illuminate\Http\Response
     */
    public function edit(Characters $characters)
    {
        //
    }

    public function hallOfFame() {
        $characterFactions = array("Ismeretlen", "Horde", "Alliance");
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;
        $characters = DB::select(DB::raw("select characters.name, characters.class, characters.score, characters.realm, characters.achievement_points, characters.ilvl, characters.guid from characters right join (SELECT max(score) as score,class FROM characters group by class order by score desc) t on characters.class = t.class and characters.score = t.score where t.score > 0"));
        return view("player/hall_of_fame")->with(compact("characters","characterClasses","characterFactions"));
    }

    public static function getCharacterLiveScore($_character, $difficulties = array(5,6), $role_id = null, $doRecovery = false) {
        $ids = Encounter::getMapEncountersIds(Defaults::EXPANSION_ID, Defaults::MAP_ID);


        $bests = MemberTop::where("realm_id","=",$_character->realm)->where("guid","=",$_character->guid)
            ->whereIn("encounter_id",$ids)->whereIn("difficulty_id", $difficulties)
            ->selectRaw("dps, hps, encounter_id, difficulty_id, class, spec");
        if ( $role_id !== null ) {
            $specs = array_keys(EncounterMember::getRoleClassSpecs($role_id, $_character->class));
            $bests->whereIn("spec",$specs);
        }
        $bests = $bests->get();

        if ( $doRecovery )
        {
            $notFoundIds = array();
            foreach ( $ids as $id ) {
                $found = false;
                foreach ( $bests as $best ) {
                    if ( $best->encounter_id == $id ) {
                        $found = true;
                        break;
                    }
                }
                if ( !$found ) {
                    $notFoundIds[] = $id;
                }
            }

            foreach ( $notFoundIds as $id ) {
                // If it's not a heroic encounter or there is data about a heroic encounter
                if ( !Encounter::isHeroicEncounter($id) || !in_array($id, Encounter::getHeroicEncounters()) ) {
                    $bestDps = EncounterMember::where("guid","=",$_character->guid)
                        ->where("encounter","=",$id)->whereIn("difficulty_id", $difficulties)
                        ->selectRaw("dps, hps, encounter as encounter_id, difficulty_id, class, spec")->orderBy("dps","desc")->first();
                    if ( $bestDps ) {
                        $bests->push($bestDps);
                    }
                    if ( EncounterMember::canClassHeal($_character->class) ) {
                        $bestHps = EncounterMember::where("guid","=",$_character->guid)
                            ->where("encounter","=",$id)->whereIn("difficulty_id", $difficulties)
                            ->selectRaw("dps, hps, encounter as encounter_id, difficulty_id, class, spec")->orderBy("hps","desc")->first();
                        if ( $bestHps ) {
                            $bests->push($bestDps);
                        }
                    }
                }
            }
        }


        $scores = array();
        $bestHeroicDps = 0;
        $bestHeroicHps = 0;
        foreach ( $bests as $best ) {
            $topDps = null;
            $topHps = null;
            switch($best->class){
                case EncounterMember::PALADIN:
                case EncounterMember::WARRIOR:
                case EncounterMember::MONK:
                case EncounterMember::DRUID:
                case EncounterMember::SHAMAN:
                    if ( EncounterMember::isHealer($best->spec) ) {
                        $topHps = Encounter::getSpecTopHps($best->encounter_id, $best->difficulty_id, $best->spec);
                    } else {
                        $topDps = Encounter::getSpecTopDps($best->encounter_id, $best->difficulty_id, $best->spec);
                    }
                    break;

                case EncounterMember::DEATH_KNIGHT:
                    if ( EncounterMember::isTank($best->spec)) {
                        $topDps = Encounter::getSpecTopDps($best->encounter_id, $best->difficulty_id, $best->spec);
                    } else {
                        $topDps = Encounter::getClassTopDpsButNotTank($best->encounter_id, $best->difficulty_id, $best->class);
                    }
                    break;

                case EncounterMember::PRIEST:
                    if ( EncounterMember::isHealer($best->spec) ) {
                        $topHps = Encounter::getClassTopHps($best->encounter_id, $best->difficulty_id, $best->class);
                    } else {
                        $topDps = Encounter::getSpecTopDps($best->encounter_id, $best->difficulty_id, $best->spec);
                    }

                default:
                    if ( EncounterMember::isHealer($best->spec) ) {
                        $topHps = Encounter::getClassTopHps($best->encounter_id, $best->difficulty_id, $best->class);
                    } else {
                        $topDps = Encounter::getClassTopDps($best->encounter_id, $best->difficulty_id, $best->class);
                    }
                    break;
            }
            $dpsScore = $topDps !== null ? (($best->dps * 100) / max(1,$topDps)) : 0;
            $hpsScore = $topHps !== null ?  ($best->hps * 100) / max(1,$topHps) : 0;
            if ( !array_key_exists($best->encounter_id, $scores)) {
                $scores[$best->encounter_id] = array(
                    "dps" => 0,
                    "hps" => 0
                );
            }
            if ( Encounter::isHeroicEncounter($best->encounter_id) ) {
                if ( $dpsScore > $bestHeroicDps ) {
                    $bestHeroicDps = $dpsScore;
                }
                if ( $hpsScore > $bestHeroicHps ) {
                    $bestHeroicHps = $hpsScore;
                }
            } else {
                if ( $dpsScore > $scores[$best->encounter_id]["dps"] ) {
                    $scores[$best->encounter_id]["dps"] = $dpsScore;
                }
                if ( $hpsScore > $scores[$best->encounter_id]["hps"] ) {
                    $scores[$best->encounter_id]["hps"] = $hpsScore;
                }
            }
        }

        $total = max($bestHeroicDps,$bestHeroicHps);
        foreach ( $scores as $score ) {
            $total += max($score["dps"],$score["hps"]);
        }

        $maxScore = 1200;
        if ( $difficulties == [5] || $difficulties == [6] || $difficulties == [5,6] ) {
            $maxScore = 1300;
        }
        return ($total/$maxScore) * 100;
    }

    public static function getCharacterLiveScores($_character, $difficulties = array(5,6), $doRecovery = false) {
        $ids = Encounter::getMapEncountersIds(Defaults::EXPANSION_ID, Defaults::MAP_ID);

        $bests = MemberTop::where("realm_id","=",$_character->realm)->where("guid","=",$_character->guid)
            ->whereIn("encounter_id",$ids)->whereIn("difficulty_id",$difficulties)
            ->selectRaw("dps, hps, encounter_id, difficulty_id, class, spec, dps_encounter_id, hps_encounter_id")->get();

        if ( $doRecovery ) {
            $notFoundIds = array();
            foreach ( $ids as $id ) {
                $found = false;
                foreach ( $bests as $best ) {
                    if ( $best->encounter_id == $id ) {
                        $found = true;
                        break;
                    }
                }
                if ( !$found ) {
                    $notFoundIds[] = $id;
                }
            }
            foreach ( $notFoundIds as $id ) {
                // If it's not a heroic encounter or there is data about a heroic encounter
                if ( !Encounter::isHeroicEncounter($id) || !in_array($id, Encounter::getHeroicEncounters()) ) {
                    $bestDps = EncounterMember::where("guid","=",$_character->guid)
                        ->where("encounter","=",$id)->whereIn("difficulty_id", $difficulties)
                        ->selectRaw("dps, hps, encounter as encounter_id, encounter_id as dps_encounter_id, difficulty_id, class, spec")->orderBy("dps","desc")->first();
                    if ( $bestDps ) {
                        $bests->push($bestDps);
                    }
                    if ( EncounterMember::canClassHeal($_character->class) ) {
                        $bestHps = EncounterMember::where("guid","=",$_character->guid)
                            ->where("encounter","=",$id)->whereIn("difficulty_id", $difficulties)
                            ->selectRaw("dps, hps, encounter as encounter_id, encounter_id as hps_encounter_id, difficulty_id, class, spec")->orderBy("hps","desc")->first();
                        if ( $bestHps ) {
                            $bests->push($bestHps);
                        }
                    }
                }
            }
        }


        $scores = array();
        $bestHeroicDpsEncounter = null;
        $bestHeroicHpsEncounter = null;
        $bestHeroicDpsEncounterId = null;
        $bestHeroicHpsEncounterId = null;
        $bestHeroicDps = 0;
        $bestHeroicHps = 0;
        $bestHeroicDpsScore = 0;
        $bestHeroicHpsScore = 0;
        foreach ( $bests as $best ) {
            $topDps = null;
            $topHps = null;
            switch($best->class){
                case EncounterMember::PALADIN:
                case EncounterMember::WARRIOR:
                case EncounterMember::MONK:
                case EncounterMember::DRUID:
                case EncounterMember::SHAMAN:
                    if ( EncounterMember::isHealer($best->spec) ) {
                        $topHps = Encounter::getSpecTopHpsData($best->encounter_id, $best->difficulty_id, $best->spec);
                    } else {
                        $topDps = Encounter::getSpecTopDpsData($best->encounter_id, $best->difficulty_id, $best->spec);
                    }
                    break;

                case EncounterMember::DEATH_KNIGHT:
                    if ( EncounterMember::isTank($best->spec)) {
                        $topDps = Encounter::getSpecTopDpsData($best->encounter_id, $best->difficulty_id, $best->spec);
                    } else {
                        $topDps = Encounter::getClassTopDpsButNotTankData($best->encounter_id, $best->difficulty_id, $best->class);
                    }
                    break;

                case EncounterMember::PRIEST:
                    if ( EncounterMember::isHealer($best->spec) ) {
                        $topHps = Encounter::getClassTopHpsData($best->encounter_id, $best->difficulty_id, $best->class);
                    } else {
                        $topDps = Encounter::getSpecTopDpsData($best->encounter_id, $best->difficulty_id, $best->spec);
                    }

                default:
                    if ( EncounterMember::isHealer($best->spec) ) {
                        $topHps = Encounter::getClassTopHpsData($best->encounter_id, $best->difficulty_id, $best->class);
                    } else {
                        $topDps = Encounter::getClassTopDpsData($best->encounter_id, $best->difficulty_id, $best->class);
                    }
                    break;
            }
            $dpsScore = $topDps !== null ? (($best->dps * 100) / max(1,$topDps->dps)) : 0;
            $hpsScore = $topHps !== null ?  ($best->hps * 100) / max(1,$topHps->hps) : 0;
            if ( !array_key_exists($best->encounter_id, $scores)) {
                $scores[$best->encounter_id] = array(
                    "dps" => 0,
                    "hps" => 0,
                    "encounter_dps" => 0,
                    "encounter_hps" => 0
                );
            }
            if ( Encounter::isHeroicEncounter($best->encounter_id) ) {
                if ( $dpsScore > $bestHeroicDpsScore ) {
                    $bestHeroicDps = $topDps;
                    $bestHeroicDpsScore = $dpsScore;
                    $bestHeroicDpsEncounter = $best;
                    $bestHeroicDpsEncounterId = $best->encounter_id;
                }
                if ( $hpsScore > $bestHeroicHpsScore ) {
                    $bestHeroicHps = $topHps;
                    $bestHeroicHpsScore = $hpsScore;
                    $bestHeroicHpsEncounter = $best;
                    $bestHeroicHpsEncounterId = $best->encounter_id;
                }
            } else {
                if ( $dpsScore > $scores[$best->encounter_id]["dps"] ) {
                    $scores[$best->encounter_id]["dps"] = $dpsScore;
                    $scores[$best->encounter_id]["top_dps"] = $topDps;
                    $scores[$best->encounter_id]["encounter_dps"] = $best;
                }
                if ( $hpsScore > $scores[$best->encounter_id]["hps"] ) {
                    $scores[$best->encounter_id]["hps"] = $hpsScore;
                    $scores[$best->encounter_id]["top_hps"] = $topHps;
                    $scores[$best->encounter_id]["encounter_hps"] = $best;
                }
                $scores[$best->encounter_id]["best"] = $scores[$best->encounter_id]["dps"] > $scores[$best->encounter_id]["hps"] ? "dps" : "hps";
            }
        }

        if ( $bestHeroicDpsScore >= $bestHeroicHpsScore ) {
            $scores[$bestHeroicDpsEncounterId]["best"] = "dps";
            $scores[$bestHeroicDpsEncounterId]["dps"] = $bestHeroicDpsScore;
            $scores[$bestHeroicDpsEncounterId]["top_dps"] = $bestHeroicDps;
            $scores[$bestHeroicDpsEncounterId]["encounter_dps"] = $bestHeroicDpsEncounter;
        } else {
            $scores[$bestHeroicHpsEncounterId]["best"] = "hps";
            $scores[$bestHeroicHpsEncounterId]["hps"] = $bestHeroicHpsScore;
            $scores[$bestHeroicHpsEncounterId]["top_hps"] = $bestHeroicHps;
            $scores[$bestHeroicHpsEncounterId]["encounter_hps"] = $bestHeroicHpsEncounter;
        }

        return $scores;
    }

    public static function getCharacterScore($_character, $difficulties = array(5,6)) {
        $ids = Encounter::getMapEncountersIds(Defaults::EXPANSION_ID, Defaults::MAP_ID);

        $bests = EncounterMember::where("realm_id","=",$_character->realm)->where("guid","=",$_character->guid)
            ->whereIn("encounter",$ids)->whereIn("difficulty_id",$difficulties)
            ->groupBy("encounter")
            ->selectRaw("MAX(dps_score) as maxDps, MAX(hps_score) as maxHps, encounter")->get();

        $totalDps = 0;
        $totalHps = 0;
        $bestHeroicDps = 0;
        $bestHeroicHps = 0;
        foreach ( $bests as $best ) {
            if ( Encounter::isHeroicEncounter($best->encounter) ) {
                $bestHeroicDps = max($bestHeroicDps, $best->maxDps);
                $bestHeroicHps = max($bestHeroicHps, $best->maxHps);
            } else {
                $totalDps += $best->maxDps;
                $totalHps += $best->maxHps;
            }
        }
        $totalDps += $bestHeroicDps;
        $totalHps += $bestHeroicHps;
        $maxScore = 1200;
        if ( $difficulties == [5] || $difficulties == [6] || $difficulties == [5,6] ) {
            $maxScore = 1300;
        }
        return max($totalDps,$totalHps) / $maxScore * 100;
    }

    public static function UpdateCharacter($_sheet,$_character)
    {
        $_character->score = self::getCharacterLiveScore($_character);
        $_character->score_10n = self::getCharacterLiveScore($_character, [3]);
        $_character->score_25n = self::getCharacterLiveScore($_character, [4]);
        $_character->score_10hc = self::getCharacterLiveScore($_character, [5]);
        $_character->score_25hc = self::getCharacterLiveScore($_character, [6]);

        $canTank = EncounterMember::canClassTank($_character->class);
        $canHeal = EncounterMember::canClassHeal($_character->class);

        if ( !$canTank && !$canHeal ) {
            $_character->score10n_dps = $_character->score_10n;
            $_character->score25n_dps = $_character->score_25n;
            $_character->score10hc_dps = $_character->score_10hc;
            $_character->score25hc_dps =  $_character->score_25hc;
        }
        else {
            $_character->score10n_dps = self::getCharacterLiveScore($_character, [3], EncounterMember::ROLE_DPS);
            $_character->score25n_dps = self::getCharacterLiveScore($_character, [4], EncounterMember::ROLE_DPS);
            $_character->score10hc_dps = self::getCharacterLiveScore($_character, [5], EncounterMember::ROLE_DPS);
            $_character->score25hc_dps = self::getCharacterLiveScore($_character, [6], EncounterMember::ROLE_DPS);

            if ( $canTank ) {
                $_character->score10n_tank = self::getCharacterLiveScore($_character, [3], EncounterMember::ROLE_TANK);
                $_character->score10hc_tank = self::getCharacterLiveScore($_character, [5], EncounterMember::ROLE_TANK);
                $_character->score25n_tank = self::getCharacterLiveScore($_character, [4], EncounterMember::ROLE_TANK);
                $_character->score25hc_tank = self::getCharacterLiveScore($_character, [6], EncounterMember::ROLE_TANK);
            }
            if ( $cankHeal ) {
                $_character->score10n_healer = self::getCharacterLiveScore($_character, [3], EncounterMember::ROLE_HEAL);
                $_character->score10hc_healer = self::getCharacterLiveScore($_character, [5], EncounterMember::ROLE_HEAL);
                $_character->score25n_healer = self::getCharacterLiveScore($_character, [4], EncounterMember::ROLE_HEAL);
                $_character->score25hc_healer = self::getCharacterLiveScore($_character, [6], EncounterMember::ROLE_HEAL);
            }
        }



        if ($_sheet && array_key_exists("response", $_sheet)) {
            $characterSheetResponse = $_sheet["response"];
            $newItemLevel = $characterSheetResponse["avgitemlevel"];
            if ( $newItemLevel > $_character->ilvl )
            {
                $_character->ilvl = $newItemLevel;
            }
            $_character->achievement_points = $characterSheetResponse["pts"];
            $_character->faction = CharacterClasses::ConvertRaceToFaction($characterSheetResponse["race"]);
            $_character->updated_at = Carbon::now();
            $guild = Guild::getOrCreate(array(
                "name" => $characterSheetResponse["guildName"],
                "faction" => $_character->faction
            ), $_character->realm);
            if ( $guild ) {
                $_character->guild_id = $guild->id;
            }
            $_character->save();
        }
        else
        {
            $_character->save();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $refreshData = array(
            530 => 10,
            520 => 20,
            510 => 30,
            500 => 40,
            490 => 80,
            480 => 120,
            400 => 240,
            300 => 480
        );
        $refreshed = false;

        $api = new Tauri\ApiClient();

        foreach ( $refreshData as $limit => $refreshTime )
        {
            $characters = Characters::where('ilvl','>',$limit)->where('updated_at', '<', Carbon::now()->subHours($refreshTime)->toDateTimeString())->orderBy('ilvl', 'desc')->limit(10)->get();
            if ( $characters->count() )
            {
                foreach ( $characters as $character )
                {
                    try {
                        TopItemLevelsController::UpdateCharacter($api->getCharacterSheet(Realm::REALMS[$character->realm], $character->name), $character);
                    } catch ( \Exception $e ) {

                    }
                }
                print("Updating item levels above " . $limit . " that are older than " . $refreshTime . " hours.");
                $refreshed = true;
                break;
            }
        }

        if ( !$refreshed )
        {
            print("No refresh required.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \TauriBay\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function destroy(TopItemLevels $topItemLevels)
    {
        //
    }
}
