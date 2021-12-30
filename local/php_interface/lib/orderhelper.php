<?
use Bitrix\Main, Bitrix\Sale, Bitrix\Main\Application, Bitrix\Main\Entity;
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Loader;
Bitrix\Main\Loader::includeModule('iblock');

class OrderHelper
{
    // получаем заказ пользователя
    // по дням недели
     public static function getOrderByDay($begin_date, $day){
        
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

    public static function getDayOrder($day){
        
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

    // данные заказа текущего пользователя
    public static function getOrderData($orderId){
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
    public static function makeOrder($basket, $site, $userId, $day_week){
        $order = \Bitrix\Sale\Order::create($site, $userId);
        $currencyCode = \Bitrix\Currency\CurrencyManager::getBaseCurrency();
        $order->setField('CURRENCY', $currencyCode);
        $order->setBasket($basket);
        $order->doFinalAction(true);
        $propertyCollection = $order->getPropertyCollection();
        $somePropValue = $propertyCollection->getItemByOrderPropertyId('20');
        $somePropValue->setValue($day_week);
        $result = $order->save();
        return $orderId = $order->getId();
    }

    // сумма всех заказов
    public static function sumAllOrders($userId, $begin_date = null){
        $orders = user_orders($userId, $begin_date);
        $sum = 0;
        foreach ($orders as $key => $order){
          $sum = $sum +  $order['PRICE'];
        }
        return $sum;
    }
}
?>
