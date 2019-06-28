<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class GuildProgress extends Model
{
    public static function reCalculateProgressionFromNameAndRealm($_name, $_realmId, $_difficultyId)
    {
        $guild = Guild::where("name", "=", $_name)->where("realm", "=", $_realmId)->first();
        if ( $guild !== null )
        {
            self::reCalculateProgression($guild,$_difficultyId);
            return self::getProgression($guild->id, $_difficultyId);
        }
    }

    public static function reCalculateProgressionForAll()
    {
        $guilds = Guild::all();
        foreach ( $guilds as $guild )
        {
            self::reCalculateProgression($guild,3); // 10 man
            self::reCalculateProgression($guild,4); // 10 man
            self::reCalculateProgression($guild,5); // 10 man
            self::reCalculateProgression($guild,6); // 25 man
        }
    }

    public static function reCalculateProgressionForNewGuilds()
    {
        $guilds = Guild::all();
        foreach ( $guilds as $guild )
        {
            if ( !GuildProgress::where("guild_id","=",$guild->id)->first() ) {
                self::reCalculateProgression($guild, 3); // 10 man
                self::reCalculateProgression($guild, 4); // 10 man
                self::reCalculateProgression($guild, 5); // 10 man
                self::reCalculateProgression($guild, 6); // 25 man
            }
        }
    }

    public static function getProgression($_guildId, $_difficultyId, $_mapId = 1098)
    {
        $mapEncounters = Encounter::MAP_ENCOUNTERS[$_mapId][$_difficultyId];

        $data = GuildProgress::where("guild_id", "=", $_guildId)->where("map_id", "=", $_mapId)->where("difficulty_id", "=", $_difficultyId)->orderBy("progress")->first();
        return array(
            "progress" => $data->progress . "/" . count($mapEncounters),
            "clear_time" => $data->clear_time,
            "first_kill" => $data->first_kill_unix
        );
    }

    public static function getFirstKill($_guildId, $_difficultyId, $_mapId)
    {
        $mapEncounters = Encounter::MAP_ENCOUNTERS[$_mapId][$_difficultyId];
        $lastEncounterId = $mapEncounters[count($mapEncounters)-1];

        $oldestLastBossKill = Encounter::where("guild_id", "=", $_guildId)
            ->where("map_id", "=", $_mapId)
            ->where("difficulty_id", "=", $_difficultyId)
            ->where("encounter_id", "=", $lastEncounterId)
            ->orderBy("killtime")
            ->first();

        return $oldestLastBossKill["killtime"];
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
        foreach ( $lastEncounterKills as $lastKill ) {
            foreach ( $firstEncounterKills as $firstKill )
            {
                $timeDifference = $lastKill->killtime - ($firstKill->killtime - ($firstKill->fight_time/1000));
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
            $progress->map_id = $_mapId;
            $progress->difficulty_id = $_difficultyId;
            $progress->first_kill_unix = 0;
            $progress->clear_time = 0;
        }

        $firstKillUnix = self::getFirstKill($_guild->id, $_difficultyId, $_mapId);;
        if ( $firstKillUnix != null ) {
            $progress->first_kill_unix = $firstKillUnix;
            $mapEncounters = Encounter::MAP_ENCOUNTERS[$_mapId][$_difficultyId];
            $progress->progress = count($mapEncounters);
            $shortestClear = self::calculateClearTime($_guild->id, $_difficultyId, $_mapId);
            $progress->clear_time = $shortestClear["time"];
        } else {
            $progress->progress = Encounter::where("guild_id", "=", $_guild->id)
                ->where("map_id", "=", $_mapId)
                ->where("difficulty_id", "=", $_difficultyId)
                ->distinct("encounter_id")->count("encounter_id");
        }

        $progress->save();
    }
}
