<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{

    public static function getOrCreate($_data, $_realmId)
    {
        $guildName = $_data["name"];
        $guild = Guild::where("name",'=',$guildName)->where('realm','=',$_realmId)->first();
        if ( $guild === null )
        {
            $guild = new Guild;
            $guild->realm = $_realmId;
            $guild->name = $guildName;
            $guild->faction = $_data["faction"];
            $guild->save();
        }
        return $guild;
    }
}