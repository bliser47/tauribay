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

    const REALMS_URL = array(
        0 => "tauri",
        1 => "wod",
        2 => "evermoon"
    );
}