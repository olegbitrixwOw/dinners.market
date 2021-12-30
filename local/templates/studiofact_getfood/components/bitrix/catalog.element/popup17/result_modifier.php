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