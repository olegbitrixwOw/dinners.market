<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->createFrame()->begin("");  ?>
<?php
$summ = 0;
foreach ($arResult['BASKET_ITEMS'] as $one_item) {
	$summ += $one_item['PRICE']*$one_item['QUANTITY'];
};
?>
<a id="small_basket" class="fr" href="<?=$arParams["PATH_TO_BASKET"];?>">
	<span class="mobile"><? if ($arResult["NUM_PRODUCTS"] > 0) { ?><span class="quant inline"><?=$arResult["NUM_PRODUCTS"];?></span><? } ?><span class="icon inline">0</span></span>
	<span class="desktop">
		<? if ($arResult["NUM_PRODUCTS"] > 0) { ?><span class="quant inline"><?=$arResult["NUM_PRODUCTS"];?></span><? } else { ?><span class="icon inline">0</span><? } ?>
		<span id="cart_heading_wrapper cart_heading_wrapper2" class="cart_heading_wrapper">
			<span class="text inline"><?=GetMessage("SF_SMALL_BASKET");?></span>
			<? if ($summ > 0) { ?><span class="summ inline"><?=str_replace(GetMessage("STUDIOFACT_RUB"), "<span class=\"rub black\">".GetMessage("STUDIOFACT_R")."</span>", CurrencyFormat($summ, "RUB"));?></span><? } else { ?><span class="summ inline"></span><? } ?>
			<span class="clear"></span>
		</span>
	</span>
</a>
<? if (count($arResult["BASKET_ITEMS"]) > 0) { ?>
	<div class="small_basket_hover_block<? if ($_REQUEST["SMALL_BASKET_OPEN"] == "Y") { echo ' active'; } ?>">
		<div class="small_basket_overflow">
		<table class="small_basket_hover_table">
			<? foreach ($arResult["BASKET_ITEMS"] as $arItem) {
				?><tr class="good_box">
					<td class="small_basket_hover_img">
						<? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0) {
							if (strlen($arResult["PRODUCTS_IMAGES"][$arItem["PRODUCT_ID"]]) > 0) {
								$basketImageSrc = $arResult["PRODUCTS_IMAGES"][$arItem["PRODUCT_ID"]];
							} else {
								$basketImageSrc = SITE_TEMPLATE_PATH.'/images/no-img.png';
							}
							echo '<a href="'.$arItem["DETAIL_PAGE_URL"].'" title="'.$arItem["NAME"].'" style="background-image: url('.$basketImageSrc.')">'; } ?>
							<?/* if (strlen($arResult["PRODUCTS_IMAGES"][$arItem["PRODUCT_ID"]]) > 0) {
								echo '<img class="radius5" src="'.$arResult["PRODUCTS_IMAGES"][$arItem["PRODUCT_ID"]].'" title="'.$arItem["NAME"].'" alt="'.$arItem["NAME"].'" />';
							} else {
								echo '<img src="'.SITE_TEMPLATE_PATH.'/images/no-img.png" title="'.$arItem["NAME"].'" alt="'.$arItem["NAME"].'" />';
							} */?>
						<? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0) { echo '</a>'; } ?>
					</td>
					<td class="small_basket_hover_name">
						<div class="name"><? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0) { echo '<a href="'.$arItem["DETAIL_PAGE_URL"].'" title="'.$arItem["NAME"].'">'; } ?>
							<? echo $arItem["NAME"]; ?>
						<? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0) { echo '</a>'; } ?></div>
						<div class="props">
							<? foreach ($arItem["PROPS"] as $arValue) {
								if ($arValue["CODE"] != "CATALOG.XML_ID" && $arValue["CODE"] != "PRODUCT.XML_ID") {
									echo '<span>'.$arValue["NAME"].':</span> '.$arValue["VALUE"].'<br />';
								}
							} ?>
						</div>
						<div class="item_quantity small_basket_hover_quantity">
							<a class="minus" href="javascript: void(0);">-</a><?
							?><input
								type="text"
								class="buy_button_a small-basket-quantity"
								data-ratio="<?=$arResult['CATALOG_RATIO'][$arItem['PRODUCT_ID']];?>"
								value="<?=$arItem["QUANTITY"];?>"
								name="QUANTITY_<?=$arItem["ID"]?>"
								id="QUANTITY_<?=$arItem["ID"]?>"<?=(isset($arItem["AVAILABLE_QUANTITY"]) ? ' data-max="' . $arItem["AVAILABLE_QUANTITY"] . '"':""); ?>
							/><?
							?><a class="plus" href="javascript: void(0);">+</a>
						</div>
						<div class="small_basket_hover_price">
							<?/*=GetMessage("SF_SMALL_BASKET_PRICE");*/?>
							<?=str_replace(GetMessage("STUDIOFACT_RUB"), "<span class=\"rub black\">".GetMessage("STUDIOFACT_R")."</span>", $arItem["PRICE_FORMATED"]);?>
						</div>
					</td>
					<td class="small_basket_hover_delete"><a href="javascript:void(0);" class="small_basket_hover_delete_action" data-id="<?=$arItem["ID"];?>"></a></td>
				</tr><?
			} ?>
		</table>
		</div>
		<div class="small_basket_hover_block__buttons clearfix">
			<a href="<?=$arParams["PATH_TO_BASKET"];?>" class="radius5 small_basket_hover_to_basket inline"><?=GetMessage("SF_SMALL_TO_BASKET");
?></a>
			<a href="javascript: void(0);" data-fancybox data-src="#oneClickModal" class="radius5 button small_basket_hover_buy inline"><?=GetMessage("SF_SMALL_BUY");
?></a>

		</div>

	</div>
<? } ?>