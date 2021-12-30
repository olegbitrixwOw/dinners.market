<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
function orderName($id){
	return 'Заказ №' . $id;
}

function orderPayed($flag){
	if($flag == 'Y')
		return 'Оплачен <i class="paid"></i>';
	else
		return 'Не оплачен <i class="not-paid"></i>';
}

function orderByUser($user_id){
	
	$rsOrders = \Bitrix\Sale\Order::getList([
		'filter' => ['USER_ID' => $user_id],
		'order' => ['ID' => 'DESC']
	]);
		    
	// $arResult['SALES'] = [];
	$sales = [];
	
	while ($order = $rsOrders->fetch()){
			    	
		$products = [];
		$basket = \Bitrix\Sale\Order::load($order["ID"])->getBasket();
		$basketList = $basket->getListOfFormatText();
		$basketItems = $basket->getBasketItems(); 
					
		foreach ($basketItems as $key => $basketItem) {
			$product = [];
			$product['WEIGHT'] = $basketItem->getWeight();
			$product['PRODUCT_ID'] = $basketItem->getProductId();
			$product['PRICE'] = $basketItem->getPrice();
			$product['FINALPRICE'] = $basketItem->getFinalPrice();
			$product['NAME'] = $basketItem->getField('NAME');
			$product['QUANTITY']  = $basketItem->getQuantity();
			$product['DETAIL_PAGE_URL'] = $basketItem->getField('DETAIL_PAGE_URL');
			$products[] = $product;

		}

		// var_dump($products);

		$arSale = [
			'ID'=> $order['ID'],
			'NAME'=> orderName($order['ID']),
			'DELIVERY'=> $order['ALLOW_DELIVERY'],
			'PAYED'=> orderPayed($order['PAYED']),
			'PRODUCTS'=> $products,
			'PRICE'=> $order['PRICE'],
			'DESCRIPTION'=> $order['ADDITIONAL_INFO']
		];

		// $arResult['SALES'][] = $arSale;
		$sales[] = $arSale;

		// pr($order);
	}

	return $sales;
}
