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

if(isset($_REQUEST['buy_all_baskets'])){

	$SITE = Bitrix\Main\Context::getCurrent()->getSite(); // текущий сайт
	$days_week = ['Mo', 'Tu', 'We', 'Th' , 'Fr', 'Sa', 'Su'];
	$arUser = getUserData($USER->GetID());

	// если пользователь корпоративный 
	if($arUser['TYPE'] == 'employee'){
		$day =  substr(date("l"), 0, 2); // текущий день недели

		// если время до которого можно делать заказ закончилось
		$final_time = currentTime($arUser['DELIVERY_ADDRESS']['TIME']);
		if($final_time){
			// удаляем корзину
			$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $day); // корзина дня
			if($basket->getQuantityList()){
				$basketItems = $basket->getBasketItems();
				foreach ($basketItems as $key => $item) {
				    $basket->getItemById($item->getId())->delete();
				}
				$basket->save();
			}
		}

		$account = getFirmAccount($arUser['FIRM']['ID']);
		$sum = sumAllBaskets();
		if($account['UF_SUM_RUB'] < $sum){
			$data['MESSAGE'] = 'Вы не можите сделать заказ, так как сумма вашего заказа больше суммы на балансе вашей организации.';
			$data['ORDERS'] = 0;
			$data['REDIRECT'] = false;
			echo json_encode($data);
			die();
		}
	}

	// получаем заказы корзин
	$orders = [];
	foreach ($days_week as $key => $lid){
		$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $lid); // корзина дня
		if($basket->getQuantityList()){
			$orderId = makeOrder($basket, $SITE, $USER->GetID(), $lid); // создаем заказ
			$orderData = getOrderData($orderId); // получаем заказ
			$orders[] = $orderData;
		}
	}
	if(count($orders) == 0) {
		$data['MESSAGE'] = 'заказов нет';
		$data['ORDERS'] = 0;
	}
	else{
	    $data['MESSAGE'] = 'заказы';
		$data['ORDERS'] = $orders;
	}
	$data['REDIRECT'] = true;
	$data['STATUS'] = 'OK';
}else{
	$data['STATUS'] = 'ERROR';
	$data['MESSAGE'] = 'ошибка в запросе';
}

echo json_encode($data);
die();
