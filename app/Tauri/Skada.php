<?php
/**
 * Created by PhpStorm.
 * User: Tamas
 * Date: 1/9/2019
 * Time: 12:45 PM
 */

namespace TauriBay\Tauri;


class Skada
{

    public static function format($ps, $precise = false)
    {
        if ( $ps > 999 )
        {
            $x = round($ps);
            $x_number_format = number_format($x);
            if ( !$precise ) {
                $x_array = explode(',', $x_number_format);
                $x_parts = array('k', 'm', 'b', 't');
                $x_count_parts = count($x_array) - 1;
                $x_display = $x_array[0] . (count($x_array) > 1 ? ((int)$x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '') : '');
                $x_display .= $x_parts[$x_count_parts - 1];
                return $x_display;
            }
            return $x_number_format;
        }
        else
        {
            return number_format($ps);
        }
    }

    public static function calculatePS($encounter, $member, $key, $noFormat = false)
    {
        $ps = $member[$key]/($encounter->fight_time/1000);
        if ( $noFormat )
        {
            return $ps;
        }
        return self::format($ps);
    }

    public static function calculatePercentage($member,$firstMember, $key)
    {
        return $member->$key * 100 / max(array($firstMember->$key, 1));
    }
}