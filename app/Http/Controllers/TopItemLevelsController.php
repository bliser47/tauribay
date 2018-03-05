<?php

namespace App\Http\Controllers;


use App\TopItemLevels;
use Illuminate\Http\Request;
use App\Trader;
use App\Tauri;
use App\Tauri\CharacterClasses;
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

        return view('top_item_levels')->with(compact('characters','realms', 'realmsShort', 'characterFactions', 'characterClasses'));
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


    public static function AddCharacter($_api, $_name, $_realmId)
    {
        $character = TopItemLevels::where(array("name" => $_name, 'realm' => $_realmId))->first();
        if (!$character) {

            $characterSheet = $_api->getCharacterSheet(self::REALMS[$_realmId], $_name);
            if ($characterSheet && array_key_exists("response", $characterSheet)) {
                $characterSheetResponse = $characterSheet["response"];
                $character = new TopItemLevels;
                $character->name = $_name;
                $character->faction = CharacterClasses::ConvertRaceToFaction($characterSheetResponse["race"]);
                $character->class = $characterSheetResponse["class"];
                if ( $character->class == 10 )
                {
                    $character->class = 11;
                }
                else if ( $character->class == 11)
                {
                    $character->class = 10;
                }
                $character->realm = $_realmId;
                $character->ilvl = 0;
                $character->created_at = Carbon::now();
                TopItemLevelsController::UpdateCharacter($characterSheet, $character);
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
        if ( !is_null($realmId) ) {
            $realms = self::REALMS;
            if (array_key_exists($realmId, $realms)) {
                $characterName = $_request->get('name');
                $guildName = $_request->get('guildName');
                $api = new Tauri\ApiClient();
                if (strlen($characterName)) {
                    TopItemLevelsController::AddCharacter($api, $characterName,$realmId);
                }
                if ( strlen($guildName) )
                {
                    $roster = $api->getGuildRoster($realms[$realmId], $guildName);
                    if ( $roster && array_key_exists("response", $roster)) {
                        $members = $roster['response']['guildList'];
                        $api = new Tauri\ApiClient();
                        foreach ( $members as $member )
                        {
                            TopItemLevelsController::AddCharacter($api,$member["name"],$realmId);
                        }
                    }
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function show(TopItemLevels $topItemLevels)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function edit(TopItemLevels $topItemLevels)
    {
        //
    }


    public static function getAverageItemLevel(Array $_items)
    {
        $memberItemsCount = count($_items);
        $totalItemLevel = 0;
        $totalItemCount = 0;

        for ( $itemIndex = 0 ; $itemIndex < $memberItemsCount ; ++$itemIndex )
        {
            $itemLevel = $_items[$itemIndex]["ilevel"];
            if ( $itemLevel > 1 )
            {
                $totalItemLevel += $itemLevel;
                $totalItemCount++;
            }
        }
        if ( $totalItemCount > 0 ) {
            return floor($totalItemLevel / $totalItemCount);
        }
        return 0;
    }

    public static function UpdateCharacter($_sheet,$_character)
    {
        if (!$_character->ilvl) {
            if ( $_sheet && array_key_exists("response", $_sheet) ) {
                $averageItemLevel = TopItemLevelsController::getAverageItemLevel($_sheet["response"]["characterItems"]);
                if ($averageItemLevel > $_character->ilvl) {
                    $_character->ilvl = $averageItemLevel;
                    $_character->save();
                }
            }
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
        $characters = TopItemLevels::all();
        $api = new Tauri\ApiClient();
        foreach ( $characters as $character )
        {

            TopItemLevelsController::UpdateCharacter($api->getCharacterSheet(self::REALMS[$character->realm], $character->name), $character);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function destroy(TopItemLevels $topItemLevels)
    {
        //
    }
}
