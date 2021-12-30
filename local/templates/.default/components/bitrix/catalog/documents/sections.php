<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
$this->addExternalCss("/bitrix/css/main/bootstrap.css");

$sectionListParams = array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"SECTION_CODE" => $arParams["SECTION_CODE"],
	"CACHE_TYPE" => "Y",
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
	"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
	"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
	"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
	"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
	"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
	"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
);
if ($sectionListParams["COUNT_ELEMENTS"] === "Y")
{
	$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_ACTIVE";
	if ($arParams["HIDE_NOT_AVAILABLE"] == "Y")
	{
		$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_AVAILABLE";
	}
}

// pr($arResult["VARIABLES"]["SECTION_ID"]);
?>
<?
$arNavParams = array(
   'nPageSize'          => 20,
   'bDescPageNumbering' => false,
   'bShowAll'           => true,
);

$arOrder = array('left_margin' => 'asc');

$arFilter = Array(
   'IBLOCK_ID'     => $sectionListParams['IBLOCK_ID'],
   'ACTIVE'        => 'Y',
   'GLOBAL_ACTIVE' => 'Y',
   'DEPTH_LEVEL' =>1
);

if($arParams['SECTION_CODE']){
	$arFilter['CODE'] = $arParams['SECTION_CODE'];
}

$rsContent = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect, $arNavParams);
$navigation = $rsContent->GetPageNavStringEx(
   $navComponentObject,
   'Разделы',
   '' //round
);
?>

<?
// var_dump(expression)
?>
<?if($arParams['USER'] == 'admin'):?>
	<p class="user-button"><a href="/firm/documents/make/">Добавить новый документ</a></p>
<?endif?>
<div class="bx_sitemap">
	<ul class="bx_sitemap_ul" style="padding-inline-start: 40px;">
		<? while($arSection = $rsContent->GetNext()):?>
		<li>
			<p class="bx_sitemap_li_title">
				<a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>"><? echo $arSection["NAME"];?></a>
			</p>
		</li>
		<? endwhile; ?>
	</ul>
</div>
<?echo '<br>' . $navigation;?>
