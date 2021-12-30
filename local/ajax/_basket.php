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

if(isset($_REQUEST['id']) && isset($_REQUEST['action'])){
	
	$productId = intVal($_REQUEST['id']);
	$quantity  = intVal($_REQUEST['quantity']);
	$action    = htmlspecialcharsEx($_REQUEST['action']);
	$day_week = htmlspecialcharsEx($_REQUEST['day_week']);
	$lid = Bitrix\Main\Context::getCurrent()->getSite();


	if($day_week){		
		$lid = $day_week;
	}

	if($action == 'ADD2BASKET'){

		$data = [
			'ID'=>$productId,
			'SUCCESS'=>0,
			'STATUS' => 'ERROR',
			'ACTION'=>$action,
			'DAY_WEEK'=>$day_week,
			'QUANTITY'=>$quantity,
		];

		$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $lid);
		$supplemented = false;

		$basketRes = Sale\Internals\BasketTable::getList(array(
		    'filter' => array(
		        'FUSER_ID' => Sale\Fuser::getId(), 
		        'ORDER_ID' => null,
		        'LID' => $lid,
		        'CAN_BUY' => 'Y',
		    )
		));

		while ($item = $basketRes->fetch()) {
		    if($item['PRODUCT_ID'] == $productId){
		        $basketItem = $basket->getItemById($item['ID']);
		        $basketItem->setField('QUANTITY', $item['QUANTITY']+$quantity);
		        $result = $basket->save();
		        $supplemented = true;
		        $message = 'Вы изменили количество товара';
		        break; 
		    }
		}

		if($supplemented == false){
		    $item = $basket->createItem('catalog', $productId);
		    $item->setFields(array(
		        'QUANTITY' => $quantity,
		        'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
		        'LID' => $lid,
		        'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
		    ));
		    $result = $basket->save();
		    $message = 'Товар успешно добавлен в корзину';
		}

		if($result->isSuccess()){
			$data['SUCCESS'] = 1;
			$data['STATUS'] = 'OK';
			$data['MESSAGE'] = $message;
		}else{
			$data['ERRORS'] = $result->getErrorMessages();

		}

		// echo json_encode($result);
		echo json_encode($data);
	}
	

}


