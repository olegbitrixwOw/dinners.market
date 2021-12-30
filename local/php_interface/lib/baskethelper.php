<?
use Bitrix\Main, Bitrix\Sale, Bitrix\Main\Application, Bitrix\Main\Entity;
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Loader;
Bitrix\Main\Loader::includeModule('iblock');

class BasketHelper
{
      // получаем данные пользователя
      public static function getUserData($userId){ 
            $result = \Bitrix\Main\UserTable::getList(array(
                'select' => array(
                  'ID',
                  'LOGIN', 
                  'EMAIL', 
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
                  'UF_DELIVERY'
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
                
            }else{ 
                $arUser = array();
                $arUser['TYPE'] = 'user';
            }

          return $arUser;
    }

    public static function getDayWeek(){
        if(isset($_REQUEST["day_week"])){
            $day_week =  htmlentities($_REQUEST["day_week"]);
        }else{
            $day_week = substr(date("l"), 0, 2);
        }
        return $day_week;
    }

    public static function getDayName($lid){
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

    public static function weekDate($begin_date){
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

        return $week_date;
    }

    public static function beginDate(){
        $begin_date = date('d.m.Y', strtotime(date("d.m.Y")));
        $objDateTime = new DateTime();
        
        if($objDateTime->format("H") >= DATE_SHIFT){

            // здесь чистим корзину текущего дня
            $day = substr(date("l"), 0, 2);
            self::removeBasket($day);

            $today = strtotime($begin_date);
            return $updete_begin_date = date('d.m.Y', strtotime("+1 day", $today));
        }

        return $begin_date;
    }

    // лимиты пользователя по дням недели
    public static function getUserDayLimits($userId){
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

    // сумма всех корзин
    public static function sumAllBaskets(){
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

    // сумма текущей корзины 
    public static function sumCurBasket($day){
        $SITE = Bitrix\Main\Context::getCurrent()->getSite(); // текущий сайт
        $sum = 0;
            $basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), $day);
            if($basket->getQuantityList()){
                $sum = $sum + $basket->getPrice();
            }
        return $sum;
    }
   
    // количество товаров в корзине
    public static function basketNumProducts($day){
        $basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), $day);
        return count($basket->getQuantityList());
    }

    // очищаем корзину текущего дня вечером
    public static function removeBasket($day){
         $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $day);
        if($basket->getQuantityList()){
          $basketItems = $basket->getBasketItems();
          foreach ($basketItems as $key => $item) {
              $basket->getItemById($item->getId())->delete();
          }
          $basket->save();
        }
    }
}
?>
