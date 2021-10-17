<?php

/**
 * Class Project
 * by World 2 Me RU
 */
class Project
{
    public static $rows = []; // clear rows

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

    public static function SetValuesFromIds($rows)
    {
        foreach( $rows as $index => $item) {
            foreach ($item as $key => $text) {
                if(self::$weeks_id == $key && strpos($text, self::$headers[0]) !== false)
                {
                    self::$weeks = [
                        'row' => $index,
                        'col' => $key,
                        'text' => $text
                    ];
                }
                if(self::$days_id == $key && !in_array($text, self::$headers))
                {
                    //костыль, тут надо как-то получить $weeks_id[index] сразу 2's items
                    if($index == 5 || $index == 94)
                    {
                        continue;
                    }else
                    {
                        if($text != null)
                        self::$days[] = [
                            'row' => $index,
                            'col' => $key,
                            'text' => $text
                        ];
                    }
                }
                if(self::$times_id == $key && !in_array($text, self::$headers))
                {
                    self::$times[] = [
                        'row' => $index,
                        'col' => $key,
                        'text' => $text
                    ];
                }
                //Text:Группа:, Row:8, Col:3, Text:Подгруппа:, Row:9, Col: 3
                //Text:Группа:, Row:97, Col: 3, Text:Подгруппа:, Row:98, Col: 3
                //echo "Text:$text, Row:$index, Col: $key";

                if(self::$groups_id == $key && ($index == 8 || $index == 97))
                {
                    for ($i = self::$groups_id + 2; $i <= count($rows[8]) - 1; $i++)
                    {
                        if($rows[8][$i] == null || $rows[8][$i] == "")
                            continue;
                        self::$groups[] = [
                            'row' => $index,
                            'col' => $i,
                            'text' => $rows[8][$i]
                        ];
                    }
                }
                if (self::$subgroups_id == $key && ($index == 9 || $index == 98))
                {
                    for ($i = self::$subgroups_id + 2; $i <= count($rows[9]) - 1; $i++)
                    {
                        if($rows[9][$i] == null || $rows[9][$i] == "")
                            continue;
                        self::$subgroups[] = [
                            'row' => $index,
                            'col' => $i,
                            'text' => $rows[9][$i]
                        ];
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
        if(is_array($rows)) {
            foreach( $rows as $index => $item) foreach ($item as $key => $text) {
                if (strpos($text, self::$headers[0]) !== false) self::$weeks_id = $key;
                if (strpos($text, self::$headers[1]) !== false) self::$days_id = $key;
                if (strpos($text, self::$headers[2]) !== false) self::$times_id = $key;
                if (strpos($text, self::$headers[3]) !== false) self::$groups_id = $key;
                if (strpos($text, self::$headers[4]) !== false) self::$subgroups_id = $key;
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
                                //if($item['row'] == $key || $item['row'] == $key + 1)
                                //echo $data[$index][$key]. "-". $data[$index][$key + 1]."\n";
                            }
                        }
                    }
                }
                //return self::getRows();
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