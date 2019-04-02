<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class LadderCache extends Model
{
    public static function getCache($encounterId, $difficultyId, $realmId, $factionId)
    {
        $cache = LadderCache::where("encounter_id", "=", $encounterId)->where("difficulty_id", "=", $difficultyId)
            ->where("realm_id","=",$realmId)->where("faction_id","=",$factionId)->first();
        if ( $cache === null ) {
            $cache = new LadderCache();
            $cache->encounter_id = $encounterId;
            $cache->difficulty_id = $difficultyId;
            $cache->realm_id = $realmId;
            $cache->faction_id = $factionId;
        }
        return $cache;
    }


    public static function calculateTopDps($encounterId, $difficultyId, $realms, $factions)
    {
        foreach ( $realms as $realmId )
        {
            foreach ( $factions as $factionId ){
                $cache = self::getCache($encounterId,$difficultyId, $realmId, $factionId);
                if ( !$cache->top_dps_encounter_member ) {
                    $topDps = MemberTop::where("encounter_id", "=", $encounterId)->where("difficulty_id", "=", $difficultyId)
                        ->where("realm_id","=",$realmId)->where("faction_id","=",$factionId);
                    $topDps = $topDps->orderBy("dps","desc")->first();
                    if ( $topDps !== null ) {
                        $cache->top_dps_encounter_member = $topDps->id;
                        $cache->save();
                    }
                }
            }
        }
    }

    public static function getTopDps($encounterId, $difficultyId, $realms, $factions)
    {
        return LadderCache::where("ladder_caches.encounter_id", "=", $encounterId)->where("ladder_caches.difficulty_id", "=", $difficultyId)
            ->whereIn("ladder_caches.realm_id",$realms)->whereIn("ladder_caches.faction_id",$factions)->leftJoin("member_tops","member_tops.id","=","ladder_caches.top_dps_encounter_member")
            ->orderBy("dps","desc")->first();
    }

    public static function getTopHps($encounterId, $difficultyId, $realms, $factions)
    {
        return LadderCache::where("ladder_caches.encounter_id", "=", $encounterId)->where("ladder_caches.difficulty_id", "=", $difficultyId)
            ->whereIn("ladder_caches.realm_id",$realms)->whereIn("ladder_caches.faction_id",$factions)->leftJoin("member_tops","member_tops.id","=","ladder_caches.top_hps_encounter_member")
            ->orderBy("hps","desc")->first();
    }

    public static function calculateFastestEncounter($encounterId, $difficultyId, $realms, $factions) {
        foreach ( $realms as $realmId ) {
            foreach ($factions as $factionId) {
                $cache = self::getCache($encounterId, $difficultyId, $realmId, $factionId);
                if (!$cache->fastest_encounter || $cache->fastest_encounter == 0) {
                    $fastestEncounter = EncounterTop::where("encounter_id","=",$encounterId)->where("difficulty_id","=",$difficultyId)
                        ->where("realm_id","=",$realmId)->where("faction_id","=", $factionId)->orderBy("fastest_encounter_time","asc")->first();
                    if ($fastestEncounter !== null) {
                        $cache->fastest_encounter = $fastestEncounter->fastest_encounter_id;
                        $cache->save();
                    }
                }
            }
        }
    }

    public static function getFastestEncounter($encounterId, $difficultyId, $realms, $factions)
    {
        return LadderCache::where("ladder_caches.encounter_id", "=", $encounterId)->where("ladder_caches.difficulty_id", "=", $difficultyId)
            ->whereIn("ladder_caches.realm_id",$realms)->whereIn("ladder_caches.faction_id",$factions)->
            where("ladder_caches.fastest_encounter","<>",0)->
            leftJoin("encounters","encounters.id","=","ladder_caches.fastest_encounter")->orderBy("encounters.fight_time","asc")->first();
    }
}