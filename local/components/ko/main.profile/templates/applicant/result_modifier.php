<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
// use Bitrix\Main;

$arResult["UF_LIMIT"] = false;
foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField){
	switch ($FIELD_NAME) {
		case 'UF_BLOCKED':
			$arResult["UF_BLOCKED"] = $arUserField;
		break;

		case 'UF_DELIVERY':
			$arResult["UF_DELIVERY"] = $arUserField;
		break;
		
		default:
			# code...
		break;
	}
}
// pr($arResult['arUser']['UF_GILD']);
$arResult['DELIVERY'] = getDeliveryAddress($arResult['arUser']['UF_GILD']);
// pr($arResult['DELIVERY']);
// pr($arResult);