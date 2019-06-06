<?php

namespace TauriBay\Http\Controllers;


use TauriBay\Characters;
use Illuminate\Http\Request;
use TauriBay\EncounterMember;
use TauriBay\Realm;
use TauriBay\Trader;
use TauriBay\Tauri;
use TauriBay\Tauri\CharacterClasses;
use Carbon\Carbon;
use TauriBay\Encounter;
use TauriBay\Defaults;


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


    public static function AddCharacter($_api, $_name, $_realmId, $_subMinutes)
    {
        $character = Characters::where("name",'=',$_name)->where('realm','=',$_realmId)->first();
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
                $character->score = self::getCharacterScore($character);
                $character->updated_at = Carbon::now();
                $character->faction = CharacterClasses::ConvertRaceToFaction($characterSheetResponse["race"]);
                $character->class = $characterSheetResponse["class"];
                $character->realm = $_realmId;
                $character->achievement_points = $characterSheetResponse["pts"];
                $character->save();
                return $character;
            }
            else if ( $character )
            {
                $character->delete();
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
        $characters = array();
        if ( !is_null($realmId) ) {
            $realms = Realm::REALMS;
            if (array_key_exists($realmId, $realms)) {
                $characterName = ucfirst(strtolower($_request->get('name')));
                $guildName = $_request->get('guildName');
                $api = new Tauri\ApiClient();
                if (strlen($characterName)) {
                    $char = TopItemLevelsController::AddCharacter($api, $characterName,$realmId, 0);
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
                            $char = TopItemLevelsController::AddCharacter($api,$member["name"],$realmId, 14400);
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

    public static function getCharacterScore($_character) {
        $ids = Encounter::getMapEncountersIds(Defaults::EXPANSION_ID, Defaults::MAP_ID);

        $bests = EncounterMember::where("realm_id","=",$_character->realm)->where("guid","=",$_character->guid)
            ->whereIn("encounter",$ids)
            ->groupBy("encounter")
            ->selectRaw("MAX(dps_score) as maxDps, MAX(hps_score) as maxHps")->get();

        $totalDps = 0;
        $totalHps = 0;
        foreach ( $bests as $best ) {
            $totalDps += $best->maxDps;
            $totalHps += $best->maxHps;
        }
        return max($totalDps,$totalHps);
    }

    public static function UpdateCharacter($_sheet,$_character)
    {
        $_character->score = self::getCharacterScore($_character);
        if ($_sheet && array_key_exists("response", $_sheet)) {
            $characterSheetResponse = $_sheet["response"];
            $newItemLevel = $characterSheetResponse["avgitemlevel"];
            if ( $newItemLevel > $_character->ilvl )
            {
                $_character->ilvl = $newItemLevel;
            }
            $_character->achievement_points = $characterSheetResponse["pts"];
            $_character->updated_at = Carbon::now();
            $_character->save();
        }
        else
        {
            $_character->delete();
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
