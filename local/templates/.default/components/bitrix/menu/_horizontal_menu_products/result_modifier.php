<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// текущая страница
use \Bitrix\Main\Application;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
$cur_page = $uri->getPath(false);

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
	// pr($arMenu);
	$code = substr($arMenu['LINK'], 8);
		switch ($code) {
			case '/rolls/':
				$class_css = 'icon-sushi';
				break;
			case '/garniry/':
				$class_css = 'icon-garniry';
				break;
			case '/deserty/':
				$class_css = 'icon-dessert';
				break;
			case '/drinks/':
				$class_css = 'icon-drink';
				break;
			case '/soups/':
				$class_css = 'icon-soup';
				break;
			case '/salads/':
				$class_css = 'icon-salad';
				break;

			case '/pastywok/':
				$class_css = 'icon-wok';
				break;

			case '/sendvichi_i_zakuski/':
				$class_css = 'icon-burger';
				break;

			case '/freshi_smuzi_limonad/':
				$class_css = 'icon-smoothie';
				break;

			case '/hot_dishes/':
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

if($cur_page == '/' ){
	$arResult['ALL_DISHES'] = true;
}
