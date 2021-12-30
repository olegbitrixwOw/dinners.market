<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>
	<ul class="clearfix">
	<? foreach ($arResult['SECTIONS'] as &$arSection) {
		$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
		$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);?>
		<li class="item_element good_box inline" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
			<div class="bx_catalog_text_title box">
				<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>">
					<? if (strlen($arSection["PICTURE"]["SRC"]) > 0) {?>
						<div class="image" style="background-image: url(<?=$arSection["PICTURE"]["SRC"]?>)"></div>
					<? } ?>
					<h2 <? if (strlen($arSection["PICTURE"]["SRC"]) > 0) {?>class="no-top-border"<?}?>>
						<?=$arSection['NAME']?>
						<? if ($arParams["COUNT_ELEMENTS"]) {?>
							<font> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span></font>
						<? } ?>
					</h2>
				</a>
			</div>
		</li>
	<? } ?>
	</ul>

