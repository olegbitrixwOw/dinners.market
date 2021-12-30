<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$strMainID = $this->GetEditAreaId($arResult["ID"]);

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
); ?>

<div class="bx_item_detail box padding good_box bx_item_detail_popup" id="<?=$this->GetEditAreaId($arResult["ID"]);?>">
	<script>
		$('body').addClass('is-product-page is-product-page-popup');
	</script>
	<div class="bx_item_title">
		<h2 itemprop="name">
			<? echo (
			isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
				? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
				: $arResult["NAME"]
			); ?>
		</h2>
	</div>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="img_box">
				<div class="icon_box"><?
					if (strlen($arResult["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["NAME"].'"></div>'; }
					if (strlen($arResult["DISPLAY_PROPERTIES"]["SALELEADER"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["SALELEADER"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["SALELEADER"]["NAME"].'"></div>'; }
					if (strlen($arResult["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["NAME"].'"></div>'; }
					if (strlen($arResult["DISPLAY_PROPERTIES"]["PRECOMMEND"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["PRECOMMEND"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["PRECOMMEND"]["NAME"].'"></div>'; }
				?></div>
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) {
						if (count($arOffer["PHOTO_SLIDER"]) > 0) { ?>
							<div class="main_detail_slider_<?=$arOffer["ID"];?> main_detail_slider_box">
								<div class="flexslider slider" id="slider_<?=$arOffer["ID"];?>">
									<ul class="slides">
										<? foreach ($arOffer["PHOTO_SLIDER"] as $key => $value) {
											?><li>
											<span style="background-image: url(<?=(strlen($value["SRC"]) > 1 ? $value["SRC"] : $value["ORIGINAL_SRC"]);?>);"></span>
											</li><?
										} ?>
									</ul>
								</div>
								<? if (count($arOffer["PHOTO_SLIDER"]) > 1) { ?>
									<div class="flexslider carousel" id="carousel_<?=$arOffer["ID"];?>">
										<ul class="slides">
											<? foreach ($arOffer["PHOTO_SLIDER"] as $key => $value) {
												?><li>
													<a href="<?=$value["ORIGINAL_SRC"];?>" style="background-image: url(<?=(strlen($value["SRC"]) > 1 ? $value["SRC"] : $value["ORIGINAL_SRC"]);?>);"></a>
												</li><?
											} ?>
										</ul>
									</div>
								<? } ?>
								<script type="text/javascript">
									$(window).load(function() {
										<? if (count($arOffer["PHOTO_SLIDER"]) > 1) { ?>
											$("#carousel_<?=$arOffer["ID"];?>").flexslider({
												animation: "slide",
												controlNav: false,
												animationLoop: false,
												slideshow: false,
												prevText: "",
												nextText: "",
												itemWidth: ($("#slider_<?=$arOffer["ID"];?>").width() - 30)/4,
												itemMargin: 10,
												asNavFor: "#slider_<?=$arOffer["ID"];?>",
												start: function () { img_box_height (); }
											});
										<? } ?>
										$("#slider_<?=$arOffer["ID"];?>").flexslider({
											animation: "slide",
											controlNav: false,
											animationLoop: false,
											slideshow: true,
											slideshowSpeed: 5000,
											pauseOnHover: true,
											directionNav: false,
											start: function () { img_box_height (); },
											<? if (count($arOffer["PHOTO_SLIDER"]) > 1) { ?>sync: "#carousel_<?=$arOffer["ID"];?>"<? } ?>
										});
									});
								</script>
							</div>
						<? }
					}
				} ?>
				<? if (count($arResult["PHOTO_SLIDER"]) > 0) { ?>
					<div class="main_detail_slider main_detail_slider_box <? if (count($arResult["OFFERS"]) < 1) { echo 'active_box'; } ?>" <? if (count($arResult["OFFERS"]) < 1) { echo 'style="opacity: 1;"'; } ?>>
						<div class="flexslider slider" id="slider_<?=$arResult["ID"];?>">
							<ul class="slides">
								<? foreach ($arResult["PHOTO_SLIDER"] as $key => $value) {
									?><li>
									<span style="background-image: url(<?=(strlen($value["SRC"]) > 1 ? $value["SRC"] : $value["ORIGINAL_SRC"]);?>)"></span>
									</li><?
								} ?>
							</ul>
						</div>
						<? if (count($arResult["PHOTO_SLIDER"]) > 1) { ?>
							<div class="flexslider carousel" id="carousel_<?=$arResult["ID"];?>">
								<ul class="slides">
									<? foreach ($arResult["PHOTO_SLIDER"] as $key => $value) {
										?><li>
											<a href="<?=$value["ORIGINAL_SRC"];?>" style="background-image: url(<?=(strlen($value["SRC"]) > 1 ? $value["SRC"] : $value["ORIGINAL_SRC"]);?>)"></a>
										</li><?
									} ?>
								</ul>
							</div>
						<? } ?>
						<script type="text/javascript">
							$(window).load(function() {
								<? if (count($arResult["PHOTO_SLIDER"]) > 1) { ?>
									$("#carousel_<?=$arResult["ID"];?>").flexslider({
										animation: "slide",
										controlNav: false,
										animationLoop: false,
										slideshow: false,
										prevText: "",
										nextText: "",
										itemWidth: ($("#slider_<?=$arResult["ID"];?>").width() - 30)/4,
										itemMargin: 10,
										asNavFor: "#slider_<?=$arResult["ID"];?>",
										start: function () { img_box_height (); }
									});
								<? } ?>
								$("#slider_<?=$arResult["ID"];?>").flexslider({
									animation: "slide",
									controlNav: false,
									animationLoop: false,
									slideshow: true,
									slideshowSpeed: 5000,
									pauseOnHover: true,
									directionNav: false,
									start: function () { img_box_height (); },
									<? if (count($arResult["PHOTO_SLIDER"]) > 1) { ?>sync: "#carousel_<?=$arResult["ID"];?>"<? } ?>
								});
							});
						</script>
					</div>
				<? }else{ ?>
				<img style="padding: 30px 65px;padding-right: 0;" src="<?=SITE_TEMPLATE_PATH?>/images/no-img.png" />
				<? } ?>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<? if (strlen($arResult["PREVIEW_TEXT"]) > 0) {
				echo '<div class="detail_preview_text main_detail_preview_text offers_hide">'.$arResult["PREVIEW_TEXT"].'</div>';
			} ?>
			<? if (count($arResult["OFFERS"]) > 0) {
				foreach ($arResult["OFFERS"] as $arOffer) {
					if (strlen($arOffer["PREVIEW_TEXT"]) > 0) {
						echo '<div class="detail_preview_text main_detail_preview_text_'.$arOffer["ID"].' offers_hide" style="display: none;">'.$arOffer["PREVIEW_TEXT"].'</div>';
					}
				}
			} ?>
			<? if (count($arResult["OFFERS"]) > 0) {
				?><div class="offers_item" id="skuId<?=$arResult["ID"];?>">
					<?
					foreach ($arResult["SKU_PROPS"] as $arSku) {
						if (count($arSku["VALUES"]) > 0 && count($arResult["SKU_THERE_ARE"][$arSku["ID"]]) > 0) {
							echo '<div class="offer_item" data-prop-id="'.$arSku["ID"].'"><div class="offer_name">'.$arSku["NAME"].':</div>';
								foreach ($arSku["VALUES"] as $value) {
									if ($value["ID"] > 0 && in_array($value["ID"], $arResult["SKU_THERE_ARE"][$arSku["ID"]])) {
										?><span class="offer_sku <?if (!empty($arSku["USER_TYPE_SETTINGS"])) echo 'color'?>" data-prop-id="<?=$arSku["ID"];?>" data-prop-code="<?=$arSku["CODE"];?>" data-prop-value-id="<?=$value["ID"];?>" data-tree='<?=json_encode($arResult["SKU_TREE"]);?>'><?=(strlen($value["PICT"]["SRC"]) > 0 ? '<img src="'.$value["PICT"]["SRC"].'" title="'.$value["NAME"].'" alt="'.$value["NAME"].'" />' : $value["NAME"]);?></span><?
									}
								}
							echo '</div>';
						}
					}
					echo '<div class="offers_item_id" style="display: none;">';
						foreach ($arResult["SKU_MASSIVE"] as $id => $value) {
							?><div class="<?=$id;?>" data-id="<?=$value;?>"></div><?
						}
					echo '</div>';
					?>
				</div><?
			} ?>
			<? if ($arParams["USE_PRODUCT_QUANTITY"] == 1) {
				?><div class="product-quantity-wrapper"><div class="detail_p_head"><?=GetMessage("CATALOG_QUANTITY");?></div><?
					?><div class="item_quantity">
					<a href="javascript: void(0);" class="minus">-</a><?
						?><input type="text"
								 name="<?=$arParams["PRODUCT_QUANTITY_VARIABLE"];?>"
								 value="<?=$arResult["CATALOG_MEASURE_RATIO"] ?: 1;?>"
					/>
					<a href="javascript: void(0);" class="plus">+</a>
					</div></div><?
			} ?>
			<div class="main_detail_price offers_hide">
				<? $priceItems = Array();
				$db_get = CCatalogGroup::GetList(Array("SORT" => "ASC"), Array(), false, false, Array("ID", "NAME_LANG"));
				while ($ar_get = $db_get->Fetch()) {
					$priceItems[$ar_get["ID"]] = $ar_get;
				} ?>
				<? if (count($arResult["PRICES"]) > 1) {
					$i = 0;
					foreach ($arResult["PRICES"] as $arPrice) { ?>
						<? if ($i > 0) { echo '<br />'; } ?>
						<div class="detail_p_head"><?=$priceItems[$arPrice["PRICE_ID"]]["NAME_LANG"];?><? if ($arPrice["DISCOUNT_DIFF"] > 0) { ?><span class="economy_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', GetMessage("ECONOMY_INFO", array("#ECONOMY#" => $arPrice["PRINT_DISCOUNT_DIFF"])));?></span><? } ?></div>
						<div class="fl">
							<? if ($arPrice["DISCOUNT_DIFF"] > 0) { ?><span class="old_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arPrice["PRINT_VALUE"]);?></span><? } ?>
							<span class="price"><? if (count($arResult["OFFERS"]) > 0) { echo GetMessage("SF_ISSET_OFFERS"); } ?><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arPrice["PRINT_DISCOUNT_VALUE"]);?></span>
						</div>

					<? $i++; }
				} else { ?>
					<div class="detail_p_head"><?=GetMessage("SF_PRICE");?><? if ($arResult["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?><span class="economy_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', GetMessage("ECONOMY_INFO", array("#ECONOMY#" => $arResult["MIN_PRICE"]["PRINT_DISCOUNT_DIFF"])));?></span><? } ?></div>
					<div class="fl">
						<? if ($arResult["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?><span class="old_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arResult["MIN_PRICE"]["PRINT_VALUE"]);?></span><? } ?>
						<span class="price"><? if (count($arResult["OFFERS"]) > 0) { echo GetMessage("SF_ISSET_OFFERS"); } ?><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arResult["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]);?></span>
					</div>

				<? } ?>
				<? if ($arResult["CAN_BUY"]) { ?>
					<a
						href="<?=$arResult["ADD_URL"];?>"
						class="detail_buy_button show_basket_popup inline buy_button_a"
						data-name="<?=$arResult["NAME"];?>"
						data-img="<?=(strlen($arResult["PHOTO_SLIDER"]["0"]["SRC"]) > 1 ? $arResult["PHOTO_SLIDER"]["0"]["SRC"] : $arResult["PHOTO_SLIDER"]["0"]["ORIGINAL_SRC"]);?>"
						data-id="<?=$arResult["ID"];?>"
						data-ratio="<?=$arResult["CATALOG_MEASURE_RATIO"];?>"
						data-basket="<?=$arParams["BASKET_URL"];?>"
						data-price="<?=str_replace(GetMessage("STUDIOFACT_RUB"), '', $arResult["MIN_PRICE"]["DISCOUNT_VALUE"]);?>"
						data-gotobasket="<?=GetMessage("SF_GO_TO_BASKET_BUTTON");?>"
						data-gotoback="<?=GetMessage("SF_GO_TO_BACK_BUTTON");?>"
					>
						<span class="icon"></span>
						<span class="text"><?=('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));?></span>
					</a>
				<? } else {
					?><div class="product-not-available"><? if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) { echo $arParams["MESS_NOT_AVAILABLE"]; } else { echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE"); } ?></div><?
				} ?>
			</div>
			<? if (count($arResult["OFFERS"]) > 0) {
				foreach ($arResult["OFFERS"] as $arOffer) {
					?><div class="main_detail_price_<?=$arOffer["ID"];?> offers_hide" style="display: none;">
						<? if (count($arOffer["PRICES"]) > 1) {
							$i = 0;
							foreach ($arOffer["PRICES"] as $arPrice) { ?>
								<? if ($i > 0) { echo '<br />'; } ?>
								<div class="detail_p_head">111<?=$priceItems[$arPrice["PRICE_ID"]]["NAME_LANG"];?><? if ($arPrice["DISCOUNT_DIFF"] > 0) { ?><span class="economy_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', GetMessage("ECONOMY_INFO", array("#ECONOMY#" => $arPrice["PRINT_DISCOUNT_DIFF"])));?></span><? } ?></div>
								<div class="fl">
									<? if ($arPrice["DISCOUNT_DIFF"] > 0) { ?><span class="old_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arPrice["PRINT_VALUE"]);?></span><? } ?>
									<span class="price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arPrice["PRINT_DISCOUNT_VALUE"]);?></span>
								</div>
							<? $i++; }
						} else { ?>
							<div class="detail_p_head"><?=GetMessage("SF_PRICE");?><? if ($arOffer["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?><span class="economy_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', GetMessage("ECONOMY_INFO", array("#ECONOMY#" => $arOffer["MIN_PRICE"]["PRINT_DISCOUNT_DIFF"])));?></span><? } ?></div>
						<div class="fl">
							<? if ($arOffer["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?><span class="old_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arOffer["MIN_PRICE"]["PRINT_VALUE"]);?></span><? } ?>
							<span class="price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arOffer["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]);?></span>
						</div>
						<? } ?>
						<? if ($arOffer["CAN_BUY"]) { ?>
							<a
								href="<?=$arOffer["ADD_URL"];?>"
								class="detail_buy_button show_basket_popup inline add_to_basket buy_button_a"
								data-name="<?=$arOffer["NAME"];?>"
								data-img="<?=(strlen($arOffer["PHOTO_SLIDER"]["0"]["SRC"]) > 1 ? $arOffer["PHOTO_SLIDER"]["0"]["SRC"] : $arResult["PHOTO_SLIDER"]["0"]["ORIGINAL_SRC"]);?>"
								data-id="<?=$arOffer["ID"];?>"
								data-ratio="<?=$arOffer["CATALOG_MEASURE_RATIO"];?>"
								data-basket="<?=$arParams["BASKET_URL"];?>"
								data-price="<?=str_replace(GetMessage("STUDIOFACT_RUB"), '', $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"]);?>"
								data-gotobasket="<?=GetMessage("SF_GO_TO_BASKET_BUTTON");?>"
								data-gotoback="<?=GetMessage("SF_GO_TO_BACK_BUTTON");?>"
							>
								<span class="icon"></span>
								<span class="text"><?=('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));?></span>
							</a>
						<? } else {
							?><div class="product-not-available"><? if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) { echo $arParams["MESS_NOT_AVAILABLE"]; } else { echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE"); } ?></div><?
						} ?>
					</div><?
				}
			} ?>
			<a class="about-product-link" href="<?=$arResult["DETAIL_PAGE_URL"];?>" target="_parent" title="<?=$arResult["NAME"];?>"><?=GetMessage("SF_ITEM_DETAIL");?></a>
            <style>
                .product_labels #advantage{
                    position: inherit !important;
                    float: right;
                }
            </style>
            <div class="product_labels">
                <? if ('Y' == $arParams['BRAND_USE']) {
                    ?><? $APPLICATION->IncludeComponent("bitrix:catalog.brandblock", ".default", array(
                        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                        "ELEMENT_ID" => $arResult['ID'],
                        "ELEMENT_CODE" => "",
                        "PROP_CODE" => $arParams['BRAND_PROP_CODE'],
                        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                        "CACHE_TIME" => $arParams['CACHE_TIME'],
                        "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                        "WIDTH" => "",
                        "HEIGHT" => ""
                    ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );
                }?>
            </div>
        </div>
	</div>

    <div class="product-divider"></div>

	<? $unset_props = Array("NEWPRODUCT", "SALELEADER", "SPECIALOFFER", $arParams["ADD_PICT_PROP"], $arParams["OFFER_ADD_PICT_PROP"], "RECOMMEND", "MINIMUM_PRICE", "MAXIMUM_PRICE");
	if (count($unset_props) > 0) {
		foreach ($arResult["DISPLAY_PROPERTIES"] as $key => $value) {
			if (in_array($key, $unset_props)) { unset($arResult["DISPLAY_PROPERTIES"][$key]); }
		}
		if (count($arResult["OFFERS"]) > 0) {
			foreach ($arResult["OFFERS"] as $key0 => $arOffer) {
				foreach ($arOffer["DISPLAY_PROPERTIES"] as $key => $value) {
					if (in_array($key, $unset_props)) { unset($arResult["OFFERS"][$key0]["DISPLAY_PROPERTIES"][$key]); }
				}
			}
		}
	} ?>

    <div class="tabs_header">
        <? if (strlen($arResult["DETAIL_TEXT"]) > 0) { ?>
            <div class="tabs_head">
                <a id="description_pp" href="#" data-href=".dt1" title="<?=GetMessage("FULL_DESCRIPTION");?>">
                    <span class="icon_description icons_head"></span>
                    <span class="text"><?= GetMessage("FULL_DESCRIPTION"); ?></span>
                </a>
            </div>
        <? } ?>
        <? if (count($arResult["DISPLAY_PROPERTIES"]) > 0) { ?>
            <div class="tabs_head">
                <a id="characters_pp" href="#" data-href=".dt2" title="<?=GetMessage("SF_ITEM_PARAMS");?>">
                    <span class="icon_item_params icons_head"></span>
                    <span class="text"><?= GetMessage("SF_ITEM_PARAMS"); ?></span>
                </a>
            </div>
        <? } ?>
        <? if ($arParams["USE_STORE"] == "Y" && \Bitrix\Main\ModuleManager::isModuleInstalled("catalog")) { ?>
            <div class="tabs_head">
                <a id="restoran_pp" href="#" data-href=".dt3" title="<?=GetMessage("SF_ITEM_PARAMS");?>">
                    <span class="icon_ostatok icons_head"></span>
                    <span class="text"><?= GetMessage("OSTATOK_STORE"); ?></span>
                </a>
            </div>
        <? } ?>
    </div>
	<div class="tabs_bodyes">
		<? if (strlen($arResult["DETAIL_TEXT"]) > 0) { ?>
			<div class="tabs_body dt1">
				<div class="main_detail_text offers_hide"><?=$arResult["DETAIL_TEXT"];?></div>
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) {
						if (strlen($arOffer["DETAIL_TEXT"]) > 0) { ?>
							<div class="main_detail_text_<?=$arOffer["ID"];?> offers_hide" style="display: none;"><?=$arOffer["DETAIL_TEXT"];?></div>
						<? }
					}
				} ?>
			</div>
		<? } ?>
		<? if (count($arResult["DISPLAY_PROPERTIES"]) > 0) {
			?><div class="tabs_body dt2">
				<div class="main_detail_props offers_hide">
					<div class="item_props">
						<? foreach ($arResult["DISPLAY_PROPERTIES"] as $key => $value) {
							?><p><span class="prop_name"><?=$value["NAME"];?></span><span class="prop_value"><?=(is_array($value["DISPLAY_VALUE"]) ? implode(' / ', $value["DISPLAY_VALUE"]) : $value["DISPLAY_VALUE"]);?></span></p><?
						} ?>
					</div>
				</div>
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) {
						if (count($arOffer["DISPLAY_PROPERTIES"]) > 0) { ?>
							<div class="main_detail_props_<?=$arOffer["ID"];?> offers_hide" style="display: none;">
								<div class="item_props">
									<? foreach ($arOffer["DISPLAY_PROPERTIES"] as $key => $value) {
										?><p><span class="prop_name"><?=$value["NAME"];?></span><span class="prop_value"><?=(is_array($value["DISPLAY_VALUE"]) ? implode(' / ', $value["DISPLAY_VALUE"]) : $value["DISPLAY_VALUE"]);?></span></p><?
									} ?>
								</div>
							</div>
						<? }
					}
				} ?>
			</div><?
		} ?>
		<? if ($arParams["USE_STORE"] == "Y" && \Bitrix\Main\ModuleManager::isModuleInstalled("catalog")) {
			?><div class="tabs_body dt3">
					<div class="main_detail_quant offers_hide"><?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", ".default", array(
					"PER_PAGE" => "1000",
					"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
					"SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
					"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
					"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
					"ELEMENT_ID" => $arResult["ID"],
					"STORE_PATH"  =>  $arParams["STORE_PATH"],
					"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
				),
				$component
				);?></div>
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) {
						?><div class="main_detail_quant_<?=$arOffer["ID"];?> offers_hide" style="display: none;"><?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", ".default", array(
							"PER_PAGE" => "1000",
							"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
							"SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
							"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
							"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
							"ELEMENT_ID" => $arOffer["ID"],
							"STORE_PATH"  =>  $arParams["STORE_PATH"],
							"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
						),
						$component
						);?></div><?
					}
				} ?>
			</div><?
		} ?>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			img_box_height ();
			if ($(".tabs_header").find(".tabs_head").length > 0) {
				$(".tabs_header").find(".tabs_head a").eq(0).click();
				$(".tabs_header").find(".tabs_head").eq(0).addClass('active');
				$(".tabs_header").removeClass("tabs_header--opened");
				$(".tabs_body:first-child").addClass("active");
				if ($(".tabs_header").find(".tabs_head").length == 1) {
					$(".tabs_header").find(".tabs_head a").css("width", "100%");
				}
			}
		});
		$(window).load(function() { img_box_height (); });
		$(window).resize(function () { img_box_height (); });
		function img_box_height () {
			var height = 0;
			$(".img_box").find(".main_detail_slider_box.active_box").each(function () {
				if (parseFloat($(this).height()) > height) { height = parseFloat($(this).height()); }
			});
			$(".bx_item_detail .img_box").css("min-height", height + "px");
		}
	</script>
	<div id="bx-composite-banner" class="popup_composite_banner" style="display: none;"></div>
</div>