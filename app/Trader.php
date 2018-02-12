<?php

namespace App;

use App\Tauri\CharacterClasses;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Trader extends Model
{

    public static function GetCharacterDataFromTauriAPI($_name)
    {
        $api = new Tauri\ApiClient();
        return $api->getCharacterTooltipData('[HU] Tauri WoW Server',$_name);
    }

    public static function GetData($_name)
    {
        $traderData = Trader::where("name",$_name)->first();
        if ( $traderData )
        {
            return array(
                "faction" => $traderData->faction,
                "class" => $traderData->class
            );
        }
        else
        {
            $apiResponse = self::GetCharacterDataFromTauriAPI($_name);
            if ( $apiResponse["success"] ) {
                $traderData = $apiResponse["response"];
                $traderData["faction"] =  CharacterClasses::ConvertRaceToFaction($traderData["race"]);
                Trader::insert(array(
                    "name" => $_name,
                    "faction" => $traderData["faction"],
                    "race" => $traderData["race"],
                    "class" => $traderData["class"],
                    "level" => $traderData["level"],
                    "updated_at" => Carbon::now()
                ));
                return $traderData;
            }
            else
            {
                Trader::insert(array(
                    "name" => $_name,
                    "updated_at" => Carbon::now()
                ));
            }
        }
    }
}