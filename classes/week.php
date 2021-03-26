<?php

/**
 * Class Week
 * Список рабочих дней
 */
class Week extends Schedule
{

    private static $week;

    public static function GetWeeks($array)
    {
        foreach ($array as $key => $value)
        {
            if(!empty($value[0]))
                self::$week[] = $value[0];
        }
        return array_unique(self::$week);
    }
}