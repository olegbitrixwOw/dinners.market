<?
use Bitrix\Main;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

// ORM где хранятся лог добавленных/измененных/удаленных элементов
class DayorderTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'dayorder_orm';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array( 
                'primary' => true,
                'autocomplete' => true 
            )), // ID
            new Entity\IntegerField('ORGANIZATION_ID'), 
            new Entity\IntegerField('DELIVERY_ADDRESS_ID'), 
            new Entity\StringField('TITLE'),
            new Entity\StringField('SUM'),
            new Entity\StringField('MIN_SUM'),
            new Entity\StringField('DELIVERY_COST'),
            new Entity\StringField('DAY'),
            new Entity\StringField('TIME'),
            new Main\Entity\BooleanField('DONE', ['values' => [0, 1], 'default_value' => 0]),
            new Entity\DateField('PUBLISH_DATE')    
        );
    } 
}
?>
