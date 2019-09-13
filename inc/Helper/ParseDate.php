<?php
namespace Inc\Helper;

class ParseDate
{

    static $startDate;
    static $endDate;

    public static function getDate($sd, $ed)
    {
        self::$startDate = strtotime($sd);
        self::$endDate = strtotime($ed);

        if (date('Y', self::$startDate) !== date('Y', self::$endDate)) {

            return date('d M Y', self::$startDate) . ' - ' . date('d M Y', self::$endDate);
        }
        if (date('M', self::$startDate) !== date('M', self::$endDate)) {

            return date('d M ', self::$startDate) . ' - ' . date('d M ', self::$endDate) . ' ' . date('Y', self::$endDate);

        }
        if (date('d', self::$startDate) !== date('d', self::$endDate)) {

            return date(' d ', self::$startDate) . ' - ' . date(' d ', self::$endDate) . ' ' . date(' M ', self::$startDate) . ' ' . date('Y', self::$endDate);
        }

        if (date('d', self::$startDate) == date('d', self::$endDate)) {

            return date(' d ', self::$startDate) . ' ' . date(' M ', self::$startDate) . ' ' . date('Y', self::$endDate);
        }

    }

}
