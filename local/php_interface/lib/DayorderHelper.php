<?
use Bitrix\Main;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use DayorderTable; 

// Содержит вспомогательные методы для работы с HlbookTable
class DayorderHelper {

    // получаем запись из ORM
    public static function GetElement($deliveryID) {
        return $element  = DayorderTable::getList(array(
            'select'  => array('*'), // имена полей, которые необходимо получить в результате
            'filter'  => array('DELIVERY_ADDRESS_ID' => $deliveryID, 'PUBLISH_DATE' => date("d.m.Y")), // описание фильтра для WHERE и HAVING
            'limit'   => 1, // количество записей
        ))->fetch();
    }
    
    // добавляем запись в ORM
    public static function AddElement($elFields) { 
        return $element = DayorderTable::add(array(
            'ORGANIZATION_ID' => (int)$elFields['ORGANIZATION_ID'],
            'DELIVERY_ADDRESS_ID'=> (int)$elFields['DELIVERY_ADDRESS_ID'],
            'TITLE' => $elFields['TITLE'],
            'SUM' =>$elFields['SUM'],
            'MIN_SUM' =>$elFields['MIN_SUM'],
            'DELIVERY_COST'=> $elFields['DELIVERY_COST'],
            'DAY'=> $elFields['DAY'],
            'TIME'=> $elFields['TIME'],
            'PUBLISH_DATE'=> new Type\Date()
        ));
    }

    // удаляем все записи в ORM
    public static function DeleteRows(){
        $connection = Application::getConnection();
        return $result = $connection->truncateTable(DayorderTable::getTableName());
    }

    public static function UpdateElement($elFields) {
        $element = self::GetElement($elFields['DELIVERY_ADDRESS_ID']);
        $sum = (float)$element['SUM'] + (float)$elFields['SUM'];
        $result = DayorderTable::update($element['ID'], array(
            'SUM'=> $sum,
        ));
        $elFields['SUM'] = $sum;
        return $elFields;
    }

    public static function checkElement($elFields){
        $element = self::GetElement($elFields['DELIVERY_ADDRESS_ID']);
        if(empty($element)){
            $element = self::AddElement($elFields);
        }
        else{
            $element = self::UpdateElement($elFields);
        }      
        return $element;
    }

    public static function getOrders($time){
        return $elements  = DayorderTable::getList(array(
                'select'  => array('*'), // имена полей, которые необходимо получить в результате
                'filter'  => array(
                'TIME'=> $time,
                'DONE'=> 0,
                'PUBLISH_DATE' => date("d.m.Y")
            ), // описание фильтра для WHERE и HAVING
           // 'limit'   => 1000, // количество записей
        ))->fetchAll();
    }

    public static function checkTime($time){
        $orders = self::getOrders($time);
        if($orders){
            foreach ($orders as $key => $order) {
                pr($order);
                self::done($order);
            }
        }
    }

    public static function done($elFields) {
        $result = DayorderTable::update($elFields['ID'], array(
            'DONE'=> 1,
        ));
        return $result;
    }


    public static function showOrderSum($deliveryID) {      
        $element  = DayorderTable::getList(array(
            'select'  => array('*'), 
            'filter'  => array('DELIVERY_ADDRESS_ID' => $deliveryID, 'PUBLISH_DATE' => date("d.m.Y"), 'DONE'=>1), 
            'limit'   => 1, 
        ))->fetch();

        if($element){
            $delivery =  $element['DELIVERY_COST'];
            if($element['MIN_SUM']<$element['SUM']){
                $delivery = 0;
            }
        
            return $result = array(
                'DELIVERY_COST' =>$delivery,
                'ORDER_SUM'=> $element['SUM'] + $delivery
            );
        }else{
            return false;
        }

    }
}
