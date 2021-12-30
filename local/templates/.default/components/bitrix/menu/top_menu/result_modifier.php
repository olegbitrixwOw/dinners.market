<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// текущий адрес страницы
$cur_page = getUrl();

// спискок записей из инфоблока Кухни
// список будет добавляться из параметра Кухни !!!
$cuisines = getCuisines();
$arParams['CUISINE_NEWS'] = false;
foreach ($cuisines as $key => $cuisine) {
	if (strpos($cur_page, $cuisine['CODE'])){
		$arParams['CUISINE'] = $cuisine['CODE'];
		break;
	}
}

