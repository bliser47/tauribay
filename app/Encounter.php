<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;
use TauriBay\Tauri\Skada;
use TauriBay\Tauri\CharacterClasses;
use Illuminate\Support\Facades\Cache;

class Encounter extends Model
{
    const HPS_INVALID_BEFORE_TIMESTAMP = 1546950226;
    const DURUMU_DMG_INVALID_BEFORE_TIMESTAMP = 1546950226;
    const BALANCE_DRUID_DMG_INVALID_BEFORE_TIMESTAMP = 1548201600;

    const DIFFICULTY_NAME = array(
        0 => "N",
        1 => "HC",
        2 => "?"
    );

    const ENCOUNTER_NAME_SHORTS = array(
        "Jin'rokh the Breaker" => "Jin'rokh",
        "Council of Elders" => "Council",
        "Durumu the Forgotten" => "Durumu",
        "Primordius" => "Primo",
        "Dark Animus" => "Animus",
        "Twin Consorts" => "Twins",
        "The Stone Guard" => "Stone Guard",
        "Feng the Accursed" => "Feng",
        "Gara'jal the Spiritbinder" => "Gara'jal",
        "The Spirit Kings" => "Spirit Kings",
        "Will of the Emperor" => "Emperor",
        "Imperial Vizier Zor'lok" => "Zor'lok",
        "Blade Lord Ta'yak" => "Ta'yak",
        "Wind Lord Mel'jarak" => "Mel'jarak",
        "Amber-Shaper Un'sok" => "Un'sok",
        "Grand Empress Shek'zeer" => "Shek'zeer",
        "Protectors of the Endless" => "Protectors",
        "Omnotron Defense System" => "Omnotron",
        "Halfus Wyrmbreaker" => "Halfus",
        "Theralion and Valiona" => "Theralion",
        "Ascendant Council" => "Council",
        "Majordomo Staghelm" => "Majordomo",
        "Conclave of Wind" => "Conclave",
        "Warlord Zon'ozz" => "Zon'ozz",
        "Yor'sahj the Unsleeping" => "Yor'sahj",
        "Warmaster Blackhorn" => "Warmaster",
        "Spine of Deathwing" => "Spine",
        "Madness of Deathwing" => "Deathwing",
        "Instructor Razuvious" => "Razuvious",
        "Gothik the Harvester" => "Gothik",
        "The Four Horsemen" => "Horsemen",
        "Grand Widow Faerlina" => "Faerlina",
        "Noth the Plaguebringer" => "Noth",
        "Heigan the Unclean" => "Heigan",
        "Flame Leviathan" => "Leviathan",
        "Ignis the Furnace Master" => "Ignis",
        "XT-002 Deconstructor" => "XT-002",
        "The Assembly of Iron" => "Assembly",
        "General Vezax" => "Vezax",
        "Algalon the Observer" => "Algalon",
        "Elder Brightleaf" => "E. Brightleaf",
        "Elder Ironbranch" => "E. Ironbranch",
        "Elder Stonebark" => "E. Stonebark",
        "Archavon the Stone Watcher" => "Archavon",
        "Emalon the Storm Watcher" => "Emalon",
        "Koralon the Flame Watcher" => "Koralon",
        "Toravon the Ice Watcher" => "Toravon",
        "Lord Marrowgar" => "Marrowgar",
        "Lady Deathwhisper" => "Deathwhisper",
        "Icecrown Gunship Battle" => "Gunship",
        "Deathbringer Saurfang" => "Saurfang",
        "Professor Putricide" => "Putricide",
        "Blood Council" => "Council",
        "Queen Lana'thel" => "Lana'thel",
        "Valithria Dreamwalker" => "Valithria",
        "The Lich King" => "LK",
        "Northrend Beasts" => "Beasts",
        "Lord Jaraxxus" => "Jaraxxus",
        "Faction Champions" => "Champions",
        "Val'kyr Twins" => "Twins",
        "Baltharus the Warborn" => "Baltharus",
        "Saviana Ragefire" => "Saviana",
        "General Zarithrian" => "Zarithrian",
        "Attumen the Huntsman" => "Attumen",
        "Maiden of the Virtue" => "Maiden",
        "Opera Event" => "Opera E.",
        "Terestian Illhoof" => "Illhoof",
        "Shade of Aran" => "Aran",
        "Chess Event" => "Chess E.",
        "Prince Malchezaar" => "Malchezaar",
        "Rage Winterchill" => "Winterchill",
        "Hydross the Unstable" => "Hydross",
        "The Lurker Below" => "Lurker",
        "Leotheras the Blind" => "Leotheras",
        "Fathom-Lord Karathress" => "Karathress",
        "Morogrim Tidewalker" => "Morogrim",
        "Lady Vashj" => "Vashj",
        "High Astromancer Solarian" => "Solarian",
        "Kael'thas Sunstrider" => "Kael'thas",
        "High Warlord Naj'entus" => "Naj'entus",
        "Shade of Akama" => "Akama",
        "Teron Gorefiend" => "Gorefiend",
        "Gurtogg Bloodboil" => "Gurtogg",
        "Reliquary of Souls" => "Reliquary",
        "Mother Shahraz" => "Mother",
        "The Illidari Council" => "I. Council",
        "Illidan Stormrage" => "Illidan",
        "High King Maulgar" => "Maulgar",
        "Gruul the Dragonkiller" => "Gruul",
        "Eredar Twins" => "E. Twins",
        "Baron Geddon" => "B. Geddon",
        "Sulfuron Harbinger" => "Sulfuron",
        "Golemagg the Incinerator" => "Golemagg",
        "Majordomo Executus" => "Majordomo",
        "Razorgore the Untamed" => "Razorgore",
        "Vaelastrasz the Corrupt" => "Vaelastrasz",
        "Broodlord Lashlayer" => "B. Lashlayer",
        "General Rajaxx" => "Rajaxx",
        "Buru the Gorger" => "Buru",
        "Ayamiss the Hunter" => "Ayamiss",
        "Ossirian the Unscarred" => "Ossirian",
        "The Prophet Skeram" => "Skeram",
        "Silithid Royalty" => "Silithid",
        "Battleguard Sartura" => "Sartura",
        "Fankriss the Unyielding" => "Fankriss",
        "Princess Huhuran" => "Huhuran",
        "Twin Emperors" => "Emperors",
        "Fallen Protectors" => "Protectors",
        "Sha of Pride" => "Sha",
        "Iron Juggernaut" => "Juggernaut",
        "Kor'kron Dark Shaman" => "Dark Shaman",
        "General Nazgrim" => "Nazgrim",
        "Spoils of Pandaria" => "Spoils",
        "Thok the Bloodthirsty" => "Thok",
        "Siegecrafter Blackfuse" => "Blackfuse",
        "Paragons of the Klaxxi" => "Paragons",
        "Garrosh Hellscream" => "Garrosh"
    );


    const ENCOUNTER_IDS = array ( 1084 => array ( 'name' => 'Onyxia', 'map_id' => 249, 'difficulty_id' => 0, ), 663 => array ( 'name' => 'Lucifron', 'map_id' => 409, 'difficulty_id' => 3, ), 664 => array ( 'name' => 'Magmadar', 'map_id' => 409, 'difficulty_id' => 3, ), 665 => array ( 'name' => 'Gehennas', 'map_id' => 409, 'difficulty_id' => 3, ), 666 => array ( 'name' => 'Garr', 'map_id' => 409, 'difficulty_id' => 3, ), 667 => array ( 'name' => 'Shazzrah', 'map_id' => 409, 'difficulty_id' => 3, ), 668 => array ( 'name' => 'Baron Geddon', 'map_id' => 409, 'difficulty_id' => 3, ), 669 => array ( 'name' => 'Sulfuron Harbinger', 'map_id' => 409, 'difficulty_id' => 3, ), 670 => array ( 'name' => 'Golemagg the Incinerator', 'map_id' => 409, 'difficulty_id' => 3, ), 671 => array ( 'name' => 'Majordomo Executus', 'map_id' => 409, 'difficulty_id' => 3, ), 672 => array ( 'name' => 'Ragnaros', 'map_id' => 409, 'difficulty_id' => 3, ), 610 => array ( 'name' => 'Razorgore the Untamed', 'map_id' => 469, 'difficulty_id' => 3, ), 611 => array ( 'name' => 'Vaelastrasz the Corrupt', 'map_id' => 469, 'difficulty_id' => 3, ), 612 => array ( 'name' => 'Broodlord Lashlayer', 'map_id' => 469, 'difficulty_id' => 3, ), 613 => array ( 'name' => 'Firemaw', 'map_id' => 469, 'difficulty_id' => 3, ), 614 => array ( 'name' => 'Ebonroc', 'map_id' => 469, 'difficulty_id' => 3, ), 615 => array ( 'name' => 'Flamegor', 'map_id' => 469, 'difficulty_id' => 3, ), 616 => array ( 'name' => 'Chromaggus', 'map_id' => 469, 'difficulty_id' => 3, ), 617 => array ( 'name' => 'Nefarian', 'map_id' => 469, 'difficulty_id' => 3, ), 718 => array ( 'name' => 'Kurinnaxx', 'map_id' => 509, 'difficulty_id' => 3, ), 719 => array ( 'name' => 'General Rajaxx', 'map_id' => 509, 'difficulty_id' => 3, ), 720 => array ( 'name' => 'Moam', 'map_id' => 509, 'difficulty_id' => 3, ), 721 => array ( 'name' => 'Buru the Gorger', 'map_id' => 509, 'difficulty_id' => 3, ), 722 => array ( 'name' => 'Ayamiss the Hunter', 'map_id' => 509, 'difficulty_id' => 3, ), 723 => array ( 'name' => 'Ossirian the Unscarred', 'map_id' => 509, 'difficulty_id' => 3, ), 709 => array ( 'name' => 'The Prophet Skeram', 'map_id' => 531, 'difficulty_id' => 3, ), 710 => array ( 'name' => 'Silithid Royalty', 'map_id' => 531, 'difficulty_id' => 3, ), 711 => array ( 'name' => 'Battleguard Sartura', 'map_id' => 531, 'difficulty_id' => 3, ), 712 => array ( 'name' => 'Fankriss the Unyielding', 'map_id' => 531, 'difficulty_id' => 3, ), 713 => array ( 'name' => 'Viscidus', 'map_id' => 531, 'difficulty_id' => 3, ), 714 => array ( 'name' => 'Princess Huhuran', 'map_id' => 531, 'difficulty_id' => 3, ), 715 => array ( 'name' => 'Twin Emperors', 'map_id' => 531, 'difficulty_id' => 3, ), 716 => array ( 'name' => 'Ouro', 'map_id' => 531, 'difficulty_id' => 3, ), 717 => array ( 'name' => 'C\'thun', 'map_id' => 531, 'difficulty_id' => 3, ), 652 => array ( 'name' => 'Attumen the Huntsman', 'map_id' => 532, 'difficulty_id' => 3, ), 653 => array ( 'name' => 'Moroes', 'map_id' => 532, 'difficulty_id' => 3, ), 654 => array ( 'name' => 'Maiden of the Virtue', 'map_id' => 532, 'difficulty_id' => 3, ), 655 => array ( 'name' => 'Opera Event', 'map_id' => 532, 'difficulty_id' => 3, ), 656 => array ( 'name' => 'The Curator', 'map_id' => 532, 'difficulty_id' => 3, ), 657 => array ( 'name' => 'Terestian Illhoof', 'map_id' => 532, 'difficulty_id' => 3, ), 658 => array ( 'name' => 'Shade of Aran', 'map_id' => 532, 'difficulty_id' => 3, ), 659 => array ( 'name' => 'Netherspite', 'map_id' => 532, 'difficulty_id' => 3, ), 660 => array ( 'name' => 'Chess Event', 'map_id' => 532, 'difficulty_id' => 3, ), 661 => array ( 'name' => 'Prince Malchezaar', 'map_id' => 532, 'difficulty_id' => 3, ), 662 => array ( 'name' => 'Nightbane', 'map_id' => 532, 'difficulty_id' => 3, ), 618 => array ( 'name' => 'Rage Winterchill', 'map_id' => 534, 'difficulty_id' => 3, ), 619 => array ( 'name' => 'Anetheron', 'map_id' => 534, 'difficulty_id' => 3, ), 620 => array ( 'name' => 'Kaz\'rogal', 'map_id' => 534, 'difficulty_id' => 3, ), 621 => array ( 'name' => 'Azgalor', 'map_id' => 534, 'difficulty_id' => 3, ), 622 => array ( 'name' => 'Archimonde', 'map_id' => 534, 'difficulty_id' => 3, ), 651 => array ( 'name' => 'Magtheridon', 'map_id' => 544, 'difficulty_id' => 3, ), 623 => array ( 'name' => 'Hydross the Unstable', 'map_id' => 548, 'difficulty_id' => 3, ), 624 => array ( 'name' => 'The Lurker Below', 'map_id' => 548, 'difficulty_id' => 3, ), 625 => array ( 'name' => 'Leotheras the Blind', 'map_id' => 548, 'difficulty_id' => 3, ), 626 => array ( 'name' => 'Fathom-Lord Karathress', 'map_id' => 548, 'difficulty_id' => 3, ), 627 => array ( 'name' => 'Morogrim Tidewalker', 'map_id' => 548, 'difficulty_id' => 3, ), 628 => array ( 'name' => 'Lady Vashj', 'map_id' => 548, 'difficulty_id' => 3, ), 730 => array ( 'name' => 'Al\'ar', 'map_id' => 550, 'difficulty_id' => 3, ), 731 => array ( 'name' => 'Void Reaver', 'map_id' => 550, 'difficulty_id' => 3, ), 732 => array ( 'name' => 'High Astromancer Solarian', 'map_id' => 550, 'difficulty_id' => 3, ), 733 => array ( 'name' => 'Kael\'thas Sunstrider', 'map_id' => 550, 'difficulty_id' => 3, ), 601 => array ( 'name' => 'High Warlord Naj\'entus', 'map_id' => 564, 'difficulty_id' => 3, ), 602 => array ( 'name' => 'Supremus', 'map_id' => 564, 'difficulty_id' => 3, ), 603 => array ( 'name' => 'Shade of Akama', 'map_id' => 564, 'difficulty_id' => 3, ), 604 => array ( 'name' => 'Teron Gorefiend', 'map_id' => 564, 'difficulty_id' => 3, ), 605 => array ( 'name' => 'Gurtogg Bloodboil', 'map_id' => 564, 'difficulty_id' => 3, ), 606 => array ( 'name' => 'Reliquary of Souls', 'map_id' => 564, 'difficulty_id' => 3, ), 607 => array ( 'name' => 'Mother Shahraz', 'map_id' => 564, 'difficulty_id' => 3, ), 608 => array ( 'name' => 'The Illidari Council', 'map_id' => 564, 'difficulty_id' => 3, ), 609 => array ( 'name' => 'Illidan Stormrage', 'map_id' => 564, 'difficulty_id' => 3, ), 649 => array ( 'name' => 'High King Maulgar', 'map_id' => 565, 'difficulty_id' => 3, ), 650 => array ( 'name' => 'Gruul the Dragonkiller', 'map_id' => 565, 'difficulty_id' => 3, ), 724 => array ( 'name' => 'Kalecgos', 'map_id' => 580, 'difficulty_id' => 3, ), 725 => array ( 'name' => 'Brutallus', 'map_id' => 580, 'difficulty_id' => 3, ), 726 => array ( 'name' => 'Felmyst', 'map_id' => 580, 'difficulty_id' => 3, ), 727 => array ( 'name' => 'Eredar Twins', 'map_id' => 580, 'difficulty_id' => 3, ), 728 => array ( 'name' => 'M\'uru', 'map_id' => 580, 'difficulty_id' => 3, ), 729 => array ( 'name' => 'Kil\'jaeden', 'map_id' => 580, 'difficulty_id' => 3, ), 1107 => array ( 'name' => 'Anub\'Rekhan', 'map_id' => 533, 'difficulty_id' => 0, ), 1110 => array ( 'name' => 'Grand Widow Faerlina', 'map_id' => 533, 'difficulty_id' => 0, ), 1116 => array ( 'name' => 'Maexxna', 'map_id' => 533, 'difficulty_id' => 0, ), 1117 => array ( 'name' => 'Noth the Plaguebringer', 'map_id' => 533, 'difficulty_id' => 0, ), 1112 => array ( 'name' => 'Heigan the Unclean', 'map_id' => 533, 'difficulty_id' => 0, ), 1115 => array ( 'name' => 'Loatheb', 'map_id' => 533, 'difficulty_id' => 0, ), 1113 => array ( 'name' => 'Instructor Razuvious', 'map_id' => 533, 'difficulty_id' => 0, ), 1109 => array ( 'name' => 'Gothik the Harvester', 'map_id' => 533, 'difficulty_id' => 0, ), 1121 => array ( 'name' => 'The Four Horsemen', 'map_id' => 533, 'difficulty_id' => 0, ), 1118 => array ( 'name' => 'Patchwerk', 'map_id' => 533, 'difficulty_id' => 0, ), 1111 => array ( 'name' => 'Grobbulus', 'map_id' => 533, 'difficulty_id' => 0, ), 1108 => array ( 'name' => 'Gluth', 'map_id' => 533, 'difficulty_id' => 0, ), 1120 => array ( 'name' => 'Thaddius', 'map_id' => 533, 'difficulty_id' => 0, ), 1119 => array ( 'name' => 'Sapphiron', 'map_id' => 533, 'difficulty_id' => 0, ), 1114 => array ( 'name' => 'Kel\'Thuzad', 'map_id' => 533, 'difficulty_id' => 0, ), 1132 => array ( 'name' => 'Flame Leviathan', 'map_id' => 603, 'difficulty_id' => 0, ), 1136 => array ( 'name' => 'Ignis the Furnace Master', 'map_id' => 603, 'difficulty_id' => 0, ), 1139 => array ( 'name' => 'Razorscale', 'map_id' => 603, 'difficulty_id' => 0, ), 1142 => array ( 'name' => 'XT-002 Deconstructor', 'map_id' => 603, 'difficulty_id' => 0, ), 1140 => array ( 'name' => 'The Assembly of Iron', 'map_id' => 603, 'difficulty_id' => 0, ), 1137 => array ( 'name' => 'Kologarn', 'map_id' => 603, 'difficulty_id' => 0, ), 1131 => array ( 'name' => 'Auriaya', 'map_id' => 603, 'difficulty_id' => 0, ), 1135 => array ( 'name' => 'Hodir', 'map_id' => 603, 'difficulty_id' => 0, ), 1141 => array ( 'name' => 'Thorim', 'map_id' => 603, 'difficulty_id' => 0, ), 1164 => array ( 'name' => 'Elder Brightleaf', 'map_id' => 603, 'difficulty_id' => 0, ), 1165 => array ( 'name' => 'Elder Ironbranch', 'map_id' => 603, 'difficulty_id' => 0, ), 1166 => array ( 'name' => 'Elder Stonebark', 'map_id' => 603, 'difficulty_id' => 0, ), 1133 => array ( 'name' => 'Freya', 'map_id' => 603, 'difficulty_id' => 0, ), 1138 => array ( 'name' => 'Mimiron', 'map_id' => 603, 'difficulty_id' => 0, ), 1134 => array ( 'name' => 'General Vezax', 'map_id' => 603, 'difficulty_id' => 0, ), 1143 => array ( 'name' => 'Yogg-Saron', 'map_id' => 603, 'difficulty_id' => 0, ), 1130 => array ( 'name' => 'Algalon the Observer', 'map_id' => 603, 'difficulty_id' => 0, ), 1093 => array ( 'name' => 'Vesperon', 'map_id' => 615, 'difficulty_id' => 0, ), 1092 => array ( 'name' => 'Tenebron', 'map_id' => 615, 'difficulty_id' => 0, ), 1091 => array ( 'name' => 'Shadron', 'map_id' => 615, 'difficulty_id' => 0, ), 1090 => array ( 'name' => 'Sartharion', 'map_id' => 615, 'difficulty_id' => 0, ), 1094 => array ( 'name' => 'Malygos', 'map_id' => 616, 'difficulty_id' => 0, ), 1126 => array ( 'name' => 'Archavon the Stone Watcher', 'map_id' => 624, 'difficulty_id' => 0, ), 1127 => array ( 'name' => 'Emalon the Storm Watcher', 'map_id' => 624, 'difficulty_id' => 0, ), 1128 => array ( 'name' => 'Koralon the Flame Watcher', 'map_id' => 624, 'difficulty_id' => 0, ), 1129 => array ( 'name' => 'Toravon the Ice Watcher', 'map_id' => 624, 'difficulty_id' => 0, ), 1101 => array ( 'name' => 'Lord Marrowgar', 'map_id' => 631, 'difficulty_id' => 0, ), 1100 => array ( 'name' => 'Lady Deathwhisper', 'map_id' => 631, 'difficulty_id' => 0, ), 1099 => array ( 'name' => 'Icecrown Gunship Battle', 'map_id' => 631, 'difficulty_id' => 0, ), 1096 => array ( 'name' => 'Deathbringer Saurfang', 'map_id' => 631, 'difficulty_id' => 0, ), 1104 => array ( 'name' => 'Rotface', 'map_id' => 631, 'difficulty_id' => 0, ), 1097 => array ( 'name' => 'Festergut', 'map_id' => 631, 'difficulty_id' => 0, ), 1102 => array ( 'name' => 'Professor Putricide', 'map_id' => 631, 'difficulty_id' => 0, ), 1095 => array ( 'name' => 'Blood Council', 'map_id' => 631, 'difficulty_id' => 0, ), 1103 => array ( 'name' => 'Queen Lana\'thel', 'map_id' => 631, 'difficulty_id' => 0, ), 1098 => array ( 'name' => 'Valithria Dreamwalker', 'map_id' => 631, 'difficulty_id' => 0, ), 1105 => array ( 'name' => 'Sindragosa', 'map_id' => 631, 'difficulty_id' => 0, ), 1106 => array ( 'name' => 'The Lich King', 'map_id' => 631, 'difficulty_id' => 0, ), 1088 => array ( 'name' => 'Northrend Beasts', 'map_id' => 649, 'difficulty_id' => 0, ), 1087 => array ( 'name' => 'Lord Jaraxxus', 'map_id' => 649, 'difficulty_id' => 0, ), 1086 => array ( 'name' => 'Faction Champions', 'map_id' => 649, 'difficulty_id' => 0, ), 1089 => array ( 'name' => 'Val\'kyr Twins', 'map_id' => 649, 'difficulty_id' => 0, ), 1085 => array ( 'name' => 'Anub\'arak', 'map_id' => 649, 'difficulty_id' => 0, ), 1147 => array ( 'name' => 'Baltharus the Warborn', 'map_id' => 724, 'difficulty_id' => 0, ), 1149 => array ( 'name' => 'Saviana Ragefire', 'map_id' => 724, 'difficulty_id' => 0, ), 1148 => array ( 'name' => 'General Zarithrian', 'map_id' => 724, 'difficulty_id' => 0, ), 1150 => array ( 'name' => 'Halion', 'map_id' => 724, 'difficulty_id' => 0, ), 1027 => array ( 'name' => 'Omnotron Defense System', 'map_id' => 669, 'difficulty_id' => 0, ), 1024 => array ( 'name' => 'Magmaw', 'map_id' => 669, 'difficulty_id' => 0, ), 1022 => array ( 'name' => 'Atramedes', 'map_id' => 669, 'difficulty_id' => 0, ), 1023 => array ( 'name' => 'Chimaeron', 'map_id' => 669, 'difficulty_id' => 0, ), 1025 => array ( 'name' => 'Maloriak', 'map_id' => 669, 'difficulty_id' => 0, ), 1026 => array ( 'name' => 'Nefarian\'s End', 'map_id' => 669, 'difficulty_id' => 0, ), 1030 => array ( 'name' => 'Halfus Wyrmbreaker', 'map_id' => 671, 'difficulty_id' => 0, ), 1032 => array ( 'name' => 'Theralion and Valiona', 'map_id' => 671, 'difficulty_id' => 0, ), 1028 => array ( 'name' => 'Ascendant Council', 'map_id' => 671, 'difficulty_id' => 0, ), 1029 => array ( 'name' => 'Cho\'gall', 'map_id' => 671, 'difficulty_id' => 0, ), 1082 => array ( 'name' => 'Sinestra', 'map_id' => 671, 'difficulty_id' => 5, ), 1083 => array ( 'name' => 'Sinestra', 'map_id' => 671, 'difficulty_id' => 6, ), 1197 => array ( 'name' => 'Beth\'tilac', 'map_id' => 720, 'difficulty_id' => 0, ), 1204 => array ( 'name' => 'Lord Rhyolith', 'map_id' => 720, 'difficulty_id' => 0, ), 1205 => array ( 'name' => 'Shannox', 'map_id' => 720, 'difficulty_id' => 0, ), 1206 => array ( 'name' => 'Alysrazor', 'map_id' => 720, 'difficulty_id' => 0, ), 1200 => array ( 'name' => 'Baleroc', 'map_id' => 720, 'difficulty_id' => 0, ), 1185 => array ( 'name' => 'Majordomo Staghelm', 'map_id' => 720, 'difficulty_id' => 0, ), 1203 => array ( 'name' => 'Ragnaros', 'map_id' => 720, 'difficulty_id' => 0, ), 1035 => array ( 'name' => 'Conclave of Wind', 'map_id' => 754, 'difficulty_id' => 0, ), 1034 => array ( 'name' => 'Al\'Akir', 'map_id' => 754, 'difficulty_id' => 0, ), 1033 => array ( 'name' => 'Argaloth', 'map_id' => 757, 'difficulty_id' => 0, ), 1250 => array ( 'name' => 'Occu\'thar', 'map_id' => 757, 'difficulty_id' => 0, ), 1332 => array ( 'name' => 'Alizabal', 'map_id' => 757, 'difficulty_id' => 0, ), 1292 => array ( 'name' => 'Morchok', 'map_id' => 967, 'difficulty_id' => 0, ), 1294 => array ( 'name' => 'Warlord Zon\'ozz', 'map_id' => 967, 'difficulty_id' => 0, ), 1295 => array ( 'name' => 'Yor\'sahj the Unsleeping', 'map_id' => 967, 'difficulty_id' => 0, ), 1296 => array ( 'name' => 'Hagara', 'map_id' => 967, 'difficulty_id' => 0, ), 1297 => array ( 'name' => 'Ultraxion', 'map_id' => 967, 'difficulty_id' => 0, ), 1298 => array ( 'name' => 'Warmaster Blackhorn', 'map_id' => 967, 'difficulty_id' => 0, ), 1291 => array ( 'name' => 'Spine of Deathwing', 'map_id' => 967, 'difficulty_id' => 0, ), 1299 => array ( 'name' => 'Madness of Deathwing', 'map_id' => 967, 'difficulty_id' => 0, ), 1409 => array ( 'name' => 'Protectors of the Endless', 'map_id' => 996, 'difficulty_id' => 0, ), 1505 => array ( 'name' => 'Tsulong', 'map_id' => 996, 'difficulty_id' => 0, ), 1506 => array ( 'name' => 'Lei Shi', 'map_id' => 996, 'difficulty_id' => 0, ), 1431 => array ( 'name' => 'Sha of Fear', 'map_id' => 996, 'difficulty_id' => 0, ), 1395 => array ( 'name' => 'The Stone Guard', 'map_id' => 1008, 'difficulty_id' => 0, ), 1390 => array ( 'name' => 'Feng the Accursed', 'map_id' => 1008, 'difficulty_id' => 0, ), 1434 => array ( 'name' => 'Gara\'jal the Spiritbinder', 'map_id' => 1008, 'difficulty_id' => 0, ), 1436 => array ( 'name' => 'The Spirit Kings', 'map_id' => 1008, 'difficulty_id' => 0, ), 1500 => array ( 'name' => 'Elegon', 'map_id' => 1008, 'difficulty_id' => 0, ), 1407 => array ( 'name' => 'Will of the Emperor', 'map_id' => 1008, 'difficulty_id' => 0, ), 1507 => array ( 'name' => 'Imperial Vizier Zor\'lok', 'map_id' => 1009, 'difficulty_id' => 0, ), 1504 => array ( 'name' => 'Blade Lord Ta\'yak', 'map_id' => 1009, 'difficulty_id' => 0, ), 1463 => array ( 'name' => 'Garalon', 'map_id' => 1009, 'difficulty_id' => 0, ), 1498 => array ( 'name' => 'Wind Lord Mel\'jarak', 'map_id' => 1009, 'difficulty_id' => 0, ), 1499 => array ( 'name' => 'Amber-Shaper Un\'sok', 'map_id' => 1009, 'difficulty_id' => 0, ), 1501 => array ( 'name' => 'Grand Empress Shek\'zeer', 'map_id' => 1009, 'difficulty_id' => 0, ), 1577 => array ( 'name' => 'Jin\'rokh the Breaker', 'map_id' => 1098, 'difficulty_id' => 0, ), 1575 => array ( 'name' => 'Horridon', 'map_id' => 1098, 'difficulty_id' => 0, ), 1570 => array ( 'name' => 'Council of Elders', 'map_id' => 1098, 'difficulty_id' => 0, ), 1565 => array ( 'name' => 'Tortos', 'map_id' => 1098, 'difficulty_id' => 0, ), 1578 => array ( 'name' => 'Megaera', 'map_id' => 1098, 'difficulty_id' => 0, ), 1573 => array ( 'name' => 'Ji-Kun', 'map_id' => 1098, 'difficulty_id' => 0, ), 1572 => array ( 'name' => 'Durumu the Forgotten', 'map_id' => 1098, 'difficulty_id' => 0, ), 1574 => array ( 'name' => 'Primordius', 'map_id' => 1098, 'difficulty_id' => 0, ), 1576 => array ( 'name' => 'Dark Animus', 'map_id' => 1098, 'difficulty_id' => 0, ), 1559 => array ( 'name' => 'Iron Qon', 'map_id' => 1098, 'difficulty_id' => 0, ), 1560 => array ( 'name' => 'Twin Consorts', 'map_id' => 1098, 'difficulty_id' => 0, ), 1579 => array ( 'name' => 'Lei Shen', 'map_id' => 1098, 'difficulty_id' => 0, ), 1580 => array ( 'name' => 'Ra-den', 'map_id' => 1098, 'difficulty_id' => 5, ), 1581 => array ( 'name' => 'Ra-den', 'map_id' => 1098, 'difficulty_id' => 6, ), 1602 => array ( 'name' => 'Immerseus', 'map_id' => 1136, 'difficulty_id' => 0, ), 1598 => array ( 'name' => 'Fallen Protectors', 'map_id' => 1136, 'difficulty_id' => 0, ), 1624 => array ( 'name' => 'Norushen', 'map_id' => 1136, 'difficulty_id' => 0, ), 1604 => array ( 'name' => 'Sha of Pride', 'map_id' => 1136, 'difficulty_id' => 0, ), 1622 => array ( 'name' => 'Galakras', 'map_id' => 1136, 'difficulty_id' => 0, ), 1600 => array ( 'name' => 'Iron Juggernaut', 'map_id' => 1136, 'difficulty_id' => 0, ), 1606 => array ( 'name' => 'Kor\'kron Dark Shaman', 'map_id' => 1136, 'difficulty_id' => 0, ), 1603 => array ( 'name' => 'General Nazgrim', 'map_id' => 1136, 'difficulty_id' => 0, ), 1595 => array ( 'name' => 'Malkorok', 'map_id' => 1136, 'difficulty_id' => 0, ), 1594 => array ( 'name' => 'Spoils of Pandaria', 'map_id' => 1136, 'difficulty_id' => 0, ), 1599 => array ( 'name' => 'Thok the Bloodthirsty', 'map_id' => 1136, 'difficulty_id' => 0, ), 1601 => array ( 'name' => 'Siegecrafter Blackfuse', 'map_id' => 1136, 'difficulty_id' => 0, ), 1593 => array ( 'name' => 'Paragons of the Klaxxi', 'map_id' => 1136, 'difficulty_id' => 0, ), 1623 => array ( 'name' => 'Garrosh Hellscream', 'map_id' => 1136, 'difficulty_id' => 0, ));


    const MAPS = array(
        1136 => array(
            "name" => "Siege of Orgrimmar",
            "id" => 1136
        ), 1098 => array(
            "name" => "Throne of Thunder",
            "id" => 1098
        ),
        996 => array(
            "name" => "Terrace of Endless Spring",
            "id" => 996
        ),
        1009 => array(
            "name" => "Heart of Fear",
            "id" => 1009
        ),
        1008 => array(
            "name" => "Mogu'shan Vaults",
            "id" => 1008
        )
    );

    const EXPANSIONS = array(
        "Classic",
        "Burning Crusade",
        "Wrath of the Lich King",
        "Cataclysm",
        "Mists of Pandaria"
    );

    const EXPANSIONS_SHORT = array(
        "Classic",
        "BC",
        "WotLK",
        "Cata",
        "MoP"
    );

    const ENCOUNTERS_DEFAULT = array(
        1577 => "Jin'rokh the Breaker",
        1575 => "Horridon",
        1570 => "Council of Elders",
        1565 => "Tortos",
        1578 => "Magaera",
        1573 => "Ji'Kun",
        1574 => "Durumu the Forgotten",
        1572 => "Primordius",
        1576 => "Dark Animus",
        1559 => "Iron Qon",
        1560 => "Twin Consorts",
        1579 => "Lei Shen",
        1580 => "Ra-den"
    );

    const MAP_SHORT = array(
        615 => "OS",
        616 => "EoE",
        624 => "VoA",
        631 => "ICC",
        649 => "TotC",
        724 => "RS",
        669 => "BWD",
        671 => "BoT",
        754 => "TotFW",
        1008 => "MSV",
        1009 => "HoF",
        996 => "ToES",
        1098 => "ToT",
        1136 => "SoO",
        534 => "Hyjal",
        544 => "Mgt",
        548 => "SSC",
        550 => "TK",
        564 => "BT",
        565 => "Gruul",
        532 => "Kara",
        580 => "SWP",
        249 => "Onyxia",
        409 => "MC",
        469 => "BWL",
        509 => "AQ20",
        531 => "AQ40"
    );

    const MAP_SHORTS = array(
        615 => "os",
        616 => "eoe",
        624 => "voa",
        631 => "icc",
        649 => "totc",
        724 => "rs",
        669 => "bwd",
        671 => "bot",
        754 => "totfw",
        1008 => "msv",
        1009 => "hof",
        996 => "toes",
        1098 => "tot",
        1136 => "soo",
        534 => "hyjal",
        544 => "mgt",
        548 => "ssc",
        550 => "tk",
        564 => "bt",
        565 => "gruul",
        532 => "kara",
        580 => "swp",
        249 => "onyxia",
        409 => "mc",
        469 => "bwl",
        509 => "aq20",
        531 => "aq40"
    );

    const EXPANSION_SHORTS = array(
        0 => "classic",
        1 => "tbc",
        2 => "wotlk",
        3 => "cata",
        4 => "mop"
    );

    const SIZE_AND_DIFFICULTY = array(
        3 => "10 Player",
        4 => "25 Player",
        5 => "10 Player (Heroic)",
        6 => "25 Player (Heroic)",
        9 => "40 Player"
    );

    const SIZE_AND_DIFFICULTY_SHORT = array(
        3 => "10N",
        4 => "25N",
        5 => "10HC",
        6 => "25HC",
        9 => "40N"
    );

    const DIFFICULTY_SIZE = array(
        3 => 10,
        4 => 25,
        5 => 10,
        6 => 25,
        9 => 49
    );

    const SIZE_AND_DIFFICULTY_URL = array(
        3 => "10n",
        4 => "25n",
        5 => "10hc",
        6 => "25hc",
        9 => "40n"
    );

    const EXPANSION_RAIDS_COMPLEX = array ( 'map_exp_0' => array ( 1 => array ( 'id' => 249, 'expansion' => 0, 'type' => 2, 'name' => 'Onyxia\'s Lair', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1084, 'encounter_map' => 249, 'encounter_difficulty' => 0, 'encounter_name' => 'Onyxia', 'encounter_order' => 0, 'encounter_index' => 0, ), ), ), 2 => array ( 'id' => 409, 'expansion' => 0, 'type' => 2, 'name' => 'Molten Core', 'available_difficulties' => array ( 0 => array ( 'id' => 9, 'name' => '40 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 663, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Lucifron', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 664, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Magmadar', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 665, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Gehennas', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 666, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Garr', 'encounter_order' => 3000, 'encounter_index' => 3, ), 4 => array ( 'encounter_id' => 667, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Shazzrah', 'encounter_order' => 4000, 'encounter_index' => 4, ), 5 => array ( 'encounter_id' => 668, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Baron Geddon', 'encounter_order' => 5000, 'encounter_index' => 5, ), 6 => array ( 'encounter_id' => 669, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Sulfuron Harbinger', 'encounter_order' => 6000, 'encounter_index' => 6, ), 7 => array ( 'encounter_id' => 670, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Golemagg the Incinerator', 'encounter_order' => 7000, 'encounter_index' => 7, ), 8 => array ( 'encounter_id' => 671, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Majordomo Executus', 'encounter_order' => 8000, 'encounter_index' => 8, ), 9 => array ( 'encounter_id' => 672, 'encounter_map' => 409, 'encounter_difficulty' => 3, 'encounter_name' => 'Ragnaros', 'encounter_order' => 9000, 'encounter_index' => 9, ), ), ), 3 => array ( 'id' => 469, 'expansion' => 0, 'type' => 2, 'name' => 'Blackwing Lair', 'available_difficulties' => array ( 0 => array ( 'id' => 9, 'name' => '40 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 610, 'encounter_map' => 469, 'encounter_difficulty' => 3, 'encounter_name' => 'Razorgore the Untamed', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 611, 'encounter_map' => 469, 'encounter_difficulty' => 3, 'encounter_name' => 'Vaelastrasz the Corrupt', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 612, 'encounter_map' => 469, 'encounter_difficulty' => 3, 'encounter_name' => 'Broodlord Lashlayer', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 613, 'encounter_map' => 469, 'encounter_difficulty' => 3, 'encounter_name' => 'Firemaw', 'encounter_order' => 3000, 'encounter_index' => 3, ), 4 => array ( 'encounter_id' => 614, 'encounter_map' => 469, 'encounter_difficulty' => 3, 'encounter_name' => 'Ebonroc', 'encounter_order' => 4000, 'encounter_index' => 4, ), 5 => array ( 'encounter_id' => 615, 'encounter_map' => 469, 'encounter_difficulty' => 3, 'encounter_name' => 'Flamegor', 'encounter_order' => 5000, 'encounter_index' => 5, ), 6 => array ( 'encounter_id' => 616, 'encounter_map' => 469, 'encounter_difficulty' => 3, 'encounter_name' => 'Chromaggus', 'encounter_order' => 6000, 'encounter_index' => 6, ), 7 => array ( 'encounter_id' => 617, 'encounter_map' => 469, 'encounter_difficulty' => 3, 'encounter_name' => 'Nefarian', 'encounter_order' => 7000, 'encounter_index' => 7, ), ), ), 4 => array ( 'id' => 509, 'expansion' => 0, 'type' => 2, 'name' => 'Ruins of Ahn\'Qiraj', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 718, 'encounter_map' => 509, 'encounter_difficulty' => 3, 'encounter_name' => 'Kurinnaxx', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 719, 'encounter_map' => 509, 'encounter_difficulty' => 3, 'encounter_name' => 'General Rajaxx', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 720, 'encounter_map' => 509, 'encounter_difficulty' => 3, 'encounter_name' => 'Moam', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 721, 'encounter_map' => 509, 'encounter_difficulty' => 3, 'encounter_name' => 'Buru the Gorger', 'encounter_order' => 3000, 'encounter_index' => 3, ), 4 => array ( 'encounter_id' => 722, 'encounter_map' => 509, 'encounter_difficulty' => 3, 'encounter_name' => 'Ayamiss the Hunter', 'encounter_order' => 4000, 'encounter_index' => 4, ), 5 => array ( 'encounter_id' => 723, 'encounter_map' => 509, 'encounter_difficulty' => 3, 'encounter_name' => 'Ossirian the Unscarred', 'encounter_order' => 5000, 'encounter_index' => 5, ), ), ), 5 => array ( 'id' => 531, 'expansion' => 0, 'type' => 2, 'name' => 'Ahn\'Qiraj Temple', 'available_difficulties' => array ( 0 => array ( 'id' => 9, 'name' => '40 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 709, 'encounter_map' => 531, 'encounter_difficulty' => 3, 'encounter_name' => 'The Prophet Skeram', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 710, 'encounter_map' => 531, 'encounter_difficulty' => 3, 'encounter_name' => 'Silithid Royalty', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 711, 'encounter_map' => 531, 'encounter_difficulty' => 3, 'encounter_name' => 'Battleguard Sartura', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 712, 'encounter_map' => 531, 'encounter_difficulty' => 3, 'encounter_name' => 'Fankriss the Unyielding', 'encounter_order' => 3000, 'encounter_index' => 3, ), 4 => array ( 'encounter_id' => 713, 'encounter_map' => 531, 'encounter_difficulty' => 3, 'encounter_name' => 'Viscidus', 'encounter_order' => 4000, 'encounter_index' => 4, ), 5 => array ( 'encounter_id' => 714, 'encounter_map' => 531, 'encounter_difficulty' => 3, 'encounter_name' => 'Princess Huhuran', 'encounter_order' => 5000, 'encounter_index' => 5, ), 6 => array ( 'encounter_id' => 715, 'encounter_map' => 531, 'encounter_difficulty' => 3, 'encounter_name' => 'Twin Emperors', 'encounter_order' => 6000, 'encounter_index' => 6, ), 7 => array ( 'encounter_id' => 716, 'encounter_map' => 531, 'encounter_difficulty' => 3, 'encounter_name' => 'Ouro', 'encounter_order' => 7000, 'encounter_index' => 7, ), 8 => array ( 'encounter_id' => 717, 'encounter_map' => 531, 'encounter_difficulty' => 3, 'encounter_name' => 'C\'thun', 'encounter_order' => 8000, 'encounter_index' => 8, ), ), ), ), 'map_exp_1' => array ( 0 => array ( 'id' => 532, 'expansion' => 1, 'type' => 2, 'name' => 'Karazhan', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 652, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Attumen the Huntsman', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 653, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Moroes', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 654, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Maiden of the Virtue', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 655, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Opera Event', 'encounter_order' => 3000, 'encounter_index' => 3, ), 4 => array ( 'encounter_id' => 656, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'The Curator', 'encounter_order' => 4000, 'encounter_index' => 4, ), 5 => array ( 'encounter_id' => 657, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Terestian Illhoof', 'encounter_order' => 5000, 'encounter_index' => 5, ), 6 => array ( 'encounter_id' => 658, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Shade of Aran', 'encounter_order' => 6000, 'encounter_index' => 6, ), 7 => array ( 'encounter_id' => 659, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Netherspite', 'encounter_order' => 7000, 'encounter_index' => 7, ), 8 => array ( 'encounter_id' => 660, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Chess Event', 'encounter_order' => 8000, 'encounter_index' => 8, ), 9 => array ( 'encounter_id' => 661, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Prince Malchezaar', 'encounter_order' => 9000, 'encounter_index' => 9, ), 10 => array ( 'encounter_id' => 662, 'encounter_map' => 532, 'encounter_difficulty' => 3, 'encounter_name' => 'Nightbane', 'encounter_order' => 10000, 'encounter_index' => 10, ), ), ), 1 => array ( 'id' => 534, 'expansion' => 1, 'type' => 2, 'name' => 'The Battle for Mount Hyjal', 'available_difficulties' => array ( 0 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 618, 'encounter_map' => 534, 'encounter_difficulty' => 3, 'encounter_name' => 'Rage Winterchill', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 619, 'encounter_map' => 534, 'encounter_difficulty' => 3, 'encounter_name' => 'Anetheron', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 620, 'encounter_map' => 534, 'encounter_difficulty' => 3, 'encounter_name' => 'Kaz\'rogal', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 621, 'encounter_map' => 534, 'encounter_difficulty' => 3, 'encounter_name' => 'Azgalor', 'encounter_order' => 3000, 'encounter_index' => 3, ), 4 => array ( 'encounter_id' => 622, 'encounter_map' => 534, 'encounter_difficulty' => 3, 'encounter_name' => 'Archimonde', 'encounter_order' => 4000, 'encounter_index' => 4, ), ), ), 2 => array ( 'id' => 544, 'expansion' => 1, 'type' => 2, 'name' => 'Magtheridon\'s Lair', 'available_difficulties' => array ( 0 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 651, 'encounter_map' => 544, 'encounter_difficulty' => 3, 'encounter_name' => 'Magtheridon', 'encounter_order' => 0, 'encounter_index' => 0, ), ), ), 3 => array ( 'id' => 548, 'expansion' => 1, 'type' => 2, 'name' => 'Coilfang: Serpentshrine Cavern', 'available_difficulties' => array ( 0 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 623, 'encounter_map' => 548, 'encounter_difficulty' => 3, 'encounter_name' => 'Hydross the Unstable', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 624, 'encounter_map' => 548, 'encounter_difficulty' => 3, 'encounter_name' => 'The Lurker Below', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 625, 'encounter_map' => 548, 'encounter_difficulty' => 3, 'encounter_name' => 'Leotheras the Blind', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 626, 'encounter_map' => 548, 'encounter_difficulty' => 3, 'encounter_name' => 'Fathom-Lord Karathress', 'encounter_order' => 3000, 'encounter_index' => 3, ), 4 => array ( 'encounter_id' => 627, 'encounter_map' => 548, 'encounter_difficulty' => 3, 'encounter_name' => 'Morogrim Tidewalker', 'encounter_order' => 4000, 'encounter_index' => 4, ), 5 => array ( 'encounter_id' => 628, 'encounter_map' => 548, 'encounter_difficulty' => 3, 'encounter_name' => 'Lady Vashj', 'encounter_order' => 5000, 'encounter_index' => 5, ), ), ), 4 => array ( 'id' => 550, 'expansion' => 1, 'type' => 2, 'name' => 'Tempest Keep', 'available_difficulties' => array ( 0 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 730, 'encounter_map' => 550, 'encounter_difficulty' => 3, 'encounter_name' => 'Al\'ar', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 731, 'encounter_map' => 550, 'encounter_difficulty' => 3, 'encounter_name' => 'Void Reaver', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 732, 'encounter_map' => 550, 'encounter_difficulty' => 3, 'encounter_name' => 'High Astromancer Solarian', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 733, 'encounter_map' => 550, 'encounter_difficulty' => 3, 'encounter_name' => 'Kael\'thas Sunstrider', 'encounter_order' => 3000, 'encounter_index' => 3, ), ), ), 5 => array ( 'id' => 564, 'expansion' => 1, 'type' => 2, 'name' => 'Black Temple', 'available_difficulties' => array ( 0 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 601, 'encounter_map' => 564, 'encounter_difficulty' => 3, 'encounter_name' => 'High Warlord Naj\'entus', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 602, 'encounter_map' => 564, 'encounter_difficulty' => 3, 'encounter_name' => 'Supremus', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 603, 'encounter_map' => 564, 'encounter_difficulty' => 3, 'encounter_name' => 'Shade of Akama', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 604, 'encounter_map' => 564, 'encounter_difficulty' => 3, 'encounter_name' => 'Teron Gorefiend', 'encounter_order' => 3000, 'encounter_index' => 3, ), 4 => array ( 'encounter_id' => 605, 'encounter_map' => 564, 'encounter_difficulty' => 3, 'encounter_name' => 'Gurtogg Bloodboil', 'encounter_order' => 4000, 'encounter_index' => 4, ), 5 => array ( 'encounter_id' => 606, 'encounter_map' => 564, 'encounter_difficulty' => 3, 'encounter_name' => 'Reliquary of Souls', 'encounter_order' => 5000, 'encounter_index' => 5, ), 6 => array ( 'encounter_id' => 607, 'encounter_map' => 564, 'encounter_difficulty' => 3, 'encounter_name' => 'Mother Shahraz', 'encounter_order' => 6000, 'encounter_index' => 6, ), 7 => array ( 'encounter_id' => 608, 'encounter_map' => 564, 'encounter_difficulty' => 3, 'encounter_name' => 'The Illidari Council', 'encounter_order' => 7000, 'encounter_index' => 7, ), 8 => array ( 'encounter_id' => 609, 'encounter_map' => 564, 'encounter_difficulty' => 3, 'encounter_name' => 'Illidan Stormrage', 'encounter_order' => 8000, 'encounter_index' => 8, ), ), ), 6 => array ( 'id' => 565, 'expansion' => 1, 'type' => 2, 'name' => 'Gruul\'s Lair', 'available_difficulties' => array ( 0 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 649, 'encounter_map' => 565, 'encounter_difficulty' => 3, 'encounter_name' => 'High King Maulgar', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 650, 'encounter_map' => 565, 'encounter_difficulty' => 3, 'encounter_name' => 'Gruul the Dragonkiller', 'encounter_order' => 1000, 'encounter_index' => 1, ), ), ), 7 => array ( 'id' => 580, 'expansion' => 1, 'type' => 2, 'name' => 'The Sunwell', 'available_difficulties' => array ( 0 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 724, 'encounter_map' => 580, 'encounter_difficulty' => 3, 'encounter_name' => 'Kalecgos', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 725, 'encounter_map' => 580, 'encounter_difficulty' => 3, 'encounter_name' => 'Brutallus', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 726, 'encounter_map' => 580, 'encounter_difficulty' => 3, 'encounter_name' => 'Felmyst', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 727, 'encounter_map' => 580, 'encounter_difficulty' => 3, 'encounter_name' => 'Eredar Twins', 'encounter_order' => 3000, 'encounter_index' => 3, ), 4 => array ( 'encounter_id' => 728, 'encounter_map' => 580, 'encounter_difficulty' => 3, 'encounter_name' => 'M\'uru', 'encounter_order' => 4000, 'encounter_index' => 4, ), 5 => array ( 'encounter_id' => 729, 'encounter_map' => 580, 'encounter_difficulty' => 3, 'encounter_name' => 'Kil\'jaeden', 'encounter_order' => 5000, 'encounter_index' => 5, ), ), ), ), 'map_exp_2' => array ( 0 => array ( 'id' => 533, 'expansion' => 2, 'type' => 2, 'name' => 'Naxxramas', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1107, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Anub\'Rekhan', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 1110, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Grand Widow Faerlina', 'encounter_order' => 250, 'encounter_index' => 3, ), 2 => array ( 'encounter_id' => 1116, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Maexxna', 'encounter_order' => 312, 'encounter_index' => 9, ), 3 => array ( 'encounter_id' => 1117, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Noth the Plaguebringer', 'encounter_order' => 343, 'encounter_index' => 10, ), 4 => array ( 'encounter_id' => 1112, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Heigan the Unclean', 'encounter_order' => 375, 'encounter_index' => 5, ), 5 => array ( 'encounter_id' => 1115, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Loatheb', 'encounter_order' => 406, 'encounter_index' => 8, ), 6 => array ( 'encounter_id' => 1113, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Instructor Razuvious', 'encounter_order' => 437, 'encounter_index' => 6, ), 7 => array ( 'encounter_id' => 1109, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Gothik the Harvester', 'encounter_order' => 500, 'encounter_index' => 2, ), 8 => array ( 'encounter_id' => 1121, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'The Four Horsemen', 'encounter_order' => 562, 'encounter_index' => 14, ), 9 => array ( 'encounter_id' => 1118, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Patchwerk', 'encounter_order' => 625, 'encounter_index' => 11, ), 10 => array ( 'encounter_id' => 1111, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Grobbulus', 'encounter_order' => 750, 'encounter_index' => 4, ), 11 => array ( 'encounter_id' => 1108, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Gluth', 'encounter_order' => 1000, 'encounter_index' => 1, ), 12 => array ( 'encounter_id' => 1120, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Thaddius', 'encounter_order' => 1250, 'encounter_index' => 13, ), 13 => array ( 'encounter_id' => 1119, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Sapphiron', 'encounter_order' => 1500, 'encounter_index' => 12, ), 14 => array ( 'encounter_id' => 1114, 'encounter_map' => 533, 'encounter_difficulty' => 0, 'encounter_name' => 'Kel\'Thuzad', 'encounter_order' => 2000, 'encounter_index' => 7, ), ), ), 1 => array ( 'id' => 603, 'expansion' => 2, 'type' => 2, 'name' => 'Ulduar', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1132, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Flame Leviathan', 'encounter_order' => -2000, 'encounter_index' => 2, ), 1 => array ( 'encounter_id' => 1136, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Ignis the Furnace Master', 'encounter_order' => -1500, 'encounter_index' => 6, ), 2 => array ( 'encounter_id' => 1139, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Razorscale', 'encounter_order' => -1375, 'encounter_index' => 9, ), 3 => array ( 'encounter_id' => 1142, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'XT-002 Deconstructor', 'encounter_order' => -1343, 'encounter_index' => 12, ), 4 => array ( 'encounter_id' => 1140, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'The Assembly of Iron', 'encounter_order' => -1312, 'encounter_index' => 10, ), 5 => array ( 'encounter_id' => 1137, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Kologarn', 'encounter_order' => -1250, 'encounter_index' => 7, ), 6 => array ( 'encounter_id' => 1131, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Auriaya', 'encounter_order' => -1000, 'encounter_index' => 1, ), 7 => array ( 'encounter_id' => 1135, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Hodir', 'encounter_order' => -750, 'encounter_index' => 5, ), 8 => array ( 'encounter_id' => 1141, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Thorim', 'encounter_order' => -625, 'encounter_index' => 11, ), 9 => array ( 'encounter_id' => 1164, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Elder Brightleaf', 'encounter_order' => -562, 'encounter_index' => 14, ), 10 => array ( 'encounter_id' => 1165, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Elder Ironbranch', 'encounter_order' => -531, 'encounter_index' => 15, ), 11 => array ( 'encounter_id' => 1166, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Elder Stonebark', 'encounter_order' => -515, 'encounter_index' => 16, ), 12 => array ( 'encounter_id' => 1133, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Freya', 'encounter_order' => -500, 'encounter_index' => 3, ), 13 => array ( 'encounter_id' => 1138, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Mimiron', 'encounter_order' => -375, 'encounter_index' => 8, ), 14 => array ( 'encounter_id' => 1134, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'General Vezax', 'encounter_order' => -250, 'encounter_index' => 4, ), 15 => array ( 'encounter_id' => 1143, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Yogg-Saron', 'encounter_order' => -125, 'encounter_index' => 13, ), 16 => array ( 'encounter_id' => 1130, 'encounter_map' => 603, 'encounter_difficulty' => 0, 'encounter_name' => 'Algalon the Observer', 'encounter_order' => 0, 'encounter_index' => 0, ), ), ), 2 => array ( 'id' => 615, 'expansion' => 2, 'type' => 2, 'name' => 'The Obsidian Sanctum', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1093, 'encounter_map' => 615, 'encounter_difficulty' => 0, 'encounter_name' => 'Vesperon', 'encounter_order' => 0, 'encounter_index' => 3, ), 1 => array ( 'encounter_id' => 1092, 'encounter_map' => 615, 'encounter_difficulty' => 0, 'encounter_name' => 'Tenebron', 'encounter_order' => 2000, 'encounter_index' => 2, ), 2 => array ( 'encounter_id' => 1091, 'encounter_map' => 615, 'encounter_difficulty' => 0, 'encounter_name' => 'Shadron', 'encounter_order' => 3000, 'encounter_index' => 1, ), 3 => array ( 'encounter_id' => 1090, 'encounter_map' => 615, 'encounter_difficulty' => 0, 'encounter_name' => 'Sartharion', 'encounter_order' => 4000, 'encounter_index' => 0, ), ), ), 3 => array ( 'id' => 616, 'expansion' => 2, 'type' => 2, 'name' => 'The Eye of Eternity', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1094, 'encounter_map' => 616, 'encounter_difficulty' => 0, 'encounter_name' => 'Malygos', 'encounter_order' => 0, 'encounter_index' => 0, ), ), ), 4 => array ( 'id' => 624, 'expansion' => 2, 'type' => 2, 'name' => 'Vault of Archavon', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1126, 'encounter_map' => 624, 'encounter_difficulty' => 0, 'encounter_name' => 'Archavon the Stone Watcher', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 1127, 'encounter_map' => 624, 'encounter_difficulty' => 0, 'encounter_name' => 'Emalon the Storm Watcher', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 1128, 'encounter_map' => 624, 'encounter_difficulty' => 0, 'encounter_name' => 'Koralon the Flame Watcher', 'encounter_order' => 2000, 'encounter_index' => 2, ), 3 => array ( 'encounter_id' => 1129, 'encounter_map' => 624, 'encounter_difficulty' => 0, 'encounter_name' => 'Toravon the Ice Watcher', 'encounter_order' => 3000, 'encounter_index' => 3, ), ), ), 5 => array ( 'id' => 631, 'expansion' => 2, 'type' => 2, 'name' => 'Icecrown Citadel', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1101, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Lord Marrowgar', 'encounter_order' => 0, 'encounter_index' => 6, ), 1 => array ( 'encounter_id' => 1100, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Lady Deathwhisper', 'encounter_order' => 1000, 'encounter_index' => 5, ), 2 => array ( 'encounter_id' => 1099, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Icecrown Gunship Battle', 'encounter_order' => 2000, 'encounter_index' => 4, ), 3 => array ( 'encounter_id' => 1096, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Deathbringer Saurfang', 'encounter_order' => 3000, 'encounter_index' => 1, ), 4 => array ( 'encounter_id' => 1104, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Rotface', 'encounter_order' => 4000, 'encounter_index' => 9, ), 5 => array ( 'encounter_id' => 1097, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Festergut', 'encounter_order' => 5000, 'encounter_index' => 2, ), 6 => array ( 'encounter_id' => 1102, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Professor Putricide', 'encounter_order' => 6000, 'encounter_index' => 7, ), 7 => array ( 'encounter_id' => 1095, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Blood Council', 'encounter_order' => 7000, 'encounter_index' => 0, ), 8 => array ( 'encounter_id' => 1103, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Queen Lana\'thel', 'encounter_order' => 9000, 'encounter_index' => 8, ), 9 => array ( 'encounter_id' => 1098, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Valithria Dreamwalker', 'encounter_order' => 10000, 'encounter_index' => 3, ), 10 => array ( 'encounter_id' => 1105, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'Sindragosa', 'encounter_order' => 12000, 'encounter_index' => 10, ), 11 => array ( 'encounter_id' => 1106, 'encounter_map' => 631, 'encounter_difficulty' => 0, 'encounter_name' => 'The Lich King', 'encounter_order' => 14000, 'encounter_index' => 11, ), ), ), 6 => array ( 'id' => 649, 'expansion' => 2, 'type' => 2, 'name' => 'Trial of the Crusader', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1088, 'encounter_map' => 649, 'encounter_difficulty' => 0, 'encounter_name' => 'Northrend Beasts', 'encounter_order' => 0, 'encounter_index' => 3, ), 1 => array ( 'encounter_id' => 1087, 'encounter_map' => 649, 'encounter_difficulty' => 0, 'encounter_name' => 'Lord Jaraxxus', 'encounter_order' => 2000, 'encounter_index' => 2, ), 2 => array ( 'encounter_id' => 1086, 'encounter_map' => 649, 'encounter_difficulty' => 0, 'encounter_name' => 'Faction Champions', 'encounter_order' => 3000, 'encounter_index' => 1, ), 3 => array ( 'encounter_id' => 1089, 'encounter_map' => 649, 'encounter_difficulty' => 0, 'encounter_name' => 'Val\'kyr Twins', 'encounter_order' => 4000, 'encounter_index' => 4, ), 4 => array ( 'encounter_id' => 1085, 'encounter_map' => 649, 'encounter_difficulty' => 0, 'encounter_name' => 'Anub\'arak', 'encounter_order' => 5000, 'encounter_index' => 0, ), ), ), 7 => array ( 'id' => 724, 'expansion' => 2, 'type' => 2, 'name' => 'The Ruby Sanctum', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1147, 'encounter_map' => 724, 'encounter_difficulty' => 0, 'encounter_name' => 'Baltharus the Warborn', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 1149, 'encounter_map' => 724, 'encounter_difficulty' => 0, 'encounter_name' => 'Saviana Ragefire', 'encounter_order' => 500, 'encounter_index' => 2, ), 2 => array ( 'encounter_id' => 1148, 'encounter_map' => 724, 'encounter_difficulty' => 0, 'encounter_name' => 'General Zarithrian', 'encounter_order' => 1000, 'encounter_index' => 1, ), 3 => array ( 'encounter_id' => 1150, 'encounter_map' => 724, 'encounter_difficulty' => 0, 'encounter_name' => 'Halion', 'encounter_order' => 2000, 'encounter_index' => 3, ), ), ), ), 'map_exp_3' => array ( 0 => array ( 'id' => 669, 'expansion' => 3, 'type' => 2, 'name' => 'Blackwing Descent', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1027, 'encounter_map' => 669, 'encounter_difficulty' => 0, 'encounter_name' => 'Omnotron Defense System', 'encounter_order' => -1000, 'encounter_index' => 5, ), 1 => array ( 'encounter_id' => 1024, 'encounter_map' => 669, 'encounter_difficulty' => 0, 'encounter_name' => 'Magmaw', 'encounter_order' => -500, 'encounter_index' => 2, ), 2 => array ( 'encounter_id' => 1022, 'encounter_map' => 669, 'encounter_difficulty' => 0, 'encounter_name' => 'Atramedes', 'encounter_order' => 0, 'encounter_index' => 0, ), 3 => array ( 'encounter_id' => 1023, 'encounter_map' => 669, 'encounter_difficulty' => 0, 'encounter_name' => 'Chimaeron', 'encounter_order' => 1000, 'encounter_index' => 1, ), 4 => array ( 'encounter_id' => 1025, 'encounter_map' => 669, 'encounter_difficulty' => 0, 'encounter_name' => 'Maloriak', 'encounter_order' => 3000, 'encounter_index' => 3, ), 5 => array ( 'encounter_id' => 1026, 'encounter_map' => 669, 'encounter_difficulty' => 0, 'encounter_name' => 'Nefarian\'s End', 'encounter_order' => 4000, 'encounter_index' => 4, ), ), ), 1 => array ( 'id' => 671, 'expansion' => 3, 'type' => 2, 'name' => 'The Bastion of Twilight', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1030, 'encounter_map' => 671, 'encounter_difficulty' => 0, 'encounter_name' => 'Halfus Wyrmbreaker', 'encounter_order' => -1000, 'encounter_index' => 2, ), 1 => array ( 'encounter_id' => 1032, 'encounter_map' => 671, 'encounter_difficulty' => 0, 'encounter_name' => 'Theralion and Valiona', 'encounter_order' => 1500, 'encounter_index' => 4, ), 2 => array ( 'encounter_id' => 1028, 'encounter_map' => 671, 'encounter_difficulty' => 0, 'encounter_name' => 'Ascendant Council', 'encounter_order' => 2000, 'encounter_index' => 0, ), 3 => array ( 'encounter_id' => 1029, 'encounter_map' => 671, 'encounter_difficulty' => 0, 'encounter_name' => 'Cho\'gall', 'encounter_order' => 2500, 'encounter_index' => 1, ), 4 => array ( 'encounter_id' => 1082, 'encounter_map' => 671, 'encounter_difficulty' => 5, 'encounter_name' => 'Sinestra', 'encounter_order' => 3500, 'encounter_index' => 3, ), 5 => array ( 'encounter_id' => 1083, 'encounter_map' => 671, 'encounter_difficulty' => 6, 'encounter_name' => 'Sinestra', 'encounter_order' => 3500, 'encounter_index' => 3, ), ), ), 2 => array ( 'id' => 720, 'expansion' => 3, 'type' => 2, 'name' => 'Firelands', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1197, 'encounter_map' => 720, 'encounter_difficulty' => 0, 'encounter_name' => 'Beth\'tilac', 'encounter_order' => -4000, 'encounter_index' => 1, ), 1 => array ( 'encounter_id' => 1204, 'encounter_map' => 720, 'encounter_difficulty' => 0, 'encounter_name' => 'Lord Rhyolith', 'encounter_order' => -3000, 'encounter_index' => 4, ), 2 => array ( 'encounter_id' => 1205, 'encounter_map' => 720, 'encounter_difficulty' => 0, 'encounter_name' => 'Shannox', 'encounter_order' => -2375, 'encounter_index' => 5, ), 3 => array ( 'encounter_id' => 1206, 'encounter_map' => 720, 'encounter_difficulty' => 0, 'encounter_name' => 'Alysrazor', 'encounter_order' => -1750, 'encounter_index' => 6, ), 4 => array ( 'encounter_id' => 1200, 'encounter_map' => 720, 'encounter_difficulty' => 0, 'encounter_name' => 'Baleroc', 'encounter_order' => -1000, 'encounter_index' => 2, ), 5 => array ( 'encounter_id' => 1185, 'encounter_map' => 720, 'encounter_difficulty' => 0, 'encounter_name' => 'Majordomo Staghelm', 'encounter_order' => 0, 'encounter_index' => 0, ), 6 => array ( 'encounter_id' => 1203, 'encounter_map' => 720, 'encounter_difficulty' => 0, 'encounter_name' => 'Ragnaros', 'encounter_order' => 2000, 'encounter_index' => 3, ), ), ), 3 => array ( 'id' => 754, 'expansion' => 3, 'type' => 2, 'name' => 'Throne of the Four Winds', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1035, 'encounter_map' => 754, 'encounter_difficulty' => 0, 'encounter_name' => 'Conclave of Wind', 'encounter_order' => -1000, 'encounter_index' => 1, ), 1 => array ( 'encounter_id' => 1034, 'encounter_map' => 754, 'encounter_difficulty' => 0, 'encounter_name' => 'Al\'Akir', 'encounter_order' => 0, 'encounter_index' => 0, ), ), ), 4 => array ( 'id' => 757, 'expansion' => 3, 'type' => 2, 'name' => 'Baradin Hold', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1033, 'encounter_map' => 757, 'encounter_difficulty' => 0, 'encounter_name' => 'Argaloth', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 1250, 'encounter_map' => 757, 'encounter_difficulty' => 0, 'encounter_name' => 'Occu\'thar', 'encounter_order' => 1000, 'encounter_index' => 1, ), 2 => array ( 'encounter_id' => 1332, 'encounter_map' => 757, 'encounter_difficulty' => 0, 'encounter_name' => 'Alizabal', 'encounter_order' => 2000, 'encounter_index' => 2, ), ), ), 5 => array ( 'id' => 967, 'expansion' => 3, 'type' => 2, 'name' => 'Dragon Soul', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), 4 => array ( 'id' => 7, 'name' => 'Looking For Raid', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1292, 'encounter_map' => 967, 'encounter_difficulty' => 0, 'encounter_name' => 'Morchok', 'encounter_order' => -1000, 'encounter_index' => 1, ), 1 => array ( 'encounter_id' => 1294, 'encounter_map' => 967, 'encounter_difficulty' => 0, 'encounter_name' => 'Warlord Zon\'ozz', 'encounter_order' => -500, 'encounter_index' => 2, ), 2 => array ( 'encounter_id' => 1295, 'encounter_map' => 967, 'encounter_difficulty' => 0, 'encounter_name' => 'Yor\'sahj the Unsleeping', 'encounter_order' => -250, 'encounter_index' => 3, ), 3 => array ( 'encounter_id' => 1296, 'encounter_map' => 967, 'encounter_difficulty' => 0, 'encounter_name' => 'Hagara', 'encounter_order' => -125, 'encounter_index' => 4, ), 4 => array ( 'encounter_id' => 1297, 'encounter_map' => 967, 'encounter_difficulty' => 0, 'encounter_name' => 'Ultraxion', 'encounter_order' => -62, 'encounter_index' => 5, ), 5 => array ( 'encounter_id' => 1298, 'encounter_map' => 967, 'encounter_difficulty' => 0, 'encounter_name' => 'Warmaster Blackhorn', 'encounter_order' => -31, 'encounter_index' => 6, ), 6 => array ( 'encounter_id' => 1291, 'encounter_map' => 967, 'encounter_difficulty' => 0, 'encounter_name' => 'Spine of Deathwing', 'encounter_order' => 0, 'encounter_index' => 0, ), 7 => array ( 'encounter_id' => 1299, 'encounter_map' => 967, 'encounter_difficulty' => 0, 'encounter_name' => 'Madness of Deathwing', 'encounter_order' => 1000, 'encounter_index' => 7, ), ), ), ), 'map_exp_4' => array ( 0 => array ( 'id' => 996, 'expansion' => 4, 'type' => 2, 'name' => 'Terrace of Endless Spring', 'available_difficulties' => array ( 0 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 1 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), 2 => array ( 'id' => 3, 'name' => '10 Player', ), 3 => array ( 'id' => 4, 'name' => '25 Player', ), 4 => array ( 'id' => 7, 'name' => 'Looking For Raid', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1409, 'encounter_map' => 996, 'encounter_difficulty' => 0, 'encounter_name' => 'Protectors of the Endless', 'encounter_order' => 0, 'encounter_index' => 0, ), 1 => array ( 'encounter_id' => 1505, 'encounter_map' => 996, 'encounter_difficulty' => 0, 'encounter_name' => 'Tsulong', 'encounter_order' => 500, 'encounter_index' => 2, ), 2 => array ( 'encounter_id' => 1506, 'encounter_map' => 996, 'encounter_difficulty' => 0, 'encounter_name' => 'Lei Shi', 'encounter_order' => 750, 'encounter_index' => 3, ), 3 => array ( 'encounter_id' => 1431, 'encounter_map' => 996, 'encounter_difficulty' => 0, 'encounter_name' => 'Sha of Fear', 'encounter_order' => 1000, 'encounter_index' => 1, ), ), ), 1 => array ( 'id' => 1008, 'expansion' => 4, 'type' => 2, 'name' => 'Mogu\'shan Vaults', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), 4 => array ( 'id' => 7, 'name' => 'Looking For Raid', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1395, 'encounter_map' => 1008, 'encounter_difficulty' => 0, 'encounter_name' => 'The Stone Guard', 'encounter_order' => -1000, 'encounter_index' => 1, ), 1 => array ( 'encounter_id' => 1390, 'encounter_map' => 1008, 'encounter_difficulty' => 0, 'encounter_name' => 'Feng the Accursed', 'encounter_order' => 0, 'encounter_index' => 0, ), 2 => array ( 'encounter_id' => 1434, 'encounter_map' => 1008, 'encounter_difficulty' => 0, 'encounter_name' => 'Gara\'jal the Spiritbinder', 'encounter_order' => 500, 'encounter_index' => 3, ), 3 => array ( 'encounter_id' => 1436, 'encounter_map' => 1008, 'encounter_difficulty' => 0, 'encounter_name' => 'The Spirit Kings', 'encounter_order' => 2000, 'encounter_index' => 4, ), 4 => array ( 'encounter_id' => 1500, 'encounter_map' => 1008, 'encounter_difficulty' => 0, 'encounter_name' => 'Elegon', 'encounter_order' => 2500, 'encounter_index' => 5, ), 5 => array ( 'encounter_id' => 1407, 'encounter_map' => 1008, 'encounter_difficulty' => 0, 'encounter_name' => 'Will of the Emperor', 'encounter_order' => 3000, 'encounter_index' => 2, ), ), ), 2 => array ( 'id' => 1009, 'expansion' => 4, 'type' => 2, 'name' => 'Heart of Fear', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), 4 => array ( 'id' => 7, 'name' => 'Looking For Raid', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1507, 'encounter_map' => 1009, 'encounter_difficulty' => 0, 'encounter_name' => 'Imperial Vizier Zor\'lok', 'encounter_order' => -2500, 'encounter_index' => 6, ), 1 => array ( 'encounter_id' => 1504, 'encounter_map' => 1009, 'encounter_difficulty' => 0, 'encounter_name' => 'Blade Lord Ta\'yak', 'encounter_order' => -1500, 'encounter_index' => 5, ), 2 => array ( 'encounter_id' => 1463, 'encounter_map' => 1009, 'encounter_difficulty' => 0, 'encounter_name' => 'Garalon', 'encounter_order' => -1250, 'encounter_index' => 0, ), 3 => array ( 'encounter_id' => 1498, 'encounter_map' => 1009, 'encounter_difficulty' => 0, 'encounter_name' => 'Wind Lord Mel\'jarak', 'encounter_order' => -1000, 'encounter_index' => 1, ), 4 => array ( 'encounter_id' => 1499, 'encounter_map' => 1009, 'encounter_difficulty' => 0, 'encounter_name' => 'Amber-Shaper Un\'sok', 'encounter_order' => 1000, 'encounter_index' => 2, ), 5 => array ( 'encounter_id' => 1501, 'encounter_map' => 1009, 'encounter_difficulty' => 0, 'encounter_name' => 'Grand Empress Shek\'zeer', 'encounter_order' => 2000, 'encounter_index' => 3, ), ), ), 3 => array ( 'id' => 1098, 'expansion' => 4, 'type' => 2, 'name' => 'Throne of Thunder', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), 4 => array ( 'id' => 7, 'name' => 'Looking For Raid', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1577, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Jin\'rokh the Breaker', 'encounter_order' => -3000, 'encounter_index' => 9, ), 1 => array ( 'encounter_id' => 1575, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Horridon', 'encounter_order' => -2000, 'encounter_index' => 7, ), 2 => array ( 'encounter_id' => 1570, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Council of Elders', 'encounter_order' => -1500, 'encounter_index' => 3, ), 3 => array ( 'encounter_id' => 1565, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Tortos', 'encounter_order' => -1000, 'encounter_index' => 2, ), 4 => array ( 'encounter_id' => 1578, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Megaera', 'encounter_order' => -750, 'encounter_index' => 10, ), 5 => array ( 'encounter_id' => 1573, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Ji-Kun', 'encounter_order' => -500, 'encounter_index' => 5, ), 6 => array ( 'encounter_id' => 1572, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Durumu the Forgotten', 'encounter_order' => -250, 'encounter_index' => 4, ), 7 => array ( 'encounter_id' => 1574, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Primordius', 'encounter_order' => -125, 'encounter_index' => 6, ), 8 => array ( 'encounter_id' => 1576, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Dark Animus', 'encounter_order' => -62, 'encounter_index' => 8, ), 9 => array ( 'encounter_id' => 1559, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Iron Qon', 'encounter_order' => 0, 'encounter_index' => 0, ), 10 => array ( 'encounter_id' => 1560, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Twin Consorts', 'encounter_order' => 1000, 'encounter_index' => 1, ), 11 => array ( 'encounter_id' => 1579, 'encounter_map' => 1098, 'encounter_difficulty' => 0, 'encounter_name' => 'Lei Shen', 'encounter_order' => 2000, 'encounter_index' => 11, ), 12 => array ( 'encounter_id' => 1580, 'encounter_map' => 1098, 'encounter_difficulty' => 5, 'encounter_name' => 'Ra-den', 'encounter_order' => 3000, 'encounter_index' => 12, ), 13 => array ( 'encounter_id' => 1581, 'encounter_map' => 1098, 'encounter_difficulty' => 6, 'encounter_name' => 'Ra-den', 'encounter_order' => 3000, 'encounter_index' => 12, ), ), ), 4 => array ( 'id' => 1136, 'expansion' => 4, 'type' => 2, 'name' => 'Siege of Orgrimmar', 'available_difficulties' => array ( 0 => array ( 'id' => 3, 'name' => '10 Player', ), 1 => array ( 'id' => 4, 'name' => '25 Player', ), 2 => array ( 'id' => 5, 'name' => '10 Player (Heroic)', ), 3 => array ( 'id' => 6, 'name' => '25 Player (Heroic)', ), 4 => array ( 'id' => 7, 'name' => 'Looking For Raid', ), 5 => array ( 'id' => 14, 'name' => 'Flexible', ), ), 'encounters' => array ( 0 => array ( 'encounter_id' => 1602, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Immerseus', 'encounter_order' => -3000, 'encounter_index' => 7, ), 1 => array ( 'encounter_id' => 1598, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Fallen Protectors', 'encounter_order' => -2000, 'encounter_index' => 3, ), 2 => array ( 'encounter_id' => 1624, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Norushen', 'encounter_order' => -1875, 'encounter_index' => 14, ), 3 => array ( 'encounter_id' => 1604, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Sha of Pride', 'encounter_order' => -1750, 'encounter_index' => 9, ), 4 => array ( 'encounter_id' => 1622, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Galakras', 'encounter_order' => -1625, 'encounter_index' => 12, ), 5 => array ( 'encounter_id' => 1600, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Iron Juggernaut', 'encounter_order' => -1500, 'encounter_index' => 5, ), 6 => array ( 'encounter_id' => 1606, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Kor\'kron Dark Shaman', 'encounter_order' => -1375, 'encounter_index' => 11, ), 7 => array ( 'encounter_id' => 1603, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'General Nazgrim', 'encounter_order' => -1250, 'encounter_index' => 8, ), 8 => array ( 'encounter_id' => 1595, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Malkorok', 'encounter_order' => 1000, 'encounter_index' => 2, ), 9 => array ( 'encounter_id' => 1594, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Spoils of Pandaria', 'encounter_order' => 1500, 'encounter_index' => 1, ), 10 => array ( 'encounter_id' => 1599, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Thok the Bloodthirsty', 'encounter_order' => 2000, 'encounter_index' => 4, ), 11 => array ( 'encounter_id' => 1601, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Siegecrafter Blackfuse', 'encounter_order' => 3000, 'encounter_index' => 6, ), 12 => array ( 'encounter_id' => 1593, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Paragons of the Klaxxi', 'encounter_order' => 3500, 'encounter_index' => 0, ), 13 => array ( 'encounter_id' => 1623, 'encounter_map' => 1136, 'encounter_difficulty' => 0, 'encounter_name' => 'Garrosh Hellscream', 'encounter_order' => 3750, 'encounter_index' => 13, ) ) ) ) );

    const EXPANSION_RAIDS = array(
        array(),
        array(),
        array(),
        array(

        ),
        array(
            1008 => "Mogu'shan Vaults",
            1009 => "Heart of Fear",
            996 => "Terrace of Endless Spring",
            1098 => "Throne of Thunder"
        )
    );

    const MAP_ENCOUNTERS = array(
        1098 => array(
            3 => array(
                1577,
                1575,
                1570,
                1565,
                1578,
                1573,
                1574,
                1572,
                1576,
                1559,
                1560,
                1579
            ),
            4 => array(
                1577,
                1575,
                1570,
                1565,
                1578,
                1573,
                1574,
                1572,
                1576,
                1559,
                1560,
                1579
            ),
            5 => array(
                1577,
                1575,
                1570,
                1565,
                1578,
                1573,
                1574,
                1572,
                1576,
                1559,
                1560,
                1579,
                1580,
            ),
            6 => array(
                1577,
                1575,
                1570,
                1565,
                1578,
                1573,
                1574,
                1572,
                1576,
                1559,
                1560,
                1579,
                1581,
            )
        )
    );

    public static function getNameShort($id)
    {
        $name = self::getName($id);
        return array_key_exists($name, self::ENCOUNTER_NAME_SHORTS) ? self::ENCOUNTER_NAME_SHORTS[$name] : $name;
    }

    public static function getUrlName($name, $isShort = false)
    {
        if ( !$isShort )
        {
            $name = self::getNameShort($name);
        }
        return self::shorten($name);
    }

    public static function getName($id)
    {
        if ( array_key_exists($id, self::ENCOUNTER_IDS))
        {
            return self::ENCOUNTER_IDS[$id]["name"];
        }
        return "";
    }

    public static function store($api, $_data, $_realmId)
    {
        $result = array(
            "result" => false
        );
        $guild = Guild::getOrCreate($_data["guilddata"], $_realmId);
        $encounter = new Encounter;
        $encounter->log_id = $_data["log_id"];
        $encounter->realm_id = $_realmId;
        $encounter->map_id = $_data["map_id"];
        if ($guild !== null) {
            $encounter->guild_id = $guild->id;
        }
        $encounter->encounter_id = $_data["encounter_id"];
        $encounter->encounter_difficulty_id = $_data["encounter_data"]["encounter_difficulty"];
        $encounter->difficulty_id = $_data["difficulty"];
        $encounter->killtime = $_data["killtime"];
        $encounter->wipes = $_data["wipes"];
        $encounter->deaths_total = $_data["deahts_total"];
        $encounter->fight_time = $_data["fight_time"];
        $encounter->deaths_fight = $_data["deaths_fight"];
        $encounter->resurrects_fight = $_data["resurrects_fight"];
        $encounter->member_count = $_data["member_count"];
        $encounter->item_count = $_data["item_count"];
        $encounter->save();

        self::updateEncounterMembers($_data, $encounter, $guild, $api);
        self::refreshEncounterTop($encounter, $guild);

        if ( array_key_exists("items", $_data) ) {
            Loot::processItems($encounter, $_data["items"], $api);
        }

        $result["result"] = true;
        return $result;
    }

    public static function updateEncounterMembers($_data, $encounter, $guild, $api)
    {
        EncounterMember::where("encounter_id", "=", $encounter->id)->delete();
        $members = $_data["members"];
        $i = 0;
        $fightTime = $encounter->fight_time / 1000;
        foreach ( $members as $memberData )
        {
            $member = new EncounterMember;
            $member->encounter_id = $encounter->id;
            $member->encounter = $encounter->encounter_id;
            $member->difficulty_id = $encounter->difficulty_id;
            $member->fight_time = $encounter->fight_time;
            $member->killtime = $encounter->killtime;
            $member->realm_id = $encounter->realm_id;
            $member->name = $memberData["name"];
            $member->race = $memberData["race"];
            $member->class = $memberData["class"];
            $member->spec = $memberData["spec"];
            $member->damage_done = $memberData["dmg_done"];
            $member->dps = $memberData["dmg_done"] / max($fightTime,1);
            $member->damage_taken = $memberData["dmg_taken"];
            $member->damage_absorb = $memberData["dmg_absorb"];
            $member->heal_done = $memberData["heal_done"];
            $member->absorb_done = $memberData["absorb_done"];
            $member->overheal = $memberData["overheal"];
            $member->hps = ($memberData["heal_done"] + $memberData["absorb_done"]) / max($fightTime,1);
            $member->heal_taken = $memberData["heal_taken"];
            $member->interrupts = $memberData["interrupts"];
            $member->dispells = $memberData["dispells"];
            $member->ilvl = $memberData["ilvl"];
            $member->guid = $memberData["guid"];
            if ( $guild != null && $guild->faction != null )
            {
                $member->faction_id = $guild->faction;
            }
            else
            {
                $member->faction_id = -1;
            }
            $member->save();

            $character = self::logCharacter($member, $api);
            self::calculateScores($member);
            self::refreshMemberTop($member, $guild, $character);

            $member->top_processed = 1;
            $member->save();

            if ( $i == 0 ) {
                $encounter->faction_id = $member->faction_id;
                $encounter->save();
            }

            ++$i;
        }
        if ( $encounter->member_count == $i )
        {
            $encounter->members_processed = true;
            $encounter->save();
        }
    }

    public static function logCharacter($member, $_api)
    {
        $character = Characters::where("realm","=",$member->realm_id)->where("name","=",$member->name)->where("guid","=",$member->guid)->first();
        if ( $character == null )
        {
            $character = new Characters;
            $character->name = $member->name;
            $character->ilvl = $member->ilvl;
            $character->faction = $member->faction;
            $character->class = $member->class;
            $character->realm = $member->realm_id;
            $character->guid = $member->guid;

            $characterSheet = $_api->getCharacterSheet(Realm::REALMS[$member->realm_id], $member->name);
            if ($characterSheet && array_key_exists("response", $characterSheet)) {
                $characterSheetResponse = $characterSheet["response"];
                $character->achievement_points = $characterSheetResponse["pts"];
                $character->faction = CharacterClasses::ConvertRaceToFaction($characterSheetResponse["race"]);
                $member->faction_id = $character->faction;
            } else {
                $character->faction = $member->faction_id;
            }

            $character->save();
            Characters::addEncounter($character, $member);
        }
        else {
            $member->faction_id = $character->faction;
            Characters::addEncounter($character, $member);
        }
        return $character;
    }

    public static function calculateScores($member)
    {
        $topDpsMember = MemberTop::where("encounter_id","=",$member->encounter)
            ->where("difficulty_id","=",$member->difficulty_id)
            ->where("spec","=",$member->spec)->orderBy("dps","desc")->first();

        $topDps = $topDpsMember !== null ? $topDpsMember->dps : 0;
        if ( $topDps > 0 && $member->dps < $topDps ) {
            $member->dps_score = intval(($member->dps * 100) / $topDps);
        }
        else {
            $member->dps_score = 100;
        }


        $topHpsMember = MemberTop::where("encounter_id","=",$member->encounter)
            ->where("difficulty_id","=",$member->difficulty_id)
            ->where("spec","=",$member->spec)->orderBy("hps","desc")->first();

        $topHps = $topHpsMember !== null ? $topHpsMember->hps : 0;
        if ( $topHps > 0 && $member->hps < $topHps ) {
            $member->hps_score = intval(($member->hps * 100) / $topHps);
        }
        else {
            $member->hps_score = 100;
        }

        $member->save();
    }

    public static function isInvalid($encounter_id) {
        $isInvalid = EncountersReported::where("status","=",1)->where("encounter_id","=",$encounter_id)->first();
        return $isInvalid != null;
    }

    public static function refreshEncounterTop($encounter, $guild)
    {
        if ( !self::isInvalid($encounter->id) ) {
            $guildId = $guild !== null ? $guild->id : 0;
            $top = EncounterTop::where("encounter_id", $encounter->encounter_id)
                ->where("difficulty_id", $encounter->difficulty_id)->where("guild_id", $guildId)->where("realm_id","=",$encounter->realm_id)->where("faction_id","=",$encounter->faction_id)->first();


            if ($top !== null) {
                if ($top->fastest_encounter_time > $encounter->fight_time) {
                    $top->fastest_encounter_id = $encounter->id;
                    $top->fastest_encounter_time = $encounter->fight_time;
                    $top->fastest_encounter_date = $encounter->killtime;
                    $top->faction_id = $encounter->faction_id;
                    self::refreshLadderSpeedKill($encounter);
                }
            } else {

                $top = new EncounterTop();
                $top->realm_id = $encounter->realm_id;
                $top->encounter_id = $encounter->encounter_id;
                $top->difficulty_id = $encounter->difficulty_id;
                $top->guild_id = $guildId;
                $top->fastest_encounter_id = $encounter->id;
                $top->fastest_encounter_time = $encounter->fight_time;
                $top->fastest_encounter_date = $encounter->killtime;
                $top->faction_id = $encounter->faction_id;
                self::refreshLadderSpeedKill($encounter);
            }

            $top->save();
        }

        $encounter->top_processed = 1;
        $encounter->save();
    }

    public static function refreshMemberTop($member, $guild, $character)
    {
        if ( !self::isInvalid($member->encounter_id) ) {
            $top = MemberTop::where("name", $member->name)->where("realm_id", $member->realm_id)->where("encounter_id", $member->encounter)
                ->where("difficulty_id", $member->difficulty_id)->where("spec", $member->spec)->first();

            $checkDps = false;
            $checkHps = false;
            if ($top !== null) {
                if ($member->encounter != 1572 || $member->killtime > Encounter::DURUMU_DMG_INVALID_BEFORE_TIMESTAMP) {
                    if ($member->spec != EncounterMember::SPEC_DRUID_BALALANCE || $member->killtime > Encounter::BALANCE_DRUID_DMG_INVALID_BEFORE_TIMESTAMP) {
                        if ($top->dps < $member->dps) {
                            $top->dps = $member->dps;
                            $top->dps_encounter_id = $member->encounter_id;
                            $top->dps_encounter_fight_time = $member->fight_time;
                            $top->dps_encounter_killtime = $member->killtime;
                            $top->dps_ilvl = $member->ilvl;
                            if ($guild !== null) {
                                $top->dps_guild_id = $guild->id;
                            }
                            $checkDps = true;
                        }
                    }
                }
                if ($member->killtime > Encounter::HPS_INVALID_BEFORE_TIMESTAMP) {
                    if ($top->hps < $member->hps) {
                        $top->hps = $member->hps;
                        $top->hps_encounter_id = $member->encounter_id;
                        $top->hps_encounter_fight_time = $member->fight_time;
                        $top->hps_encounter_killtime = $member->killtime;
                        $top->hps_ilvl = $member->ilvl;
                        if ($guild !== null) {
                            $top->hps_guild_id = $guild->id;
                        }
                        $checkHps = true;
                    }
                }
                if ( $character && ($checkDps || $checkHps) ) {
                    $top->character_id = $character->id;
                    $top->guid = $character->guid;
                }
                $top->faction_id = $member->faction_id;
            } else {
                $top = new MemberTop();
                $top->name = $member->name;
                $top->class = $member->class;
                $top->spec = $member->spec;
                $top->realm_id = $member->realm_id;
                $top->difficulty_id = $member->difficulty_id;
                $top->encounter_id = $member->encounter;
                $top->faction_id = $member->faction_id;
                if ( $character ) {
                    $top->character_id = $character->id;
                    $top->guid = $character->guid;
                }
                if ($member->encounter != 1572 || $member->killtime > Encounter::DURUMU_DMG_INVALID_BEFORE_TIMESTAMP) {

                    if ($member->spec != EncounterMember::SPEC_DRUID_BALALANCE || $member->killtime > Encounter::BALANCE_DRUID_DMG_INVALID_BEFORE_TIMESTAMP) {
                        $top->dps = $member->dps;
                        $top->dps_encounter_id = $member->encounter_id;
                        $top->dps_encounter_fight_time = $member->fight_time;
                        $top->dps_encounter_killtime = $member->killtime;
                        $top->dps_ilvl = $member->ilvl;
                        $checkDps = true;
                    }
                }
                if ($member->killtime > Encounter::HPS_INVALID_BEFORE_TIMESTAMP) {
                    $top->hps = $member->hps;
                    $top->hps_encounter_id = $member->encounter_id;
                    $top->hps_encounter_fight_time = $member->fight_time;
                    $top->hps_encounter_killtime = $member->killtime;
                    $top->hps_ilvl = $member->ilvl;
                    $checkHps = true;
                }
                if ($guild !== null) {
                    $top->dps_guild_id = $guild->id;
                    $top->hps_guild_id = $guild->id;
                }
            }
            $top->save();

            if ($checkDps) {
                self::refreshLadderDps($top);
            }
            if ($checkHps) {
                self::refreshLadderHps($top);
            }
        }
    }

    public static function refreshLadderSpeedKill($encounter) {
        $cache = LadderCache::getCache($encounter->encounter_id, $encounter->difficulty_id, $encounter->realm_id, $encounter->faction_id);
        $fastest = $cache->fastest_encounter > 0 ? Encounter::where("id", "=", $cache->fastest_encounter)->first() : null;
        if (!$fastest || $fastest->fight_time > $encounter->fight_time ) {
            $cache->fastest_encounter = $encounter->id;
            $cache->save();
        }
    }

    public static function refreshLadderDps($memberTop) {
        $cache = LadderCache::getCache($memberTop->encounter_id, $memberTop->difficulty_id, $memberTop->realm_id, $memberTop->faction_id);
        $topDps = $cache->top_dps_encounter_member > 0 ? MemberTop::where("id", "=", $cache->top_dps_encounter_member)->first() : null;
        if (!$topDps || $topDps->dps < $memberTop->dps) {
            $cache->top_dps_encounter_member = $memberTop->id;
            $cache->save();
        }
    }

    public static function refreshLadderHps($memberTop) {
        $cache = LadderCache::getCache($memberTop->encounter_id, $memberTop->difficulty_id, $memberTop->realm_id, $memberTop->faction_id);
        $topHps = $cache->top_hps_encounter_member > 0 ? MemberTop::where("id", "=", $cache->top_hps_encounter_member)->first() : null;
        if (!$topHps || $topHps->hps < $memberTop->hps) {
            $cache->top_hps_encounter_member = $memberTop->id;
            $cache->save();
        }
    }

    public static function getFastest($encounterId, $difficultyId, $realms, $factions)
    {
        return LadderCache::getFastestEncounter($encounterId,$difficultyId, $realms, $factions);
    }

    public static function getSpecTopDps($encounterId, $difficultyId, $specId) {
        $topDps = MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("spec","=",$specId)
            ->orderBy("dps","desc")->first();
        if ( $topDps ) {
            return $topDps->dps;
        }
        return 0;
    }

    public static function getSpecTopDpsData($encounterId, $difficultyId, $specId) {
        return MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("spec","=",$specId)
            ->orderBy("dps","desc")->first();
    }

    public static function getClassTopDpsButNotTank($encounterId, $difficultyId, $classId) {
        $topDps = MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("class","=",$classId)->whereNotIn("spec",EncounterMember::SPEC_IS_TANK)
            ->orderBy("dps","desc")->first();
        if ( $topDps ) {
            return $topDps->dps;
        }
        return 0;
    }

    public static function getClassTopDpsButNotTankData($encounterId, $difficultyId, $classId) {
        return MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("class","=",$classId)->whereNotIn("spec",EncounterMember::SPEC_IS_TANK)
            ->orderBy("dps","desc")->first();
    }

    public static function getClassTopDps($encounterId, $difficultyId, $classId) {
        $topDps = MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("class","=",$classId)
            ->orderBy("dps","desc")->first();
        if ( $topDps ) {
            return $topDps->dps;
        }
        return 0;
    }

    public static function getClassTopDpsData($encounterId, $difficultyId, $classId) {
        return MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("class","=",$classId)
            ->orderBy("dps","desc")->first();
    }

    public static function getSpecTopHps($encounterId, $difficultyId, $specId) {
        $topHps = MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("spec","=",$specId)
            ->orderBy("hps","desc")->first();
        if ( $topHps ) {
           return $topHps->hps;
        }
        return 0;
    }

    public static function getSpecTopHpsData($encounterId, $difficultyId, $specId) {
        return MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("spec","=",$specId)
            ->orderBy("hps","desc")->first();
    }

    public static function getClassTopHps($encounterId, $difficultyId, $classId) {
        $topHps = MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("class","=",$classId)
            ->orderBy("hps","desc")->first();
        if ( $topHps ) {
            return $topHps->hps;
        }
        return 0;
    }

    public static function getClassTopHpsData($encounterId, $difficultyId, $classId) {
        return MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("class","=",$classId)
            ->orderBy("hps","desc")->first();
    }

    public static function getSpecTopDpsOnRealm($encounterId, $difficultyId, $specId, $realmId) {
        $topDps = MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("spec","=",$specId)->where("realm_id",$realmId)
            ->orderBy("dps","desc")->first();
        if ( $topDps ) {
            return $topDps->dps;
        }
        return 0;
    }

    public static function getSpecTopHpsOnRealm($encounterId, $difficultyId, $specId, $realmId) {
        $topHps = MemberTop::where("encounter_id","=",$encounterId)
            ->where("difficulty_id","=",$difficultyId)->where("spec","=",$specId)->where("realm_id",$realmId)
            ->orderBy("hps","desc")->first();
        if ( $topHps ) {
            return $topHps->hps;
        }
        return 0;
    }

    public static function getTopDps($encounterId, $difficultyId, $realms, $factions)
    {
        return LadderCache::getTopDps($encounterId,$difficultyId, $realms, $factions);
    }

    public static function getTopHps($encounterId, $difficultyId, $realms, $factions)
    {
        return LadderCache::getTopHps($encounterId,$difficultyId, $realms, $factions);
    }

    // Ra-den and Sinestra
    public static function isHeroicEncounter($_encounter_id)
    {
        return $_encounter_id == 1580 || $_encounter_id == 1581 || $_encounter_id == 1082 || $_encounter_id == 1083;
    }

    public static function getHeroicEncounters() {
        return array(1580,1581,1082,1083);
    }

    public static function doubleCheckEncounterExistsOnDifficulty($_encounter_id, $_difficulty_id) {
        if ( !self::isHeroicEncounter($_encounter_id) ){
            return true;
        } else {
            if ( $_encounter_id == 1580 && $_difficulty_id == 5 ) {
                return true;
            } else if ( $_encounter_id == 1581 && $_difficulty_id == 6 ) {
                return true;
            } else if ( $_encounter_id == 1082 && $_difficulty_id == 5 ) {
                return true;
            } else if ( $_encounter_id == 1083 && $_difficulty_id == 6 ) {
                return true;
            }
        }
        return false;
    }

    public static function getMapDifficulties($_expansion_id, $_map_id, $_encounter_id = 0)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionRaids = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ( $expansionRaids as $raid )
            {
                if ( $raid["id"] == $_map_id )
                {
                    $difficulties = array();
                    if ( $_encounter_id == 0 || !self::isHeroicEncounter($_encounter_id) ) {
                        foreach ($raid["available_difficulties"] as $difficulty) {
                            if (array_key_exists($difficulty["id"], Encounter::SIZE_AND_DIFFICULTY)) {
                                $difficulties[] = $difficulty;
                            }
                        }
                    }
                    else
                    {
                        // Heroic encounters
                        $difficulties[] = array(
                            "id" => 5,
                            "name" => Encounter::SIZE_AND_DIFFICULTY[5]
                        );
                        $difficulties[] = array(
                            "id" => 6,
                            "name" => Encounter::SIZE_AND_DIFFICULTY[6]
                        );
                    }
                    return $difficulties;
                }
            }
        }
        return array();
    }

    public static function getMapDifficultiesForSelect($_expansion_id, $_map_id, $_encounter_id = 0)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionRaids = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ( $expansionRaids as $raid )
            {
                if ( $raid["id"] == $_map_id )
                {
                    $difficulties = array();
                    if ( $_encounter_id == 0 || !self::isHeroicEncounter($_encounter_id) ) {
                        foreach ($raid["available_difficulties"] as $difficulty) {
                            if (array_key_exists($difficulty["id"], Encounter::SIZE_AND_DIFFICULTY)) {
                                $difficulties[$difficulty["id"]] = $difficulty["name"];
                            }
                        }
                    }
                    else
                    {
                        // Heroic encounters
                        $difficulties[5] = Encounter::SIZE_AND_DIFFICULTY[5];
                        $difficulties[6] = Encounter::SIZE_AND_DIFFICULTY[6];
                    }
                    return $difficulties;
                }
            }
        }
        return array();
    }

    public static function getMapDifficultiesShortForSelect($_expansion_id, $_map_id, $_encounter_id = 0)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionRaids = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ( $expansionRaids as $raid )
            {
                if ( $raid["id"] == $_map_id )
                {
                    $difficulties = array();
                    if ( $_encounter_id == 0 || !self::isHeroicEncounter($_encounter_id) ) {
                        foreach ($raid["available_difficulties"] as $difficulty) {
                            if (array_key_exists($difficulty["id"], Encounter::SIZE_AND_DIFFICULTY_SHORT)) {
                                $difficulties[$difficulty["id"]] = Encounter::SIZE_AND_DIFFICULTY_SHORT[$difficulty["id"]];
                            }
                        }
                    }
                    else
                    {
                        // Heroic encounters
                        $difficulties[5] = Encounter::SIZE_AND_DIFFICULTY_SHORT[5];
                        $difficulties[6] = Encounter::SIZE_AND_DIFFICULTY_SHORT[6];
                    }
                    return $difficulties;
                }
            }
        }
        return array();
    }

    public static function getMapEncounters($_expansion_id, $_map_id)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionRaids = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ( $expansionRaids as $raid )
            {
                if ( $raid["id"] == $_map_id )
                {
                    $encounters = array();
                    foreach ( $raid["encounters"] as $encounter )
                    {
                        // Ra-den 25 HC double entry
                        if ( $encounter["encounter_id"] == 1581 || $encounter["encounter_id"] == 1083 )
                        {
                            continue;
                        }
                        $encounters[$encounter["encounter_id"]] = $encounter["encounter_name"];
                    }
                    return $encounters;
                }
            }
        }
        return array();
    }

    public static function getMapEncountersShort($_expansion_id, $_map_id)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionRaids = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ( $expansionRaids as $raid )
            {
                if ( $raid["id"] == $_map_id )
                {
                    $encounters = array();
                    foreach ( $raid["encounters"] as $encounter )
                    {
                        // Ra-den 25 HC double entry
                        if ( $encounter["encounter_id"] == 1581 || $encounter["encounter_id"] == 1083 )
                        {
                            continue;
                        }
                        $encounters[$encounter["encounter_id"]] = self::getNameShort($encounter["encounter_id"]);
                    }
                    return $encounters;
                }
            }
        }
        return array();
    }


    public static function getMapEncountersIds($_expansion_id, $_map_id)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionRaids = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ( $expansionRaids as $raid )
            {
                if ( $raid["id"] == $_map_id )
                {
                    $encounters = array();
                    foreach ( $raid["encounters"] as $encounter )
                    {
                        $encounters[] = $encounter["encounter_id"];
                    }
                    return $encounters;
                }
            }
        }
        return array();
    }

    public static function convertExpansionShortNameToId($_expansion_short_name)
    {
        foreach ( self::EXPANSION_SHORTS as $id => $short )
        {
            if  ( $short == $_expansion_short_name )
            {
                return $id;
            }
        }
        return Defaults::EXPANSION_ID;
    }

    public static function convertMapShortNameToId($_map_short_name, $_expansion_id)
    {
        foreach ( self::MAP_SHORTS as $id => $short )
        {
            if  ( $short == $_map_short_name )
            {
                return $id;
            }
        }
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionMaps = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ($expansionMaps as $map) {
                if ( self::shorten($map["name"]) == $_map_short_name) {
                    return $map["id"];
                }
            }
        }
        return Defaults::MAP_ID;
    }

    public static function convertEncounterShortNameToId($_expansion_id, $_map_id, $_encounter_name_short)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionMaps = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ($expansionMaps as $map) {
                if ($map["id"] == $_map_id) {
                    foreach ($map["encounters"] as $encounter) {
                        $name = Encounter::getUrlName($encounter["encounter_id"]);
                        if ( $name == $_encounter_name_short )
                        {
                            return intval($encounter["encounter_id"]);
                        }
                    }
                }
            }
        }
        return 0;
    }

    public static function shorten($str)
    {
        return strtolower(preg_replace("/\PL/u", "", $str));
    }

    public static function getMapNameShort($_expansion_id, $_map_id)
    {
        $name = Encounter::getMapName($_expansion_id, $_map_id);
        return Encounter::shorten($name);
    }

    public static function getMapName($_expansion_id, $_map_id)
    {
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionMaps = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ($expansionMaps as $map) {
                if ($map["id"] == $_map_id) {
                    return $map["name"];
                }
            }
        }
        return "";
    }

    public static function getMapUrl($expansionId, $mapId)
    {
        return array_key_exists($mapId, self::MAP_SHORTS) ? self::MAP_SHORTS[$mapId] : self::getMapNameShort($expansionId, $mapId);
    }

    public static function convertDifficultyShortNameToId($_difficulty_name_short)
    {
        foreach ( self::SIZE_AND_DIFFICULTY_URL as $difficultyId => $difficultyShort )
        {
            if ( $_difficulty_name_short == $difficultyShort )
            {
                return $difficultyId;
            }
        }
        return Defaults::DIFFICULTY_ID;
    }

    public static function getMapExpansion($_map_id)
    {
        $index = 0;
        foreach ( self::EXPANSION_RAIDS_COMPLEX as $expansionMaps )
        {
            foreach ( $expansionMaps as $map )
            {
                if ( $map["id"] == $_map_id )
                {
                    return $index;
                }
            }
            ++$index;
        }
    }

    public static function getExpansionMapsShort($_expansion_id)
    {
        $maps = array();
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionRaids = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ( $expansionRaids as $raid )
            {
                $maps[$raid["id"]] = array_key_exists($raid["id"], self::MAP_SHORT) ? self::MAP_SHORT[$raid["id"]] : $raid["name"];
            }
            return $maps;
        }
        return array();
    }

    public static function getExpansionMaps($_expansion_id)
    {
        $maps = array();
        $expansionKey = "map_exp_".$_expansion_id;
        if ( array_key_exists($expansionKey, Encounter::EXPANSION_RAIDS_COMPLEX)) {
            $expansionRaids = Encounter::EXPANSION_RAIDS_COMPLEX[$expansionKey];
            foreach ( $expansionRaids as $raid )
            {
                $maps[$raid["id"]] = $raid["name"];
            }
            return $maps;
        }
        return array();
    }

}