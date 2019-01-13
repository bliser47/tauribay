<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class LadderCache extends Model
{
    public static function getCache($encounterId, $difficultyId)
    {
        $cache = LadderCache::where("encounter_id", "=", $encounterId)->where("difficulty_id", "=", $difficultyId)->first();
        if ( $cache === null ) {
            $cache = new LadderCache();
            $cache->encounter_id = $encounterId;
            $cache->difficulty_id = $difficultyId;
        }
        return $cache;
    }

    public static function getTopDpsId($encounterId, $difficultyId)
    {
        $cache = self::getCache($encounterId,$difficultyId);
        if ( !$cache->top_dps_encounter_member ) {
            $topDps = EncounterMember::where("encounter", "=", $encounterId)
            ->where("difficulty_id", "=", $difficultyId);

            // Hack for fixing HPS and Durumu DPS
            if ( $encounterId == 1572 )
            {
                $topDps = $topDps->where("killtime",">",0)->where("killtime", "<", 1546950226);
            }

            $topDps->orderBy("dps","desc")->first();
            if ( $topDps !== null ) {
                $cache->top_dps_encounter_member = $topDps->id;
                $cache->save();
            }
        }
        return $cache->top_dps_encounter_member;
    }

    public static function getFastestEncounterId($encounterId, $difficultyId)
    {
        $cache = self::getCache($encounterId,$difficultyId);
        if ( !$cache->fastest_encounter ) {
            $fastestEncounter = Encounter::getFastest($encounterId, $difficultyId);
            if ( $fastestEncounter !== null) {
                $cache->fastest_encounter = $fastestEncounter->id;
                $cache->save();
            }
        }
        return $cache->fastest_encounter;
    }
}