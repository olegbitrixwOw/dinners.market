<?php
use Bitrix\Main;
use Bitrix\Main\Entity;

use Bitrix\Sale;
use Bitrix\Sale\Registry;
use Bitrix\Sale\Fuser;

Bitrix\Main\Loader::IncludeModule('iblock');
Bitrix\Main\Loader::IncludeModule('highloadblock');
Bitrix\Main\Loader::IncludeModule('catalog');
Bitrix\Main\Loader::IncludeModule('sale');

define('CATALOG_PATH', SITE_DIR . 'catalog/');
define('IBLOCK_PRODUCTS', 2);
define('IBLOCK_BALANCE', 12);
define('HL_ACCOUNT', 4);
define('DATE_SHIFT', 22);
define('BLOCKING_TIME', 10);
define('IBLOCK_DOCUMENTS', 9);
define('ADMIN_GROUP_ID', 1);
define('PROPERTY_FIRM_ID', 49);
define('DELIVERY_FIRM_ADDRESS', 11);
define('IBLOCK_GILDS', 7);

// типы пользователей
define('USER_TYPE_USER', 1);
define('USER_TYPE_EMPLOYEE', 2);
define('USER_TYPE_MANAGER', 3);
define('USER_TYPE_ADMIN', 4);

// группы пользователей Юр или Физ лица
define('USER_GROUP_CORPORATE', 10);
define('USER_GROUP_INDIVIDUAL', 11);

// свойства заказа
define('ORDER_PROPERTY_DAY', 20);
define('ORGANIZATION', 28);
define('DELIVERY_ADDRESS', 29);
define('CUISINE', 33);
define('DELIVERY_PRICE', 34);

// платежные системы
define('PAY_SYSTEM_COMPANY', 9); // внутренний счет компании 
define('PAY_SYSTEM_SBER', 10); // сбербанк

define('IBLOCK_CUISINES', 13); // кухни
define('IBLOCK_SOCIAL_NETWORKS', 5); // социальные сети
define('IBLOCK_SHOP_CONTACTS', 15); // контакты


// define('CLASS_LIB', '/local/php_interface/lib');

// автозагрузка классов при объявлении
// function loader($className)
// {
//     $dr = $_SERVER['DOCUMENT_ROOT'];
//     $ds = DIRECTORY_SEPARATOR;
//     $className = ltrim($className, '\\');
//     $classDir = $dr . CLASS_LIB;
//     $fileName = $classDir . $ds . $className . '.php';

//     if (file_exists($fileName))
//     {
//         require $fileName;
//     }
// }
// spl_autoload_register('loader');


// автозагрузка классов при объявлении
Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    'DayorderTable' => '/local/php_interface/lib/DayorderTable.php',   
    'DayorderHelper' => '/local/php_interface/lib/DayorderHelper.php',   
]);


if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/yp/custom_yp.php")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/yp/custom_yp.php");
}

if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/functions.php")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/functions.php");
}

if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/documents_functions_add.php")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/documents_functions_add.php");
}

if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/documents_functions_update.php")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/documents_functions_update.php");
}

if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/iblock_event_handler.php")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/iblock_event_handler.php");
}


include_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');