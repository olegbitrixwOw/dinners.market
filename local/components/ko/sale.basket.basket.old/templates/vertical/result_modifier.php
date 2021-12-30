<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\Getfood\Image;

CModule::IncludeModule("iblock");

$arBasketIds = [];
foreach ($arResult["CATEGORIES"]["READY"] as $key => &$arItem)
{
    $res = CCatalogMeasureRatio::getList(array(), array("PRODUCT_ID" => $arItem["PRODUCT_ID"]), false, false, array('RATIO'));
    $arRes = $res->GetNext();
    $arResult["CATEGORIES"]["READY"][$key]["RATIO"] = $arRes['RATIO'];

	/**
	 * –есайзинг изображений
	 */
	if (!empty($arItem['PREVIEW_PICTURE']))
	{
		$arItem['PICTURE_SRC'] = Image::resize($arItem['PREVIEW_PICTURE'], 70, 70);
	}
	elseif (!empty($arItem['DETAIL_PICTURE']))
	{
		$arItem['PICTURE_SRC'] = Image::resize($arItem['DETAIL_PICTURE'], 70, 70);
	}
    else // поиск изображени€ в свойстве MORE_PHOTO
    {
	    $arProductInfo = CIBlockElement::GetList(
	    	array(),
		    array(
		    	'ID' => $arItem['PRODUCT_ID']
		    ),
		    false,
		    false,
		    array('PROPERTY_MORE_PHOTO', 'PROPERTY_CML2_LINK')
	    )->Fetch();

	    if (!empty($arProductInfo['PROPERTY_MORE_PHOTO_VALUE']))
	    {
		    $arItem['PICTURE_SRC'] = Image::resize($arProductInfo['PROPERTY_MORE_PHOTO_VALUE'], 70, 70);
	    }
	    elseif (!empty($arProductInfo['PROPERTY_CML2_LINK_VALUE'])) // это торговое предложение
	    {
	    	// поиск изображени€ в основном товаре
		    $arMorePhoto = CIBlockElement::GetList(
			    array(),
			    array(
				    'ID' => $arProductInfo['PROPERTY_CML2_LINK_VALUE']
			    ),
			    false,
			    false,
			    array('PROPERTY_MORE_PHOTO')
		    )->Fetch();

		    if (!empty($arMorePhoto['PROPERTY_MORE_PHOTO_VALUE']))
		    {
			    $arItem['PICTURE_SRC'] = Image::resize($arMorePhoto['PROPERTY_MORE_PHOTO_VALUE'], 70, 70);
		    }
	    }
    }

	$arBasketIds[$arItem['ID']] = $arItem['ID'];
}

/**
 * —войства товаров
 */
$dbBasketProp = CSaleBasket::GetPropsList(
	array('BASKET_ID' => 'ASC', 'SORT' => 'ASC', 'ID' => 'ASC'),
	array('BASKET_ID' => $arBasketIds)
);

$arProductProps = [];
while ($arProperty = $dbBasketProp->GetNext())
{
	$arProperty['CODE'] = (string)$arProperty['CODE'];
	if ($arProperty['CODE'] == 'CATALOG.XML_ID' || $arProperty['CODE'] == 'PRODUCT.XML_ID' || $arProperty['CODE'] == 'SUM_OF_CHARGE' || !isset($arBasketIds[$arProperty['BASKET_ID']]))
	{
		continue;
	}

	$arProductProps[$arProperty['BASKET_ID']][] = [
		'NAME' => $arProperty['NAME'],
		'VALUE' => $arProperty['VALUE']
	];
}

if (!empty($arProductProps))
{
	foreach ($arResult['CATEGORIES']['READY'] as &$arProduct)
	{
		if (isset($arProductProps[$arProduct['ID']]))
		{
			$arProduct['PROPS'] = $arProductProps[$arProduct['ID']];
		}
	}
}

// pr($arParams['LIMIT']);
// pr($arParams['SUM_CURRENT_BASKET']);