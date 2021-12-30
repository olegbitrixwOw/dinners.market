<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Sale;
foreach ($arResult['ORDERS'] as $key => $order) {
		// получаем данные пользователя который оформил заказ
		$obj = Sale\Order::loadByAccountNumber($order['ORDER']['ID']);
		$arUser = getUserData($obj->getUserId());
		$arResult['ORDERS'][$key]['USER'] = $arUser;
		$arResult['ORDERS'][$key]['DELIVERY_ADDRESS'] = $arUser['DELIVERY_ADDRESS']['NAME'];
}

// foreach ($arResult['ORDERS'] as $key => $order) {
// 		if($order['ORDER']['ID'] == 275){
// 			pr($order['DELIVERY_ADDRESS']);
// 		}
// }
?>