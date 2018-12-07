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
                return count($progress) . "/" . $_totalProgression;
            }
        }
        return "";
    }

    public static function reCalculateProgression($_guildId)
    {
        GuildProgress::where("guild_id", "=", $_guildId)->delete();
        $guildEncounters = Encounter::where("guild_id", "=", $_guildId)->get();
        foreach ( $guildEncounters as $encounter )
        {
            $bossSkill = GuildProgress::where("guild_id", "=", $_guildId)
                ->where("map_id", "=", $encounter->map_id)
                ->where("difficulty_id", "=", $encounter->encounter_difficulty_id)
                ->where("encounter_id", "=", $encounter->encounter_id);
            if ( $bossSkill == null )
            {
                $bossSkill = new GuildProgress;
                $bossSkill->map_id = $encounter->map_id;
                $bossSkill->encounter_id = $encounter->encounter_id;
                $bossSkill->difficulty_id = $encounter->encounter_difficulty_id;
            }
            ++$bossSkill->killcount;
        }
    }
}
