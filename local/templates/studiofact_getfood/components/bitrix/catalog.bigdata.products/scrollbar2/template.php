<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$frame = $this->createFrame()->begin("");

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

$injectId = 'bigdata_recommeded_products_'.rand();

?>

<script type="text/javascript">
	BX.cookie_prefix = '<?=CUtil::JSEscape(COption::GetOptionString("main", "cookie_name", "BITRIX_SM"))?>';
	BX.cookie_domain = '<?=$APPLICATION->GetCookieDomain()?>';
	BX.current_server_time = '<?=time()?>';

	BX.ready(function(){
		bx_rcm_recommendation_event_attaching(BX('<?=$injectId?>'));
	});

</script>
  <div id="<?=$injectId?>" role="tabpanel" class="tab-pane active recommended-products" id="recommended-products"></div>
<?

if (isset($arResult['REQUEST_ITEMS']))
{
	CJSCore::Init(array('ajax'));

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.bd.products.recommendation'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.bd.products.recommendation');
	$arResult['RCM_PARAMS']["type"] = "any_personal";
	?>

<!--	<span id="--><?//=$injectId?><!--" class="bigdata_recommended_products_container"></span>-->

	<script type="text/javascript">
		BX.ready(function(){
			bx_rcm_get_from_cloud(
				'<?=CUtil::JSEscape($injectId)?>',
				<?=CUtil::PhpToJSObject($arResult['RCM_PARAMS'])?>,
				{
					'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
					'template': '<?=CUtil::JSEscape($signedTemplate)?>',
					'site_id': '<?=CUtil::JSEscape(SITE_ID)?>',
					'rcm': 'yes'
				}
			);
		});
	</script>

	<?
	$frame->end();
	return;
}


if (!empty($arResult['ITEMS']))
{
	?><script type="text/javascript">
	BX.message({
		CBD_MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CVP_TPL_MESS_BTN_BUY')); ?>',
		CBD_MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CVP_TPL_MESS_BTN_ADD_TO_BASKET')); ?>',

		CBD_MESS_BTN_DETAIL: '<? echo ('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CVP_TPL_MESS_BTN_DETAIL')); ?>',

		CBD_MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CVP_TPL_MESS_BTN_DETAIL')); ?>',
		CBD_BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CVP_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
		BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
		CBD_ADD_TO_BASKET_OK: '<? echo GetMessageJS('CVP_ADD_TO_BASKET_OK'); ?>',
		CBD_TITLE_ERROR: '<? echo GetMessageJS('CVP_CATALOG_TITLE_ERROR') ?>',
		CBD_TITLE_BASKET_PROPS: '<? echo GetMessageJS('CVP_CATALOG_TITLE_BASKET_PROPS') ?>',
		CBD_TITLE_SUCCESSFUL: '<? echo GetMessageJS('CVP_ADD_TO_BASKET_OK'); ?>',
		CBD_BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CVP_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
		CBD_BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('CVP_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
		CBD_BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CVP_CATALOG_BTN_MESSAGE_CLOSE') ?>'
	});
	</script>
  <?
  if (count($arResult["ITEMS"]) < 1) { return; }
  if (count($arResult["ITEMS"]) > 0) {
  $rand = $this->randString();
  ?>
  <? if (strlen($arParams["SECTION_NAME"]) > 0) { ?><div class="scrollSectionName"><?=$arParams["SECTION_NAME"];?></div><? } ?>

  <div class="section_box">
    <div class="scroll-standard"><?
      ?><div class="section adaptive_scroll_slider" id="section_<?=$rand;?>"><?
        $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
        $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
        $arElementDeleteParams = array("CONFIRM" => GetMessage("CT_BCS_TPL_ELEMENT_DELETE_CONFIRM"));
        foreach ($arResult["ITEMS"] as $arItem) {
          $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], $strElementEdit);
          $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], $strElementDelete, $arElementDeleteParams);
          $strMainID = $this->GetEditAreaId($arItem["ID"]);
          $can_buy = 0;
          if ($arItem["CAN_BUY"] == "1" && count($arItem["OFFERS"]) < 1) { $can_buy = 1; }
          if (count($arItem["OFFERS"]) > 0) {
            foreach ($arItem["OFFERS"] as $arOffer) {
              if ($arOffer["CAN_BUY"] == "1") { $can_buy = 1; }
            }
          }
          ?><div class="item_element good_box inline" id="<?=$strMainID;?>" data-id="<?=$arItem["ID"];?>">
          <div style="display: none;" itemscope itemtype="http://schema.org/Product">
            <meta itemprop="name" content="<?=$arItem["NAME"];?>" />
            <meta itemprop="description" content="<?=$arItem["PREVIEW_TEXT"];?>" />
            <meta itemprop="url" content="<?=$arItem["DETAIL_PAGE_URL"];?>" />
            <img itemprop="image" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>" />
          </div>
          <div class="hover_box box<? if ($can_buy != "1") { echo ' disabled'; } ?>">
            <div class="img_box">
              <a href="<?=$arItem["DETAIL_PAGE_URL"];?>" title="<?=$arItem["NAME"];?>" class="image" <? if (strlen($arItem["PREVIEW_PICTURE"]["SRC"]) > 0) { ?>style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>');"<? } ?>></a>
              <div class="hover_over">
                <a href="javascript:;" data-fancybox="group8" data-src="<?=$arItem["DETAIL_PAGE_URL"];?>?open_popup=Y" class="open_fancybox" rel="gallery">
                  <?=GetMessage("STUDIOFACT_FAST_VIEW");?>
                </a>
                <a href="<?=$arItem["DETAIL_PAGE_URL"];?>" title="<?=$arItem["NAME"];?>" class="image main_preview_image offers_hide"></a>
                <? if (count($arItem["OFFERS"]) > 0) {
                  foreach ($arItem["OFFERS"] as $arOffer) {
                    if (strlen($arOffer["PREVIEW_PICTURE"]["SRC"]) > 0) {
                      ?><a href="<?=$arItem["DETAIL_PAGE_URL"];?>" title="<?=$arItem["NAME"];?>" class="image main_preview_image_<?=$arOffer["ID"];?> offers_hide" <? if (strlen($arItem["PREVIEW_PICTURE"]["SRC"]) > 0) { ?>style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>'); display: none;"<? } ?>></a><?
                    }
                  }
                } ?>
              </div>
            </div>
            <a href="<?=$arItem["DETAIL_PAGE_URL"];?>" title="<?=$arItem["NAME"];?>" class="name"><?=$arItem["NAME"];?></a>
            <div class="icon_box"><?
              if (strlen($arItem["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arItem["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["CODE"]).'" title="'.$arItem["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["NAME"].'"></div>'; }
              if (strlen($arItem["DISPLAY_PROPERTIES"]["SALELEADER"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arItem["DISPLAY_PROPERTIES"]["SALELEADER"]["CODE"]).'" title="'.$arItem["DISPLAY_PROPERTIES"]["SALELEADER"]["NAME"].'"></div>'; }
              if (strlen($arItem["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arItem["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["CODE"]).'" title="'.$arItem["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["NAME"].'"></div>'; }
              if (strlen($arItem["DISPLAY_PROPERTIES"]["PRECOMMEND"]["VALUE"]) > 0) { echo '<div class="'.mb_strtolower($arItem["DISPLAY_PROPERTIES"]["PRECOMMEND"]["CODE"]).'" title="'.$arItem["DISPLAY_PROPERTIES"]["PRECOMMEND"]["NAME"].'"></div>'; }
              ?></div>

            <!--price-->
            <div class="price_box main_preview_price offers_hide">
              <div class="fl">
                <? if ($arItem["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?><div class="old_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arItem["MIN_PRICE"]["PRINT_VALUE"]);?></div><? } ?>
                <div class="price_box__actual-price"><? if (count($arItem["OFFERS"]) > 0) { echo GetMessage("SF_ISSET_OFFERS"); } ?><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]);?></div>
              </div>
              <div class="price_box__controls">
                <? if ($arParams["USE_PRODUCT_QUANTITY"] == 1 && $can_buy == 1) {?>
                  <div class="item_quantity inline">
                  <a data-inc-value="<?=$arResult["CATALOG_MEASURE_RATIO"] ?: 1;?>" href="javascript: void(0);" class="minus">-</a>
                  <input type="text" name="<?=$arParams["PRODUCT_QUANTITY_VARIABLE"];?>" value="<?=$arItem["CATALOG_MEASURE_RATIO"]?>" />
                  <a data-inc-value="<?=$arResult["CATALOG_MEASURE_RATIO"] ?: 1;?>" href="javascript: void(0);" class="plus">+</a>
                  </div><?
                } ?>
                <? if ($can_buy == "1") { ?>
                  <div class="buy_box fr">
                    <a
                      href="<?=$arItem["ADD_URL"];?>"
                      class="buy buy_button_a <? if (count($arItem["OFFERS"]) > 0) { echo 'show_offers_basket_popup'; } else { echo 'show_basket_popup'; } ?> inline"
                      data-name="<?=$arItem["NAME"];?>"
                      data-img="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>"
                      data-id="<?=$arItem["ID"];?>"
                      data-ratio="<?=$arItem["CATALOG_MEASURE_RATIO"];?>"
                      data-basket="<?=$arParams["BASKET_URL"];?>"
                      data-price="<?=str_replace(GetMessage("STUDIOFACT_RUB"), '', $arItem["MIN_PRICE"]["DISCOUNT_VALUE"]);?>"
                      data-gotobasket="<?=GetMessage("SF_GO_TO_BASKET_BUTTON");?>"
                      data-gotoback="<?=GetMessage("SF_GO_TO_BACK_BUTTON");?>"
                    >
										<span class="buy_popup">
											<span></span>
                      <?=('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));?>
										</span>
                    </a>
                  </div>
                <? } else {
                  ?><div class="buy_box fr price_box__not-avilable"><?if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) { echo $arParams["MESS_NOT_AVAILABLE"]; } else { echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE"); } ?></div><?
                } ?>
              </div>
              <div class="clear"></div>
            </div>
            <div class="scrollbar_offers_items">
              <? if (count($arItem["OFFERS"]) > 0) {
                ?><div class="offers_item" id="skuId<?=$arItem["ID"];?>">
                <?
                foreach ($arResult["SKU_PROPS"] as $arSku) {
                  if (count($arSku["VALUES"]) > 0 && count($arItem["SKU_THERE_ARE"][$arSku["ID"]]) > 0) {
                    echo '<div class="offer_item" data-prop-id="'.$arSku["ID"].'"><div class="offer_name">'.$arSku["NAME"].':</div>';
                    foreach ($arSku["VALUES"] as $value) {
                      if ($value["ID"] > 0 && in_array($value["ID"], $arItem["SKU_THERE_ARE"][$arSku["ID"]])) {
                        ?><span class="offer_sku" data-prop-id="<?=$arSku["ID"];?>" data-prop-code="<?=$arSku["CODE"];?>" data-prop-value-id="<?=$value["ID"];?>" data-tree='<?=json_encode($arItem["SKU_TREE"]);?>'><?=(strlen($value["PICT"]["SRC"]) > 0 ? '<img src="'.$value["PICT"]["SRC"].'" title="'.$value["NAME"].'" alt="'.$value["NAME"].'" />' : $value["NAME"]);?></span><?
                      }
                    }
                    echo '</div>';
                  }
                }
                echo '<div class="offers_item_id" style="display: none;">';
                foreach ($arItem["SKU_MASSIVE"] as $id => $value) {
                  ?><div class="<?=$id;?>" data-id="<?=$value;?>"></div><?
                }
                echo '</div>';
                ?>
                </div><?
              } ?>
              <div class="price_box main_preview_price offers_hide">
                <div class="pr">
                  <span class="price"><? if (count($arItem["OFFERS"]) > 0) { echo GetMessage("SF_ISSET_OFFERS"); } ?><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]);?></span>
                  <? if ($arItem["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?><span class="old_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arItem["MIN_PRICE"]["PRINT_VALUE"]);?></span><? } ?>
                </div>
                <? if ($arItem["CAN_BUY"]) { ?>
                  <div class="nav_buttons">
                    <a
                      href="<?=$arItem["ADD_URL"];?>"
                      target="_parent"
                      class="button show_basket_popup inline buy_button_a"
                      data-name="<?=$arItem["NAME"];?>"
                      data-img="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>"
                      data-id="<?=$arItem["ID"];?>"
                      data-ratio="<?=$arItem["CATALOG_MEASURE_RATIO"];?>"
                      data-basket="<?=$arParams["BASKET_URL"];?>"
                      data-price="<?=str_replace(GetMessage("STUDIOFACT_RUB"), '', $arItem["MIN_PRICE"]["DISCOUNT_VALUE"]);?>"
                      data-gotobasket="<?=GetMessage("SF_GO_TO_BASKET_BUTTON");?>"
                      data-gotoback="<?=GetMessage("SF_GO_TO_BACK_BUTTON");?>"
                    >
                      <?=('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));?></a>
                    <a href="javascript: void(0);" class="button_white"><?=GetMessage("SF_GO_TO_BACK_BUTTON");?>
                    </a>
                  </div>
                <? } else {
                  ?><div class="nav_buttons"><?if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) { echo $arParams["MESS_NOT_AVAILABLE"]; } else { echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE"); } ?><a href="javascript: void(0);" class="button_white"><?=GetMessage("SF_GO_TO_BACK_BUTTON");?></a></div><?
                } ?>
              </div>
              <? if (count($arItem["OFFERS"]) > 0) {
                foreach ($arItem["OFFERS"] as $arOffer) { ?>
                  <div class="price_box main_preview_price_<?=$arOffer["ID"];?> offers_hide" style="display: none;">
                    <div class="pr">
                      <span class="price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arOffer["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]);?></span>
                      <? if ($arOffer["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?><span class="old_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arOffer["MIN_PRICE"]["PRINT_VALUE"]);?></span><? } ?>
                    </div>
                    <? if ($arOffer["CAN_BUY"]) { ?>
                      <div class="nav_buttons">
                        <a
                          href="<?=$arOffer["ADD_URL"];?>"
                          target="_parent"
                          class="button show_basket_popup inline buy_button_a"
                          data-name="<?=$arOffer["NAME"];?>"
                          data-img="<?=(strlen($arOffer["PREVIEW_PICTURE"]["SRC"]) > 0 ? $arOffer["PREVIEW_PICTURE"]["SRC"] : $arItem["PREVIEW_PICTURE"]["SRC"]);?>"
                          data-id="<?=$arOffer["ID"];?>"
                          data-ratio="<?=$arOffer["CATALOG_MEASURE_RATIO"];?>"
                          data-basket="<?=$arParams["BASKET_URL"];?>"
                          data-price="<?=str_replace(GetMessage("STUDIOFACT_RUB"), '', $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"]);?>"
                          data-gotobasket="<?=GetMessage("SF_GO_TO_BASKET_BUTTON");?>"
                          data-gotoback="<?=GetMessage("SF_GO_TO_BACK_BUTTON");?>"
                        >
                          <?=('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));?>
                        </a>
                        <a href="javascript: void(0);" class="button_white"><?=GetMessage("SF_GO_TO_BACK_BUTTON");?></a>
                      </div>
                    <? } else {
                      ?><div class="nav_buttons"><?if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) { echo $arParams["MESS_NOT_AVAILABLE"]; } else { echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE"); } ?><a href="javascript: void(0);" class="button_white"><?=GetMessage("SF_GO_TO_BACK_BUTTON");?></a></div><?
                    } ?>
                  </div>
                <? }
              } ?>
            </div>
            <? if (count($arItem["OFFERS"]) > 0) {
              foreach ($arItem["OFFERS"] as $arOffer) { ?>
                <div class="good_box price_box main_preview_price_<?=$arOffer["ID"];?> offers_hide" style="display: none;">
                  <div class="fl">
                    <? if ($arOffer["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?><div class="old_price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arOffer["MIN_PRICE"]["PRINT_VALUE"]);?></div><? } ?>
                    <div class="price_box__actual-price"><?=str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">'.GetMessage("STUDIOFACT_R").'</span>', $arOffer["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]);?></div>
                  </div>

                  <div class="price_box__controls">
                    <? if ($arParams["USE_PRODUCT_QUANTITY"] == 1 && $can_buy == 1) {
                      ?><div class="item_quantity inline">
                      <a href="javascript: void(0);" class="minus">-</a><input type="text" name="<?=$arParams["PRODUCT_QUANTITY_VARIABLE"];?>" value="<?=$arItem["CATALOG_MEASURE_RATIO"]?>" /><a href="javascript: void(0);" class="plus">+</a>
                      </div><?
                    } ?>
                    <? if ($arOffer["CAN_BUY"]) { ?>
                      <div class="buy_box fr">
                        <a
                          href="<?=$arOffer["ADD_URL"];?>"
                          class="buy show_basket_popup inline buy_button_a"
                          data-name="<?=$arOffer["NAME"];?>"
                          data-img="<?=(strlen($arOffer["PREVIEW_PICTURE"]["SRC"]) > 0 ? $arOffer["PREVIEW_PICTURE"]["SRC"] : $arItem["PREVIEW_PICTURE"]["SRC"]);?>"
                          data-id="<?=$arOffer["ID"];?>"
                          data-ratio="<?=$arOffer["CATALOG_MEASURE_RATIO"];?>"
                          data-basket="<?=$arParams["BASKET_URL"];?>"
                          data-price="<?=str_replace(GetMessage("STUDIOFACT_RUB"), '', $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"]);?>"
                          data-gotobasket="<?=GetMessage("SF_GO_TO_BASKET_BUTTON");?>"
                          data-gotoback="<?=GetMessage("SF_GO_TO_BACK_BUTTON");?>"
                        >
													<span class="buy_popup">
														<span></span>
                            <?=('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));?>
													</span>
                        </a>
                      </div>
                    <? } else {
                      ?><div class="buy_box fr price_box__not-avilable"><?if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) { echo $arParams["MESS_NOT_AVAILABLE"]; } else { echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE"); } ?></div><?
                    } ?>
                  </div>

                  <div class="clear"></div>
                </div>
              <? }
            } ?>
            <!--price-->

            <? $unset_props = Array("NEWPRODUCT", "SALELEADER", "SPECIALOFFER", "PRECOMMEND", $arParams["ADD_PICT_PROP"], $arParams["OFFER_ADD_PICT_PROP"], "RECOMMEND", "MINIMUM_PRICE", "MAXIMUM_PRICE");
            if (count($unset_props) > 0) {
              foreach ($arItem["DISPLAY_PROPERTIES"] as $key => $value) {
                if (in_array($key, $unset_props)) { unset($arItem["DISPLAY_PROPERTIES"][$key]); }
              }
              if (count($arItem["OFFERS"]) > 0) {
                foreach ($arItem["OFFERS"] as $key0 => $arOffer) {
                  foreach ($arOffer["DISPLAY_PROPERTIES"] as $key => $value) {
                    if (in_array($key, $unset_props)) { unset($arItem["OFFERS"][$key0]["DISPLAY_PROPERTIES"][$key]); }
                  }
                }
              }
            } ?>

            <div class="list-info-buttons">
              <?php if (count($arItem["DISPLAY_PROPERTIES"]) > 0) {?><button class="list-props-button"><?= GetMessage("SF_CHARACTS")?></button><?php } ?>
              <?php if (count($arItem["OFFERS"]) > 0) { ?><button class="list-offer-button"><?= GetMessage("SF_SKU_SELECT")?></button><?php } ?>
            </div>

            <? if (count($arItem["OFFERS"]) > 0 || count($arItem["DISPLAY_PROPERTIES"]) > 0 || $arParams["USE_PRODUCT_QUANTITY"] == 1) { ?>
            <div class="hidden_hover_element">
              <? } ?>
              <? if (count($arItem["OFFERS"]) > 0) {
                ?><div class="offers_item" id="skuId<?=$arItem["ID"];?>" itemprop="sku">
                <?
                foreach ($arResult["SKU_PROPS"] as $arSku) {
                  $i = 0;
                  if (count($arSku["VALUES"]) > 0 && count($arItem["SKU_THERE_ARE"][$arSku["ID"]]) > 0) {
                    echo '<div class="offer_item" data-prop-id="'.$arSku["ID"].'"><div class="offer_name">'.$arSku["NAME"].'</div>';
                    foreach ($arSku["VALUES"] as $value) {
                      if ($value["ID"] > 0 && in_array($value["ID"], $arItem["SKU_THERE_ARE"][$arSku["ID"]])) {
                        ?><span class="offer_sku<?=(!$i)?' active':''?>" data-prop-id="<?=$arSku["ID"];?>" data-prop-code="<?=$arSku["CODE"];?>" data-prop-value-id="<?=$value["ID"];?>" data-tree='<?=json_encode($arItem["SKU_TREE"]);?>'><?=(strlen($value["PICT"]["SRC"]) > 0 ? '<img src="'.$value["PICT"]["SRC"].'" title="'.$value["NAME"].'" alt="'.$value["NAME"].'" />' : $value["NAME"]);?></span><? $i++;
                      }
                    }
                    echo '</div>';
                  }
                }
                echo '<div class="offers_item_id" style="display: none;">';
                foreach ($arItem["SKU_MASSIVE"] as $id => $value) {
                  ?><div class="<?=$id;?>" data-id="<?=$value;?>"></div><?
                }
                echo '</div>';
                ?>
                </div><?
              } ?>
              <? if (count($arItem["DISPLAY_PROPERTIES"]) > 0) {
                ?><div class="item_props main_preview_props offers_hide">
                <? foreach ($arItem["DISPLAY_PROPERTIES"] as $key => $value) {
                  ?><p><span class="prop_name"><?=$value["NAME"];?></span><span class="prop_value"><?=(is_array($value["DISPLAY_VALUE"]) ? implode(' / ', $value["DISPLAY_VALUE"]) : $value["DISPLAY_VALUE"]);?></span></p><?
                } ?>
                </div><?
              } ?>
              <? if (count($arItem["OFFERS"]) > 0) {
                foreach ($arItem["OFFERS"] as $arOffer) {
                  if (count($arOffer["DISPLAY_PROPERTIES"]) > 0) {
                    ?><div class="item_props main_preview_props_<?=$arOffer["ID"];?> offers_hide" style="display: none;">
                    <? foreach ($arOffer["DISPLAY_PROPERTIES"] as $key => $value) {
                      ?><p><span class="prop_name"><?=$value["NAME"];?></span><span class="prop_value"><?=(is_array($value["DISPLAY_VALUE"]) ? implode(' / ', $value["DISPLAY_VALUE"]) : $value["DISPLAY_VALUE"]);?></span></p><?
                    } ?>
                    </div><?
                  }
                }
              } ?>
              <? if (count($arItem["OFFERS"]) > 0 || count($arItem["DISPLAY_PROPERTIES"]) > 0 || $arParams["USE_PRODUCT_QUANTITY"] == 1) { ?></div><? } ?>


          </div>
          </div><?
        }
        ?></div><?
      ?></div>
    <div class="slide_scroll_left"></div>
    <div class="slide_scroll_right"></div>
    <script>
      console.log(1);
      $('[id ^= bigdata_recommeded_products]').attr('id', 'recommended-products').removeClass('active');
      $('#bigdata-presentation-li').show();
      all_func();
      /*setTimeout(function () {
        $('[id ^= bigdata_recommeded_products]').attr('id', 'recommended-products').removeClass('active');
        $('.product-tabs:not(.gifts) .nav-tabs').append('<li role="presentation"><a href="#recommended-products" aria-controls="recommended-products" role="tab">Рекомендации</a></li>');
         all_func();
      }, 200);*/
    </script>

  <? }

}
$frame->end(); ?>
  </div>
