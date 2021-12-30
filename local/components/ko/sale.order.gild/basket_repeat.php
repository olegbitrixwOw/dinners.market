<?
define("NO_AGENT_CHECK", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$APPLICATION->RestartBuffer();

if(!empty($_REQUEST['id'])){

	$order_id = (int)$_REQUEST['id'];
  $day_week = htmlentities($_REQUEST['day_week']);
  if($day_week){
    $lid = $day_week;
  }else{
    $lid = \Bitrix\Main\Context::getCurrent()->getSite();
  }
	$messmate_basket = \Bitrix\Sale\Order::load($order_id)->getBasket();
    $basketList = $messmate_basket->getListOfFormatText();
    $basketItems = $messmate_basket->getBasketItems();

    // Получение корзины для текущего пользователя
    $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
       \Bitrix\Sale\Fuser::getId(), 
       $lid
    ); 

    foreach ($basketItems as $key => $basketItem) {
        // добавление товара
        $item = $basket->createItem('catalog', $basketItem->getProductId());
        $item->setFields([
          'QUANTITY' => $basketItem->getQuantity(),
          'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
          'LID' => $lid,
          'PRODUCT_PROVIDER_CLASS' => \Bitrix\Catalog\Product\Basket::getDefaultProviderName() ,
       ]);
    }
    
    if($basket->save()){
    	$result = array('success' => 1); 
    }
	
}else{
	$result = array("На сервер пришел не валидный запрос!");
}

header('Content-Type: application/json');
echo json_encode($result);
die;