<?

/**
 * Конфигуратор для подлючения всех классов
 */

ini_set('error_reporting', E_ALL );
ini_set('display_errors', 1 );
header('Content-Type: application/json; charset=utf-8');
require_once 'vendor/autoload.php'; 

require_once 'classes/validator.php';
require_once 'classes/unsetter.php';
require_once 'classes/schedule.php';
