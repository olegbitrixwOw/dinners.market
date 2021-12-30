<?php
use Bitrix\Main, Bitrix\Sale;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

// вывод данных 
function pr($var, $type = false) {
    echo '<pre style="font-size:10px; border:1px solid #000; background:#FFF; text-align:left; color:#000;">';
    if ($type)
        var_dump($var);
    else
        print_r($var);
    echo '</pre>';
} 


// запись в файл логов
function setLog($arFields){
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log_iblock_setlog.txt', serialize($arFields)."\r\n", FILE_APPEND);
}


function getUserLimit($userId, $day = null){
      $result = \Bitrix\Main\UserTable::getList(array(
            'select' => array('UF_LIMIT_SU',  'UF_LIMIT_SA', 'UF_LIMIT_FR', 'UF_LIMIT_TH', 'UF_LIMIT_WE', 'UF_LIMIT_TU', 'UF_LIMIT_MO'), 
            'filter' => array('ID' => $userId)
      ));
      if(empty($day)){
        $day = strtoupper(substr(date("l"), 0, 2));
      }else{
        $day = strtoupper($day);
      }

      if($arUser = $result->fetch()){
          $limit = $arUser['UF_LIMIT_'.$day];    
      }
      return $limit;
}


function getUserDayLimit($userId, $day){
    
      $result = \Bitrix\Main\UserTable::getList(array(
            'select' => array('UF_LIMIT_SU',  'UF_LIMIT_SA', 'UF_LIMIT_FR', 'UF_LIMIT_TH', 'UF_LIMIT_WE', 'UF_LIMIT_TU', 'UF_LIMIT_MO'), 
            'filter' => array('ID' => $userId)
      ));
      // $day = strtoupper(substr(date("l"), 0, 2));
      if($arUser = $result->fetch()){
          $limit = $arUser['UF_LIMIT_'.strtoupper($day)];    
      }
      return $limit;
}

function getUserDayLimits($userId){
      $limits = [];
      $sum = 0;
      $result = \Bitrix\Main\UserTable::getList(array(
            'select' => array('UF_LIMIT_SU',  'UF_LIMIT_SA', 'UF_LIMIT_FR', 'UF_LIMIT_TH', 'UF_LIMIT_WE', 'UF_LIMIT_TU', 'UF_LIMIT_MO'), 
            'filter' => array('ID' => $userId)
      ));
      $week_days = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
      $arUser = $result->fetch();
      foreach ($week_days as $day) {
          $limits[$day] = $arUser['UF_LIMIT_'.strtoupper($day)];  
          $sum = $sum + $arUser['UF_LIMIT_'.strtoupper($day)];
      }

      $limits['SUM'] = $sum;
      return $limits;
}

// ДАТЫ
// дата начала недели от текущего дня недели
function getBeginDate($day = null){
      if(!$day){
         $day = substr(date("l"), 0, 2);
      }
      $string_today = date("d.m.Y");
      $today = strtotime($string_today);
      switch ($day) {
            case 'Mo':
              $begin_date = $string_today;
              break;
            case 'Tu':
              $begin_date = date('d.m.Y', strtotime("-1 day", $today));
              break;
            case 'We':
              $begin_date = date('d.m.Y', strtotime("-2 day", $today));
              break;
            case 'Th':
              $begin_date = date('d.m.Y', strtotime("-3 day", $today));
              break;
            case 'Fr':
              $begin_date = date('d.m.Y', strtotime("-4 day", $today));
              break;
            case 'Sa':
              $begin_date = date('d.m.Y', strtotime("-5 day", $today));
              break;
            case 'Su':
              $begin_date = date('d.m.Y', strtotime("-6 day", $today));
              break;
            default:
              # code...
            break;
      }
      return $begin_date;
}


// сумма всех корзин
function sumAllBaskets(){
    $SITE = Bitrix\Main\Context::getCurrent()->getSite(); // текущий сайт
    $days_week = ['Mo', 'Tu', 'We', 'Th' , 'Fr', 'Sa', 'Su'];
    $sum = 0;
    foreach ($days_week as $key => $lid){
        $basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), $lid);
        if($basket->getQuantityList()){
            $sum = $sum + $basket->getPrice();
        }
    }
    return $sum;
}


function sumCurBasket($day){
    $SITE = Bitrix\Main\Context::getCurrent()->getSite(); // текущий сайт
    $sum = 0;
        $basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), $day);
        if($basket->getQuantityList()){
            $sum = $sum + $basket->getPrice();
        }
    return $sum;
}


function basketNumProducts($day){
    $basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), $day);
    return count($basket->getQuantityList());
}


function checkCuisine($cuisine, $day){
    if(isset($_SESSION[$day.'_cuisine'])){         
        if($_SESSION[$day.'_cuisine']['cuisine'] !== $cuisine){
          return true;
        }
    }
    return false;
}

// КУХНИ
function getCuisine($day){
    if(isset($_SESSION[$day.'_cuisine'])){         
      return $_SESSION[$day.'_cuisine'];
    }
    return false;
}

function setCuisine($cuisine, $day){
    // начинаем сессию
    session_start();
    $_SESSION[$day.'_cuisine'] = array('cuisine'=>$cuisine, 'day'=>$day);
    setLog('setCuisine');
    return true;
}

function deleteCuisine($day){
    if(isset($_SESSION[$day.'_cuisine'])){
        unset($_SESSION[$day.'_cuisine']);
    }
}

function getShopContacts(){    
    $arFilter = array("IBLOCK_ID"=>IBLOCK_SHOP_CONTACTS, "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize"=>1), array());
    $arItem = $res->fetch();    
    
    if($arItem){
        $dbProperty = CIBlockElement::getProperty($arItem['IBLOCK_ID'], $arItem['ID'], array("sort", "asc"),array());
        while ($arProperty = $dbProperty->GetNext()) {
            if($arProperty['CODE']){
                  $arItem[$arProperty['CODE']] =  $arProperty['VALUE'];  // город 
            }   
        }
        return $arItem;
    }else{
        return false;
    }
}

function getCuisineElement($cuisine){    
    $arFilter = array("IBLOCK_ID"=>IBLOCK_CUISINES, "ACTIVE"=>"Y", "CODE"=>$cuisine);
    $res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize"=>500), array());
    $arItem = $res->fetch();    
    
    if($arItem){
        $dbProperty = CIBlockElement::getProperty($arItem['IBLOCK_ID'], $arItem['ID'], array("sort", "asc"),array());
        while ($arProperty = $dbProperty->GetNext()) {
            if($arProperty['CODE']){
                  $arItem[$arProperty['CODE']] =  $arProperty['VALUE'];  // город 
            }   
        }
        return $arItem;
    }else{
        return false;
    }
}

function getCuisines(){    
    $arFilter = array("IBLOCK_ID"=>IBLOCK_CUISINES, "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize"=>500), array());
    $arItems = [];
   
    while($arItem = $res->fetch()){      
      $dbProperty = CIBlockElement::getProperty($arItem['IBLOCK_ID'], $arItem['ID'], array("sort", "asc"),array());
      while ($arProperty = $dbProperty->GetNext()) {
            if($arProperty['CODE']){
                $arItem[$arProperty['CODE']] =  $arProperty['VALUE'];
            }             
      }
      $arItems[] = $arItem;
    }   
    return $arItems;
}

// URLS

function getUrl(){
  $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
  $uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
  return $cur_page = $uri->getPath(false);
}


// ДАТЫ
function weekDate($begin_date){
      $begin_day = date("w", strtotime($begin_date));
      $today = strtotime($begin_date);
      
      switch ($begin_day) {
          case '1':
              $week_date = array(
                'Mo'=>substr($begin_date,0,5),
                'Tu'=>date('d.m', strtotime("+1 day", $today)),
                'We'=>date('d.m', strtotime("+2 day", $today)),
                'Th'=>date('d.m', strtotime("+3 day", $today)),
                'Fr'=>date('d.m', strtotime("+4 day", $today)),
                'Sa'=>date('d.m', strtotime("+5 day", $today)),
                'Su'=>date('d.m', strtotime("+6 day", $today))
              );
          break;

          case '2':
              $week_date = array(
                'Tu'=>substr($begin_date,0,5),
                'We'=>date('d.m', strtotime("+1 day", $today)),
                'Th'=>date('d.m', strtotime("+2 day", $today)),
                'Fr'=>date('d.m', strtotime("+3 day", $today)),
                'Sa'=>date('d.m', strtotime("+4 day", $today)),
                'Su'=>date('d.m', strtotime("+5 day", $today)),
                'Mo'=>date('d.m', strtotime("+6 day", $today))
              );
          break;

          case '3':
              $week_date = array(
                'We'=>substr($begin_date,0,5),
                'Th'=>date('d.m', strtotime("+1 day", $today)),
                'Fr'=>date('d.m', strtotime("+2 day", $today)),
                'Sa'=>date('d.m', strtotime("+3 day", $today)),
                'Su'=>date('d.m', strtotime("+4 day", $today)),
                'Mo'=>date('d.m', strtotime("+5 day", $today)), 
                'Tu'=>date('d.m', strtotime("+6 day", $today))
              );
          break;

          case '4':
              $week_date = array(
                'Th'=>substr($begin_date,0,5),
                'Fr'=>date('d.m', strtotime("+1 day", $today)),
                'Sa'=>date('d.m', strtotime("+2 day", $today)),
                'Su'=>date('d.m', strtotime("+3 day", $today)),
                'Mo'=>date('d.m', strtotime("+4 day", $today)), 
                'Tu'=>date('d.m', strtotime("+5 day", $today)),
                'We'=>date('d.m', strtotime("+6 day", $today))
              );
          break;

          case '5':
              $week_date = array(
                  'Fr'=>substr($begin_date,0,5),
                  'Sa'=>date('d.m', strtotime("+1 day", $today)),
                  'Su'=>date('d.m', strtotime("+2 day", $today)),
                  'Mo'=>date('d.m', strtotime("+3 day", $today)), 
                  'Tu'=>date('d.m', strtotime("+4 day", $today)),
                  'We'=>date('d.m', strtotime("+5 day", $today)),
                  'Th'=>date('d.m', strtotime("+6 day", $today))
              );
          break;

          case '6':
               $week_date = array(
                  'Sa'=>substr($begin_date,0,5),
                  'Su'=>date('d.m', strtotime("+1 day", $today)),
                  'Mo'=>date('d.m', strtotime("+2 day", $today)), 
                  'Tu'=>date('d.m', strtotime("+3 day", $today)),
                  'We'=>date('d.m', strtotime("+4 day", $today)),
                  'Th'=>date('d.m', strtotime("+5 day", $today)),
                  'Fr'=>date('d.m', strtotime("+6 day", $today))
              );
          break;

          case '0':
              $week_date = array(
                'Su'=>substr($begin_date,0,5),
                'Mo'=>date('d.m', strtotime("+1 day", $today)), 
                'Tu'=>date('d.m', strtotime("+2 day", $today)),
                'We'=>date('d.m', strtotime("+3 day", $today)),
                'Th'=>date('d.m', strtotime("+4 day", $today)),
                'Fr'=>date('d.m', strtotime("+5 day", $today)),
                'Sa'=>date('d.m', strtotime("+6 day", $today))
              );
          break;

        default:
              # code...
        break;
      }

    // pr($week_date);
    return $week_date;
}


function _getBeginDate($string_today = null, $day = null){

      if(!$day){
         $day = substr(date("l"), 0, 2);
      }
      if(!$string_today){
        $string_today = date("d.m.Y");
      }
      // echo $string_today.'<br>';
      // echo $day.'<br>';
      
      $today = strtotime($string_today);

      switch ($day) {
            case 'Mo':
              $begin_date = $string_today;
              break;
            case 'Tu':
              $begin_date = date('d.m.Y', strtotime("-1 day", $today));
              break;
            case 'We':
              $begin_date = date('d.m.Y', strtotime("-2 day", $today));
              break;
            case 'Th':
              $begin_date = date('d.m.Y', strtotime("-3 day", $today));
              break;
            case 'Fr':
              $begin_date = date('d.m.Y', strtotime("-4 day", $today));
              break;
            case 'Sa':
              $begin_date = date('d.m.Y', strtotime("-5 day", $today));
              break;
            case 'Su':
              $begin_date = date('d.m.Y', strtotime("-6 day", $today));
              break;
            default:
              # code...
            break;
      }
      return $begin_date;
}

// получаем дату начала недели
function beginDate(){
    $begin_date = date('d.m.Y', strtotime(date("d.m.Y")));
    $objDateTime = new DateTime();
    
    if($objDateTime->format("H") >= DATE_SHIFT){
        // здесь чистим корзину текущего дня
        $day = substr(date("l"), 0, 2);
        removeBasket($day);

        $today = strtotime($begin_date);
        return $updete_begin_date = date('d.m.Y', strtotime("+1 day", $today));
    }

    return $begin_date;
}

// очищаем корзину текущего дня вечером
function removeBasket($day){
     $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $day);
    if($basket->getQuantityList()){
      $basketItems = $basket->getBasketItems();
      foreach ($basketItems as $key => $item) {
          $basket->getItemById($item->getId())->delete();
      }
      $basket->save();
    }
}




// ГЛАВНАЯ СТРАНИЦА
function catalogSections(){ 
    $sections = [];
    $rsParentSection = CIBlockSection::GetList(
        array('sort' => 'asc'), 
        array('IBLOCK_ID' => IBLOCK_PRODUCTS, 'ACTIVE' => 'Y')
    );
    while ($arParentSection = $rsParentSection->GetNext())
    {
      $sections[] = $arParentSection;
    } 
    return $sections;
}


function getDayWeek(){
  if(isset($_REQUEST["day_week"])){
    $day_week =  htmlentities($_REQUEST["day_week"]);
  }else{
    $day_week = substr(date("l"), 0, 2);
  }
  return $day_week;
}

function getDayName($lid){
    switch ($lid) {
          case 'Mo':
            $day = 'понедельник';
            break;
          case 'Tu':
            $day = 'вторник';
            break;
          case 'We':
            $day = 'среда';
            break;
          case 'Th':
            $day = 'четверг';
            break;
          case 'Fr':
            $day = 'пятница';
            break;
          case 'Sa':
            $day = 'суббота';
            break;
          case 'Su':
            $day = 'воскресенье';
            break;
          default:
            $day = 'все дни недели';
            break;
    }
  return $day;
}


function putUsersToCSV($file, $array){
    $fields_type = 'R'; //дописываем строки в файл
    $delimiter = ";";   //разделитель для csv-файла
    $csvFile = new \CCSVData($fields_type, false);
    $csvFile->SetFieldsType($fields_type);
    $csvFile->SetDelimiter($delimiter);
    $csvFile->SetFirstHeader(true);
    $arrayHeader = array(
      "ID",
      "ИМЯ", // NAME
      "ФАМИЛИЯ", // LAST_NAME
      "LOGIN", // LOGIN
      "EMAIL", // EMAIL
      "ТЕЛ", // WORK_PHONE
      "ДАТА ПОСЛЕДНЕЙ АВТОРИЗАЦИИ", // LAST_LOGIN
      "ДАТА РЕГИСТРАЦИИ", // DATE_REGISTER
      "ГРУППЫ ПОЛЬЗОВАТЕЛЯ"
    );
    $arrayFields = array(
      "ID",
      "NAME",
      "LAST_NAME",
      "LOGIN",
      "EMAIL",
      "WORK_PHONE",
      "LAST_LOGIN",
      "DATE_REGISTER",
      "GROUPS"
    );

    // запишем заголовки:
    $csvFile->SaveFile($file,$arrayHeader);
    foreach ($array as $arValue){
      $row = array();
      foreach ($arrayFields as $arrayField)
      {
        $row[] = $arValue[$arrayField];
      }
      $csvFile->SaveFile($file,$row);
    }
}




// ДОКУМЕНТЫ

// получаем документ из раздела документов 
function documentList($iblock_id, $section_id){
    // $arSelect = array("ID", "NAME", "DETAIL_PAGE_URL", "ACTIVE_FROM", "CODE", "PROPERTY_*");
    $arSelect = array("*", "PROPERTY_*");
    $arFilter = array("IBLOCK_ID"=>$iblock_id, "ACTIVE"=>"Y", "IBLOCK_SECTION_ID"=>$section_id);
    $arItems = [];
    $res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize"=>500), $arSelect);
    while($arItem = $res->fetch()){
          $dbProperty = CIBlockElement::getProperty($arItem['IBLOCK_ID'], $arItem['ID'], array("sort", "asc"),array());
          while ($arProperty = $dbProperty->GetNext()) {
          if($arProperty['CODE'] == 'CITY'){
            $arItem['CITY'][] =  $arProperty['VALUE'];  // город 
          }   

          if($arProperty['CODE'] == 'PARTICIPANTS'){
                $element['PARTICIPANTS'][] = $arProperty['VALUE']; // участник 
            }
        }

      $arItems[] = $arItem;
    }   
    return $arItems;
}

// ОРГАНИЗАЦИИ И ПОЛЬЗОВАТЕЛИ
// пользователь
function getUserData($userId){ 
        $result = \Bitrix\Main\UserTable::getList(array(
            'select' => array(
              'ID',
              'LOGIN', 
              'EMAIL', 
              'NAME',
              'LAST_NAME',
              'SECOND_NAME',
              'UF_BILLING_ACCOUNT', 
              'UF_USER_TYPE', 
              'UF_GILD',
              'UF_LIMIT_FR', 
              'UF_LIMIT_TH', 
              'UF_LIMIT_WE', 
              'UF_LIMIT_TU', 
              'UF_LIMIT_MO',
              'UF_BALANCE_FR',
              'UF_BALANCE_TH',
              'UF_BALANCE_WE',
              'UF_BALANCE_TU',
              'UF_BALANCE_MO',
              'UF_DELIVERY',
              'UF_DELIVERY_ADDRESS'
            ), 
            'filter' => array('ID' => $userId)
        ));
        if($arUser = $result->fetch()){
            if($arUser['UF_USER_TYPE']){
                switch ($arUser['UF_USER_TYPE']) {
                  case '1':
                      $arUser['TYPE'] = 'user';
                      break;
                  case '2':
                      $arUser['TYPE'] = 'employee';
                      break;
                  case '3':
                      $arUser['TYPE'] = 'manager';
                      break;
                  case '4':
                      $arUser['TYPE'] = 'admin';
                      break;
                  default:
                      $arUser['TYPE'] = 'user';
                      break;
                }
            }

            if($arUser['UF_GILD']){
                $res = CIBlockElement::GetByID($arUser['UF_GILD']);
                if($ar_res = $res->GetNext()){
                    $arUser['FIRM'] = $ar_res;
                    $dbProperty = CIBlockElement::getProperty($ar_res['IBLOCK_ID'], $ar_res['ID'], array("sort", "asc"),array());
                    while ($arProperty = $dbProperty->GetNext()) {
                      if($arProperty['CODE'] == 'BALANCE'){
                        $arUser['BALANCE'] = $arProperty['VALUE'];
                      }
                    }
                }
            }

            if($arUser['UF_DELIVERY_ADDRESS']){
                $arUser['DELIVERY_ADDRESS'] = userDeliveryAddress($arUser['UF_DELIVERY_ADDRESS']);
            }
            
        }else{
            $arUser = array();
            $arUser['TYPE'] = 'user';
        }

      return $arUser;
}

// список пользователей организации
function getUsersByFirm($firmId){
      $usersRes = \Bitrix\Main\UserTable::getList(array(
            'select' => array(
              'ID',  
              'LOGIN', 
              'EMAIL', 
              'UF_BILLING_ACCOUNT', 
              'UF_USER_TYPE',
              'UF_GILD'
            ), 
            'filter' => array('UF_GILD' => $firmId) 
      ));
      $firm_users = [];
      while ($user = $usersRes->fetch()){
        $firm_users[] = $user;
      }
      return $firm_users;
}


// обновляем баланс организации 
function updateFirmAccount($sum, $firmID, $replace = false){
    $hlblock = HL\HighloadBlockTable::getById(HL_ACCOUNT)->fetch(); 
    $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
    $entity_data_class = $entity->getDataClass(); 
    $rsData = $entity_data_class::getList(array(
       "select" => array("ID", "UF_SUM_RUB"),
       "filter" => array("UF_GILD_ID"=>$firmID)  // Задаем параметры фильтра выборки
    ));
    while($arData = $rsData->Fetch()){
      if(!$replace){
        $sum = $arData['UF_SUM_RUB'] + $sum;
      }
      if($arData['ID']){
        $data = array("UF_SUM_RUB"=>$sum);
        $result = $entity_data_class::update($arData['ID'], $data); 
      }
    }
    return $sum;
}


function addFirmAccount($firmID, $firmName, $firmCode){
      $hlblock = HL\HighloadBlockTable::getById(HL_ACCOUNT)->fetch(); 
      $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
      $entity_data_class = $entity->getDataClass(); 
      $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
      $entity_data_class = $entity->getDataClass(); 
      $data = array(
          "UF_GILD_ID"=>$firmID, 
          "UF_TITLE"=>'Счет компании '.$firmName, 
          "UF_GILD_NAME"=>$firmName,
          "UF_XML_ID"=>$firmCode
      );
      $result = $entity_data_class::add($data);
}


function getFirmAccount($firmID){
    $hlblock = HL\HighloadBlockTable::getById(HL_ACCOUNT)->fetch(); 
    $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
    $entity_data_class = $entity->getDataClass(); 
    $rsData = $entity_data_class::getList(array(
       "select" => array("ID", "UF_SUM_RUB"),
       "filter" => array("UF_GILD_ID"=>$firmID)  // Задаем параметры фильтра выборки
    ));

    if($arData = $rsData->Fetch()){
        return $arData;
    }
}

// добавление новых организаций
// регистрация корпоративных пользователей
function makeUserManager($user_id, $gild_id){
    $user = new CUser;
    $rsUser = $user->GetByID($user_id);
    $arUser = $rsUser->Fetch();
    $result = false;
    if(!empty($arUser)){
        $fields = array(
          "UF_GILD" => $gild_id, 
          "UF_USER_TYPE" =>USER_TYPE_MANAGER
        );
        $result = $user->update($user_id, $fields);
    }
    else{
        // $result = array('message'=>'пользователь не найден');
    }
    return $result;
}


// выборка элементов инфоблока адреса доставки
function getDeliveryAddress($firm_id){
  $arSelect = array("ID", "NAME", "DETAIL_PAGE_URL", "CODE", "PROPERTY_*");
  $arFilter = array("IBLOCK_ID"=>DELIVERY_FIRM_ADDRESS, "=PROPERTY_FIRM"=>$firm_id);
  $arItems = [];
  $res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize"=>500), $arSelect);
  while($arItem = $res->fetch()){
      $arItems[] = $arItem; 
  }
  return $arItems;
}

function getUserFirm($firm_id){
  $result = false;
  $res = CIBlockElement::GetByID($firm_id);
  if($ar_res = $res->GetNext()){
    $result = $ar_res;
    $dbProperty = CIBlockElement::getProperty($ar_res['IBLOCK_ID'], $ar_res['ID'], array("sort", "asc"),array());
    while ($arProperty = $dbProperty->GetNext()) {
        if($arProperty['CODE'] == 'BALANCE'){
            $result['BALANCE'] = $arProperty['VALUE'];
        }
    }
  }
  return $result;
}

// адреc доставки пользователя
function userDeliveryAddress($deliveryID){
    $res = CIBlockElement::GetByID($deliveryID);
      if($ar_res = $res->GetNext()){
          $dbProperty = CIBlockElement::getProperty($ar_res['IBLOCK_ID'], $ar_res['ID'], array("sort", "asc"),array());
          while ($arProperty = $dbProperty->GetNext()) {
            $ar_res[$arProperty['CODE']] = $arProperty['VALUE'];
          }
      }
      return $ar_res;
}

// ЗАКАЗЫ

// заказы пользователя по его ID
function user_orders($usersID, $begin_date = null){
  if($begin_date){
    $filter = array('USER_ID' => $usersID, '>=DATE_INSERT'=>$begin_date);
  }else{
    $filter = array('USER_ID' => $usersID);
  }

  $rsOrders = \Bitrix\Sale\Order::getList([
        'filter' =>$filter,
        'order' => [
            'ID' => 'DESC'
        ]
    ]);
   
    $orders = [];
    while ($order = $rsOrders->fetch()){
      $orders[] = $order;
    }
    return $orders;
}

// получаем заказ пользователя
// по дням недели
function getOrderByDay($begin_date, $day){
    
    global $USER;
    $dbRes = \Bitrix\Sale\Order::getList([
        'select' => [
            "ID", 
        ],
        'filter' => [
            'USER_ID' => $USER->GetID(),
            'PROPERTY.ORDER_PROPS_ID' => 20,// 15 - ID свойства
            'PROPERTY.VALUE' => $day,
            '>=DATE_INSERT' => $begin_date
        ]
    ]);

    if($order = $dbRes->fetch()){
        return $order;
    }else{
        return false; 
    }
}

function getDayOrder($day){
    
    global $USER;
    $dbRes = \Bitrix\Sale\Order::getList([
        'select' => [
            "ID", 
        ],
        'filter' => [
            'USER_ID' => $USER->GetID(),
            'PROPERTY.ORDER_PROPS_ID' => 20,// 15 - ID свойства
            'PROPERTY.VALUE' => $day
        ]
    ]);

    if($order = $dbRes->fetch()){
        return $order;
    }else{
        return false; 
    }
}

function deliveryPrice($orderId){ 
    $order = \Bitrix\Sale\Order::load($orderId);
    $propertyCollection = $order->getPropertyCollection();
    $deliveryPriceProperty = $propertyCollection->getItemByOrderPropertyId(DELIVERY_PRICE);
    if($deliveryPriceProperty){
        $deliveryPrice = $deliveryPriceProperty->getValue();
        if(!empty($deliveryPrice)){
            return $deliveryPrice;
        }
    }
    return false;
}

// данные заказа текущего пользователя
function getOrderData($orderId){ 
      $order = \Bitrix\Sale\Order::load($orderId);

      $basket = $order->getBasket();
      $basketItems = $basket->getBasketItems();

      $products = array();
      foreach ($basketItems as $key => $basketItem) {
          $product = array();
          $product['ProductId'] = $basketItem->getProductId();  // ID товара
          $product['Price'] = $basketItem->getPrice(); // Цена за единицу
          $product['Quantity'] = $basketItem->getQuantity();  // Количество
          $product['FinalPrice'] = $basketItem->getFinalPrice();  // Сумма
          $product['Weight'] = $basketItem->getWeight(); // Вес
          $product['NAME'] = $basketItem->getField('NAME');
          $products[] = $product;
       }

      return $orderData = [
          'PersonTypeId'=> $order->getPersonTypeId(),// ID типа покупателя
          'UserId'=> $order->getUserId(), // ID пользователя
          'Price' => $order->getPrice(), // Сумма заказа
          'Paid' => $order->isPaid(), // true, если оплачен
          'Products' => $products // товары в заказе
      ];
}

// создаем заказ
function makeOrder($basket, $site, $userId, $day_week){
    $order = \Bitrix\Sale\Order::create($site, $userId);
    $currencyCode = \Bitrix\Currency\CurrencyManager::getBaseCurrency();
    $order->setField('CURRENCY', $currencyCode);
    $order->setBasket($basket);
    $order->doFinalAction(true);
    $propertyCollection = $order->getPropertyCollection();
    $somePropValue = $propertyCollection->getItemByOrderPropertyId(ORDER_PROPERTY_DAY);
    $somePropValue->setValue($day_week);
    $result = $order->save();
    return $orderId = $order->getId();
}

// сумма всех заказов
function sumAllOrders($userId, $begin_date = null){
    $orders = user_orders($userId, $begin_date);
    $sum = 0;
    foreach ($orders as $key => $order){
      $sum = $sum +  $order['PRICE'];
    }
    return $sum;
}

// список заказов для доставки по адресу
function ordersByAddress($deliveryAddress, $day = flase){
    if($day == flase){
        $day = substr(date("l"), 0, 2);}    
    $begin_date = date('d.m.Y', strtotime(date("d.m.Y"))); 
    $dbRes = \Bitrix\Sale\Order::getList([
        'select' => [
                "*", 
        ],
        'filter' => [
            '=PROPERTY_VAL.CODE' => 'DAY',
            '=PROPERTY_VAL.VALUE' => $day, 
            '=PROPERTY_VAL_2.CODE' => 'DELIVERY_ADDRESS',
            '=PROPERTY_VAL_2.VALUE' => $deliveryAddress,
            '>=DATE_INSERT' => new \Bitrix\Main\Type\DateTime(date_create_from_format('d.m.Y', $begin_date)->format($begin_date))
        ],
        'runtime' => [
            new \Bitrix\Main\Entity\ReferenceField(
                'PROPERTY_VAL',
                '\Bitrix\sale\Internals\OrderPropsValueTable',
                ["=this.ID" => "ref.ORDER_ID"],
                ["join_type"=>"left"]
            ),
            new \Bitrix\Main\Entity\ReferenceField(
                'PROPERTY_VAL_2',
                '\Bitrix\sale\Internals\OrderPropsValueTable',
                ["=this.ID" => "ref.ORDER_ID"],
                ["join_type"=>"left"]
            ),
        ]
    ]);
    $orders = [];
    while ($order = $dbRes->fetch()){
        $orders[] = $order;
    }
    return $orders;
}

// проверка времени текущего дня
function currentTime($deliveryTime){
    if($deliveryTime){
        $currentTime = date('H:i');     
        $currentDateTime = strtotime(date('d.m.Y')  ." ". $currentTime); 
        $stopDateTime = strtotime(date('d.m.Y')  ." ".$deliveryTime);
        if($stopDateTime <= $currentDateTime){
            return true;
        }
    }
    return false;
}


function currentTimeCuisine($openingTime, $closingTime){
    // if($openingTime && $closingTime){
        $currentTime = date('H:i');     
        $currentDateTime = strtotime(date('d.m.Y')  ." ". $currentTime); 
        $openingTime = strtotime(date('d.m.Y')  ." ".$openingTime);
        $closingTime = strtotime(date('d.m.Y')  ." ".$closingTime);
        
        if($openingTime <= $currentDateTime && $closingTime > $currentDateTime){
            return true;
        }else{
          return false;
        }
    // }
    return false;
}



