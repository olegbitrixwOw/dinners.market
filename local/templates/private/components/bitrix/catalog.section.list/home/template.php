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
$rand = $this->randString();


?>
<div class="product-categories-wrapper">
	<div class="section_box">
		<h3 class="product-categories-title"><?=GetMessage("CT_BCSL_GOODS_CATEGORY");?></h3>
		<div class="scroll-standard">
			<? if (0 < $arResult["SECTIONS_COUNT"]) { ?>
				<div class="product-categories section adaptive_scroll_slider categories_goods_home" id="section_<?=$rand;?>">
				<?
				foreach ($arResult['SECTIONS'] as &$arSection)
							{
								if (false === $arSection['PICTURE'])
									$arSection['PICTURE'] = array(
										'SRC' => $this->GetFolder().'/images/tile-empty.png',
										'ALT' => (
											'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
											? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
											: $arSection["NAME"]
										),
										'TITLE' => (
											'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
											? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
											: $arSection["NAME"]
										)
									);
								?><div class="item_element" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
								<a class="hover-overlay" href="catalog/<? echo $arSection['SECTION_PAGE_URL']; ?>"></a>
								<a
									href="catalog/<? echo $arSection['SECTION_PAGE_URL']; ?>"
									class="product-categories__image"
									style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>');"
									title="<? echo $arSection['PICTURE']['TITLE']; ?>"
								> </a><?
								if ('Y' != $arParams['HIDE_SECTION_NAME'])
								{
									?><h2 class="product-categories__title">
									<a class="product-categories__link" href="catalog/<? echo $arSection['SECTION_PAGE_URL']; ?>">
										<? echo '<span class="product-categories__name">'.$arSection['NAME'].'</span>';
										$ar_result=CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$arSection["IBLOCK_ID"], "ID"=>$arSection['ID']),false, Array("UF_CATEGORY_PRICE"));
										if($res=$ar_result->GetNext()){

										    if (strlen($res["UF_CATEGORY_PRICE"])>0) {
                                                echo '<br /><span class="product-categories__price">' . GetMessage("TEXT_FROM") . $res["UF_CATEGORY_PRICE"] . GetMessage("TEXT_VAL").'</span>';
                                            }
										}
										?>
									</a><?
									if ($arParams["COUNT_ELEMENTS"])
									{
										?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
									}
								?></h2><?
								}
								?></div><?
							}
							unset($arSection);
				?>
				</div>
			<?php } ?>
		</div>

		<div class="slide_scroll_left"></div>
		<div class="slide_scroll_right"></div>

	</div>
</div>