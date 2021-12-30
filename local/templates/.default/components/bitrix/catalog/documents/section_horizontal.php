<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] === 'Y')
{
	$basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
}
else
{
	$basketAction = isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
}
?>
	<div class="<?=($isSidebar ? "col-md-9 col-sm-8" : "col-xs-12")?>">
		<div class="row">

			<div class="col-xs-12">

				<?
				// var_dump($arResult["VARIABLES"]["SECTION_CODE"]);
				$sectionListParams = array(
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
					"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
					"USER"=> $arParams["USER"]
				);
				if ($sectionListParams["COUNT_ELEMENTS"] === "Y")
				{
					// $sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_ACTIVE";
					// if ($arParams["HIDE_NOT_AVAILABLE"] == "Y")
					// {
					// 	$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_AVAILABLE";
					// }
					$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_ACTIVE";
				}

				// pr($sectionListParams);

				$APPLICATION->IncludeComponent(
					"bitrix:catalog.section.list",
					"",
				    // "multilevel_tree",
					$sectionListParams,
					$component,
					array("HIDE_ICONS" => "Y")
				);
				unset($sectionListParams);
				// $dbItems = CIBlockSection::GetList(false, array('IBLOCK_ID' => $arResult["IBLOCK_ID"], '=CODE' => $arResult["VARIABLES"]["SECTION_CODE"]));
				?>
			</div>
			
		</div>
	</div>
