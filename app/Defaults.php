<?php
/**
 * Created by PhpStorm.
 * User: Tamas
 * Date: 1/9/2019
 * Time: 12:36 PM
 */

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class Defaults extends Model
{
    const EXPANSION_ID = 4; // MoP
    const MAP_ID  = 1098; // Throne of Thunder
    const DIFFICULTY_ID  = 5; // 10 Heroic
    const ENCOUNTER_SORT = "dps";
    const PLAYER_MODE = "recent";

    const SIZE_AND_DIFFICULTY = array(
        3 => "10 Player",
        4 => "25 Player",
        5 => "10 Player (Heroic)",
        6 => "25 Player (Heroic)"
    );
}