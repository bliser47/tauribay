<?php

namespace TauriBay\Tauri;

class WoWInstanceDifficulty
{
    const WOW_INSTANCE_DIFFICULTY_NAMES = array(
        'Normal',
        'Heroic',
        'Ismeretlen'
    );

    const WOW_INSTANCE_DIFFICULTY_NORMAL = array(
        " 10n ",
        " 10 n ",
        " 25 n ",
        " 25n ",
        " norm ",
        " normal ",
        " normalt ",
        " normalra ",
        " normalre ",
        " normra",
        " norm-ra "
    );

    const WOW_INSTANCE_DIFFICULTY_HEROIC = array(
        " 10hc ",
        " 10 hc ",
        " 25 hc ",
        " 25hc ",
        " hc ",
        " hcra ",
        " hcre ",
        " heroic ",
        " heroicot ",
        " heroict ",
        " heroicra ",
        " heroicre "
    );

    const WOW_INSTANCE_DIFFICULTIES = array(
        self::WOW_INSTANCE_DIFFICULTY_NORMAL,
        self::WOW_INSTANCE_DIFFICULTY_HEROIC
    );
    
    public static function GetInstanceDifficulty($_text) {
        $foundInstanceDifficulties = array();
        foreach ( self::WOW_INSTANCE_DIFFICULTIES as $wow_instance_difficulty_id => $wow_instance_difficulty )
        {
            $pos = SmartParser::TextContainsArrayPart($_text,$wow_instance_difficulty);
            if ( $pos !== false )
            {
                array_push($foundInstanceDifficulties,array(
                    "pos" => $pos,
                    "wow_instance_difficulty_id" => $wow_instance_difficulty_id
                ));
            }
        }
        if ( count($foundInstanceDifficulties) > 0 ) {
            usort($foundInstanceDifficulties, function ($a, $b) {
                return $a["pos"] > $b["pos"];
            });
            return $foundInstanceDifficulties[0]["wow_instance_difficulty_id"];
        }
        return false;
    }
}
