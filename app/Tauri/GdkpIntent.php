<?php


namespace TauriBay\Tauri;

class GdkpIntent
{
    /*
     * 0 SELL/TRADE
     * 1 SELL
     * 2 BUY
     * 3 TRADE
     */

    const GDKP_INTENT_NAMES = array(
        'Szervezés', 'Csatlakozás'
    );

    const GDKP_INTENT_MAKE = array
    (
        ' invel. ',
        ' invel.',
        ' invelek ',
        ' invelek.',
        ' invelünk ',
        ' invelünk.',
        ' lfm ',
        ' gdkp ',
        ' gdkp-ra ',
        ' gdkp.',
        ' gdkp-ra.',
        ' gdkp-re.',
    );

    const GDKP_INTENT_JOIN = array
    (
        ' gdkp ra menne  ',
        ' gdkpra menne  ',
        ' gdkp-ra menne  ',

        ' gdkp ra mennek  ',
        ' gdkpra mennek  ',
        ' gdkp-ra mennek  ',

        ' gdkp t keres  ',
        ' gdkp-t keres  ',
        ' gdkpt keres  ',

        ' gdkp t keresek  ',
        ' gdkp-t keresek  ',
        ' gdkpt keresek  ',

        ' lf '
    );


    const GDKP_INTENTS = array(
        self::GDKP_INTENT_MAKE,
        self::GDKP_INTENT_JOIN,
    );

    public static function IsGdkpTrade($_text) {
        foreach ( GdkpIntent::GDKP_INTENTS as $gdkp_intent_id => $gdkp_intent )
        {
            $pos = SmartParser::TextContainsArrayPart($_text,$gdkp_intent);
            if ( $pos !== false )
            {
                return $gdkp_intent_id;
            }
        }
        return false;
    }


    public static function GetData($_text) {
        return array(
            "wow_instance_id" => WowInstance::GetInstance($_text),
            'wow_instance_size_id' => WowInstanceSize::GetInstanceSize($_text),
            'wow_instance_difficulty_id' => WoWInstanceDifficulty::GetInstanceDifficulty($_text)
        );
    }


}