<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
// use Bitrix\Main;

$arResult["UF_LIMIT"] = false;
foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField){

	switch ($FIELD_NAME) {
		case 'UF_LIMIT_MO':
			$arResult["UF_LIMIT_MO"] = $arUserField;
			break;
		case 'UF_LIMIT_TU':
			$arResult["UF_LIMIT_TU"] = $arUserField;
			break;
		case 'UF_LIMIT_WE':
			$arResult["UF_LIMIT_WE"] = $arUserField;
			break;
		case 'UF_LIMIT_TH':
			$arResult["UF_LIMIT_TH"] = $arUserField;
			break;
		case 'UF_LIMIT_FR':
			$arResult["UF_LIMIT_FR"] = $arUserField;
			break;
		case 'UF_LIMIT_SU':
			$arResult["UF_LIMIT_SU"] = $arUserField;
			break;
		case 'UF_LIMIT_SA':
			$arResult["UF_LIMIT_SA"] = $arUserField;
			break;
		case 'UF_DELIVERY':
			$arResult["UF_DELIVERY"] = $arUserField;
			break;

		case 'UF_DELIVERY_ADDRESS':
			$arResult["UF_DELIVERY_ADDRESS"] = $arUserField;
			break;

		case 'UF_BLOCKED':
			    $arResult["UF_BLOCKED"] = $arUserField;
			break;
		
		default:
			# code...
			break;
	}
}

$arResult["DELIVERY_ADDRESS"] = userDeliveryAddress($arResult["UF_DELIVERY_ADDRESS"]["VALUE"]);
$arResult['DELIVERY'] = getDeliveryAddress($arResult['arUser']['UF_GILD']);
