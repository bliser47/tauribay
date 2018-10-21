<?php

namespace TauriBay\Http\Controllers;


use TauriBay\TopItemLevels;
use Illuminate\Http\Request;
use TauriBay\Trader;
use TauriBay\Tauri;
use TauriBay\Tauri\CharacterClasses;
use Carbon\Carbon;


class TopItemLevelsController extends Controller
{

    const REALMS = array(
        0 => "[HU] Tauri WoW Server",
        1 => "[HU] Warriors of Darkness",
        2 => "[EN] Evermoon"
    );

    const REALMS_SHORT = array(
        0 => "Tauri",
        1 => "WoD",
        2 => "Evermoon"
    );


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $_request)
    {
        $characters = TopItemLevels::GetTopItemLevels($_request)->paginate(16);
        $realms = self::REALMS;
        $realmsShort = self::REALMS_SHORT;

        $characterFactions = array("Ismeretlen", "Horde", "Alliance");
        $characterClasses = CharacterClasses::CHARACTER_CLASS_NAMES;
        $wrapper = true;

        $sortBy = array(
            "ilvl" => "iLvL",
            "achievement_points" => "Achi"
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
        $character = TopItemLevels::where("name",'=',$_name)->where('realm','=',$_realmId)->first();
        if ( $character === null || Carbon::parse($character->updated_at) < Carbon::now()->subMinutes($_subMinutes) )
        {
            $characterSheet = $_api->getCharacterSheet(self::REALMS[$_realmId], $_name);
            if ($characterSheet && array_key_exists("response", $characterSheet)) {
                $characterSheetResponse = $characterSheet["response"];
                if ($character === null) {
                    $character = new TopItemLevels;
                    $character->name = $_name;
                    $character->created_at = Carbon::now();
                }
                $character->updated_at = Carbon::now();
                $character->faction = CharacterClasses::ConvertRaceToFaction($characterSheetResponse["race"]);
                $character->class = $characterSheetResponse["class"];
                // TODO: Fix this
                if ( $character->class == 10 )
                {
                    $character->class = 11;
                }
                else if ( $character->class == 11)
                {
                    $character->class = 10;
                }
                $character->realm = $_realmId;
                $character->ilvl = $characterSheetResponse["avgitemlevel"];
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
            $realms = self::REALMS;
            if (array_key_exists($realmId, $realms)) {
                $characterName = $_request->get('name');
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
                            $char = TopItemLevelsController::AddCharacter($api,$member["name"],$realmId, 1440);
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
        $realmsShort = self::REALMS_SHORT;
        $realms = self::REALMS;
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
     * @param  \TauriBay\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function show(TopItemLevels $topItemLevels)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \TauriBay\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function edit(TopItemLevels $topItemLevels)
    {
        //
    }

    public static function UpdateCharacter($_sheet,$_character)
    {
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
            530 => 1,
            520 => 2,
            510 => 3,
            500 => 4,
            490 => 8,
            480 => 12
        );
        $refreshed = false;

        foreach ( $refreshData as $limit => $refreshTime )
        {
            $characters = TopItemLevels::where('ilvl','>',$limit)->where('updated_at', '<', Carbon::now()->subHours($refreshTime)->toDateTimeString())->orderBy('ilvl', 'desc')->limit(10)->get();
            if ( $characters->count() )
            {
                $api = new Tauri\ApiClient();
                foreach ( $characters as $character )
                {
                    TopItemLevelsController::UpdateCharacter($api->getCharacterSheet(self::REALMS[$character->realm], $character->name), $character);
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
