<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class GuildProgress extends Model
{
    // Defaults to ToT Heroic
    public static function getProgression($_guildId, $mapId = 1098, $_difficultyId = 5, $_totalProgression = 13)
    {
        if ( $_guildId !== null ) {
            $progress = GuildProgress::where("guild_id", "=", $_guildId)
                ->where("map_id", "=", $mapId)
                ->where("difficulty_id", "=", $_difficultyId)
                ->get();
            if ($progress !== null) {
                return array(
                    "progress" =>$progress->count(),
                    "total" => $_totalProgression
                );
            }
        }
        return array(
            "progress" => 0,
            "total" => $_totalProgression
        );
    }

    public static function reCalculateProgressionFromNameAndRealm($_name, $_realmId)
    {
        $guild = Guild::where("name", "=", $_name)->where("realm", "=", $_realmId)->first();
        if ( $guild !== null )
        {
            self::reCalculateProgression($guild->id);
            return self::getProgression($guild->id);
        }
    }

    public static function reCalculateProgressionForAll()
    {
        $guilds = Guild::all();
        foreach ( $guilds as $guild )
        {
            self::reCalculateProgression($guild->id);
        }
    }


    public static function reCalculateProgression($_guildId)
    {
        GuildProgress::where("guild_id", "=", $_guildId)->delete();
        $guildEncounters = Encounter::where("guild_id", "=", $_guildId)->get();
        foreach ( $guildEncounters as $encounter )
        {
            $progress = GuildProgress::where("guild_id", "=", $_guildId)
                ->where("map_id", "=", $encounter->map_id)
                ->where("difficulty_id", "=", $encounter->difficulty_id)
                ->where("encounter_id", "=", $encounter->encounter_id)->first();
            if ( $progress == null )
            {
                $progress = new GuildProgress;
                $progress->guild_id = $_guildId;
                $progress->map_id = $encounter->map_id;
                $progress->encounter_id = $encounter->encounter_id;
                $progress->difficulty_id = $encounter->difficulty_id;
                $progress->kill_count = 0;
            }
            $progress->kill_count = $progress->kill_count + 1;
            $progress->save();
        }
    }
}
