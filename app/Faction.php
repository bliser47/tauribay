<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    const HORDE = 1;
    const ALLIANCE = 2;
    const NEUTRAL = 3;

    public static function getAllFactionIds() {
        return array(
            self::HORDE,
            self::ALLIANCE,
            self::NEUTRAL
        );
    }
}