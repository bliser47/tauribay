<?php

namespace TauriBay\Tauri;

class WowInstance
{
    const WOW_INSTANCE_NAMES = array(
        "Throne of Thunder",
        "Terrace of Endless Spring",
        "Heart of Fear",
        "Mogu'shan Vaults",
        'Blacwing descent',
        'Bastion of Twilight',
        'Throne of the Four Winds',
        'Firelands',
        'Dragon Soul',
        "Ismeretlen"
    );

    const WOW_INSTANCE_SHORT_NAMES = array(
        'tot','toes','hof','msv'
    );

    const WOW_INSTANCE_SHORT_NAMES_NICE = array(
        'ToT','ToES','HoF','MSV'
    );

    const WOW_INSTANCE_THRONE_OF_THUNDER = array(
            ' tot ',
            ' totra ',
            ' totre ',
            ' totba ',
            ' totbe ',
            ' tot-ra ',
            ' tot-re ',
            ' tot-ba ',
            ' tot-be ',
            ' throne of thunder ',
        );

    const WOW_INSTANCE_TERRACE_OF_ENDLESS_SPRING = array(
        ' toes ',
        ' toesra ',
        ' toesre ',
        ' toes-ra ',
        ' toes-re ',
        ' toesba ',
        ' toesbe ',
        ' toes-be ',
        ' toes-ba',
        ' terracera ',
        ' terracere ',
        ' terraceba ',
        ' terracebe ',
        ' terrace-ra ',
        ' terrace-re ',
        ' terrace-ba ',
        ' terrace-be ',
        ' terrace of endless spring '
    );

    const WOW_INSTANCE_HEART_OF_FEAR = array(
            ' hof ',
            ' hofra',
            ' hofre ',
            ' hof-ra ',
            ' hof-re ',
            ' hofba ',
            ' hofbe ',
            ' hof-be ',
            ' hof-ba ',
            ' hear of fear '
    );

    const WOW_INSTANCE_MOGU_SHAN_VAULTS = array(
            ' msv ',
            ' msvra',
            ' msvre ',
            ' msv-ra ',
            ' msv-re ',
            ' msvba ',
            ' msvbe ',
            ' msv-be ',
            ' msv-ba ',
            ' mogushan vaults ',
            " mogu'shan vaults "
    );

    const WOW_INSTANCE_BLACKWING_DESCENT = array(
        ' bwd '
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
        self::WOW_INSTANCE_THRONE_OF_THUNDER,
        self::WOW_INSTANCE_TERRACE_OF_ENDLESS_SPRING,
        self::WOW_INSTANCE_HEART_OF_FEAR,
        self::WOW_INSTANCE_MOGU_SHAN_VAULTS,
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
