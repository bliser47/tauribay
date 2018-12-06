<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{

    public static function store($_data, $_realmId)
    {
        // Check if the raid doesn't exist yet
        $logId = $_data["log_id"];
        $raid = Encounter::where("log_id",'=',$logId)->first();
        if ( $raid === null )
        {
            $guild = Guild::getOrCreate($_data["guilddata"], $_realmId);
            if ( $guild !== null )
            {
                $encounter = new Encounter;
                $encounter->log_id = $logId;
                $encounter->map_id = $_data["map_id"];
                $encounter->encounter_id = $_data["encounter_id"];
                $encounter->encounter_difficulty_id = $_data["encounter_data"]["encounter_difficulty"];
                $encounter->difficulty_id = $_data["difficulty_id"];
                $encounter->killtime = $_data["killtime"];
                $encounter->wipes = $_data["wipes"];
                $encounter->deaths_total = $_data["deaths_total"];
                $encounter->fight_time = $_data["fight_time"];
                $encounter->deaths_total = $_data["deaths_total"];
                $encounter->deaths_fight = $_data["deaths_fight"];
                $encounter->resurrects_fight = $_data["resurrects_fight"];
                $encounter->member_count = $_data["member_count"];
                $encounter->item_count = $_data["item_count"];
            }
        }
    }
}