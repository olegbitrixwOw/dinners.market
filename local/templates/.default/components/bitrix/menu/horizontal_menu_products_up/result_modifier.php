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

$CUISINE = false;

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

	if($arMenu['PARAMS']['MENU_CLASS_CSS']){
		$arResult[$i]['MENU_CLASS_CSS'] = $arMenu['PARAMS']['MENU_CLASS_CSS'];
	}

	if($arMenu['PARAMS']['MENU_PICTURE']){
		$arResult[$i]['MENU_PICTURE'] = CFile::GetPath($arMenu['PARAMS']['MENU_PICTURE']);
	}

	if($arMenu['PARAMS']['MENU_PICTURE_HOVER']){
		$arResult[$i]['MENU_PICTURE_HOVER'] = CFile::GetPath($arMenu['PARAMS']['MENU_PICTURE_HOVER']);
	}


	if($current_day){
		$arResult[$i]['LINK'] = $arMenu['LINK'].'?day_week='.$current_day;
	}

	if($arMenu['LINK'] == $cur_page){
		$arResult[$i]['SELECTED'] = true;
	}

}


if($arResult[0]['PARAMS']['CUISINE_PARAMS']){
	$arResult['CUISINE_ROOT'] = $arResult[0]['PARAMS']['CUISINE_PARAMS']['CUISINE_ROOT'];
	$arResult['CUISINE_LOGO'] = CFile::GetPath($arResult[0]['PARAMS']['CUISINE_PARAMS']['CUISINE_LOGO']);
}

if($cur_page == '/'.$arResult['CUISINE_ROOT'].'/'){
	$arResult['ALL_DISHES'] = true;
}

$arResult['cur_page'] = $cur_page;


// pr($arResult);
