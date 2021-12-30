<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$frame = $this->createFrame()->begin();
$injectId = 'sale_gift_product_' . rand();
$currentProductId = (int)$arResult['POTENTIAL_PRODUCT_TO_BUY']['ID'];
?>
    <div id="<?= $injectId ?>"></div>
<?
if (isset($arResult['REQUEST_ITEMS'])) {
    CJSCore::Init(array('ajax'));

    // component parameters
    $signer = new \Bitrix\Main\Security\Sign\Signer;
    $signedParameters = $signer->sign(
        base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
        'bx.sale.gift.product'
    );
    $signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.sale.gift.product');

    ?>

    <span id="<?= $injectId ?>" class="tab-pane active" role="tabpanel"></span>

    <script type="text/javascript">
        BX.ready(function () {

            var currentProductId = <?=CUtil::JSEscape($currentProductId)?>;
            var giftAjaxData = {
                'parameters': '<?=CUtil::JSEscape($signedParameters)?>',
                'template': '<?=CUtil::JSEscape($signedTemplate)?>',
                'site_id': '<?=CUtil::JSEscape(SITE_ID)?>'
            };

            bx_sale_gift_product_load(
                '<?=CUtil::JSEscape($injectId)?>',
                giftAjaxData
            );

            BX.addCustomEvent('onCatalogStoreProductChange', function (offerId) {
                if (currentProductId == offerId) {
                    return;
                }
                currentProductId = offerId;
                bx_sale_gift_product_load(
                    '<?=CUtil::JSEscape($injectId)?>',
                    giftAjaxData,
                    {offerId: offerId}
                );
            });
        });
    </script>

    <?
    $frame->end();
    return;
}


if (!empty($arResult['ITEMS'])) {
    $templateData = array(
        'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css',
        'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME']
    );

    $arSkuTemplate = array();
    if (is_array($arResult['SKU_PROPS'])) {
        foreach ($arResult['SKU_PROPS'] as $iblockId => $skuProps) {
            $arSkuTemplate[$iblockId] = array();
            foreach ($skuProps as &$arProp) {
                ob_start();
                if ('TEXT' == $arProp['SHOW_MODE']) {
                    if (5 < $arProp['VALUES_COUNT']) {
                        $strClass = 'bx_item_detail_size full';
                        $strWidth = ($arProp['VALUES_COUNT'] * 20) . '%';
                        $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                        $strSlideStyle = '';
                    } else {
                        $strClass = 'bx_item_detail_size';
                        $strWidth = '100%';
                        $strOneWidth = '20%';
                        $strSlideStyle = 'display: none;';
                    }
                    ?>
                <div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont">
                    <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>

                    <div class="bx_size_scroller_container">
                        <div class="bx_size">
                            <ul id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;"><?
                                foreach ($arProp['VALUES'] as $arOneValue) {
                                    ?>
                                <li
                                        data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID']; ?>"
                                        data-onevalue="<? echo $arOneValue['ID']; ?>"
                                        style="width: <? echo $strOneWidth; ?>;"
                                ><i></i><span class="cnt"><? echo htmlspecialcharsex($arOneValue['NAME']); ?></span>
                                    </li><?
                                }
                                ?></ul>
                        </div>
                        <div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left"
                             data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                        <div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right"
                             data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                    </div>
                    </div><?
                } elseif ('PICT' == $arProp['SHOW_MODE']) {
                    if (5 < $arProp['VALUES_COUNT']) {
                        $strClass = 'bx_item_detail_scu full';
                        $strWidth = ($arProp['VALUES_COUNT'] * 20) . '%';
                        $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                        $strSlideStyle = '';
                    } else {
                        $strClass = 'bx_item_detail_scu';
                        $strWidth = '100%';
                        $strOneWidth = '20%';
                        $strSlideStyle = 'display: none;';
                    }
                    ?>
                <div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont">
                    <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>

                    <div class="bx_scu_scroller_container">
                        <div class="bx_scu">
                            <ul id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;"><?
                                foreach ($arProp['VALUES'] as $arOneValue) {
                                    ?>
                                <li
                                        data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID'] ?>"
                                        data-onevalue="<? echo $arOneValue['ID']; ?>"
                                        style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>;"
                                ><i title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
                                    <span class="cnt"><span class="cnt_item"
                                                            style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
                                                            title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"
                                        ></span></span></li><?
                                }
                                ?></ul>
                        </div>
                        <div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left"
                             data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                        <div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right"
                             data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                    </div>
                    </div><?
                }
                $arSkuTemplate[$iblockId][$arProp['CODE']] = ob_get_contents();
                ob_end_clean();
                unset($arProp);
            }
        }
    }

    ?>
    <script type="text/javascript">
        BX.message({
            CVP_MESS_BTN_BUY: '<? echo('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CVP_TPL_MESS_BTN_BUY_GIFT')); ?>',
            CVP_MESS_BTN_ADD_TO_BASKET: '<? echo('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CVP_TPL_MESS_BTN_ADD_TO_BASKET')); ?>',

            CVP_MESS_BTN_DETAIL: '<? echo('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CVP_TPL_MESS_BTN_DETAIL')); ?>',

            CVP_MESS_NOT_AVAILABLE: '<? echo('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CVP_TPL_MESS_BTN_DETAIL')); ?>',
            CVP_BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CVP_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
            CVP_BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
            CVP_ADD_TO_BASKET_OK: '<? echo GetMessageJS('CVP_ADD_TO_BASKET_OK'); ?>',
            CVP_TITLE_ERROR: '<? echo GetMessageJS('CVP_CATALOG_TITLE_ERROR') ?>',
            CVP_TITLE_BASKET_PROPS: '<? echo GetMessageJS('CVP_CATALOG_TITLE_BASKET_PROPS') ?>',
            CVP_TITLE_SUCCESSFUL: '<? echo GetMessageJS('CVP_ADD_TO_BASKET_OK'); ?>',
            CVP_BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CVP_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
            CVP_BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('CVP_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
            CVP_BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CVP_CATALOG_BTN_MESSAGE_CLOSE') ?>'
        });
    </script>
    <? if (strlen($arParams["SECTION_NAME"]) > 0) { ?><div class="scrollSectionName"><?= $arParams["SECTION_NAME"]; ?></div><? } ?>
    <div class="product-tabs">
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="section clearfix">
                    <? if (empty($arParams['HIDE_BLOCK_TITLE']) || $arParams['HIDE_BLOCK_TITLE'] == 'N') { ?>
                        <div class="gift_title">
                            <? echo($arParams['BLOCK_TITLE'] ? htmlspecialcharsbx($arParams['BLOCK_TITLE']) : GetMessage('SGP_TPL_BLOCK_TITLE_DEFAULT')) ?>
                        </div>
                    <? } ?>
                    <div class="section_box">
                        <div class="scroll-standard gift-scroll-wrapper">
                            <div class="adaptive_scroll_slider flex" id="section_<?= $rand; ?>">
                                <? $elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
                                $elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
                                $elementDeleteParams = array('CONFIRM' => GetMessage('CVP_TPL_ELEMENT_DELETE_CONFIRM'));
                                foreach ($arResult['ITEMS'] as $key => $arItem) {
                                    if($arItem["NAME"]){
                                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $elementEdit);
                                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $elementDelete, $elementDeleteParams);
                                        $strMainID = $this->GetEditAreaId($arItem['ID'] . $key);

                                        $arItemIDs = array(
                                            'ID' => $strMainID,
                                            'PICT' => $strMainID . '_pict',
                                            'SECOND_PICT' => $strMainID . '_secondpict',
                                            'MAIN_PROPS' => $strMainID . '_main_props',

                                            'QUANTITY' => $strMainID . '_quantity',
                                            'QUANTITY_DOWN' => $strMainID . '_quant_down',
                                            'QUANTITY_UP' => $strMainID . '_quant_up',
                                            'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
                                            'BUY_LINK' => $strMainID . '_buy_link',
                                            'SUBSCRIBE_LINK' => $strMainID . '_subscribe',

                                            'PRICE' => $strMainID . '_price',
                                            'DSC_PERC' => $strMainID . '_dsc_perc',
                                            'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',

                                            'PROP_DIV' => $strMainID . '_sku_tree',
                                            'PROP' => $strMainID . '_prop_',
                                            'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
                                            'BASKET_PROP_DIV' => $strMainID . '_basket_prop'
                                        );

                                        $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

                                        $strTitle = (
                                        isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
                                            ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
                                            : $arItem['NAME']
                                        );
                                        $showImgClass = $arParams['SHOW_IMAGE'] != "Y" ? "no-imgs" : "";

                                        if ($arItem["CAN_BUY"] == "1" && count($arItem["OFFERS"]) < 1) {
                                            $can_buy = 1;
                                        }


                                        if (count($arItem["OFFERS"]) > 0) {
                                            foreach ($arItem["OFFERS"] as $arOffer) {
                                                if ($arOffer["CAN_BUY"] == "1") {
                                                    $can_buy = 1;
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="item_element good_box inline gift_item_element" id="<?= $strMainID; ?>" data-id="<?= $arItem["ID"]; ?>">
                                            <div style="display: none;" itemscope itemtype="http://schema.org/Product">
                                                <meta itemprop="name" content="<?= $arItem["NAME"]; ?>"/>
                                                <meta itemprop="description" content="<?= $arItem["PREVIEW_TEXT"]; ?>"/>
                                                <meta itemprop="url" content="<?= $arItem["DETAIL_PAGE_URL"]; ?>"/>
                                                <img itemprop="image" src="<?= $arItem["PREVIEW_PICTURE"]["SRC"]; ?>"/>
                                            </div>
                                            <div class="hover_box box<? if ($can_buy != "1") {
                                                echo ' disabled';
                                            } ?>">
                                                <div class="img_box">
                                                    <a
                                                            href="<?= $arItem["DETAIL_PAGE_URL"]; ?>"
                                                            title="<?= $arItem["NAME"]; ?>"
                                                            class="image image-gift"
                                                        <? if (strlen($arItem["PREVIEW_PICTURE"]["SRC"]) > 0) { ?>
                                                            style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]["SRC"]; ?>');"
                                                        <? } ?>
                                                    ></a>
                                                    <? if (count($arItem["OFFERS"]) > 0) {
                                                        foreach ($arItem["OFFERS"] as $arOffer) {
                                                            if (strlen($arOffer["PREVIEW_PICTURE"]["SRC"]) > 0) { ?>
                                                                <a
                                                                        href="<?= $arItem["DETAIL_PAGE_URL"]; ?>"
                                                                        title="<?= $arItem["NAME"]; ?>"
                                                                        class="image main_preview_image_<?= $arOffer["ID"]; ?> offers_hide"
                                                                    <? if (strlen($arItem["PREVIEW_PICTURE"]["SRC"]) > 0) { ?>
                                                                        style="background-image: url('<?= $arOffer["PREVIEW_PICTURE"]["SRC"]; ?>'); display: none;"
                                                                    <? } ?>
                                                                ></a>
                                                            <? }
                                                        }
                                                    } ?>
                                                    <div class="hover_over">
                                                        <a href="javascript:;" data-width="810" data-fancybox="group"
                                                           data-src="<?= $arItem["DETAIL_PAGE_URL"]; ?>?open_popup=Y"
                                                           class="open_fancybox" rel="gallery">
                                                            <?= GetMessage("STUDIOFACT_FAST_VIEW"); ?>
                                                        </a>
                                                        <a href="<?= $arItem["DETAIL_PAGE_URL"]; ?>"
                                                           title="<?= $arItem["NAME"]; ?>"
                                                           class="image main_preview_image offers_hide"></a>

                                                    </div>
                                                </div>
                                                <a href="<?= $arItem["DETAIL_PAGE_URL"]; ?>" title="<?= $arItem["NAME"]; ?>"
                                                   class="name"><? if($arItem["NAME"]){echo $arItem["NAME"];}else{echo $arItem["OFFERS"][0]["NAME"];} ?></a>
                                                <div class="icon_box"><?
                                                    if (strlen($arItem["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["VALUE"]) > 0) {
                                                        echo '<div class="' . mb_strtolower($arItem["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["CODE"]) . '" title="' . $arItem["DISPLAY_PROPERTIES"]["NEWPRODUCT"]["NAME"] . '"></div>';
                                                    }
                                                    if (strlen($arItem["DISPLAY_PROPERTIES"]["SALELEADER"]["VALUE"]) > 0) {
                                                        echo '<div class="' . mb_strtolower($arItem["DISPLAY_PROPERTIES"]["SALELEADER"]["CODE"]) . '" title="' . $arItem["DISPLAY_PROPERTIES"]["SALELEADER"]["NAME"] . '"></div>';
                                                    }
                                                    if (strlen($arItem["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["VALUE"]) > 0) {
                                                        echo '<div class="' . mb_strtolower($arItem["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["CODE"]) . '" title="' . $arItem["DISPLAY_PROPERTIES"]["SPECIALOFFER"]["NAME"] . '"></div>';
                                                    }
                                                    if (strlen($arItem["DISPLAY_PROPERTIES"]["PRECOMMEND"]["VALUE"]) > 0) {
                                                        echo '<div class="' . mb_strtolower($arItem["DISPLAY_PROPERTIES"]["PRECOMMEND"]["CODE"]) . '" title="' . $arItem["DISPLAY_PROPERTIES"]["PRECOMMEND"]["NAME"] . '"></div>';
                                                    }
                                                    ?></div>


                                                <!--price-->
                                                <div class="price_box main_preview_price_<?= $arItem["ID"] ?> offers_hide gift-flex">
                                                    <div class="fl">
                                                        <? if ($arItem["RATIO_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
                                                            <div class="old_price"><?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arItem["MIN_PRICE"]["PRINT_VALUE"]); ?></div><? } ?>
                                                        <div class="price_box__actual-price t1">
                                                            0 <span class="rub"><?= GetMessage("STUDIOFACT_R") ?></span>
                                                        </div>
                                                    </div>
                                                    <?/*<div class="price_box__controls">
                                                        <? if ($arParams["USE_PRODUCT_QUANTITY"] == 1 && $can_buy == 1) { ?>
                                                            <div class="item_quantity inline">
                                                            <a data-inc-value="<?= $arResult["CATALOG_MEASURE_RATIO"] ?: 1; ?>"
                                                               href="javascript: void(0);" class="minus">-</a>
                                                            <input type="text"
                                                                   name="<?= $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
                                                                   value="<?= $arItem["CATALOG_MEASURE_RATIO"] ?>"/>
                                                            <a data-inc-value="<?= $arResult["CATALOG_MEASURE_RATIO"] ?: 1; ?>"
                                                               href="javascript: void(0);" class="plus">+</a>
                                                            </div><?
                                                        } ?>
                                                        <? if ($can_buy == "1") { ?>
                                                            <div class="buy_box fr"
                                                                 style="background-image: none !important;">
                                                                <a
                                                                    <? $arItem["ADD_URL"] = str_replace('BUY', 'ADD2BASKET', $arItem["ADD_URL"]) ?>
                                                                        href="<?= $arItem["ADD_URL"]; ?>"
                                                                        class="buy buy_button_a <? if (count($arItem["OFFERS"]) > 0) {
                                                                            echo 'show_offers_basket_popup';
                                                                        } else {
                                                                            echo 'show_basket_popup';
                                                                        } ?> inline"
                                                                        data-name="<?= $arItem["NAME"]; ?>"
                                                                        data-img="<?= $arItem["PREVIEW_PICTURE"]["SRC"]; ?>"
                                                                        data-id="<?= $arItem["ID"]; ?>"
                                                                        data-ratio="<?= $arItem["CATALOG_MEASURE_RATIO"]; ?>"
                                                                        data-basket="<?= $arParams["BASKET_URL"]; ?>"
                                                                        data-price="<?= str_replace(GetMessage("STUDIOFACT_RUB"), '', $arItem["MIN_PRICE"]["DISCOUNT_VALUE"]); ?>"
                                                                        data-gotobasket="<?= GetMessage("SF_GO_TO_BASKET_BUTTON"); ?>"
                                                                        data-gotoback="<?= GetMessage("SF_GO_TO_BACK_BUTTON"); ?>"
                                                                >
                                                                </a>
                                                            </div>
                                                        <? } else {
                                                            ?>
                                                            <div class="buy_box fr price_box__not-avilable"><? if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) {
                                                                echo $arParams["MESS_NOT_AVAILABLE"];
                                                            } else {
                                                                echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE");
                                                            } ?></div><?
                                                        } ?>
                                                    </div>*/?>
                                                    <a class="gift_more" href="<?=$arItem["DETAIL_PAGE_URL"]?>">Подробнее</a>
                                                </div>
                                                <div class="scrollbar_offers_items">
                                                    <? if (count($arItem["OFFERS"]) > 0) { ?>
                                                        <div class="offers_item" id="skuId<?= $arItem["ID"]; ?>">
                                                            <? foreach ($arResult["SKU_PROPS"] as $arSku) {
                                                                foreach ($arSku as $scuValue) {
                                                                    if (count($scuValue["VALUES"]) > 0 && count($arItem["SKU_THERE_ARE"][$scuValue["ID"]]) > 0) {
                                                                        echo '<div class="offer_item" data-prop-id="' . $scuValue["ID"] . '"><div class="offer_name">' . $scuValue["NAME"] . ':</div>';
                                                                        foreach ($scuValue["VALUES"] as $value) {
                                                                            if ($value["ID"] > 0 && in_array($value["ID"], $arItem["SKU_THERE_ARE"][$scuValue["ID"]])) {
                                                                                ?><span class="offer_sku"
                                                                                        data-prop-id="<?= $scuValue["ID"]; ?>"
                                                                                        data-prop-code="<?= $scuValue   ["CODE"]; ?>"
                                                                                        data-prop-value-id="<?= $value["ID"]; ?>"
                                                                                        data-tree='<?= json_encode($arItem["SKU_TREE"]); ?>'><?= (strlen($value["PICT"]["SRC"]) > 0 ? '<img src="' . $value["PICT"]["SRC"] . '" title="' . $value["NAME"] . '" alt="' . $value["NAME"] . '" />' : $value["NAME"]); ?></span><?
                                                                            }
                                                                        }
                                                                        echo '</div>';
                                                                    }
                                                                }
                                                            }
                                                            echo '<div class="offers_item_id" style="display: none;">';
                                                            foreach ($arItem["SKU_MASSIVE"] as $id => $value) { ?>
                                                            <div class="<?= $id; ?>" data-id="<?= $value; ?>"></div><?
                                                            }
                                                            echo '</div>'; ?>
                                                        </div>
                                                    <? } ?>
                                                    <div class="price_box main_preview_price_<?= $arItem["ID"] ?> offers_hide">
                                                        <div class="pr">
                                                            <span class="price"><?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arOffer["RATIO_PRICE"]["PRINT_DISCOUNT_VALUE"]); ?></span>
                                                            <? if ($arOffer["RATIO_PRICE"]["DISCOUNT_DIFF"] > 0) { ?><span
                                                                    class="old_price"><?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arOffer["RATIO_PRICE"]["PRINT_VALUE"]); ?></span><? } ?>
                                                        </div>
                                                        <? if ($arItem["CAN_BUY"]) { ?>
                                                            <div class="nav_buttons">
                                                                <a
                                                                        href="<?= $arItem["ADD_URL"]; ?>"
                                                                        target="_parent"
                                                                        class="button show_basket_popup inline buy_button_a"
                                                                        data-name="<?= $arItem["NAME"]; ?>"
                                                                        data-img="<?= $arItem["PREVIEW_PICTURE"]["SRC"]; ?>"
                                                                        data-id="<?= $arItem["ID"]; ?>"
                                                                        data-ratio="<?= $arItem["CATALOG_MEASURE_RATIO"]; ?>"
                                                                        data-basket="<?= $arParams["BASKET_URL"]; ?>"
                                                                        data-price="<?= str_replace(GetMessage("STUDIOFACT_RUB"), '', $arItem["MIN_PRICE"]["DISCOUNT_VALUE"]); ?>"
                                                                        data-gotobasket="<?= GetMessage("SF_GO_TO_BASKET_BUTTON"); ?>"
                                                                        data-gotoback="<?= GetMessage("SF_GO_TO_BACK_BUTTON"); ?>"
                                                                >
                                                                    <?= ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY')); ?></a>
                                                                <a href="javascript: void(0);"
                                                                   class="button_white"><?= GetMessage("SF_GO_TO_BACK_BUTTON"); ?>
                                                                </a>
                                                            </div>
                                                        <? } else {
                                                            ?>
                                                            <div class="nav_buttons"><? if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) {
                                                                echo $arParams["MESS_NOT_AVAILABLE"];
                                                            } else {
                                                                echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE");
                                                            } ?><a href="javascript: void(0);"
                                                                   class="button_white"><?= GetMessage("SF_GO_TO_BACK_BUTTON"); ?></a>
                                                            </div><?
                                                        } ?>
                                                    </div>
                                                    <? if (count($arItem["OFFERS"]) > 0) {
                                                        foreach ($arItem["OFFERS"] as $arOffer) { ?>
                                                            <div class="price_box main_preview_price_<?= $arOffer["ID"]; ?> offers_hide"
                                                                 style="display: none;">
                                                                <div class="pr">
                                                                    <span class="price"><?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arOffer["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]); ?></span>
                                                                    <? if ($arOffer["MIN_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
                                                                        <span class="old_price"><?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arOffer["MIN_PRICE"]["PRINT_VALUE"]); ?></span><? } ?>
                                                                </div>
                                                                <? if ($arOffer["CAN_BUY"]) { ?>
                                                                    <div class="nav_buttons">
                                                                        <a
                                                                                href="<?= $arOffer["ADD_URL"]; ?>"
                                                                                target="_parent"
                                                                                class="button show_basket_popup inline buy_button_a"
                                                                                data-name="<?= $arOffer["NAME"]; ?>"
                                                                                data-img="<?= (strlen($arOffer["PREVIEW_PICTURE"]["SRC"]) > 0 ? $arOffer["PREVIEW_PICTURE"]["SRC"] : $arItem["PREVIEW_PICTURE"]["SRC"]); ?>"
                                                                                data-id="<?= $arOffer["ID"]; ?>"
                                                                                data-ratio="<?= $arOffer["CATALOG_MEASURE_RATIO"]; ?>"
                                                                                data-basket="<?= $arParams["BASKET_URL"]; ?>"
                                                                                data-price="<?= str_replace(GetMessage("STUDIOFACT_RUB"), '', $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"]); ?>"
                                                                                data-gotobasket="<?= GetMessage("SF_GO_TO_BASKET_BUTTON"); ?>"
                                                                                data-gotoback="<?= GetMessage("SF_GO_TO_BACK_BUTTON"); ?>"
                                                                        >
                                                                            <?= ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY')); ?>
                                                                        </a>
                                                                        <a href="javascript: void(0);"
                                                                           class="button_white"><?= GetMessage("SF_GO_TO_BACK_BUTTON"); ?></a>
                                                                    </div>
                                                                <? } else {
                                                                    ?>
                                                                    <div class="nav_buttons"><? if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) {
                                                                        echo $arParams["MESS_NOT_AVAILABLE"];
                                                                    } else {
                                                                        echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE");
                                                                    } ?><a href="javascript: void(0);"
                                                                           class="button_white"><?= GetMessage("SF_GO_TO_BACK_BUTTON"); ?></a>
                                                                    </div><?
                                                                } ?>
                                                            </div>
                                                        <? }
                                                    } ?>
                                                </div>
                                                <? if (count($arItem["OFFERS"]) > 0) {
                                                    foreach ($arItem["OFFERS"] as $arOffer) { ?>
                                                        <div class="good_box price_box main_preview_price_<?= $arOffer["ID"]; ?> offers_hide ver-middle"
                                                             style="display: none;">
                                                            <div class="fl">
                                                                <? if ($arOffer["RATIO_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
                                                                    <div class="old_price"><?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arOffer["RATIO_PRICE"]["PRINT_VALUE"]); ?></div><? } ?>
                                                                <div class="price_box__actual-price t2">
                                                                    0 <span class="rub"><?= GetMessage("STUDIOFACT_R") ?></span>
                                                                </div>
                                                            </div>

                                                            <?/*<div class="price_box__controls">
                                                                <? if ($arParams["USE_PRODUCT_QUANTITY"] == 1 && $can_buy == 1) { ?>
                                                                    <div class="item_quantity inline">
                                                                    <a data-inc-value="<?= $arResult["CATALOG_MEASURE_RATIO"] ?: 1; ?>"
                                                                       href="javascript: void(0);" class="minus">-</a>
                                                                    <input type="text"
                                                                           name="<?= $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
                                                                           value="<?= $arItem["CATALOG_MEASURE_RATIO"] ?>"/>
                                                                    <a data-inc-value="<?= $arResult["CATALOG_MEASURE_RATIO"] ?: 1; ?>"
                                                                       href="javascript: void(0);" class="plus">+</a>
                                                                    </div><?
                                                                } ?>
                                                                <? if ($can_buy == "1") { ?>
                                                                    <div class="buy_box fr">
                                                                        <a
                                                                            <? $arItem["ADD_URL"] = str_replace('BUY', 'ADD2BASKET', $arItem["ADD_URL"]) ?>
                                                                                href="<?= $arItem["ADD_URL"]; ?>"
                                                                                class="buy buy_button_a <? if (count($arItem["OFFERS"]) > 0) {
                                                                                    echo 'show_offers_basket_popup';
                                                                                } else {
                                                                                    echo 'show_basket_popup';
                                                                                } ?> inline"
                                                                                data-name="<?= $arItem["NAME"]; ?>"
                                                                                data-img="<?= $arItem["PREVIEW_PICTURE"]["SRC"]; ?>"
                                                                                data-id="<?= $arItem["ID"]; ?>"
                                                                                data-ratio="<?= $arItem["CATALOG_MEASURE_RATIO"]; ?>"
                                                                                data-basket="<?= $arParams["BASKET_URL"]; ?>"
                                                                                data-price="<?= str_replace(GetMessage("STUDIOFACT_RUB"), '', $arItem["MIN_PRICE"]["DISCOUNT_VALUE"]); ?>"
                                                                                data-gotobasket="<?= GetMessage("SF_GO_TO_BASKET_BUTTON"); ?>"
                                                                                data-gotoback="<?= GetMessage("SF_GO_TO_BACK_BUTTON"); ?>"
                                                                        >
										<span class="buy_popup">
											<span></span>
                                            <?= ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY')); ?>
										</span>
                                                                        </a>
                                                                    </div>
                                                                <? } else {
                                                                    ?>
                                                                    <div class="t1 buy_box fr price_box__not-avilable"><? if (strlen($arParams["MESS_NOT_AVAILABLE"]) > 0) {
                                                                        echo $arParams["MESS_NOT_AVAILABLE"];
                                                                    } else {
                                                                        echo GetMessage("CT_BCE_CATALOG_NOT_AVAILABLE");
                                                                    } ?></div><?
                                                                } ?>
                                                            </div>*/?>
                                                            <a class="gift_more" href="<?=$arOffer["DETAIL_PAGE_URL"]?>">Подробнее</a>
                                                            <div class="clear"></div>
                                                        </div>
                                                    <? }
                                                } ?>
                                                <!--price-->

                                                <? $unset_props = Array("NEWPRODUCT", "SALELEADER", "SPECIALOFFER", "PRECOMMEND", $arParams["ADD_PICT_PROP"], $arParams["OFFER_ADD_PICT_PROP"], "RECOMMEND", "MINIMUM_PRICE", "MAXIMUM_PRICE");
                                                if (count($unset_props) > 0) {
                                                    foreach ($arItem["DISPLAY_PROPERTIES"] as $key => $value) {
                                                        if (in_array($key, $unset_props)) {
                                                            unset($arItem["DISPLAY_PROPERTIES"][$key]);
                                                        }
                                                    }
                                                    if (count($arItem["OFFERS"]) > 0) {
                                                        foreach ($arItem["OFFERS"] as $key0 => $arOffer) {
                                                            foreach ($arOffer["DISPLAY_PROPERTIES"] as $key => $value) {
                                                                if (in_array($key, $unset_props)) {
                                                                    unset($arItem["OFFERS"][$key0]["DISPLAY_PROPERTIES"][$key]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                } ?>
                                                
                                                <? if (count($arItem["OFFERS"]) > 0 || count($arItem["DISPLAY_PROPERTIES"]) > 0 || $arParams["USE_PRODUCT_QUANTITY"] == 1) { ?>
                                                <div class="hidden_hover_element">
                                                    <? } ?>
                                                    <? if (count($arItem["OFFERS"]) > 0) {?>
                                                    <div class="offers_item" id="skuId<?= $arItem["ID"]; ?>" itemprop="sku">
                                                        <? foreach ($arResult["SKU_PROPS"] as $arSku) {
                                                                foreach ($arSku as $scuValue) {
                                                                $i = 0;
                                                                if (count($scuValue["VALUES"]) > 0 && count($arItem["SKU_THERE_ARE"][$scuValue["ID"]]) > 0) {
                                                                    echo '<div class="offer_item" data-prop-id="' . $scuValue["ID"] . '"><div class="offer_name">' . $scuValue["NAME"] . '</div>';
                                                                    foreach ($scuValue["VALUES"] as $value) {
                                                                        if ($value["ID"] > 0 && in_array($value["ID"], $arItem["SKU_THERE_ARE"][$scuValue["ID"]])) { ?>
                                                                            <span
                                                                                    class="<?if (!empty($scuValue["USER_TYPE_SETTINGS"])) echo 'color '?>offer_sku<?= (!$i) ? ' active' : '' ?>"
                                                                                    data-prop-id="<?= $scuValue["ID"]; ?>"
                                                                                    data-prop-code="<?= $scuValue["CODE"]; ?>"
                                                                                    data-prop-value-id="<?= $value["ID"]; ?>"
                                                                                    data-tree='<?= json_encode($arItem["SKU_TREE"]); ?>'
                                                                            >
                                                                        <?= (strlen($value["PICT"]["SRC"]) > 0 ? '<img src="' . $value["PICT"]["SRC"] . '" title="' . $value["NAME"] . '" alt="' . $value["NAME"] . '" />' : $value["NAME"]); ?>
                                                                        </span>
                                                                            <? $i++;
                                                                        }
                                                                    }
                                                                    echo '</div>';
                                                                }
                                                            }

                                                        }
                                                        echo '<div class="offers_item_id" style="display: none;">';
                                                        foreach ($arItem["SKU_MASSIVE"] as $id => $value) {
                                                            ?>
                                                        <div class="<?= $id; ?>" data-id="<?= $value; ?>"></div><?
                                                        }
                                                        echo '</div>';
                                                        ?>
                                                        </div><?
                                                    } ?>

                                                    <? if (count($arItem["DISPLAY_PROPERTIES"]) > 0) {
                                                        ?>
                                                        <div class="item_props main_preview_props offers_hide">
                                                        <? foreach ($arItem["DISPLAY_PROPERTIES"] as $key => $value) {
                                                            ?><p><span class="prop_name"><?= $value["NAME"]; ?></span><span
                                                                    class="prop_value"><?= (is_array($value["DISPLAY_VALUE"]) ? implode(' / ', $value["DISPLAY_VALUE"]) : $value["DISPLAY_VALUE"]); ?></span>
                                                            </p><?
                                                        } ?>
                                                        </div><?
                                                    } ?>
                                                    <? if (count($arItem["OFFERS"]) > 0) {
                                                        foreach ($arItem["OFFERS"] as $arOffer) {
                                                            if (count($arOffer["DISPLAY_PROPERTIES"]) > 0) {
                                                                ?>
                                                            <div
                                                                    class="item_props main_preview_props_<?= $arOffer["ID"]; ?> offers_hide"
                                                                    style="display: none;">
                                                                <? foreach ($arItem["DISPLAY_PROPERTIES"] as $key => $value) {
                                                                    ?><p><span
                                                                            class="prop_name"><?= $value["NAME"]; ?></span><span
                                                                            class="prop_value"><?= (is_array($value["DISPLAY_VALUE"]) ? implode(' / ', $value["DISPLAY_VALUE"]) : $value["DISPLAY_VALUE"]); ?></span>
                                                                    </p><?
                                                                } ?>
                                                                </div><?
                                                            }
                                                        }
                                                    } ?>
                                                    <? if (count($arItem["OFFERS"]) > 0 || count($arItem["DISPLAY_PROPERTIES"]) > 0 || $arParams["USE_PRODUCT_QUANTITY"] == 1) { ?></div><? } ?>
                                            </div>
                                        </div>
                                    <?}}?>

                            </div>
                        </div>
                        <div class="slide_scroll_left"></div>
                        <div class="slide_scroll_right"></div>
                        <script>
                            $('[id ^= sale_gift_product_]').attr('id', 'gifts');
                            parentTab = $('#section_<?=$rand;?>').closest('.tab-pane');
                            parentTab.show();
                            parentId = parentTab.attr('id');
                            parentTab.closest('.product-tabs').find('a[href$=' + parentId + ']').parent().show();
                            parentTab.closest('.product-tabs').show();
                            all_func();
                            adaptateScroll();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?
}
?>
<? $frame->beginStub(); ?>
<? $frame->end(); ?>