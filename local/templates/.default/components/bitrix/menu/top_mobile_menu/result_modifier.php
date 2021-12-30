<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// текущая страница
use \Bitrix\Main\Application;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
$cur_page = $uri->getPath(false);

$bWasSelected = false;
$selectedItem = 0;
$depth = 1;

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

	if($arMenu['LINK'] == $cur_page){
		$arResult[$i]['SELECTED'] = true;
	}
}

if($cur_page == '/' ){
	$arResult['ALL_DISHES'] = true;
}

foreach($arResult as $i => $arMenu){
	// pr($arMenu);
}
