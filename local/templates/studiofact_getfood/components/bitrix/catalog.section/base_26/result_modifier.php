<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
// pr($arParams['PAGE_ELEMENT_COUNT']);
// pr($arParams['HIDE_NOT_AVAILABLE']);
foreach ($arResult['ITEMS'] as $key => $item){
	if(in_array($arParams['DAY_WEEK'], $item['DISPLAY_PROPERTIES']['DAY_OF_WEEK']['VALUE_XML_ID'])){
		unset($arResult['ITEMS'][$key]);
		array_unshift($arResult['ITEMS'], $item); 
	}
}