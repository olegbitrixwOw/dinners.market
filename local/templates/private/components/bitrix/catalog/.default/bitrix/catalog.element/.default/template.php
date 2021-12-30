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
);
?>

<!--rating-->
<div class="bx_item_detail_rating_wrapper">
	<? if ('Y' == $arParams['USE_VOTE_RATING']) {
	?><?$APPLICATION->IncludeComponent(
		"bitrix:iblock.vote",
		"stars",
		array(
			"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['IBLOCK_ID'],
			"ELEMENT_ID" => $arResult['ID'],
			"ELEMENT_CODE" => "",
			"MAX_VOTE" => "5",
			"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
			"SET_STATUS_404" => "N",
			"DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
			"CACHE_TYPE" => $arParams['CACHE_TYPE'],
			"CACHE_TIME" => $arParams['CACHE_TIME']
		),
		$component,
		array("HIDE_ICONS" => "Y")
	);?>


	<a href="#" class="detail_buy_button">
		<span class="icon"></span>
		<span class="text"><?=GetMessage("CT_BCE_CATALOG_BUY");?></span>
	</a>
	<? } ?>
</div>
<!--rating-->

<div class="bx_item_detail box padding good_box" id="<?=$this->GetEditAreaId($arResult["ID"]);?>" itemscope itemtype="http://schema.org/Product">
    <script>
        $('body').addClass('is-product-page');
    </script>
	<div class="row product-wrapper">
		<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 product-images-wrapper">
			<div class="img_box">
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) {
						if (count($arOffer["PHOTO_SLIDER"]) > 0) { ?>
							<div class="main_detail_slider_<?=$arOffer["ID"];?> main_detail_slider_box">
								<div class="flexslider slider" id="slider_<?=$arOffer["ID"];?>">
									<ul class="slides">
										<? foreach ($arOffer["PHOTO_SLIDER"] as $key => $value) { ?>
											<li>
												<a href="<?=(strlen($value["SRC"]) > 1?$value["SRC"]:$value["ORIGINAL_SRC"]);?>" title="<?=$strTitle;?>" class="fancybox" rel="gallery_<?=$arOffer["ID"];?>" itemprop="image" style="background-image:url(<?=(strlen($value["SRC"]) > 1?$value["SRC"]:$value["ORIGINAL_SRC"]);?>);"></a>
											</li>
										<? } ?>
									</ul>
									<div class="icon_box"><?
										if (strlen($arResult["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["NAME"].'"></div>'; }
										if (strlen($arResult["DISPLAY_PROPERTIES"]["SALELEADER"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["SALELEADER"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["SALELEADER"]["NAME"].'"></div>'; }
										if (strlen($arResult["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["NAME"].'"></div>'; }
										if (strlen($arResult["DISPLAY_PROPERTIES"]["PRECOMMEND"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["PRECOMMEND"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["PRECOMMEND"]["NAME"].'"></div>'; }
									?></div>
								</div>
								<? if (count($arOffer["PHOTO_SLIDER"]) > 1) { ?>
									<div class="flexslider carousel" id="carousel_<?=$arOffer["ID"];?>">
										<ul class="slides">
											<? foreach ($arOffer["PHOTO_SLIDER"] as $key => $value) {
												?><li>
													<a href="<?=$value["ORIGINAL_SRC"];?>" style="background-image: url(<?=(strlen($value["SRC"]) > 1?$value["SRC"]:$value["ORIGINAL_SRC"]);?>)"></a>
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
								<? foreach ($arResult["PHOTO_SLIDER"] as $key => $value) { ?>
									<li>
										<a href="<?=(strlen($value["SRC"]) > 1?$value["SRC"]:$value["ORIGINAL_SRC"]);?>" title="<?=$strTitle;?>" class="fancybox" rel="gallery_<?=$arResult["ID"];?>" itemprop="image" style="background-image:url(<?=(strlen($value["SRC"]) > 1?$value["SRC"]:$value["ORIGINAL_SRC"]);?>);"></a>
									</li>
								<? } ?>
							</ul>
							<div class="icon_box"><?
								if (strlen($arResult["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["NAME"].'"></div>'; }
								if (strlen($arResult["DISPLAY_PROPERTIES"]["SALELEADER"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["SALELEADER"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["SALELEADER"]["NAME"].'"></div>'; }
								if (strlen($arResult["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["NAME"].'"></div>'; }
								if (strlen($arResult["DISPLAY_PROPERTIES"]["PRECOMMEND"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arResult["DISPLAY_PROPERTIES"]["PRECOMMEND"]["CODE"]).'" title="'.$arResult["DISPLAY_PROPERTIES"]["PRECOMMEND"]["NAME"].'"></div>'; }
							?></div>
						</div>
						<? if (count($arResult["PHOTO_SLIDER"]) > 1) { ?>
							<div class="flexslider carousel" id="carousel_<?=$arResult["ID"];?>">
								<ul class="slides">
									<? foreach ($arResult["PHOTO_SLIDER"] as $key => $value) {
										?><li>
											<a href="<?=$value["ORIGINAL_SRC"];?>" style="background-image: url(<?=(strlen($value["SRC"]) > 1?$value["SRC"]:$value["ORIGINAL_SRC"]);?>);"></a>
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
				<? } ?>
				<? if (!(count($arResult["PHOTO_SLIDER"]) > 0) && !(count($arResult["OFFERS"]) > 0)) { ?>
				<img style="padding: 100px;" src="<?=SITE_TEMPLATE_PATH?>/images/no-img.png" alt="" />
				<? } ?>
			</div>
		</div>
		<? if ('Y' == $arParams['BRAND_USE']) { ?>
		<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 product-description-wrapper">
		<? } else { ?>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-8">
		<? } ?>
			<? if ("Y" == $arParams["DISPLAY_NAME"]) { ?>
				<div class="bx_item_title">
					<h2 itemprop="name">
						<? echo (
							isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
							? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
							: $arResult["NAME"]
						); ?>
					</h2>
				</div>
			<? } ?>

			<!-- price block-->
			<div class="product-price-block clearfix">
				<? if ($arParams["USE_PRODUCT_QUANTITY"] == 1) {
					?><div class="product-quantity-wrapper"><div class="detail_p_head"><?=GetMessage("CATALOG_QUANTITY");?></div><?
					?><div class="item_quantity">
					<a href="javascript: void(0);" class="minus">-</a><?
						?><input type="text" name="<?=$arParams["PRODUCT_QUANTITY_VARIABLE"];?>" value="<?=$arResult["CATALOG_MEASURE_RATIO"] ?: 1;?>" /><?
					?><a href="javascript: void(0);" class="plus">+</a>
					</div></div><?
				} ?>
				<div class="main_detail_price_<?=$arResult["ID"];?> offers_hide">
					<? $priceItems = Array();
					$db_get = CCatalogGroup::GetList(Array("SORT" => "ASC"), Array(), false, false, Array("ID", "NAME_LANG"));
					while ($ar_get = $db_get->Fetch()) {
						$priceItems[$ar_get["ID"]] = $ar_get;
					} ?>
					<? if (count($arResult["PRICES"]) > 1) {
						$i = 0;
						foreach ($arResult["PRICES"] as $arPrice) { ?>
							<? if ($i > 0) { ?>
								<br />
							<? } ?>
							<div class="detail_p_head">
								<?=$priceItems[$arPrice["PRICE_ID"]]["NAME_LANG"]; ?>
								<? if ($arPrice["DISCOUNT_DIFF"] > 0) { ?>
									<span class="economy_price">
										<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', GetMessage("ECONOMY_INFO", array("#ECONOMY#" => $arPrice["PRINT_DISCOUNT_DIFF"])));?>
									</span>
								<? } ?>
							</div>
							<div class="fl">
								<? if ($arPrice["DISCOUNT_DIFF"] > 0) { ?>
									<span class="old_price">
										<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arPrice["PRINT_VALUE"]);?>
									</span>
								<? } ?>
								<span class="price">
									<? if (count($arResult["OFFERS"]) > 0) { echo GetMessage("SF_ISSET_OFFERS"); } ?>
									<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arPrice["PRINT_DISCOUNT_VALUE"]);?>
								</span>
							</div>
							<? $i++; }
					} else { ?>
						<div class="detail_p_head">
							<?=GetMessage("SF_PRICE");?>
							<? if ($arResult["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
								<span class="economy_price">
									<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', GetMessage("ECONOMY_INFO", array("#ECONOMY#" => $arResult["MIN_PRICE"]["PRINT_DISCOUNT_DIFF"])));?>
								</span>
							<? } ?>
						</div>
						<div class="fl">
							<? if ($arResult["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
								<span class="old_price">
									<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arResult["MIN_PRICE"]["PRINT_VALUE"]);?>
								</span>
							<? } ?>
							<span class="price">
								<? if (count($arResult["OFFERS"]) > 0) { echo GetMessage("SF_ISSET_OFFERS"); } ?>
								<?=show_price($arResult["MIN_PRICE"]["DISCOUNT_VALUE"], $arParams["CURRENCY_ID"])?>
							</span>
						</div>

					<? } ?>
					<? if ($arResult["CAN_BUY"]) { ?>
						<a
							href="<?=$arResult["ADD_URL"];?>"
							class="detail_buy_button show_basket_popup inline buy_button_a bb1"
							data-name="<?=$arResult["NAME"];?>"
							data-img="<?=(strlen($arResult["PHOTO_SLIDER"]["0"]["SRC"]) > 1?$arResult["PHOTO_SLIDER"]["0"]["SRC"]:$arResult["PHOTO_SLIDER"]["0"]["ORIGINAL_SRC"]);?>"
							data-id="<?=$arResult["ID"];?>"
							data-ratio="<?=$arResult["CATALOG_MEASURE_RATIO"];?>"
							data-basket="<?=$arParams["BASKET_URL"];?>"
							data-price="<?=str_replace(GetMessage("STUDIOFACT_RUB"), '', $arResult["MIN_PRICE"]["DISCOUNT_VALUE"] / ($arResult["CATALOG_MEASURE_RATIO"])?:1);?>"
							data-gotobasket="<?=GetMessage("SF_GO_TO_BASKET_BUTTON");?>"
							data-gotoback="<?=GetMessage("SF_GO_TO_BACK_BUTTON");?>"
						>
							<span class="icon"></span>
							<span class="text"><?=('' != $arParams['MESS_BTN_BUY']?$arParams['MESS_BTN_BUY']:GetMessage('CT_BCE_CATALOG_BUY'));?></span>
						</a>
					<? } else {
						?><div class="product-not-available"><? if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) { echo $arParams["MESS_NOT_AVAILABLE"]; } else { echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE"); } ?></div><?
					} ?>
				</div>
                <?if (count($arResult["OFFERS"]) > 0){?>
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
                <?}?>
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) { ?>
						<div class="main_detail_price_<?=$arOffer["ID"];?> offers_hide" style="display: none;">
						<? if (count($arOffer["PRICES"]) > 1) {
							$i = 0;
							foreach ($arOffer["PRICES"] as $arPrice) { ?>
								<? if ($i > 0) { ?>
									<br />
								<? } ?>
								<div class="detail_p_head">
									<?=$priceItems[$arPrice["PRICE_ID"]]["NAME_LANG"];?>
									<? if ($arPrice["DISCOUNT_DIFF"] > 0) { ?>
										<span class="economy_price">
											<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', GetMessage("ECONOMY_INFO", array("#ECONOMY#" => $arPrice["PRINT_DISCOUNT_DIFF"])));?>
										</span>
									<? } ?>
								</div>
								<div class="fl">
									<? if ($arPrice["DISCOUNT_DIFF"] > 0) { ?>
										<span class="old_price">
											<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arPrice["PRINT_VALUE"]);?>
										</span>
									<? } ?>
									<span class="price">
										<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arPrice["PRINT_DISCOUNT_VALUE"]);?>
									</span>
								</div>

								<? $i++; }
						} else { ?>
							<div class="detail_p_head">
								<?=GetMessage("SF_PRICE");?>
								<? if ($arOffer["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
									<span class="economy_price">
											<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', GetMessage("ECONOMY_INFO", array("#ECONOMY#" => $arOffer["MIN_PRICE"]["PRINT_DISCOUNT_DIFF"])));?>
									</span>
								<? } ?>
							</div>
							<div class="fl">
								<? if ($arOffer["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
									<span class="old_price">
										<?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arOffer["MIN_PRICE"]["PRINT_VALUE"]);?>
									</span>
								<? } ?>
								<span class="price">
									<?=show_price($arOffer["MIN_PRICE"]["DISCOUNT_VALUE"] * $arOffer["CATALOG_MEASURE_RATIO"], $arParams["CURRENCY_ID"])?>
								</span>
							</div>
						<? } ?>
						<? if ($arOffer["CAN_BUY"]) { ?>
							<a
								href="<?=$arOffer["ADD_URL"];?>"
								class="detail_buy_button show_basket_popup inline add_to_basket buy_button_a"
								data-name="<?=$arOffer["NAME"];?>"
								data-img="<?=(strlen($arOffer["PHOTO_SLIDER"]["0"]["SRC"]) > 1?$arOffer["PHOTO_SLIDER"]["0"]["SRC"]:$arResult["PHOTO_SLIDER"]["0"]["ORIGINAL_SRC"]);?>"
								data-id="<?=$arOffer["ID"];?>"
								data-ratio="<?=$arOffer["CATALOG_MEASURE_RATIO"];?>"
								data-basket="<?=$arParams["BASKET_URL"];?>"
								data-price="<?=str_replace(GetMessage("STUDIOFACT_RUB"), '', $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"]);?>"
								data-gotobasket="<?=GetMessage("SF_GO_TO_BASKET_BUTTON");?>"
								data-gotoback="<?=GetMessage("SF_GO_TO_BACK_BUTTON");?>"
							>
								<span class="icon"></span>
								<span class="text"><?=('' != $arParams['MESS_BTN_BUY']?$arParams['MESS_BTN_BUY']:GetMessage('CT_BCE_CATALOG_BUY'));?></span>
							</a>
						<? } else {
							?><? if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) { echo $arParams["MESS_NOT_AVAILABLE"]; } else { echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE"); } ?><?
						} ?>
						</div><?
					}
				} ?>
			</div>
			<!-- price block-->

			<!--options block-->
			<div class="product-options-wrapper">
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) {
						if (strlen($arOffer["PREVIEW_TEXT"]) > 0) {
							echo '<div class="detail_preview_text main_detail_preview_text_'.$arOffer["ID"].' offers_hide" itemprop="description" style="display: none;">'.$arOffer["PREVIEW_TEXT"].'</div>';
						}
					}
				} ?>
				<? if (count($arResult["OFFERS"]) > 0) {
					?><div class="offers_item" id="skuId<?=$arResult["ID"];?>" itemprop="sku">
						<? foreach ($arResult["SKU_PROPS"] as $arSku) {
							if (count($arSku["VALUES"]) > 0 && count($arResult["SKU_THERE_ARE"][$arSku["ID"]]) > 0) {
								echo '<div class="offer_item" data-prop-id="'.$arSku["ID"].'"><div class="offer_name">'.$arSku["NAME"].'</div>';
									foreach ($arSku["VALUES"] as $value) {
										if ($value["ID"] > 0 && in_array($value["ID"], $arResult["SKU_THERE_ARE"][$arSku["ID"]])) {
											?><span class="offer_sku <?if (!empty($arSku["USER_TYPE_SETTINGS"])) echo 'color'?>" data-prop-id="<?=$arSku["ID"];?>" data-prop-code="<?=$arSku["CODE"];?>" data-prop-value-id="<?=$value["ID"];?>" data-tree='<?=json_encode($arResult["SKU_TREE"]);?>'><?=(strlen($value["PICT"]["SRC"]) > 0?'<img src="'.$value["PICT"]["SRC"].'" title="'.$value["NAME"].'" alt="'.$value["NAME"].'" />':$value["NAME"]);?></span><?
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

				<? if (strlen($arResult["PREVIEW_TEXT"]) > 0) {
					echo '<div class="detail_preview_text main_detail_preview_text offers_hide" itemprop="description">'.$arResult["PREVIEW_TEXT"].'</div>';
				} ?>

				<!--brands block-->
				<? if ('Y' == $arParams['BRAND_USE']) {
					?><?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", ".default", array(
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
					);?><?
				}
				?>
				<!--brands block-->

			</div>
			<!--options block-->
		</div>
		<div class="col-lg-3 col-md-2 col-sm-4 col-xs-4" style="display: none;">
			<div class="bx_optionblock col-lg-12">
				<?if ('Y' == $arParams['BRAND_USE']) {
					?><?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", ".default", array(
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
					);?><?
				}?>
			</div>
            <? if ($arResult["CAN_BUY"]) { ?>
				<a
					href="<?=$arResult["ADD_URL"];?>"
					class="detail_buy_button show_basket_popup inline buy_button_a"
					data-name="<?=$arResult["NAME"];?>"
					data-img="<?=(strlen($arResult["PHOTO_SLIDER"]["0"]["SRC"]) > 1?$arResult["PHOTO_SLIDER"]["0"]["SRC"]:$arResult["PHOTO_SLIDER"]["0"]["ORIGINAL_SRC"]);?>"
					data-id="<?=$arResult["ID"];?>"
					data-basket="<?=$arParams["BASKET_URL"];?>"
					data-price="<?=str_replace(GetMessage("STUDIOFACT_RUB"), '', $arResult["MIN_PRICE"]["DISCOUNT_VALUE"]);?>"
					data-gotobasket="<?=GetMessage("SF_GO_TO_BASKET_BUTTON");?>"
					data-gotoback="<?=GetMessage("SF_GO_TO_BACK_BUTTON");?>"
				>
                    <span class="icon"></span>
                    <span class="text"><?=('' != $arParams['MESS_BTN_BUY']?$arParams['MESS_BTN_BUY']:GetMessage('CT_BCE_CATALOG_BUY'));?></span>
                </a>
            <? } else {
                ?><br /><? if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) { echo $arParams["MESS_NOT_AVAILABLE"]; } else { echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE"); } ?><br /><br /><?
            } ?>
		</div>
	</div>

		<? if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
			if ($arResult['OFFER_GROUP']) {
				foreach ($arResult['OFFERS'] as $arOffer) {
					if (!$arOffer['OFFER_GROUP'])
						continue; ?>
					<span id="<? echo $arItemIDs['OFFER_GROUP'].$arOffer['ID']; ?>" style="display: none;">
					<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
						".default",
						array(
							"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
							"ELEMENT_ID" => $arOffer['ID'],
							"PRICE_CODE" => $arParams["PRICE_CODE"],
							"BASKET_URL" => $arParams["BASKET_URL"],
							"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
							"CACHE_TYPE" => $arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
							"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
							"CURRENCY_ID" => $arParams["CURRENCY_ID"],
						),
						$component,
						array("HIDE_ICONS" => "N")
					);?>
				</span>
				<? }
			}
		} else {
			if ($arResult['MODULES']['catalog']) {
				?><?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
					".default",
					array(
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"ELEMENT_ID" => $arResult["ID"],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"BASKET_URL" => $arParams["BASKET_URL"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
						"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					),
					$component,
					array("HIDE_ICONS" => "N")
				);?><?
			}
		} ?>
        
<?php $recom = $arResult["DISPLAY_PROPERTIES"]["RECOMMEND"];?>

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

		<div class="product-divider"></div>

	<div class="tabs_header">
		<? if (strlen($arResult["DETAIL_TEXT"]) > 0) { ?>
            <div class="tabs_head">
                <a id="description" href="#" data-href=".dt1" title="<?=GetMessage("FULL_DESCRIPTION");?>">
                    <span class="icon_description icons_head"></span>
                    <span class="text"><?=GetMessage("FULL_DESCRIPTION");?></span>
                </a>
            </div>
        <? } ?>
		<? if (count($arResult["DISPLAY_PROPERTIES"]) > 0) { ?>
            <div class="tabs_head">
                <a id="characters" href="#" data-href=".dt2" title="<?=GetMessage("SF_ITEM_PARAMS");?>">
                    <span class="icon_item_params icons_head"></span>
                    <span class="text"><?=GetMessage("SF_ITEM_PARAMS");?></span>
                </a>
            </div>
        <? } ?>
		<? if ($arParams["USE_STORE"] == "Y" && \Bitrix\Main\ModuleManager::isModuleInstalled("catalog")) { ?>
            <div class="tabs_head">
                <a id="restoran" href="#" data-href=".dt3" title="<?=GetMessage("SF_ITEM_PARAMS");?>">
                    <span class="icon_ostatok icons_head"></span>
                    <span class="text"><?=GetMessage("OSTATOK_STORE");?></span>
                </a>
            </div>
        <? } ?>
		<? if ("Y" == $arParams["USE_COMMENTS"]) { ?>
            <div class="tabs_head">
                <a id="comment" href="#" data-href=".dt4" title="<?=GetMessage("COMMENTARY");?>" onClick="showComment('0'); $(this).removeAttr('onClick');">
                    <span class="icon_comments icons_head"></span>
                    <span class="text"><?=GetMessage("COMMENTARY");?></span>
                </a>
            </div>
        <? } ?>
	</div>
	<div class="tabs_bodyes">
		<? if (strlen($arResult["DETAIL_TEXT"]) > 0) { ?>
			<div class="tabs_body dt1">
				<div class="main_detail_text offers_hide" itemprop="description"><?=$arResult["DETAIL_TEXT"];?></div>
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) {
						if (strlen($arOffer["DETAIL_TEXT"]) > 0) { ?>
							<div class="main_detail_text_<?=$arOffer["ID"];?> offers_hide" itemprop="description" style="display: none;">
                                <?=$arOffer["DETAIL_TEXT"];?>
                            </div>
						<? }
					}
				} ?>
			</div>
		<? } ?>
		<? if (count($arResult["DISPLAY_PROPERTIES"]) > 0) { ?>
            <div class="tabs_body dt2">
				<div class="main_detail_props offers_hide">
					<div class="item_props">
						<? foreach ($arResult["DISPLAY_PROPERTIES"] as $key => $value) { ?>
                            <p>
                                <span class="prop_name">
                                    <?=$value["NAME"];
                                    if(!empty($value["HINT"])) { ?>
                                        <span class="quest">?<span class="tooltip"><?=$value["HINT"]?></span></span>
                                    <?}?>
                                </span>
                                <span class="prop_value">
                                    <?=(is_array($value["DISPLAY_VALUE"])?implode(' / ', $value["DISPLAY_VALUE"]):$value["DISPLAY_VALUE"]);?>
                                </span>
                            </p>
                        <? } ?>
					</div>
				</div>
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) {
						if (count($arOffer["DISPLAY_PROPERTIES"]) > 0) { ?>
							<div class="main_detail_props_<?=$arOffer["ID"];?> offers_hide" style="display: none;">
								<div class="item_props">
									<? foreach ($arOffer["DISPLAY_PROPERTIES"] as $key => $value) {?>
                                        <p>
                                            <span class="prop_name">
                                                <?=$value["NAME"];
                                                if(!empty($value["HINT"])) { ?>
                                                    <span class="quest">?<span class="tooltip"><?=$value["HINT"]?></span></span>
                                                <? } ?>
                                            </span>
                                            <span class="prop_value">
                                                <?=(is_array($value["DISPLAY_VALUE"])?implode(' / ', $value["DISPLAY_VALUE"]):$value["DISPLAY_VALUE"]);?>
                                            </span>
                                        </p>
                                    <? } ?>
								</div>
							</div>
						<? }
					}
				} ?>
			</div><?
		} ?>
		<? if ($arParams["USE_STORE"] == "Y" && \Bitrix\Main\ModuleManager::isModuleInstalled("catalog")) {
			?><div class="tabs_body dt3">
				<div class="main_detail_quant offers_hide">
					<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", ".default", array(
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
					);?>
				</div>
				<? if (count($arResult["OFFERS"]) > 0) {
					foreach ($arResult["OFFERS"] as $arOffer) {
						?><div class="main_detail_quant_<?=$arOffer["ID"];?>offers_hide" style="display: none;"><?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", ".default", array(
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
		<? if ("Y" == $arParams["USE_COMMENTS"]) { ?>
			<div class="tabs_body dt4">
				<? //$frame = $this->createFrame()->begin(); ?>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.comments",
                    "",
                    array(
                        "ELEMENT_ID" => $arResult['ID'],
                        "ELEMENT_CODE" => "",
                        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                        "SHOW_DEACTIVATED" => $arParams['SHOW_DEACTIVATED'],
                        "URL_TO_COMMENT" => "",
                        "WIDTH" => "",
                        "COMMENTS_COUNT" => "5",
                        "BLOG_USE" => $arParams['BLOG_USE'],
                        "FB_USE" => $arParams['FB_USE'],
                        "FB_APP_ID" => $arParams['FB_APP_ID'],
                        "VK_USE" => $arParams['VK_USE'],
                        "VK_API_ID" => $arParams['VK_API_ID'],
                        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                        "CACHE_TIME" => $arParams['CACHE_TIME'],
                        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                        "BLOG_TITLE" => "",
                        "BLOG_URL" => $arParams['BLOG_URL'],
                        "PATH_TO_SMILE" => "",
                        "EMAIL_NOTIFY" => $arParams['BLOG_EMAIL_NOTIFY'],
                        "AJAX_POST" => "Y",
                        "SHOW_SPAM" => "Y",
                        "SHOW_RATING" => "N",
                        "FB_TITLE" => "",
                        "FB_USER_ADMIN_ID" => "",
                        "FB_COLORSCHEME" => "light",
                        "FB_ORDER_BY" => "reverse_time",
                        "VK_TITLE" => "",
                        "TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME']
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );?>
				<? //$frame->end(); ?>
			</div>
		<? } ?>
	</div>
</div>
        <? if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
        {   //подарки
            
            

            $arBlock = CCatalog::GetByID($arParams["IBLOCK_ID"]);
            $APPLICATION->IncludeComponent(
                'bitrix:sale.gift.product',
                '',
                array(
                    'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
                    'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
                    'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
                    'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
                    'SUBSCRIBE_URL_TEMPLATE' => $arResult['~SUBSCRIBE_URL_TEMPLATE'],
                    'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
                    
                    "PROPERTY_CODE_".$arParams["IBLOCK_ID"] => $arParams["PROPERTY_CODE"],
                    "DETAIL_PROPERTY_CODE" =>$arParams["PROPERTY_CODE"],

                    'SHOW_DISCOUNT_PERCENT' => "Y",//$arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
                    'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
                    'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
                    'LINE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
                    'HIDE_BLOCK_TITLE' => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
                    'BLOCK_TITLE' => $arParams['GIFTS_DETAIL_BLOCK_TITLE'],
                    'TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
                    'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
                    'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
                    'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],

                    'SHOW_PRODUCTS_'.$arParams['IBLOCK_ID'] => 'Y',
                    'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                    'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
                    'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                    'PRICE_CODE' => $arParams['PRICE_CODE'],
                    'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
                    'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'BASKET_URL' => $arParams['BASKET_URL'],
                    'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
                    'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
                    'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
                    'USE_PRODUCT_QUANTITY' => 'N',
                    'OFFER_TREE_PROPS_'.$arBlock['OFFERS_IBLOCK_ID'] => $arParams['OFFER_TREE_PROPS'],
                    'CART_PROPERTIES_'.$arBlock['OFFERS_IBLOCK_ID'] => $arParams['OFFERS_CART_PROPERTIES'],
                    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                    'POTENTIAL_PRODUCT_TO_BUY' => array(
                        'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
                        'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
                        'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
                        'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
                        'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

                        'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
                        'SECTION' => array(
                            'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
                            'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
                            'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
                            'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
                        ),
                    )
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            );
        }

        //Товары с подарками
        if ($arResult['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
        {
            $APPLICATION->IncludeComponent(
                'bitrix:sale.gift.main.products',
                '',
                array(
                    'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
                    'BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],
                    'OFFERS_FIELD_CODE' => $arParams['OFFERS_FIELD_CODE'],
                    'OFFERS_PROPERTY_CODE' => $arParams['OFFERS_PROPERTY_CODE'],
                    'AJAX_MODE' => $arParams['AJAX_MODE'],

                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'ELEMENT_SORT_FIELD' => 'ID',
                    'ELEMENT_SORT_ORDER' => 'DESC',
                    'FILTER_NAME' => 'searchFilter',

                    'SECTION_URL' => $arParams['SECTION_URL'],
                    'DETAIL_URL' => $arParams['DETAIL_URL'],
                    'BASKET_URL' => $arParams['BASKET_URL'],
                    'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
                    'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
                    'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],

                    'SET_TITLE' => $arParams['SET_TITLE'],
                    'PROPERTY_CODE' => $arParams['PROPERTY_CODE'],
                    'PRICE_CODE' => $arParams['PRICE_CODE'],
                    'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                    'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],

                    'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
                    'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),

                    'ADD_PICT_PROP' => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),

                    'LABEL_PROP' => (isset($arParams['LABEL_PROP']) ? $arParams['LABEL_PROP'] : ''),
                    'OFFER_ADD_PICT_PROP' => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),
                    'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : ''),
                    'SHOW_DISCOUNT_PERCENT' => (isset($arParams['SHOW_DISCOUNT_PERCENT']) ? $arParams['SHOW_DISCOUNT_PERCENT'] : ''),
                    'SHOW_OLD_PRICE' => (isset($arParams['SHOW_OLD_PRICE']) ? $arParams['SHOW_OLD_PRICE'] : ''),
                    'MESS_BTN_BUY' => (isset($arParams['MESS_BTN_BUY']) ? $arParams['MESS_BTN_BUY'] : ''),
                    'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['MESS_BTN_ADD_TO_BASKET']) ? $arParams['MESS_BTN_ADD_TO_BASKET'] : ''),
                    'MESS_BTN_DETAIL' => (isset($arParams['MESS_BTN_DETAIL']) ? $arParams['MESS_BTN_DETAIL'] : ''),
                    'MESS_NOT_AVAILABLE' => (isset($arParams['MESS_NOT_AVAILABLE']) ? $arParams['MESS_NOT_AVAILABLE'] : ''),
                    'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
                    'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
                    'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) ? $arParams['DISPLAY_COMPARE'] : ''),
                    'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),
                )
                + array(
                    'OFFER_ID' => empty($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
                        ? $arResult['ID']
                        : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'],
                    'SECTION_ID' => $arResult['SECTION']['ID'],
                    'ELEMENT_ID' => $arResult['ID'],
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            );
        }


        ?>


<? function show_price($price, $currency) {
	return str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', CurrencyFormat($price, $currency));
}