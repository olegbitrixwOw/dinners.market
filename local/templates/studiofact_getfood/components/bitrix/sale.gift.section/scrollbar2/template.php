<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);


if (count($arResult["ITEMS"]) > 0) {
    $rand = $this->randString();
    ?>
    <div class="product-tabs">
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="section clearfix">

                    <? foreach ($arResult["ITEMS"] as $arItem) {
                        if(count($arItem["OFFERS"]) < 1)
                            $hide_title = false;
                     }

                    if((empty($arParams['HIDE_BLOCK_TITLE']) || $arParams['HIDE_BLOCK_TITLE'] !== 'Y') && !$hide_title){ ?>
                        <div class="gift_title" style="top:10px;">
                            <? echo ($arParams['BLOCK_TITLE']? htmlspecialcharsbx($arParams['BLOCK_TITLE']) : GetMessage('SGS_TPL_BLOCK_TITLE_DEFAULT')) ?>
                        </div>
                    <? } ?>

                    <div class="section_box">
                        <div class="scroll-standard section-gift-scroll"><?
                            ?><div class="section adaptive_scroll_slider" style="padding-top: 5px" id="section_<?=$rand;?>"><?
                                $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
                                $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
                                $arElementDeleteParams = array("CONFIRM" => GetMessage("CT_BCS_TPL_ELEMENT_DELETE_CONFIRM"));
                                foreach ($arResult["ITEMS"] as $arItem) {
                                    if($arItem["NAME"] && count($arItem["OFFERS"]) < 1){
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
                                        ?><div class="item_element good_box inline gift_item_element" id="<?=$strMainID;?>" data-id="<?=$arItem["ID"];?>">
                                        <div style="display: none;" itemscope itemtype="http://schema.org/Product">
                                            <meta itemprop="name" content="<?=$arItem["NAME"];?>" />
                                            <meta itemprop="description" content="<?=$arItem["PREVIEW_TEXT"];?>" />
                                            <meta itemprop="url" content="<?=$arItem["DETAIL_PAGE_URL"];?>" />
                                            <img itemprop="image" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>" />
                                        </div>
                                        <div class="hover_box box<? if ($can_buy != "1") { echo ' disabled'; } ?>">
                                            <div class="img_box">
                                                <a
                                                        href="<?=$arItem["DETAIL_PAGE_URL"];?>"
                                                        title="<?=$arItem["NAME"];?>"
                                                        class="image"
                                                    <? if (strlen($arItem["PREVIEW_PICTURE"]["SRC"]) > 0) { ?>
                                                        style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>');"
                                                    <? } ?>
                                                ></a>
                                                <? if (count($arItem["OFFERS"]) > 0) {
                                                    foreach ($arItem["OFFERS"] as $arOffer) {
                                                        if (strlen($arOffer["PREVIEW_PICTURE"]["SRC"]) > 0) {?>
                                                            <a
                                                                    href="<?=$arItem["DETAIL_PAGE_URL"];?>"
                                                                    title="<?=$arItem["NAME"];?>"
                                                                    class="image main_preview_image_<?=$arOffer["ID"];?> offers_hide"
                                                                <? if (strlen($arItem["PREVIEW_PICTURE"]["SRC"]) > 0) { ?>
                                                                    style="background-image: url('<?=$arOffer["PREVIEW_PICTURE"]["SRC"];?>'); display: none;"
                                                                <? } ?>
                                                            ></a>
                                                        <? }
                                                    }
                                                } ?>
                                                <div class="hover_over">
                                                    <a href="javascript:;" data-width="810" data-fancybox="group" data-src="<?=$arItem["DETAIL_PAGE_URL"];?>?open_popup=Y" class="open_fancybox" rel="gallery">
                                                        <?=GetMessage("STUDIOFACT_FAST_VIEW");?>
                                                    </a>
                                                    <a href="<?=$arItem["DETAIL_PAGE_URL"];?>" title="<?=$arItem["NAME"];?>" class="image main_preview_image offers_hide"></a>

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
                                            <div class="price_box main_preview_price_<?= $arItem["ID"] ?> offers_hide gift-flex">
                                                <div class="fl">
                                                    <? if ($arItem["RATIO_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
                                                        <div class="old_price"><?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arItem["MIN_PRICE"]["PRINT_VALUE"]); ?></div><? } ?>
                                                    <div class="price_box__actual-price t1">
                                                        0 <span class="rub"><?= GetMessage("STUDIOFACT_R") ?></span>
                                                    </div>
                                                </div>
                                               <?/* <div class="price_box__controls">

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
                                                <? if (count($arItem["OFFERS"]) > 0) {
                                                    ?>
                                                <div class="offers_item" id="skuId<?= $arItem["ID"]; ?>">
                                                    <?
                                                    foreach ($arResult["SKU_PROPS"] as $arSku) {
                                                        if (count($arSku["VALUES"]) > 0 && count($arItem["SKU_THERE_ARE"][$arSku["ID"]]) > 0) {
                                                            echo '<div class="offer_item" data-prop-id="' . $arSku["ID"] . '"><div class="offer_name">' . $arSku["NAME"] . ':</div>';
                                                            foreach ($arSku["VALUES"] as $value) {
                                                                if ($value["ID"] > 0 && in_array($value["ID"], $arItem["SKU_THERE_ARE"][$arSku["ID"]])) {
                                                                    ?><span class="offer_sku"
                                                                            data-prop-id="<?= $arSku["ID"]; ?>"
                                                                            data-prop-code="<?= $arSku["CODE"]; ?>"
                                                                            data-prop-value-id="<?= $value["ID"]; ?>"
                                                                            data-tree='<?= json_encode($arItem["SKU_TREE"]); ?>'><?= (strlen($value["PICT"]["SRC"]) > 0 ? '<img src="' . $value["PICT"]["SRC"] . '" title="' . $value["NAME"] . '" alt="' . $value["NAME"] . '" />' : $value["NAME"]); ?></span><?
                                                                }
                                                            }
                                                            echo '</div>';
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
                                                    <div class="good_box price_box main_preview_price_<?= $arOffer["ID"]; ?> offers_hide"
                                                         style="display: none;">
                                                        <div class="fl">
                                                            <? if ($arOffer["RATIO_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
                                                                <div class="old_price"><?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arOffer["RATIO_PRICE"]["PRINT_VALUE"]); ?></div><? } ?>
                                                            <div class="price_box__actual-price t2">
                                                                <?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arOffer["RATIO_PRICE"]["PRINT_DISCOUNT_VALUE"]); ?>
                                                            </div>
                                                        </div>

                                                        <?/*<div class="price_box__controls">
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
                                            

                                            <? if (count($arItem["OFFERS"]) > 0 || count($arItem["DISPLAY_PROPERTIES"]) > 0 || $arParams["USE_PRODUCT_QUANTITY"] == 1) { ?>
                                            <style>
                                                .gift_hidden_hover{
                                                    background: white;
                                                    border-radius: 5px;
                                                }
                                            </style>
                                            <div class="hidden_hover_element gift_hidden_hover">
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
                                                            <? foreach ($arItem["DISPLAY_PROPERTIES"] as $key => $value) {
                                                                ?><p><span class="prop_name"><?=$value["NAME"];?></span><span class="prop_value"><?=(is_array($value["DISPLAY_VALUE"]) ? implode(' / ', $value["DISPLAY_VALUE"]) : $value["DISPLAY_VALUE"]);?></span></p><?
                                                            } ?>
                                                            </div><?
                                                        }
                                                    }
                                                } ?>
                                                <? if (count($arItem["OFFERS"]) > 0 || count($arItem["DISPLAY_PROPERTIES"]) > 0 || $arParams["USE_PRODUCT_QUANTITY"] == 1) { ?></div><? } ?>


                                        </div>
                                        </div><?
                                    }}
                                ?></div><?
                            ?></div>
                        <div class="slide_scroll_left"></div>
                        <div class="slide_scroll_right"></div>
                        <script>
                            $('[id ^= sale_gift_product_]').attr('id', 'gifts');
                            parentTab = $('#section_<?=$rand;?>').closest('.tab-pane');
                            parentTab.show();
                            parentId = parentTab.attr('id');
                            parentTab.closest('.product-tabs').find('a[href$=' + parentId + ']').parent().show();
                            parentTab.closest('.product-tabs').show();
                            /*all_func();*/
                        </script>
                    </div>
                </div>
            </div></div></div>
<? } ?>