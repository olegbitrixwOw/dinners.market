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
if(isset($_REQUEST['get_sum_baskets'])){
	$data['STATUS'] = 'OK';
	$data['SUM_BASKETS'] = sumAllBaskets();

}
elseif(isset($_REQUEST['current_basket'])){
        $lid = htmlspecialchars($_REQUEST['current_basket']);
        $data['STATUS'] = 'OK';
        $data['SUM_CURRENT_BASKET'] = sumCurBasket($lid);
}
else{
	$data['STATUS'] = 'ERROR';
	$data['MESSAGE'] = 'ошибка в Ajax запросе';
}
echo json_encode($data);

