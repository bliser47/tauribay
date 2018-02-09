<?php

namespace App\Tauri;

use Illuminate\Database\Eloquent\Model;

class WowInstance extends Model
{
    const WOW_INSTANCE_NAMES = array(
        'Blacwing descent',
        'Bastion of Twilight',
        'Throne of the Four Winds',
        'Firelands',
        'Dragon Soul',
        "Ismeretlen"
    );

    const WOW_INSTANCE_SHORT_NAMES = array(
        'bwc','bot','totfw','fl','ds'
    );

    const WOW_INSTANCE_BLACKWING_DESCENT = array(

        ' bwd ',
        ' bwdt ',
        ' bwdre ',
        ' bwdra ',
        ' blackwing descent ',
        ' blackwing descentet ',
        ' blackwing descentre ',
        ' blackwing decentra '

    );

    const WOW_INSTANCE_BASTION_OF_TWILIGHT = array(

        ' bot ',
        ' botre ',
        ' botra ',
        ' bastion ',
        ' bastiont ',
        ' bastionra ',
        ' bastionre ',
        ' bastion of twilightot ',
        ' bastion of twilight ',
        ' bastion of twilightre ',
        ' bastion of twilightra ',
        ' bastion of twillight ',
        ' bastion of twillightre ',
        ' bastion of twillightra ',

    );

    const WOW_INSTANCE_THRONE_OF_THE_FOUR_WINDS = array(

        ' totfw ',
        ' totfwra ',
        ' totfwre ',
        ' thronera ',
        ' thronere ',
        ' fourwinds ',
        ' fourwindsra ',
        ' fourwindsre ',
        ' fourwinds re ',
        ' foundwinds ra ',
        ' four winds ',
        ' four windre ',
        ' four windsra ',
        ' four winds re ',
        ' four winds ra'
    );

    const WOW_INSTANCE_FIRELANDS = array(
        ' fl10 ',
        ' fl25 ',
        ' fl ',
        ' flre ',
        ' flra ',
        ' fireland ',
        ' firelandt ',
        ' firelandre ',
        ' firelandra ',
        ' firelands',
        ' firelandst',
        ' firelandsre ',
        ' firelandsra '

    );

    const WOW_INSTANCE_DRAGON_SOUL = array(
        ' d10 ',
        ' d25 ',
        ' ds ',
        ' dsre ',
        ' dsra ',
        ' dragon soul ',
        ' dragonsoul ',
        ' dragon soulra ',
        ' dragon soulre ',
        ' dragonsoulra ',
        ' dragonsoulre ',
        ' dragon soult',
        ' dragonsoult'

    );

    const WOW_INSTANCES = array(
        self::WOW_INSTANCE_BLACKWING_DESCENT,
        self::WOW_INSTANCE_BASTION_OF_TWILIGHT,
        self::WOW_INSTANCE_THRONE_OF_THE_FOUR_WINDS,
        self::WOW_INSTANCE_FIRELANDS,
        self::WOW_INSTANCE_DRAGON_SOUL
    );

    public static function GetInstance($_text) {
        foreach ( WowInstance::WOW_INSTANCES as $wow_instance_id => $wow_instance )
        {
            $pos = SmartParser::TextContainsArrayPart($_text,$wow_instance);
            if ( $pos !== false )
            {
                return $wow_instance_id;
            }
        }
        return false;
    }
}
