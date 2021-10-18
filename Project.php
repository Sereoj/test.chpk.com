<?php

/**
 * Class Project
 * by World 2 Me RU
 */
class Project
{
    public static $rows = []; // clear rows

    // !!!system!!!
    // rows => col => id
    private static $weeks_id;
    private static $days_id;
    private static $times_id;
    private static $groups_id;
    private static $subgroups_id;

    //navigation helpers
    private static $headers = ['Расписание','День недели', 'Время','Группа:', 'Подгруппа:'];

    // array => ['col','row','text']
    private static $weeks;
    private static $days;
    private static $times;
    private static $groups;
    private static $subgroups;

    private static $offset = 2;

    // result;
    private static $object;

    /**
     * Удаление стоп слов.
     * @param $rows
     * @return array|mixed
     */
    private static function RemoveWords($rows)
    {
        $stopWords = [
            'УТВЕРЖДАЮ',
            'директор',
            'Минобразования',
            '_',
        ];
        if(is_array($rows))
        {
            foreach( $rows as $item)
            {
                self::$rows[] = array_udiff($item, $stopWords, function ($text, $value)
                {
                    if (stripos($text, $value) !== false) {
                        return 0;
                    }
                    return 1;
                });
            }
            return self::$rows;
        }
        return false;
    }

    /**
     * Очистка массивов, чтобы избавиться от повторов.
     */
    private static function clear_ids()
    {
        self::$weeks_id = null;
        self::$days_id = null;
        self::$times_id = null;
        self::$groups_id = null;
        self::$subgroups_id = null;
    }

    /**
     * @param string $name
     * @param $groups
     * @param $subgroups
     * @return array
     * Получение одной группы и подгрупп
     */
    public static function FormatOneGroupNSub($name, $groups, $subgroups)
    {

        if($name == null)
        {
            $name = current(self::getGroups())['text']; // текущая группа, по умолчанию первая колонка.
        }

        $name = strtoupper($name);

        if(is_array($groups) && is_array($subgroups) )
        {
            $arr = null;
            $counter = 0;
            foreach ($groups as $itemGroup)
            {
                foreach ($subgroups as $itemSubgroup)
                {
                    if($itemGroup['col'] == $itemSubgroup['col'] && $itemGroup['row'] + 1 == $itemSubgroup['row'])
                    {
                        if($itemGroup['text'] == $name)
                        {
                            $counter ++;
                            $arr[$counter]['group'] = $itemGroup;
                            $arr[$counter][1] = $itemSubgroup;
                        }
                    }else if($itemGroup['col'] + 2 == $itemSubgroup['col'] && $itemGroup['row'] + 1 == $itemSubgroup['row'])
                    {
                        if($itemGroup['text'] == $name)
                        {
                            $arr[$counter][2] = $itemSubgroup;
                        }
                    }
                }
            }
            return $arr;
        }
    }

    /**
     * @param $groups
     * @param $subgroups
     * @return array
     * Получение всех групп и подгрупп
     */
    public static function FormatGroupsNSub($groups, $subgroups)
    {
        if( is_array($groups) && is_array($subgroups) )
        {
            $arr = null;
            $counter = 0;
            foreach ($groups as $itemGroup)
            {
                foreach ($subgroups as $itemSubgroup)
                {
                    if($itemGroup['col'] == $itemSubgroup['col'] && $itemGroup['row'] + 1 == $itemSubgroup['row'])
                    {
                        $counter ++;
                        $arr[$counter]['group'] = $itemGroup;
                        $arr[$counter][1] = $itemSubgroup;
                    }else if($itemGroup['col'] + 2 == $itemSubgroup['col'] && $itemGroup['row'] + 1 == $itemSubgroup['row'])
                    {
                        $arr[$counter][2] = $itemSubgroup;
                    }
                }
            }
            return $arr;
        }
    }

    /**
     * Получение всех времени и дней
     * @param $days
     * @param $times
     */
    public static function FormatDaysNTimes($days, $times)
    {
        if(is_array($days) && is_array($times))
        {
            $arr = null;
            $next_day = null;
            $next_row = null;

            foreach ($days as $index => $item_day)
            {
                foreach ($times as $key => $item_times)
                {
                    if($index == $key )
                    {
                        echo $item_day['text'];
                    }
                }

            }
        }
    }

    /**
     * @param $rows
     * Формирование ОБЯЗАТЕЛЬНЫХ данных
     */
    public static function SetValuesFromIds($rows)
    {
        foreach( $rows as $index => $item) {
            foreach ($item as $key => $text) {

                //Set days
                if(self::$days_id[0]['col'] == $key && $index > self::$days_id[0]['row'])
                {
                    if($text != null && !in_array($text, self::$headers) && self::$weeks_id[1]['row'] != $index)
                    {
                        //echo "Text:$text, Row:$index, Col: $key\n";
                        self::$days[] = [
                            'row' => $index,
                            'col' => $key,
                            'text' => $text
                        ];
                    }
                }

                //Set Times
                if(self::$times_id[0]['col'] == $key && $index > self::$subgroups_id[0]['row'])
                {
                    if($text != null && !in_array($text, self::$headers))
                    {
                        self::$times[] = [
                            'row' => $index,
                            'col' => $key,
                            'text' => $text
                        ];
                    }
                }

                //echo "Text:$text, Row:$index, Col: $key\n";

                //Set Groups
                if(self::$groups_id[0]['col'] == $key && (self::$groups_id[0]['row'] == $index || self::$groups_id[1]['row'] == $index))
                {
                    $row_id = self::$groups_id[0]['row'];
                    for ($i = self::$groups_id[0]['col'] + self::$offset; $i <= count($rows[$row_id]) - 1; $i++)
                    {
                        if($rows[$row_id][$i] != null)
                        {
                            $texts = $rows[$row_id][$i];
                            self::$groups[] = [
                                'row' => $index,
                                'col' => $i,
                                'text' => $texts
                            ];
                        }
                    }
                }

                //Set Subgroups
                if (self::$subgroups_id[0]['col'] == $key && (self::$subgroups_id[0]['row'] == $index || self::$subgroups_id[1]['row'] == $index))
                {
                    $row_id = self::$subgroups_id[0]['row'];
                    for ($i = self::$subgroups_id[0]['col'] + self::$offset; $i <= count($rows[$row_id]) - 1; $i ++)
                    {
                        if($rows[$row_id][$i] != null || $rows[$row_id][$i] != "")
                        {
                            $texts = $rows[$row_id][$i];
                            self::$subgroups[] = [
                                'row' => $index,
                                'col' => $i,
                                'text' => $texts
                            ];
                        }
                    }
                }
            }
        }
    }
    /**
     * Получение ID columns
     * @param $rows
     * @return array|false
     */
    public static function GetIds($rows)
    {
        self::clear_ids();

        if(is_array($rows)) {
            foreach( $rows as $index => $item) foreach ($item as $key => $text) {
                if (strpos($text, self::$headers[0]) !== false) self::$weeks_id[] = [
                    'row' => $index,
                    'col' => $key,
                    'text' => $text
                ];
                if (strpos($text, self::$headers[1]) !== false) self::$days_id[] = [
                    'row' => $index,
                    'col' => $key,
                    'text' => $text
                ];
                if (strpos($text, self::$headers[2]) !== false) self::$times_id[] = [
                    'row' => $index,
                    'col' => $key,
                    'text' => $text
                ];
                if (strpos($text, self::$headers[3]) !== false) self::$groups_id[]= [
                    'row' => $index,
                    'col' => $key,
                    'text' => $text
                ];
                if (strpos($text, self::$headers[4]) !== false) self::$subgroups_id[]= [
                    'row' => $index,
                    'col' => $key,
                    'text' => $text
                ];
            }

            return [
                'weeks_id' => self::$weeks_id,
                'days_id' => self::$days_id,
                'times_id' => self::$times_id,
                'groups_id' => self::$groups_id,
                'subgroups_id' => self::$subgroups_id
            ];
        }
        return false;
    }

    /**
     * TODO: Вывод расписания по группе или Id
     * NO Working!!!
     * @param $nameOrId
     * @return mixed|null
     */
    public static function GetValuesFromGroup($nameOrId = '')
    {
        if($nameOrId == "")
        {
            $nameOrId = current(self::getGroups())['text'];
        }

        $nameOrId = strtoupper($nameOrId); // ис-1-18 => ИС-1-18
        if(!is_numeric($nameOrId)){
            if(self::getGroups() != null)
            {
                $arr = [];
                foreach (self::getGroups() as $item)
                {
                    if($item['text'] == $nameOrId)
                    {
                        $data = self::getRows();
                        foreach ($data as $index => $rows)
                        {
                            foreach ($rows as $key => $text)
                            {
                                //echo $text;
                                if($item['row'] == $key || $item['row'] == $key + 1)
                                    echo $data[$index][$key]. "-". $data[$index][$key + 1]."\n";
                            }
                        }
                    }
                }
            }
            return null;
        }
    }

    /**
     * Открытие файла и удаление стоп слов и установка id
     * @param $path
     * @return false|true
     */
    public static function OpenFile($path)
    {
        if(class_exists('SimpleXLSX'))
        {
            if($xlsx = SimpleXLSX::parse($path)) {
                self::RemoveWords($xlsx->rows());
                self::GetIds(self::getRows());
                return true;
            }
        }
        return false;
    }

    /**
     * Получение чистого rows без стоп-слов.
     * @return mixed
     */
    public static function getRows()
    {
        return self::$rows;
    }

    /**
     * Получение недели
     * @return mixed
     */
    public static function getWeeks()
    {
        return self::$weeks;
    }
    /**
     * Получение дней недели
     * @return mixed
     */
    public static function getDays()
    {
        return self::$days;
    }

    /**
     * Получение времени
     * @return mixed
     */
    public static function getTimes()
    {
        return self::$times;
    }

    /**
     * Получение групп
     * @return mixed
     */
    public static function getGroups()
    {
        return self::$groups;
    }

    /**
     * Получение подгрупп
     * @return mixed
     */
    public static function getSubgroups()
    {
        return self::$subgroups;
    }
}