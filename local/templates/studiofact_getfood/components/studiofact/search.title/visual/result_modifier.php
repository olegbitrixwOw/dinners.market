<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Citfact\Getfood\Image;

$PREVIEW_WIDTH = intval($arParams["PREVIEW_WIDTH"]);
if ($PREVIEW_WIDTH <= 0)
	$PREVIEW_WIDTH = 75;

$PREVIEW_HEIGHT = intval($arParams["PREVIEW_HEIGHT"]);
if ($PREVIEW_HEIGHT <= 0)
	$PREVIEW_HEIGHT = 75;

$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

$arCatalogs = array();
if (CModule::IncludeModule("catalog"))
{
	$rsCatalog = CCatalog::GetList(array(
		"sort" => "asc",
	));
	while ($ar = $rsCatalog->Fetch())
	{
//		if ($ar["PRODUCT_IBLOCK_ID"])
//			$arCatalogs[$ar["PRODUCT_IBLOCK_ID"]] = 1;
//		else
//			$arCatalogs[$ar["IBLOCK_ID"]] = 1;

		$arCatalogs[$ar["IBLOCK_ID"]] = 1;
	}
}

$arResult["ELEMENTS"] = array();
$arResult["SEARCH"] = array();
foreach($arResult["CATEGORIES"] as $category_id => $arCategory)
{
	foreach($arCategory["ITEMS"] as $i => $arItem)
	{
		if(isset($arItem["ITEM_ID"]))
		{
			$arResult["SEARCH"][] = &$arResult["CATEGORIES"][$category_id]["ITEMS"][$i];
			if (
				$arItem["MODULE_ID"] == "iblock"
				&& array_key_exists($arItem["PARAM2"], $arCatalogs)
				&& substr($arItem["ITEM_ID"], 0, 1) !== "S"
			)
			{
				$arResult["ELEMENTS"][$arItem["ITEM_ID"]] = $arItem["ITEM_ID"];
			}
		}
	}
}

if (!empty($arResult["ELEMENTS"]) && CModule::IncludeModule("iblock"))
{
	$arConvertParams = array();
	if ('Y' == $arParams['CONVERT_CURRENCY'])
	{
		if (!CModule::IncludeModule('currency'))
		{
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		}
		else
		{
			$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
			if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo)))
			{
				$arParams['CONVERT_CURRENCY'] = 'N';
				$arParams['CURRENCY_ID'] = '';
			}
			else
			{
				$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
			}
		}
	}

	$obParser = new CTextParser;

	if (is_array($arParams["PRICE_CODE"]))
		$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices(0, $arParams["PRICE_CODE"]);
	else
		$arResult["PRICES"] = array();

	$arSelect = array(
		"ID",
		"IBLOCK_ID",
		"PREVIEW_TEXT",
		"PREVIEW_PICTURE",
		"DETAIL_PICTURE",
	);
	$arFilter = array(
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE_DATE" => "Y",
		"ACTIVE" => "Y",
		"CHECK_PERMISSIONS" => "Y",
		"MIN_PERMISSION" => "R",
	);
	foreach($arResult["PRICES"] as $value)
	{
		$arSelect[] = $value["SELECT"];
		$arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = 1;
	}
	$arFilter["=ID"] = $arResult["ELEMENTS"];
	$rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	while($arElement = $rsElements->Fetch())
	{
		$arElement["PRICES"] = CIBlockPriceTools::GetItemPrices($arElement["IBLOCK_ID"], $arResult["PRICES"], $arElement, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
		if($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
			$arElement["PREVIEW_TEXT"] = $obParser->html_cut($arElement["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);

		$arResult["ELEMENTS"][$arElement["ID"]] = $arElement;
	}
}

foreach($arResult["SEARCH"] as $i=>$arItem)
{
	switch($arItem["MODULE_ID"])
	{
		case "iblock":
			if(array_key_exists($arItem["ITEM_ID"], $arResult["ELEMENTS"]))
			{
				$arElement = &$arResult["ELEMENTS"][$arItem["ITEM_ID"]];

				if ($arParams["SHOW_PREVIEW"] == "Y")
				{
					if ($arElement["PREVIEW_PICTURE"] > 0)
					{
						$arElement['PICTURE'] = Image::resize($arElement['PREVIEW_PICTURE'], $PREVIEW_WIDTH, $PREVIEW_HEIGHT);
					}
					elseif ($arElement["DETAIL_PICTURE"] > 0)
					{
						$arElement['PICTURE'] = Image::resize($arElement['DETAIL_PICTURE'], $PREVIEW_WIDTH, $PREVIEW_HEIGHT);
					}
					else // поиск изображения в свойстве MORE_PHOTO
					{
						$arProductProps = CIBlockElement::GetList(
							array(),
							array(
								'IBLOCK_ID' => $arElement['IBLOCK_ID'],
								'ID' => $arElement['ID']
							),
							false,
							false,
							array('PROPERTY_MORE_PHOTO', 'PROPERTY_CML2_LINK')
						)->Fetch();

						if ($arProductProps['PROPERTY_MORE_PHOTO_VALUE'])
						{
							$arElement['PICTURE'] = Image::resize($arProductProps['PROPERTY_MORE_PHOTO_VALUE'], $PREVIEW_WIDTH, $PREVIEW_HEIGHT);
						}
						elseif ($arProductProps['PROPERTY_CML2_LINK_VALUE']) // это торговое предложение
						{
							// поиск изображения в основном товаре
							$arProduct = CIBlockElement::GetList(
								array(),
								array(
									'ID' => $arProductProps['PROPERTY_CML2_LINK_VALUE']
								),
								false,
								false,
								array('PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_MORE_PHOTO')
							)->Fetch();

							if ($arProduct['PREVIEW_PICTURE'])
							{
								$arElement['PICTURE'] = Image::resize($arProduct['PREVIEW_PICTURE'], $PREVIEW_WIDTH, $PREVIEW_HEIGHT);
							}
							elseif ($arProduct['DETAIL_PICTURE'])
							{
								$arElement['PICTURE'] = Image::resize($arProduct['DETAIL_PICTURE'], $PREVIEW_WIDTH, $PREVIEW_HEIGHT);
							}
							elseif ($arProduct['PROPERTY_MORE_PHOTO_VALUE'])
							{
								$arElement['PICTURE'] = Image::resize($arProduct['PROPERTY_MORE_PHOTO_VALUE'], $PREVIEW_WIDTH, $PREVIEW_HEIGHT);
							}
						}
					}
				}
			}
			break;
	}

	$arResult["SEARCH"][$i]["ICON"] = true;
}