<?
/**
 * Удаление мусора при помощи стоп-слов
 */

class Unsetter
{
    /**
     * Список запрещенных фраз в строке массива
     */
    private static $lists = [
        'утверждаю',
        'Директор ГАПОУ ЧР "ЧПК" Минобразования Чувашии',
        '_____________________ (О.Г. Якимов)',
        'УТВЕРЖДАЮ',
        '"___" __________________ 2021 г.',
        'Дисциплина',
        'День недели',
        'Ауд.',
        'Преподаватель',
        '№',
        'Зам. директора по УМ и НР_________________ (А.А. Кириллова)',
        'Зав. учебной частью _____________________ (В.В. Ямщикова)',
        'Диспетчер _____________________ (О.Г. Кольцова)',
        'Расписание занятий 3 курса на II полугодие 2020-2021 учебный год (1 неделя)',
        'Расписание занятий 3 курса на II полугодие 2020-2021 учебный год (2 неделя)',
        '', null,' '
    ];
    private static $collection;

    private static function strposa($haystack, $array_list) {

        unset($haystack[0]);
        unset($haystack[1]);
        unset($haystack[2]);
        unset($haystack[3]);
        foreach ($haystack as $key => $item )
        {
                $texts = array_diff( array_map('trim',$item), $array_list);
                self::$collection[] = $texts;
        }
        return self::$collection;
    }

    public static function ListSorter($array_list)
    {
        return self::strposa($array_list, self::$lists);
    }
}