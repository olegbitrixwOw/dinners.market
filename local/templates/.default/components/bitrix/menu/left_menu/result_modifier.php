<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$bWasSelected = false;
$selectedItem = 0;
$depth = 1;

foreach ($arResult as $i => $arMenu) {
	if ($arMenu['SELECTED'] == true) {
		$bWasSelected = true;
		$selectedItem = $i;
		$depth = $arMenu["DEPTH_LEVEL"];
		break;
	}
		
}

if ($bWasSelected) {
	for($i=$selectedItem; $i >= 0; $i--){
		if(isset($arResult[$i]) && $arResult[$i]["DEPTH_LEVEL"] < $depth){
			$depth--;
			$arResult[$i]["SELECTED"] = true;
		}
	}
}

// текущий адрес страницы
$cur_page = getUrl();

// спискок записей из инфоблока Кухни
// список будет добавляться из параметра Кухни !!!
$cuisines = getCuisines();
$arParams['CUISINE'] = false;
foreach ($cuisines as $key => $cuisine) {
	if (strpos($cur_page, $cuisine['CODE'])){
		$arParams['CUISINE'] = $cuisine['CODE'];
		break;
	}
}

