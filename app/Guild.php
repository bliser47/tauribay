<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{

    public static function getOrCreate($_data, $_realmId)
    {
        if ( array_key_exists("name", $_data)) {
            $guildName = $_data["name"];
            if (strlen($guildName) > 0) {
                $guild = Guild::where("name", '=', $guildName)->where('realm', '=', $_realmId)->first();
                if ($guild === null) {
                    $guild = new Guild;
                    $guild->realm = $_realmId;
                    $guild->name = $guildName;
                    $guild->faction = $_data["faction"];
                    $guild->save();
                }
                return $guild;
            }
        }
    }

    public static function getName($id)
    {
        if ( $id !== null ) {
            $guild = Guild::where("id", "=", $id)->first();
            if ($guild !== null) {
                return $guild->name;
            }
        }
    }
}