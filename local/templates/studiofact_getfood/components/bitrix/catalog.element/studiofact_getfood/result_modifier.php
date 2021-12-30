<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\Getfood\Image;

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();


/**
 * Ресайзинг изображений
 */

Image::resizeCatalogElement($arResult, 960, 960);


/**
 * С этим товаром рекомендуем
 */
if (isset($arResult['DISPLAY_PROPERTIES']['RECOMMEND']['LINK_ELEMENT_VALUE']) && !empty($arResult['DISPLAY_PROPERTIES']['RECOMMEND']['LINK_ELEMENT_VALUE']))
{
	$arLinks = [];
	foreach ($arResult['DISPLAY_PROPERTIES']['RECOMMEND']['LINK_ELEMENT_VALUE'] as $arLink)
	{
		$arLinks[] = "<a href='{$arLink['DETAIL_PAGE_URL']}' target='_parent'>{$arLink['NAME']}</a>";
	}

	$arResult['DISPLAY_PROPERTIES']['RECOMMEND']['DISPLAY_VALUE'] = implode(' / ', $arLinks);
}

$arResult['DAY_WEEK'] = false;
if($arResult['DISPLAY_PROPERTIES']['DAY_OF_WEEK']){
	if(!empty($arResult['DISPLAY_PROPERTIES']['DAY_OF_WEEK']['VALUE_XML_ID'])){
		foreach ($arResult['DISPLAY_PROPERTIES']['DAY_OF_WEEK']['VALUE_XML_ID'] as $key => $value){
			if($value == $arParams['DAY_WEEK']){
					$arResult['DAY_WEEK'] = $arParams['DAY_WEEK'];
				break;
			}
		}
	}
}

// pr($arResult['DAY_WEEK']);
// pr(empty($arResult['DAY_WEEK']));