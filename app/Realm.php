<?php
/**
 * Created by PhpStorm.
 * User: Tamas
 * Date: 1/9/2019
 * Time: 12:30 PM
 */

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class Realm extends Model
{

    const TAURI = 0;
    const WOD = 1;
    const EVERMOON = 2;

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

    const REALMS_SHORTEST = array(
        0 => "T",
        1 => "W",
        2 => "E"
    );

    const REALMS_URL = array(
        0 => "tauri",
        1 => "wod",
        2 => "evermoon"
    );

    const REALMS_URL_KEY = array(
        "tauri" => "Tauri",
        "wod" => "WoD",
        "evermoon" => "Evermoon"
    );

    public static function getShortNameFromURL($_short_name)
    {
        $index = array_search($_short_name, self::REALMS_URL);
        if ( $index >= 0 ) {
            return self::REALMS_SHORT[$index];
        }
    }

    public static function getIdFromShort($_short_name) {
        return array_search($_short_name, self::REALMS_URL);
    }

    public static function getShortestNameFromID($id) {
        return self::REALMS_SHORTEST[$id];
    }

    public static function getShortNameFromID($id) {
        return self::REALMS_SHORT[$id];
    }



    public static function getAllRealmIds() {
        return array(
            self::TAURI,
            self::WOD,
            self::EVERMOON
        );
    }
}