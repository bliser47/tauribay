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
            "progress" => array(
                5 => $size10->progress,
                6 => $size25->progress
            )
        );
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
        $progress->save();
    }
}
