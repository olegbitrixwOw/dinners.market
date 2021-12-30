<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'bx_sitemap_ul',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?>
<?
if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID'])
{
	$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

	?><?
}
if (0 < $arResult["SECTIONS_COUNT"])
{
?>
<div class="bx_catalog_tile">

			<div class="section">
			<?
				foreach ($arResult['SECTIONS'] as &$arSection)
				{
							$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
							$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
						?>
							<div class="item_element good_box bx_catalog_item " data-entity="items-row">
								<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>" class="sect-link" style="text-decoration: none;">
									<div class="char-pop-up bx_catalog_item_container" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>" data-entity="item">
										<div class="img_box" style="background-color: #81a796;">																		
											<div style="width: 100%; height: 100%; background-image: url(<?=$arSection['PICTURE']['SRC']?>); background-size: cover; border-top-right-radius:8px; border-top-left-radius: 8px;"></div>									
											<div class="hover_over" style="display: none;">
												<!-- <a href="<? echo $arSection['SECTION_PAGE_URL']; ?>" class="open_fancybox">
													Перейти к кухне 
												</a>	 -->										
											</div>
										</div>

										<div class="item-info">
											<dev class="bx_catalog_item_title">
									<!-- 			<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>">Кухня <? echo $arSection['NAME'];?></a> -->
												<p><? echo $arSection['NAME'];?></p>
											</dev>

											<div class="bx_catalog_item_title delivery">
													Сумма бесплатной доставки <br/>от <? echo $arSection['CUISINE']['DELIVERY_COST'];?> руб.
											</div>

											<div class="bx_catalog_item_title working_time">
													<strong>Время работы:</strong> <? echo $arSection['CUISINE']['WORKING_HOURS'];?>
											</div>

										</div>
									</div>
								</a>
							</div>
							<?
				}
				unset($arSection);
			?>
		</div>

</div>
<?
}
?>