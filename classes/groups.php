<?php

/**
 * Class Groups
 * Список групп
 */
class Groups extends Schedule
{
    /**
     * Список запрещенных фраз в строке массива
     */
    private static $lists = [
        'Группа:'
    ];

    public static function GetGroups($array_groups)
    {
        return array_diff($array_groups, self::$lists);
    }



}