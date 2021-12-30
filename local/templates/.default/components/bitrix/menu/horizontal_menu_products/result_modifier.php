<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// текущая страница
use \Bitrix\Main\Application;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
$cur_page = $uri->getPath(false);

// pr($cur_page);

$bWasSelected = false;
$selectedItem = 0;
$depth = 1;

$current_day = false;
if(isset($_REQUEST["day_week"])){
    $current_day = htmlentities($_REQUEST["day_week"]);
}

if ($bWasSelected) {
	for($i=$selectedItem; $i >= 0; $i--){
		if(isset($arResult[$i]) && $arResult[$i]["DEPTH_LEVEL"] < $depth){
			$depth--;
			$arResult[$i]["SELECTED"] = true;
		}
	}
}
foreach($arResult as $i => $arMenu){
 		$code  = $arMenu['LINK'];
		switch ($code) {
			case '/eshberi/rolls/':
				$class_css = 'icon-sushi';
				break;
			case '/eshberi/garniry/':
				$class_css = 'icon-garniry';
				break;
			case '/eshberi/deserty/':
				$class_css = 'icon-dessert';
				break;
			case '/eshberi/drinks/':
				$class_css = 'icon-drink';
				break;
			case '/eshberi/soups/':
				$class_css = 'icon-soup';
				break;
			case '/eshberi/salads/':
				$class_css = 'icon-salad';
				break;

			case '/eshberi/pastywok/':
				$class_css = 'icon-wok';
				break;

			case '/eshberi/sendvichi_i_zakuski/':
				$class_css = 'icon-burger';
				break;

			case '/eshberi/freshi_smuzi_limonad/':
				$class_css = 'icon-smoothie';
				break;

			case '/eshberi/hot_dishes/':
				$class_css = 'icon-hot';
				break;

			default:
				$class_css = 'icon-cake';
				break;
		}

	$arResult[$i]['CLASS_CSS'] = $class_css;

	if($current_day){
		$arResult[$i]['LINK'] = $arMenu['LINK'].'?day_week='.$current_day;
	}

	if($arMenu['LINK'] == $cur_page){
		$arResult[$i]['SELECTED'] = true;
	}
}

// здесь вывести спискок записей из инфоблока Кухни
// список будет добавляться из параметра Кухни

if (strpos($cur_page, 'kyoto')) {
   	$arResult['CUISINE_ROOT'] = '/kyoto/';
   	$arResult['CUISINE_LOGO'] = '/include/header/menu_logo_2.php';
}

elseif (strpos($cur_page, 'eshberi')) {
   	$arResult['CUISINE_ROOT'] = '/eshberi/';
   	$arResult['CUISINE_LOGO'] = '/include/header/menu_logo_1.php';
}

elseif (strpos($cur_page, 'palermo')) {
   	$arResult['CUISINE_ROOT'] = '/palermo/';
   	$arResult['CUISINE_LOGO'] = '/include/header/menu_logo_3.php';
}
else{
	$arResult['CUISINE_ROOT'] = '/eshberi/';
   	$arResult['CUISINE_LOGO'] = '/include/header/menu_logo_1.php';
}

if($cur_page == $arResult['CUISINE_ROOT']){
	$arResult['ALL_DISHES'] = true;
}

$arResult['cur_page'] = $cur_page;

// pr($cur_page);
// pr($arResult['CUISINE_ROOT']);
// pr($arResult);

// foreach($arResult as $arItem){
// 	pr($arItem);
// }