<?php

namespace TauriBay;

use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    const REALM_NAMES = array(
        0 => "[HU] Tauri WoW Server",
        1 => "[HU] Warriors of Darkness",
        2 => "[EN] Evermoon"
    );

    const SHORT_REALM_NAMES = array(
        0 => "Tauri",
        1 => "WoD",
        2 => "Evermoon"
    );


    const ENCOUNTER_IDS = array ( 1084 => array ( 'name' => 'Onyxia', 'map_id' => 249, 'difficulty_id' => 0, ), 663 => array ( 'name' => 'Lucifron', 'map_id' => 409, 'difficulty_id' => 3, ), 664 => array ( 'name' => 'Magmadar', 'map_id' => 409, 'difficulty_id' => 3, ), 665 => array ( 'name' => 'Gehennas', 'map_id' => 409, 'difficulty_id' => 3, ), 666 => array ( 'name' => 'Garr', 'map_id' => 409, 'difficulty_id' => 3, ), 667 => array ( 'name' => 'Shazzrah', 'map_id' => 409, 'difficulty_id' => 3, ), 668 => array ( 'name' => 'Baron Geddon', 'map_id' => 409, 'difficulty_id' => 3, ), 669 => array ( 'name' => 'Sulfuron Harbinger', 'map_id' => 409, 'difficulty_id' => 3, ), 670 => array ( 'name' => 'Golemagg the Incinerator', 'map_id' => 409, 'difficulty_id' => 3, ), 671 => array ( 'name' => 'Majordomo Executus', 'map_id' => 409, 'difficulty_id' => 3, ), 672 => array ( 'name' => 'Ragnaros', 'map_id' => 409, 'difficulty_id' => 3, ), 610 => array ( 'name' => 'Razorgore the Untamed', 'map_id' => 469, 'difficulty_id' => 3, ), 611 => array ( 'name' => 'Vaelastrasz the Corrupt', 'map_id' => 469, 'difficulty_id' => 3, ), 612 => array ( 'name' => 'Broodlord Lashlayer', 'map_id' => 469, 'difficulty_id' => 3, ), 613 => array ( 'name' => 'Firemaw', 'map_id' => 469, 'difficulty_id' => 3, ), 614 => array ( 'name' => 'Ebonroc', 'map_id' => 469, 'difficulty_id' => 3, ), 615 => array ( 'name' => 'Flamegor', 'map_id' => 469, 'difficulty_id' => 3, ), 616 => array ( 'name' => 'Chromaggus', 'map_id' => 469, 'difficulty_id' => 3, ), 617 => array ( 'name' => 'Nefarian', 'map_id' => 469, 'difficulty_id' => 3, ), 718 => array ( 'name' => 'Kurinnaxx', 'map_id' => 509, 'difficulty_id' => 3, ), 719 => array ( 'name' => 'General Rajaxx', 'map_id' => 509, 'difficulty_id' => 3, ), 720 => array ( 'name' => 'Moam', 'map_id' => 509, 'difficulty_id' => 3, ), 721 => array ( 'name' => 'Buru the Gorger', 'map_id' => 509, 'difficulty_id' => 3, ), 722 => array ( 'name' => 'Ayamiss the Hunter', 'map_id' => 509, 'difficulty_id' => 3, ), 723 => array ( 'name' => 'Ossirian the Unscarred', 'map_id' => 509, 'difficulty_id' => 3, ), 709 => array ( 'name' => 'The Prophet Skeram', 'map_id' => 531, 'difficulty_id' => 3, ), 710 => array ( 'name' => 'Silithid Royalty', 'map_id' => 531, 'difficulty_id' => 3, ), 711 => array ( 'name' => 'Battleguard Sartura', 'map_id' => 531, 'difficulty_id' => 3, ), 712 => array ( 'name' => 'Fankriss the Unyielding', 'map_id' => 531, 'difficulty_id' => 3, ), 713 => array ( 'name' => 'Viscidus', 'map_id' => 531, 'difficulty_id' => 3, ), 714 => array ( 'name' => 'Princess Huhuran', 'map_id' => 531, 'difficulty_id' => 3, ), 715 => array ( 'name' => 'Twin Emperors', 'map_id' => 531, 'difficulty_id' => 3, ), 716 => array ( 'name' => 'Ouro', 'map_id' => 531, 'difficulty_id' => 3, ), 717 => array ( 'name' => 'C\'thun', 'map_id' => 531, 'difficulty_id' => 3, ), 652 => array ( 'name' => 'Attumen the Huntsman', 'map_id' => 532, 'difficulty_id' => 3, ), 653 => array ( 'name' => 'Moroes', 'map_id' => 532, 'difficulty_id' => 3, ), 654 => array ( 'name' => 'Maiden of the Virtue', 'map_id' => 532, 'difficulty_id' => 3, ), 655 => array ( 'name' => 'Opera Event', 'map_id' => 532, 'difficulty_id' => 3, ), 656 => array ( 'name' => 'The Curator', 'map_id' => 532, 'difficulty_id' => 3, ), 657 => array ( 'name' => 'Terestian Illhoof', 'map_id' => 532, 'difficulty_id' => 3, ), 658 => array ( 'name' => 'Shade of Aran', 'map_id' => 532, 'difficulty_id' => 3, ), 659 => array ( 'name' => 'Netherspite', 'map_id' => 532, 'difficulty_id' => 3, ), 660 => array ( 'name' => 'Chess Event', 'map_id' => 532, 'difficulty_id' => 3, ), 661 => array ( 'name' => 'Prince Malchezaar', 'map_id' => 532, 'difficulty_id' => 3, ), 662 => array ( 'name' => 'Nightbane', 'map_id' => 532, 'difficulty_id' => 3, ), 618 => array ( 'name' => 'Rage Winterchill', 'map_id' => 534, 'difficulty_id' => 3, ), 619 => array ( 'name' => 'Anetheron', 'map_id' => 534, 'difficulty_id' => 3, ), 620 => array ( 'name' => 'Kaz\'rogal', 'map_id' => 534, 'difficulty_id' => 3, ), 621 => array ( 'name' => 'Azgalor', 'map_id' => 534, 'difficulty_id' => 3, ), 622 => array ( 'name' => 'Archimonde', 'map_id' => 534, 'difficulty_id' => 3, ), 651 => array ( 'name' => 'Magtheridon', 'map_id' => 544, 'difficulty_id' => 3, ), 623 => array ( 'name' => 'Hydross the Unstable', 'map_id' => 548, 'difficulty_id' => 3, ), 624 => array ( 'name' => 'The Lurker Below', 'map_id' => 548, 'difficulty_id' => 3, ), 625 => array ( 'name' => 'Leotheras the Blind', 'map_id' => 548, 'difficulty_id' => 3, ), 626 => array ( 'name' => 'Fathom-Lord Karathress', 'map_id' => 548, 'difficulty_id' => 3, ), 627 => array ( 'name' => 'Morogrim Tidewalker', 'map_id' => 548, 'difficulty_id' => 3, ), 628 => array ( 'name' => 'Lady Vashj', 'map_id' => 548, 'difficulty_id' => 3, ), 730 => array ( 'name' => 'Al\'ar', 'map_id' => 550, 'difficulty_id' => 3, ), 731 => array ( 'name' => 'Void Reaver', 'map_id' => 550, 'difficulty_id' => 3, ), 732 => array ( 'name' => 'High Astromancer Solarian', 'map_id' => 550, 'difficulty_id' => 3, ), 733 => array ( 'name' => 'Kael\'thas Sunstrider', 'map_id' => 550, 'difficulty_id' => 3, ), 601 => array ( 'name' => 'High Warlord Naj\'entus', 'map_id' => 564, 'difficulty_id' => 3, ), 602 => array ( 'name' => 'Supremus', 'map_id' => 564, 'difficulty_id' => 3, ), 603 => array ( 'name' => 'Shade of Akama', 'map_id' => 564, 'difficulty_id' => 3, ), 604 => array ( 'name' => 'Teron Gorefiend', 'map_id' => 564, 'difficulty_id' => 3, ), 605 => array ( 'name' => 'Gurtogg Bloodboil', 'map_id' => 564, 'difficulty_id' => 3, ), 606 => array ( 'name' => 'Reliquary of Souls', 'map_id' => 564, 'difficulty_id' => 3, ), 607 => array ( 'name' => 'Mother Shahraz', 'map_id' => 564, 'difficulty_id' => 3, ), 608 => array ( 'name' => 'The Illidari Council', 'map_id' => 564, 'difficulty_id' => 3, ), 609 => array ( 'name' => 'Illidan Stormrage', 'map_id' => 564, 'difficulty_id' => 3, ), 649 => array ( 'name' => 'High King Maulgar', 'map_id' => 565, 'difficulty_id' => 3, ), 650 => array ( 'name' => 'Gruul the Dragonkiller', 'map_id' => 565, 'difficulty_id' => 3, ), 724 => array ( 'name' => 'Kalecgos', 'map_id' => 580, 'difficulty_id' => 3, ), 725 => array ( 'name' => 'Brutallus', 'map_id' => 580, 'difficulty_id' => 3, ), 726 => array ( 'name' => 'Felmyst', 'map_id' => 580, 'difficulty_id' => 3, ), 727 => array ( 'name' => 'Eredar Twins', 'map_id' => 580, 'difficulty_id' => 3, ), 728 => array ( 'name' => 'M\'uru', 'map_id' => 580, 'difficulty_id' => 3, ), 729 => array ( 'name' => 'Kil\'jaeden', 'map_id' => 580, 'difficulty_id' => 3, ), 1107 => array ( 'name' => 'Anub\'Rekhan', 'map_id' => 533, 'difficulty_id' => 0, ), 1110 => array ( 'name' => 'Grand Widow Faerlina', 'map_id' => 533, 'difficulty_id' => 0, ), 1116 => array ( 'name' => 'Maexxna', 'map_id' => 533, 'difficulty_id' => 0, ), 1117 => array ( 'name' => 'Noth the Plaguebringer', 'map_id' => 533, 'difficulty_id' => 0, ), 1112 => array ( 'name' => 'Heigan the Unclean', 'map_id' => 533, 'difficulty_id' => 0, ), 1115 => array ( 'name' => 'Loatheb', 'map_id' => 533, 'difficulty_id' => 0, ), 1113 => array ( 'name' => 'Instructor Razuvious', 'map_id' => 533, 'difficulty_id' => 0, ), 1109 => array ( 'name' => 'Gothik the Harvester', 'map_id' => 533, 'difficulty_id' => 0, ), 1121 => array ( 'name' => 'The Four Horsemen', 'map_id' => 533, 'difficulty_id' => 0, ), 1118 => array ( 'name' => 'Patchwerk', 'map_id' => 533, 'difficulty_id' => 0, ), 1111 => array ( 'name' => 'Grobbulus', 'map_id' => 533, 'difficulty_id' => 0, ), 1108 => array ( 'name' => 'Gluth', 'map_id' => 533, 'difficulty_id' => 0, ), 1120 => array ( 'name' => 'Thaddius', 'map_id' => 533, 'difficulty_id' => 0, ), 1119 => array ( 'name' => 'Sapphiron', 'map_id' => 533, 'difficulty_id' => 0, ), 1114 => array ( 'name' => 'Kel\'Thuzad', 'map_id' => 533, 'difficulty_id' => 0, ), 1132 => array ( 'name' => 'Flame Leviathan', 'map_id' => 603, 'difficulty_id' => 0, ), 1136 => array ( 'name' => 'Ignis the Furnace Master', 'map_id' => 603, 'difficulty_id' => 0, ), 1139 => array ( 'name' => 'Razorscale', 'map_id' => 603, 'difficulty_id' => 0, ), 1142 => array ( 'name' => 'XT-002 Deconstructor', 'map_id' => 603, 'difficulty_id' => 0, ), 1140 => array ( 'name' => 'The Assembly of Iron', 'map_id' => 603, 'difficulty_id' => 0, ), 1137 => array ( 'name' => 'Kologarn', 'map_id' => 603, 'difficulty_id' => 0, ), 1131 => array ( 'name' => 'Auriaya', 'map_id' => 603, 'difficulty_id' => 0, ), 1135 => array ( 'name' => 'Hodir', 'map_id' => 603, 'difficulty_id' => 0, ), 1141 => array ( 'name' => 'Thorim', 'map_id' => 603, 'difficulty_id' => 0, ), 1164 => array ( 'name' => 'Elder Brightleaf', 'map_id' => 603, 'difficulty_id' => 0, ), 1165 => array ( 'name' => 'Elder Ironbranch', 'map_id' => 603, 'difficulty_id' => 0, ), 1166 => array ( 'name' => 'Elder Stonebark', 'map_id' => 603, 'difficulty_id' => 0, ), 1133 => array ( 'name' => 'Freya', 'map_id' => 603, 'difficulty_id' => 0, ), 1138 => array ( 'name' => 'Mimiron', 'map_id' => 603, 'difficulty_id' => 0, ), 1134 => array ( 'name' => 'General Vezax', 'map_id' => 603, 'difficulty_id' => 0, ), 1143 => array ( 'name' => 'Yogg-Saron', 'map_id' => 603, 'difficulty_id' => 0, ), 1130 => array ( 'name' => 'Algalon the Observer', 'map_id' => 603, 'difficulty_id' => 0, ), 1093 => array ( 'name' => 'Vesperon', 'map_id' => 615, 'difficulty_id' => 0, ), 1092 => array ( 'name' => 'Tenebron', 'map_id' => 615, 'difficulty_id' => 0, ), 1091 => array ( 'name' => 'Shadron', 'map_id' => 615, 'difficulty_id' => 0, ), 1090 => array ( 'name' => 'Sartharion', 'map_id' => 615, 'difficulty_id' => 0, ), 1094 => array ( 'name' => 'Malygos', 'map_id' => 616, 'difficulty_id' => 0, ), 1126 => array ( 'name' => 'Archavon the Stone Watcher', 'map_id' => 624, 'difficulty_id' => 0, ), 1127 => array ( 'name' => 'Emalon the Storm Watcher', 'map_id' => 624, 'difficulty_id' => 0, ), 1128 => array ( 'name' => 'Koralon the Flame Watcher', 'map_id' => 624, 'difficulty_id' => 0, ), 1129 => array ( 'name' => 'Toravon the Ice Watcher', 'map_id' => 624, 'difficulty_id' => 0, ), 1101 => array ( 'name' => 'Lord Marrowgar', 'map_id' => 631, 'difficulty_id' => 0, ), 1100 => array ( 'name' => 'Lady Deathwhisper', 'map_id' => 631, 'difficulty_id' => 0, ), 1099 => array ( 'name' => 'Icecrown Gunship Battle', 'map_id' => 631, 'difficulty_id' => 0, ), 1096 => array ( 'name' => 'Deathbringer Saurfang', 'map_id' => 631, 'difficulty_id' => 0, ), 1104 => array ( 'name' => 'Rotface', 'map_id' => 631, 'difficulty_id' => 0, ), 1097 => array ( 'name' => 'Festergut', 'map_id' => 631, 'difficulty_id' => 0, ), 1102 => array ( 'name' => 'Professor Putricide', 'map_id' => 631, 'difficulty_id' => 0, ), 1095 => array ( 'name' => 'Blood Council', 'map_id' => 631, 'difficulty_id' => 0, ), 1103 => array ( 'name' => 'Queen Lana\'thel', 'map_id' => 631, 'difficulty_id' => 0, ), 1098 => array ( 'name' => 'Valithria Dreamwalker', 'map_id' => 631, 'difficulty_id' => 0, ), 1105 => array ( 'name' => 'Sindragosa', 'map_id' => 631, 'difficulty_id' => 0, ), 1106 => array ( 'name' => 'The Lich King', 'map_id' => 631, 'difficulty_id' => 0, ), 1088 => array ( 'name' => 'Northrend Beasts', 'map_id' => 649, 'difficulty_id' => 0, ), 1087 => array ( 'name' => 'Lord Jaraxxus', 'map_id' => 649, 'difficulty_id' => 0, ), 1086 => array ( 'name' => 'Faction Champions', 'map_id' => 649, 'difficulty_id' => 0, ), 1089 => array ( 'name' => 'Val\'kyr Twins', 'map_id' => 649, 'difficulty_id' => 0, ), 1085 => array ( 'name' => 'Anub\'arak', 'map_id' => 649, 'difficulty_id' => 0, ), 1147 => array ( 'name' => 'Baltharus the Warborn', 'map_id' => 724, 'difficulty_id' => 0, ), 1149 => array ( 'name' => 'Saviana Ragefire', 'map_id' => 724, 'difficulty_id' => 0, ), 1148 => array ( 'name' => 'General Zarithrian', 'map_id' => 724, 'difficulty_id' => 0, ), 1150 => array ( 'name' => 'Halion', 'map_id' => 724, 'difficulty_id' => 0, ), 1027 => array ( 'name' => 'Omnotron Defense System', 'map_id' => 669, 'difficulty_id' => 0, ), 1024 => array ( 'name' => 'Magmaw', 'map_id' => 669, 'difficulty_id' => 0, ), 1022 => array ( 'name' => 'Atramedes', 'map_id' => 669, 'difficulty_id' => 0, ), 1023 => array ( 'name' => 'Chimaeron', 'map_id' => 669, 'difficulty_id' => 0, ), 1025 => array ( 'name' => 'Maloriak', 'map_id' => 669, 'difficulty_id' => 0, ), 1026 => array ( 'name' => 'Nefarian\'s End', 'map_id' => 669, 'difficulty_id' => 0, ), 1030 => array ( 'name' => 'Halfus Wyrmbreaker', 'map_id' => 671, 'difficulty_id' => 0, ), 1032 => array ( 'name' => 'Theralion and Valiona', 'map_id' => 671, 'difficulty_id' => 0, ), 1028 => array ( 'name' => 'Ascendant Council', 'map_id' => 671, 'difficulty_id' => 0, ), 1029 => array ( 'name' => 'Cho\'gall', 'map_id' => 671, 'difficulty_id' => 0, ), 1082 => array ( 'name' => 'Sinestra', 'map_id' => 671, 'difficulty_id' => 5, ), 1083 => array ( 'name' => 'Sinestra', 'map_id' => 671, 'difficulty_id' => 6, ), 1197 => array ( 'name' => 'Beth\'tilac', 'map_id' => 720, 'difficulty_id' => 0, ), 1204 => array ( 'name' => 'Lord Rhyolith', 'map_id' => 720, 'difficulty_id' => 0, ), 1205 => array ( 'name' => 'Shannox', 'map_id' => 720, 'difficulty_id' => 0, ), 1206 => array ( 'name' => 'Alysrazor', 'map_id' => 720, 'difficulty_id' => 0, ), 1200 => array ( 'name' => 'Baleroc', 'map_id' => 720, 'difficulty_id' => 0, ), 1185 => array ( 'name' => 'Majordomo Staghelm', 'map_id' => 720, 'difficulty_id' => 0, ), 1203 => array ( 'name' => 'Ragnaros', 'map_id' => 720, 'difficulty_id' => 0, ), 1035 => array ( 'name' => 'Conclave of Wind', 'map_id' => 754, 'difficulty_id' => 0, ), 1034 => array ( 'name' => 'Al\'Akir', 'map_id' => 754, 'difficulty_id' => 0, ), 1033 => array ( 'name' => 'Argaloth', 'map_id' => 757, 'difficulty_id' => 0, ), 1250 => array ( 'name' => 'Occu\'thar', 'map_id' => 757, 'difficulty_id' => 0, ), 1332 => array ( 'name' => 'Alizabal', 'map_id' => 757, 'difficulty_id' => 0, ), 1292 => array ( 'name' => 'Morchok', 'map_id' => 967, 'difficulty_id' => 0, ), 1294 => array ( 'name' => 'Warlord Zon\'ozz', 'map_id' => 967, 'difficulty_id' => 0, ), 1295 => array ( 'name' => 'Yor\'sahj the Unsleeping', 'map_id' => 967, 'difficulty_id' => 0, ), 1296 => array ( 'name' => 'Hagara', 'map_id' => 967, 'difficulty_id' => 0, ), 1297 => array ( 'name' => 'Ultraxion', 'map_id' => 967, 'difficulty_id' => 0, ), 1298 => array ( 'name' => 'Warmaster Blackhorn', 'map_id' => 967, 'difficulty_id' => 0, ), 1291 => array ( 'name' => 'Spine of Deathwing', 'map_id' => 967, 'difficulty_id' => 0, ), 1299 => array ( 'name' => 'Madness of Deathwing', 'map_id' => 967, 'difficulty_id' => 0, ), 1409 => array ( 'name' => 'Protectors of the Endless', 'map_id' => 996, 'difficulty_id' => 0, ), 1505 => array ( 'name' => 'Tsulong', 'map_id' => 996, 'difficulty_id' => 0, ), 1506 => array ( 'name' => 'Lei Shi', 'map_id' => 996, 'difficulty_id' => 0, ), 1431 => array ( 'name' => 'Sha of Fear', 'map_id' => 996, 'difficulty_id' => 0, ), 1395 => array ( 'name' => 'The Stone Guard', 'map_id' => 1008, 'difficulty_id' => 0, ), 1390 => array ( 'name' => 'Feng the Accursed', 'map_id' => 1008, 'difficulty_id' => 0, ), 1434 => array ( 'name' => 'Gara\'jal the Spiritbinder', 'map_id' => 1008, 'difficulty_id' => 0, ), 1436 => array ( 'name' => 'The Spirit Kings', 'map_id' => 1008, 'difficulty_id' => 0, ), 1500 => array ( 'name' => 'Elegon', 'map_id' => 1008, 'difficulty_id' => 0, ), 1407 => array ( 'name' => 'Will of the Emperor', 'map_id' => 1008, 'difficulty_id' => 0, ), 1507 => array ( 'name' => 'Imperial Vizier Zor\'lok', 'map_id' => 1009, 'difficulty_id' => 0, ), 1504 => array ( 'name' => 'Blade Lord Ta\'yak', 'map_id' => 1009, 'difficulty_id' => 0, ), 1463 => array ( 'name' => 'Garalon', 'map_id' => 1009, 'difficulty_id' => 0, ), 1498 => array ( 'name' => 'Wind Lord Mel\'jarak', 'map_id' => 1009, 'difficulty_id' => 0, ), 1499 => array ( 'name' => 'Amber-Shaper Un\'sok', 'map_id' => 1009, 'difficulty_id' => 0, ), 1501 => array ( 'name' => 'Grand Empress Shek\'zeer', 'map_id' => 1009, 'difficulty_id' => 0, ), 1577 => array ( 'name' => 'Jin\'rokh the Breaker', 'map_id' => 1098, 'difficulty_id' => 0, ), 1575 => array ( 'name' => 'Horridon', 'map_id' => 1098, 'difficulty_id' => 0, ), 1570 => array ( 'name' => 'Council of Elders', 'map_id' => 1098, 'difficulty_id' => 0, ), 1565 => array ( 'name' => 'Tortos', 'map_id' => 1098, 'difficulty_id' => 0, ), 1578 => array ( 'name' => 'Megaera', 'map_id' => 1098, 'difficulty_id' => 0, ), 1573 => array ( 'name' => 'Ji-Kun', 'map_id' => 1098, 'difficulty_id' => 0, ), 1572 => array ( 'name' => 'Durumu the Forgotten', 'map_id' => 1098, 'difficulty_id' => 0, ), 1574 => array ( 'name' => 'Primordius', 'map_id' => 1098, 'difficulty_id' => 0, ), 1576 => array ( 'name' => 'Dark Animus', 'map_id' => 1098, 'difficulty_id' => 0, ), 1559 => array ( 'name' => 'Iron Qon', 'map_id' => 1098, 'difficulty_id' => 0, ), 1560 => array ( 'name' => 'Twin Consorts', 'map_id' => 1098, 'difficulty_id' => 0, ), 1579 => array ( 'name' => 'Lei Shen', 'map_id' => 1098, 'difficulty_id' => 0, ), 1580 => array ( 'name' => 'Ra-den', 'map_id' => 1098, 'difficulty_id' => 5, ), 1581 => array ( 'name' => 'Ra-den', 'map_id' => 1098, 'difficulty_id' => 6, ), 1602 => array ( 'name' => 'Immerseus', 'map_id' => 1136, 'difficulty_id' => 0, ), 1598 => array ( 'name' => 'Fallen Protectors', 'map_id' => 1136, 'difficulty_id' => 0, ), 1624 => array ( 'name' => 'Norushen', 'map_id' => 1136, 'difficulty_id' => 0, ), 1604 => array ( 'name' => 'Sha of Pride', 'map_id' => 1136, 'difficulty_id' => 0, ), 1622 => array ( 'name' => 'Galakras', 'map_id' => 1136, 'difficulty_id' => 0, ), 1600 => array ( 'name' => 'Iron Juggernaut', 'map_id' => 1136, 'difficulty_id' => 0, ), 1606 => array ( 'name' => 'Kor\'kron Dark Shaman', 'map_id' => 1136, 'difficulty_id' => 0, ), 1603 => array ( 'name' => 'General Nazgrim', 'map_id' => 1136, 'difficulty_id' => 0, ), 1595 => array ( 'name' => 'Malkorok', 'map_id' => 1136, 'difficulty_id' => 0, ), 1594 => array ( 'name' => 'Spoils of Pandaria', 'map_id' => 1136, 'difficulty_id' => 0, ), 1599 => array ( 'name' => 'Thok the Bloodthirsty', 'map_id' => 1136, 'difficulty_id' => 0, ), 1601 => array ( 'name' => 'Siegecrafter Blackfuse', 'map_id' => 1136, 'difficulty_id' => 0, ), 1593 => array ( 'name' => 'Paragons of the Klaxxi', 'map_id' => 1136, 'difficulty_id' => 0, ), 1623 => array ( 'name' => 'Garrosh Hellscream', 'map_id' => 1136, 'difficulty_id' => 0, ), 1605 => array ( 'name' => 'Omar\'s Test Encounter (Cosmetic only) DNT', 'map_id' => 1136, 'difficulty_id' => 0, ));


    const MAPS = array(
        1098 => array(
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

    const MAP_ENCOUNTERS = array(
        1098 => array(
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

    const MAP_ENCOUNTERS_MERGED = array(
        1098 => array(
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
            array(
                1580, 1581
            )
        ),
        996 => array(
            1409,
            1505,
            1506,
            1431
        ),
        1009 => array(
            1507,
            1504,
            1463,
            1498,
            1499,
            1501
        ),
        1008 => array(
            1395,
            1390,
            1434,
            1436,
            1500,
            1407
        )
    );

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
        // Check if the raid doesn't exist yet
        $logId = $_data["log_id"];
        if ( strlen($logId) > 0 ) {
            $raid = Encounter::where("log_id", '=', $logId)->where("realm_id", "=", $_realmId)->first();
            if ($raid === null) {
                $guild = Guild::getOrCreate($_data["guilddata"], $_realmId);
                $encounter = new Encounter;
                $encounter->log_id = $logId;
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

                self::updateEncounterMembers($api, $encounter);

                $result["result"] = true;
            }
            else
            {
                $result["error"] = "Raid (log_id: " . $raid->log_id . ") already exists";
            }
        }
        else
        {
            $result["error"] = "Log_id missing: " . $logId;
        }
        return $result;
    }

    public static function updateEncounterMembers($api, $encounter)
    {
        EncounterMember::where("encounter_id", "=", $encounter->id)->delete();
        $raidLog = $api->getRaidLog(self::REALM_NAMES[$encounter->realm_id], $encounter->log_id);
        $members = $raidLog["response"]["members"];
        $i = 0;
        foreach ( $members as $memberData )
        {
            $member = new EncounterMember;
            $member->encounter_id = $encounter->id;
            $member->realm_id = $encounter->realm_id;
            $member->name = $memberData["name"];
            $member->class = $memberData["race"];
            $member->spec = $memberData["spec"];
            $member->damage_done = $memberData["dmg_done"];
            $member->damage_taken = $memberData["dmg_taken"];
            $member->damage_absorb = $memberData["dmg_absorb"];
            $member->heal_done = $memberData["heal_done"];
            $member->absorb_done = $memberData["absorb_done"];
            $member->overheal = $memberData["overheal"];
            $member->heal_taken = $memberData["heal_taken"];
            $member->interrupts = $memberData["interrupts"];
            $member->dispells = $memberData["dispells"];
            $member->ilvl = $memberData["ilvl"];
            $member->save();

            ++$i;
        }
        if ( $encounter->member_count == $i )
        {
            $encounter->members_processed = true;
            $encounter->save();
        }
    }
}