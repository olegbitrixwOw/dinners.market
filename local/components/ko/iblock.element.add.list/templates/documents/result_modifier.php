<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */
// pr($arParams['FIRM_ID']);
$sort = array('ID' => 'DESC');
$arFilter =  array('IBLOCK_ID' => IBLOCK_BALANCE,'=PROPERTY_ORGANIZATION'=>$arParams['FIRM_ID']);
$arSelect = array('ID', 'NAME', 'PROPERTY_ORGANIZATION');
$res = CIBlockElement::GetList($sort, $arFilter, false, array("nPageSize"=>1), $arSelect);
while($arItem =  $res->fetch()) {
	$arResult['RECENT'] = $arItem;
}

// pr($arResult['RECENT']['ID']);

// foreach ($arResult['ELEMENTS'] as $key => $value) {
// 	pr($value['ID']);
// }

