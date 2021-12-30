<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
//?><!--<pre style="display: block">--><?//print_r($arParams);?><!--</pre>--><?//
//?><!--<pre style="display: block">--><?//print_r($arResult);?><!--</pre>--><?//
$frame = $this->createFrame()->begin();
$injectId = 'sale_gift_product_'.rand();

$currentProductId = (int)$arResult['POTENTIAL_PRODUCT_TO_BUY']['ID'];

if (isset($arResult['REQUEST_ITEMS']))
{
    CJSCore::Init(array('ajax'));

    // component parameters
    $signer = new \Bitrix\Main\Security\Sign\Signer;
    $signedParameters = $signer->sign(
        base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
        'bx.sale.gift.product'
    );
    $signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.sale.gift.product');

    ?>

    <div id="<?=$injectId?>" class="tab-pane active" role="tabpanel" style="display: none;"></div>

    <script type="text/javascript">
        BX.ready(function(){

            var currentProductId = <?=CUtil::JSEscape($currentProductId)?>;
            var giftAjaxData = {
                'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
                'template': '<?=CUtil::JSEscape($signedTemplate)?>',
                'site_id': '<?=CUtil::JSEscape(SITE_ID)?>'
            };

            bx_sale_gift_product_load(
                '<?=CUtil::JSEscape($injectId)?>',
                giftAjaxData
            );

            BX.addCustomEvent('onCatalogStoreProductChange', function(offerId){
                if(currentProductId == offerId)
                {
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

if (count($arResult["ITEMS"]) > 0) {
    $rand = $this->randString();
    ?>
    <? if (strlen($arParams["SECTION_NAME"]) > 0) { ?><div class="scrollSectionName"><?=$arParams["SECTION_NAME"];?></div><? } ?>
    <div class="product-tabs">
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="section clearfix">
                    <? if(empty($arParams['HIDE_BLOCK_TITLE']) || $arParams['HIDE_BLOCK_TITLE'] == 'N'){ ?>
                        <div class="gift_title" style="top: 20px">
                            <? echo ($arParams['BLOCK_TITLE']? htmlspecialcharsbx($arParams['BLOCK_TITLE']) : GetMessage('SGP_TPL_BLOCK_TITLE_DEFAULT')) ?>
                        </div>
                    <? } ?>
                    <div class="section_box">
                        <div class="scroll-standard"><?
                            ?><div class="section adaptive_scroll_slider" id="section_<?=$rand;?>"><?
                                $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
                                $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
                                $arElementDeleteParams = array("CONFIRM" => GetMessage("CT_BCS_TPL_ELEMENT_DELETE_CONFIRM"));
                                foreach ($arResult["ITEMS"] as $arItem) {
                                    if($arItem["NAME"]){
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
                                        <div class="hover_box box">
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
                                                    <a href="javascript:;" data-fancybox="group2" data-src="<?=$arItem["DETAIL_PAGE_URL"];?>?open_popup=Y" class="open_fancybox" rel="gallery">
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
                                                ?>
                                            </div>


                                            <div class="price_box main_preview_price_<?= $arItem["ID"] ?> offers_hide gift-flex">
                                                <div class="fl">
                                                    <? if ($arItem["RATIO_PRICE"]["DISCOUNT_DIFF"] > 0) { ?>
                                                        <div class="old_price"><?= str_replace(GetMessage("STUDIOFACT_RUB"), '<span class="rub">' . GetMessage("STUDIOFACT_R") . '</span>', $arItem["MIN_PRICE"]["PRINT_VALUE"]); ?></div><? } ?>
                                                    <div class="price_box__actual-price t1">
                                                        0 <span class="rub"><?= GetMessage("STUDIOFACT_R") ?></span>
                                                    </div>
                                                </div>
                                                <!--div class="gift-label"><?= GetMessage("GIFT_LABEL") ?></div-->
                                                <a class="gift_more" href="<?=$arItem["DETAIL_PAGE_URL"]?>">Подробнее</a>
                                            </div>

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
                        </script>
                    </div>
                </div></div></div></div>
<? } ?>
<?$frame->beginStub();?>
<?$frame->end();?>