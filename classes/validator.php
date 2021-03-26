<?

class Validator
{
    public static function IsFileXML($url)
    {
        $getExt = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        return $getExt === "xlsx" || $getExt === "xls";
    }

    

}