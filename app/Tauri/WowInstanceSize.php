<?php

namespace TauriBay\Tauri;

class WowInstanceSize
{

    /*
     * 0 - 10
     * 1 - 25
     */

    const WOW_INSTANCE_SIZE_NAMES = array(
        '10',
        '25',
        '?'
    );

    const WOW_INSTANCE_SIZE_10 = array(
        ' 10 ',

        ' msv10 ',
        ' hof10 ',
        ' toes10 ',
        ' tot10 ',
        ' bwd10 ',
        ' bot10 ',
        ' totfw10 ',
        ' fl10 ',
        ' ds10 ',
        
        ' msv(10) ',
        ' hof(10) ',
        ' toes(10) ',
        ' tot(10) ',
        ' bwd(10) ',
        ' bot(10) ',
        ' totfw(10) ',
        ' fl(10) ',
        ' ds(10) ',

        ' msv10n ',
        ' hof10n ',
        ' toes10n ',
        ' tot10n ',
        ' bwd10n ',
        ' bot10n ',
        ' totfw10n ',
        ' fl10n ',
        ' ds10n ',

        ' msv10hc ',
        ' hof10hc ',
        ' toes10hc ',
        ' tot10hc ',
        ' bwd10hc ',
        ' bot10hc ',
        ' totfw10hc ',
        ' fl10hc ',
        ' ds10hc ',

        ' 10hc ',
        ' 10n ',

        ' 10 hc ',
        ' 10 n ',

        ' 10 n ra ',
        ' 10 n re ',

        ' 10 hc ra ',
        ' 10 hc re ',

        ' 10hc ',
        ' 10n ',

        ' 10 hc ',
        ' 10 n ',

        ' 10heroic ',
        ' 10 heroic ',

        ' 10normal ',
        ' 10 normal ',

        ' 10 ra ',
        ' 10 re ',

        ' 10ra ',
        ' 10re ',
    );

    const WOW_INSTANCE_SIZE_25 = array(
        ' 25 ',
        
        ' msv25 ',
        ' hof25 ',
        ' toes25 ',
        ' tot25 ',
        ' bwd25 ',
        ' bot25 ',
        ' totfw25 ',
        ' fl25 ',
        ' ds25 ',
        
        ' msv(25) ',
        ' hof(25) ',
        ' toes(25) ',
        ' tot(25) ',
        ' bwd(25) ',
        ' bot(25) ',
        ' totfw(25) ',
        ' fl(25) ',
        ' ds(25) ',

        ' msv25n ',
        ' hof25n ',
        ' toes25n ',
        ' tot25n ',
        ' bwd25n ',
        ' bot25n ',
        ' totfw25n ',
        ' fl25n ',
        ' ds25n ',

        ' msv25hc ',
        ' hof25hc ',
        ' toes25hc ',
        ' tot25hc ',
        ' bwd25hc ',
        ' bot25hc ',
        ' totfw25hc ',
        ' fl25hc ',
        ' ds25hc ',

        ' 25hc ',
        ' 25n ',

        ' 25 hc ',
        ' 25 n ',

        ' 25 n ra ',
        ' 25 n re ',

        ' 25 hc ra ',
        ' 25 hc re ',

        ' 25hc ',
        ' 25n ',

        ' 25 hc ',
        ' 25 n ',

        ' 25heroic ',
        ' 25 heroic ',

        ' 25normal ',
        ' 10 normal ',

        ' 25 ra ',
        ' 25 re ',

        ' 25ra ',
        ' 25re ',
    );

    const WOW_INSTANCE_SIZES = array(
        self::WOW_INSTANCE_SIZE_10,
        self::WOW_INSTANCE_SIZE_25
    );

    public static function GetInstanceSize($_text) {
        $foundInstanceSizes = array();
        foreach ( self::WOW_INSTANCE_SIZES as $wow_instance_size_id => $wow_instance_size )
        {
            $pos = SmartParser::TextContainsArrayPart($_text,$wow_instance_size);
            if ( $pos !== false )
            {
                array_push($foundInstanceSizes,array(
                    "pos" => $pos,
                    "wow_instance_size_id" => $wow_instance_size_id
                ));
            }
        }
        if ( count($foundInstanceSizes) > 0 ) {
            usort($foundInstanceSizes, function ($a, $b) {
                return $a["pos"] > $b["pos"];
            });
            return $foundInstanceSizes[0]["wow_instance_size_id"];
        }
        return false;
    }
}
