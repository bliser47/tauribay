<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class GuildProgress extends Model
{
    public static function reCalculateProgressionFromNameAndRealm($_name, $_realmId)
    {
        $guild = Guild::where("name", "=", $_name)->where("realm", "=", $_realmId)->first();
        if ( $guild !== null )
        {
            self::reCalculateProgression($guild,5); // 10 man
            self::reCalculateProgression($guild,6); // 25 man
            return self::getProgression($guild->id);
        }
    }

    public static function reCalculateProgressionForAll()
    {
        $guilds = Guild::all();
        foreach ( $guilds as $guild )
        {
            self::reCalculateProgression($guild,5); // 10 man
            self::reCalculateProgression($guild,6); // 25 man
        }
    }

    public static function getProgression($_guildId, $_mapId = 1098)
    {
        $size10 = GuildProgress::where("guild_id", "=", $_guildId)->where("map_id", "=", $_mapId)->where("difficulty_id", "=", 5)->orderBy("progress")->first();
        $size25 = GuildProgress::where("guild_id", "=", $_guildId)->where("map_id", "=", $_mapId)->where("difficulty_id", "=", 6)->orderBy("progress")->first();
        return array(
            "difficulty" => array(
                5 => array(
                    "progress" => $size10->progress,
                    "clear_time" => $size10->clear_time,
                    "first_kill_log_id" => $size10->first_kill_log_id,
                    "last_kill_log_id" => $size10->last_kill_log_id
                ),
                6 => array(
                    "progress" => $size25->progress,
                    "clear_time" => $size25->clear_time,
                    "first_kill_log_id" => $size25->first_kill_log_id,
                    "last_kill_log_id" => $size25->last_kill_log_id
                )
            )
        );
    }

    public static function calculateClearTime($_guildId, $_difficultyId, $_mapId)
    {
        $mapEncounters = Encounter::MAP_ENCOUNTERS[$_mapId][$_difficultyId];

        $firstEncounterId = $mapEncounters[0];
        $lastEncounterId = $mapEncounters[count($mapEncounters)-1];

        $firstEncounterKills = Encounter::where("guild_id", "=", $_guildId)
                    ->where("map_id", "=", $_mapId)
                    ->where("difficulty_id", "=", $_difficultyId)
                    ->where("encounter_id", "=", $firstEncounterId)->get();

        $lastEncounterKills = Encounter::where("guild_id", "=", $_guildId)
            ->where("map_id", "=", $_mapId)
            ->where("difficulty_id", "=", $_difficultyId)
            ->where("encounter_id", "=", $lastEncounterId)->get();

        $shortestClear = array(
            "time" => -1,
            "first" => 0,
            "last" => 0
        );
        foreach ( $firstEncounterKills as $firstKill ) {
            foreach ( $lastEncounterKills as $lastKill )
            {
                $timeDifference = $lastKill->killtime - $firstKill->killtime;
                if ( $timeDifference > 0 && ($shortestClear["time"] < 0 || $timeDifference < $shortestClear["time"]) )
                {
                    $shortestClear = array(
                        "time" => $timeDifference,
                        "last" => $lastKill->id,
                        "first" => $firstKill->id
                    );
                }
            }
        }
        return $shortestClear;
    }


    public static function reCalculateProgression($_guild, $_difficultyId, $_mapId = 1098)
    {
        $progress = GuildProgress::where("guild_id", "=", $_guild->id)
                ->where("map_id", "=", $_mapId)
                ->where("difficulty_id", "=", $_difficultyId)->first();
        if ( $progress == null  )
        {
            $progress = new GuildProgress;
            $progress->guild_id = $_guild->id;
            $progress->realm_id = $_guild->realm;
            $progress->map_id = $_mapId;
            $progress->difficulty_id = $_difficultyId;
        }
        $progress->progress = Encounter::where("guild_id", "=", $_guild->id)
            ->where("map_id", "=", $_mapId)
            ->where("difficulty_id", "=", $_difficultyId)
            ->distinct("encounter_id")->count("encounter_id");
        $shortestClear = self::calculateClearTime($_guild->id, $_difficultyId, $_mapId);
        $progress->clear_time = $shortestClear["time"];
        $progress->first_kill_log_id = $shortestClear["first"];
        $progress->last_kill_log_id = $shortestClear["last"];
        $progress->save();
    }
}
