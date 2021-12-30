<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Main\Application,
    Bitrix\Sale\DiscountCouponsManager;

global $USER;

$data = [];


if(isset($_REQUEST['delete_all_baskets'])){
	$SITE = Bitrix\Main\Context::getCurrent()->getSite(); // текущий сайт
	$days_week = ['Mo', 'Tu', 'We', 'Th' , 'Fr', 'Sa', 'Su'];
	$orders = [];
	foreach ($days_week as $key => $lid){
		$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $lid); // корзина дня
		if($basket->getQuantityList()){
		    $basketItems = $basket->getBasketItems();
		    foreach ($basketItems as $key => $item) {
		        $basket->getItemById($item->getId())->delete(); 
		    }
		    $basket->save();
		}
	}
	$data['MESSAGE'] = 'корзины удалены';
	$data['STATUS'] = 'OK';
}else{
	$data['STATUS'] = 'ERROR';
	$data['MESSAGE'] = 'ошибка в Ajax запросе';
}

echo json_encode($data);
die();



