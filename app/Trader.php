<?php

namespace TauriBay;

use TauriBay\Tauri\CharacterClasses;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Trader extends Model
{

    public static function GetCharacterDataFromTauriAPI($_name, $_realmId)
    {
        $api = new Tauri\ApiClient();
        return $api->getCharacterTooltipData(Realm::REALMS[$_realmId],$_name);
    }

    public static function GetData($_name, $_realm_id)
    {
        $traderData = Trader::where("realm_id","=",$_realm_id)->where("name","=",$_name)->first();
        if ( $traderData )
        {
            return array(
                "faction" => $traderData->faction,
                "class" => $traderData->class
            );
        }
        else
        {
            $apiResponse = self::GetCharacterDataFromTauriAPI($_name, $_realm_id);
            if ( $apiResponse["success"] ) {
                $traderData = $apiResponse["response"];
                $traderData["faction"] =  CharacterClasses::ConvertRaceToFaction($traderData["race"]);
                Trader::insert(array(
                    "name" => $_name,
                    "realm_id" => $_realm_id,
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
                    "realm_id" => $_realm_id,
                    "updated_at" => Carbon::now()
                ));
            }
        }
    }
}