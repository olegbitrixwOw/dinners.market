<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("iblock")) die();

global $USER;
if(!$USER->IsAuthorized()){
	ShowError('Вы не авторизованы!');
	return false;
}

include_once 'functions.php';

if(isset($_POST['AJAX'])){
	global $APPLICATION;
	$APPLICATION->RestartBuffer();
}

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

if(!isset($arParams["PAGE_SIZE"]))
	$arParams["PAGE_SIZE"] = 10;

if(isset($_POST['SORT']))
	$arParams['SORT'] = [$_POST['SORT'][0] => $_POST['SORT'][1]];
else
	$arParams['SORT'] = ["DATE_INSERT" => "ASC"];

$filter = ['ID' => $USER->GetID()];
$order = ['sort' => 'asc'];
$tmp = 'sort';
$rsUser = CUser::GetList($order, $tmp, $filter);
if($arUser = $rsUser->Fetch()){
	$arResult['USER'] = $arUser;
	$arResult['USER']['FULL_NAME'] = $USER->GetFullName();

	$arParams["PAGE"] = isset($_POST['PAGE']) ? (int)$_POST['PAGE'] : 1;

	$arNav = [
		"iNumPage" => $arParams["PAGE"],
		"nPageSize" => $arParams["PAGE_SIZE"]
	];

	$arFilter = ["USER_ID" => $arUser['ID']];

	$arResult['ALL_ELEMENTS_COUNT'] = 0;
	$response = CSaleOrder::GetList([], $arFilter, false);
	while ($row = $response->GetNext())
		$arResult['ALL_ELEMENTS_COUNT']++;

	if($this->StartResultCache(false, [$arUser['ID'], $arResult['ALL_ELEMENTS_COUNT'], $arNav])) {
		
		$rsSales = CSaleOrder::GetList($arParams['SORT'], $arFilter, false, $arNav);
		while ($row = $rsSales->Fetch()) {
			$arSale = [
				'ID'            => $row['ID'],
				'NAME'          => orderName($row['ID']),
				'DELIVERY'      => $row['ALLOW_DELIVERY'],
				'PAYED'         => orderPayed($row['PAYED']),
				// 'STATUS'        => OrderStatus::get($row['STATUS_ID']),
				'PRICE'         => $row['PRICE'],
				'DESCRIPTION'   => $row['ADDITIONAL_INFO']
			];

			$products =[];
			$dbItemsInOrder = CSaleBasket::GetList(["ID" => "ASC"], ["ORDER_ID" => $row['ID']]);
			while ($ItemsInOrder = $dbItemsInOrder->Fetch()) {
				$arSale['PRODUCTS']['NAMEs'][$ItemsInOrder['ID']] = $ItemsInOrder["NAME"];
				$arSale['PRODUCTS']['PRICEs'][$ItemsInOrder['ID']] = (float)$ItemsInOrder['PRICE'];
				$arSale['PRODUCTS']['QUANTITIES'][$ItemsInOrder['ID']] = $ItemsInOrder["QUANTITY"];
				$arSale['PRODUCTS']['FULL_PRICEs'][$ItemsInOrder['ID']] = (float)$ItemsInOrder['PRICE'] * (int)$ItemsInOrder['QUANTITY'];

				$section = '';
				$sections = CIBlockElement::GetElementGroups($ItemsInOrder["PRODUCT_ID"], true, ['IBLOCK_SECTION_ID']);
				if($sections)
					$section = $sections->Fetch()['IBLOCK_SECTION_ID'];

				$arSale['PRODUCTS']['DETAIL_URLs'][$ItemsInOrder['ID']] = CATALOG_PATH . $section . '/' . $ItemsInOrder['ID'];
			}


			$arResult['SALES'][] = $arSale;
		}

		if(isset($_POST['AJAX']) && $_POST['AJAX'] == 'PAGE')
			$arResult['SHOW_ELEMENTS_COUNT'] = $arParams["PAGE"] * $arParams["PAGE_SIZE"];
		else
			$arResult['SHOW_ELEMENTS_COUNT'] = count($arResult['SALES']);

		$this->IncludeComponentTemplate();
	}

}else{
	ShowError('Пользователь не найден');
	return false;
}

if(isset($_POST['AJAX']))
	die(); 
