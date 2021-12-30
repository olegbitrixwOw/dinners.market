<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
// require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

// use Bitrix\Main,
//     Bitrix\Main\Loader,
//     Bitrix\Main\Config\Option,
//     Bitrix\Sale,
//     Bitrix\Sale\Order,
//     Bitrix\Main\Application,
//     Bitrix\Sale\DiscountCouponsManager;
// global $USER; 

if(isset($_REQUEST['basket_hide'])){ 
	session_start();
	$_SESSION['basket_hide'] = (int)$_POST['basket_hide'];
	echo $_SESSION['basket_hide'];
	exit();
}


if(isset($_REQUEST['mobile_basket_hide'])){ 
	session_start();
	$_SESSION['mobile_basket_hide'] = (int)$_POST['mobile_basket_hide'];
	echo $_SESSION['mobile_basket_hide'];
	exit();
}