<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\Getfood\Image;

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

// ID инфоблока торгового предложения
if (!isset($arResult['OFFERS_IBLOCK'])) {
	$arSKU = CCatalogSku::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
	$arResult['OFFERS_IBLOCK'] = $arSKU['IBLOCK_ID'];
}


/**
 * Ресайзинг изображений
 */

Image::resizeCatalogElement($arResult, 960, 960);


/**
 * Баннер раздела
 */

if ($arResult['SECTION']['DETAIL_PICTURE']) // текущий раздел
{
	$arResult['SECTION_DETAIL_PICTURE'] = CFile::GetPath($arResult['SECTION']['DETAIL_PICTURE']);
}
else // верхние разделы
{
	// ID разделов
	$arSectionIds = [];
	foreach ($arResult['SECTION']['PATH'] as $arSection)
	{
		$arSectionIds[] = $arSection['ID'];
	}

	// исключение текущего раздела
	$arSectionIds = array_diff($arSectionIds, [$arResult['SECTION']['ID']]);

	$dbSection = CIBlockSection::GetList(
		[
			'ID' => 'DESC'
		],
		[
			'IBLOCK_ID' => $arParams['IBLOCK_ID'],
			'ID' => $arSectionIds
		],
		false,
		['DETAIL_PICTURE']
	);

	while ($rsSection = $dbSection->Fetch())
	{
		if ($rsSection['DETAIL_PICTURE'])
		{
			$arResult['SECTION_DETAIL_PICTURE'] = CFile::GetPath($rsSection['DETAIL_PICTURE']);
			break;
		}
	}
}
