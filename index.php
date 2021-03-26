<?

/**
 * Парсеру важно! 
 * Достоверность ссылки.
 * Достоверность дива
 * Достоверность ссылок в диве
 * Достоверность контента в ссылке
 * Достоверность наличия расширения
 */
require_once 'inc.php'; 

// use KubAT\PhpSimple\HtmlDomParser;

// $content = HtmlDomParser::file_get_html('http://chpk.rchuv.ru/action/deyateljnostj/raspisanie');

// foreach($content->find('div[class=activiti-text type_cont]') as $a)
// {
//     foreach($a->find('a') as $link)
//     {
//         if(!empty($link->plaintext))
//         {
//             $path = $link->href;
//             if(Validator::IsFileXML($path))
//             {
//                 echo $path . "<br>";
//             }
//         }
//     }
// }

//header('Content-Type: text/html; charset=utf-8');

$xlsx = SimpleXLSX::parse('C:\Users\ser12\Downloads\3-kurs-1xog3lif.xlsx');
$contents = $xlsx->rows();
$items = Unsetter::ListSorter($contents);
print_r(json_encode(Week::GetWeeks($items)));
print_r(json_encode($items));