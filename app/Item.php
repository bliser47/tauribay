<?php
/**
 * Created by PhpStorm.
 * User: thoma
 * Date: 2/8/2019
 * Time: 11:13 AM
 */

namespace TauriBay;


use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    const INVENTORY_TYPE = array(
        26 => array(
            "name" => "Ranged",
            "sub_class" => array(
                18 => "Crossbow",
                19 => "Wand"
            )
        ),
        23 => "Held In Off-hand",
        20 => "Chest",
        19 => "",
        18 => array(
            "name" => "Bag"
        ),
        17 => array(
            "name" => "Two-hand",
            "sub_class" => array(
                1 => "Axe",
                4 => "Mace",
                6 => "Polearm",
                8 =>"Sword",
                10 => "Staff"
            )
        ),
        16 => "Cloak",
        15 => array(
            "name" => "Ranged",
            "sub_class" => array(
                2 => "Bow"
            )
        ),
        14 => array(
            "name" => "Off-hand",
            "sub_class" => array(
                6 => "Shield"
            )
        ),
        13 => array(
            "name" => "One-hand",
            "sub_class" => array(
                0 => "Axe",
                4 => "Mace",
                7 => "Sword",
                15 => "Dagger"
            )
        ),
        12 => array(
            "name" => "Trinket"
        ),
        11 => array(
            "name" => "Finger"
        ),
        10 => "Hands",
        9 => "Wrist",
        8 => "Feet",
        7 => "Legs",
        6 => "Waist",
        5 => "Chest",
        4 => "Shirt",
        3 => "Shoulder",
        2 => array(
            "name" => "Neck"
        ),
        1 => "Head",
        0 => array(
            "name" => ""
        )
    );

    const SUB_CLASS = array(
        0 => "",
        1 => "Cloth",
        2 => "Leather",
        3 => "Mail",
        4 => "Plate",
        5 => "?",
        6 => "Shield",
        7 => "Token",
        8 => "?",
        9 => "?",
        10 => "?"
    );

    public static function getInventoryType($type)
    {
        if ( array_key_exists($type, self::INVENTORY_TYPE) ) {
            $type = self::INVENTORY_TYPE[$type];
            if (is_array($type)) {
                return $type["name"];
            }
            return $type;
        }
        return "";
    }

    public static function getSubClass($type, $subclass)
    {
        if ( array_key_exists($type, self::INVENTORY_TYPE) ) {
            $type = self::INVENTORY_TYPE[$type];
            if (is_array($type)) {
                if ( array_key_exists("sub_class", $type) ) {
                    if ( array_key_exists($subclass, $type["sub_class"]) ) {
                        return $type["sub_class"][$subclass];
                    }
                    else
                    {
                        return "";
                    }
                }
                else
                {
                    return "";
                }
            }
            if ( array_key_exists($subclass, self::SUB_CLASS)) {
                return self::SUB_CLASS[$subclass];
            }
        }
        return "";
    }
}