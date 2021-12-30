<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
$config = new \Citfact\Getfood\Configurator();
/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
}
else
{
	$basketAction = isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
}

?>

<div class="bx_catalog_section_box">
	<div class="bx_catalog_text">
		<?
		$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list",
			".default",
			array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
				"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
				"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
				"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
				"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);
		?>
        
        <?$arSort = $APPLICATION->IncludeComponent(
            "studiofact:catalog.sort",
            ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "IBLOCK_ID" => "2",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "citfact_sort_show_name" => "Y",
                "citfact_sort_alternative_name" => "",
                "citfact_sort_sort_name" => "100",
                "citfact_sort_show_id" => "N",
                "citfact_sort_show_popular" => "Y",
                "citfact_sort_show_price" => "Y",
                "citfact_sort_alternative_price" => "",
                "citfact_sort_sort_price" => "10",
                "citfact_sort_show_sort" => "Y",
                "citfact_sort_show_change_date" => "N",
                "citfact_sort_show_property" => "N",
                "PROPERTY_CODE" => "PROTEINS",
                "citfact_sort_alternative_property" => "",
                "citfact_sort_sort_property" => "",
                "citfact_sort_alternative_popular" => "",
                "citfact_sort_sort_popular" => "200",
                "citfact_sort_alternative_sort" => "",
                "citfact_sort_sort_sort" => "10",
                "citfact_sort_alternative_change_date" => "",
                "citfact_sort_sort_change_date" => "20",
                "citfact_sort_alternative_id" => "",
                "citfact_sort_sort_id" => ""
            ),
            false,
            array(
                "HIDE_ICONS" => "N"
            )
        );?>
	</div>

	
	
	<div id="catalog_section_top"></div>

	<?
	$arSort[0]["ORDER"] = 'desc';
	$arSort[0]["FIELD"] = 'catalog_PRICE_1';
	$intSectionID = $APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"base_26",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],

			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
			"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
			"ADD_SECTIONS_CHAIN" => "N",
			'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
			"BASKET_URL" => "/personal/cart/",
			"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
			"CACHE_FILTER" => $arParams["CACHE_FILTER"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"COMPARE_PATH" => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
			"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
			"CURRENCY_ID" => $arParams['CURRENCY_ID'],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
			"DISABLE_INIT_JS_IN_COMPONENT" => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
			"DISCOUNT_PERCENT_POSITION" => $arParams['DISCOUNT_PERCENT_POSITION'],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],

			"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
			"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
			"ELEMENT_SORT_FIELD" => $arSort[0]["FIELD"],
			"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
			"ELEMENT_SORT_ORDER" => $arSort[0]["ORDER"],
			"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],

			"LABEL_PROP" => $arParams['LABEL_PROP'],
			"MESSAGE_404" => $arParams["~MESSAGE_404"],
			"MESS_BTN_ADD_TO_BASKET" => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
			"MESS_BTN_BUY" => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
			"MESS_BTN_DETAIL" => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
			"MESS_BTN_SUBSCRIBE" => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
			"MESS_NOT_AVAILABLE" => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
			"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
			"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],

			"OFFERS_CART_PROPERTIES" =>$arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
			"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
			"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" =>  $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_FIELD2" =>$arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFER_ADD_PICT_PROP" => $arParams['OFFER_ADD_PICT_PROP'],

			"OFFER_TREE_PROPS" => $arParams['OFFER_TREE_PROPS'],
			"PAGER_BASE_LINK_ENABLE" =>$arParams["PAGER_BASE_LINK_ENABLE"],
			"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
			"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
			"PAGER_TITLE" => $arParams["PAGER_TITLE"],
			"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
			"PARTIAL_PRODUCT_PROPERTIES" =>  (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
			"PRICE_CODE" => $arParams["PRICE_CODE"],

			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRODUCT_DISPLAY_MODE" =>$arParams['PRODUCT_DISPLAY_MODE'],
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
			"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"SET_TITLE" => $arParams["SET_TITLE"],

			"SHOW_ALL_WO_SECTION" => "Y",
			"SLIDER_ID" => "new-products", // !!!
			"TOP" => "Y",
			"SHOW_404" => $arParams["SHOW_404"],
			"SHOW_CLOSE_POPUP" => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
			"SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
			"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],

			"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
			"LINE_ELEMENT_COUNT" =>$arParams["LINE_ELEMENT_COUNT"],
			"TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
			"SHOW_MAX_QUANTITY" => $arParams['SHOW_MAX_QUANTITY'],
			"ADD_TO_BASKET_ACTION" => $basketAction,
			"MESS_BTN_COMPARE" => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),
			"COMPARE_NAME" => $arParams['COMPARE_NAME'],
			"USE_ENHANCED_ECOMMERCE" =>  (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
			"LAZY_LOAD" => $arParams["LAZY_LOAD"],			
			"LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],
			"COMPATIBLE_MODE" => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"PRODUCT_ROW_VARIANTS" =>  $arParams['LIST_PRODUCT_ROW_VARIANTS'],
			"ENLARGE_PRODUCT" => $arParams['LIST_ENLARGE_PRODUCT'],
			"PRODUCT_BLOCKS_ORDER" => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
			"SHOW_SLIDER" => $arParams['LIST_SHOW_SLIDER'],
			"SLIDER_INTERVAL" => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
			"SLIDER_PROGRESS" =>  isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',
			"IS_SECTION" => 'Y', // if section hadn't item, then you saw message about this
			"CONFIGURATOR"=>$config->getCurrentOptions(),
			"DAY_WEEK"=>$arParams['DAY_WEEK'],
			"HIDE_BASKET"=>$arParams['HIDE_BASKET'],
			"CUISINE"=>$arParams['CUISINE']
		),
		$component
	);

	

	// Banner and section information
	$APPLICATION->ShowViewContent('section_description');
	?>

	<?
	// Gifts
	if (ModuleManager::isModuleInstalled("sale"))
	{
		$arRecomData = array();
		$recomCacheID = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		$obCache = new CPHPCache();
		if ($obCache->InitCache(36000, serialize($recomCacheID), "/sale/bestsellers"))
		{
			$arRecomData = $obCache->GetVars();
		}
		elseif ($obCache->StartDataCache())
		{
			if (Loader::includeModule("catalog"))
			{
				$arSKU = CCatalogSku::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
				$arRecomData['OFFER_IBLOCK_ID'] = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
			}
			$obCache->EndDataCache($arRecomData);
		}

		if (!empty($arRecomData) && $arParams['USE_GIFTS_SECTION'] === 'Y')
		{
			?>
			<div data-entity="parent-container">
				<?
				if (!isset($arParams['GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE']) || $arParams['GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE'] !== 'Y')
				{
					?>
					<div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
						<?=($arParams['GIFTS_SECTION_LIST_BLOCK_TITLE'] ?: \Bitrix\Main\Localization\Loc::getMessage('CT_GIFTS_SECTION_LIST_BLOCK_TITLE_DEFAULT'))?>
					</div>
					<?
				}
				CBitrixComponent::includeComponentClass('bitrix:sale.products.gift.section');				
				?>
			</div>
			<?
		}
	}
	?>
</div>

<script>
	$('body').addClass('is-catalog-page');
</script>