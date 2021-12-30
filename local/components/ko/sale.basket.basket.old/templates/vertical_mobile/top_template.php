<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');
$summ = 0;
foreach ($arResult['CATEGORIES']['READY'] as $one_item)
{
    $summ += $one_item['PRICE']*$one_item['QUANTITY'];
};
?>



<? if (count($arResult['CATEGORIES']['READY']) > 0) { ?>
    <!--- !!! -->
    <div class="small_basket_hover_block<? if ($_REQUEST["SMALL_BASKET_OPEN"] == "Y") { echo ' active'; } ?>">
        

            <div class="small_basket_overflow">
                <table class="small_basket_hover_table">
                    <?foreach ($arResult['CATEGORIES']['READY'] as $arItem):?>
                        <tr class="good_box">
                      
                        <td class="small_basket_hover_name">
                            <div class="name"><? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0) { echo '<a href="'.$arItem["DETAIL_PAGE_URL"].'" title="'.$arItem["NAME"].'">'; } ?>
                                <? echo $arItem["NAME"]; ?>
                                <? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0) { echo '</a>'; } ?></div>


                            <div class="item_quantity mobile_basket_hover_quantity" data-cart-id="<?=$cartId?>" data-siteid="<?=SITE_ID?>" data-ajax-path="<?=$componentPath?>/ajax.php" data-template-name="<?=$templateName?>">
                                <a class="minus" href="javascript: void(0);" data-cart-id="<?=$cartId?>">
                                <a class="minus" href="javascript: void(0);">-</a><?
                                ?><input
                                        type="text"
                                        class="buy_button_a small-basket-quantity"
                                        maxlength="2"
                                        data-ratio="<?=$arItem["RATIO"];?>"
                                        value="<?=$arItem["QUANTITY"];?>"
                                        name="QUANTITY_<?=$arItem["ID"]?>"
                                        data-id = "<?=$arItem["ID"]?>"
                                        data-path = "<?=$templateFolder?>/ajax.php"
                                        id="<?=$arItem["PRODUCT_ID"]?>"<?=(isset($arItem["AVAILABLE_QUANTITY"]) ? ' data-max="' . $arItem["AVAILABLE_QUANTITY"] . '"':""); ?>
                                /><?
                                ?><a class="plus" href="javascript: void(0);">+</a>
                            </div>
                            <div class="small_basket_hover_price">
                                  <?=str_replace(GetMessage("STUDIOFACT_RUB"), "<span class=\"box_rub_price black\">".GetMessage("STUDIOFACT_R")."</span>", CurrencyFormat($arItem["PRICE"], "RUB"));?>
                            </div>


                        </td>
                        <td class="small_basket_hover_delete"><a href="javascript:void(0);" class="mobile_basket_hover_delete_action" data-id="<?=$arItem["ID"];?>"></a></td>
                        </tr>
    				<?endforeach;?> 
                </table>
            </div>

        <div class="small_basket_hover_block__buttons clearfix">
            <a href="#" class="small_basket_block">
                <span <? if ($summ == 0):?> style="display: none;" <?endif ?>>
                        <? if ($arResult["NUM_PRODUCTS"] > 0) { ?><span class="quant inline"><?=$arResult["NUM_PRODUCTS"];?></span><? } else { ?><span class="icon inline">0</span><? } ?>
                        <span id="cart_heading_wrapper cart_heading_wrapper2" class="cart_heading_wrapper">
                            <span class="text inline"><?=GetMessage("SF_SMALL_BASKET");?></span>
                            <? if ($summ > 0) { ?>
                                <span class="summ inline"><?=str_replace(GetMessage("STUDIOFACT_RUB"), "<span class=\"rub black\">".GetMessage("STUDIOFACT_R")."</span>", CurrencyFormat($summ, "RUB"));?></span>
                           
                            <? } else { ?>
                                <span class="summ inline"></span>                
                            <? } ?>
                            <span class="clear"></span>
                        </span>
                </span>
            </a>

            <?
                $button = false;
                if ($arParams["USER_TYPE"] == 'user') { 
                    $cuisine = getCuisine($arParams['DAY_WEEK']); 
                    $el = getCuisineElement($cuisine['cuisine']);
                    $button = currentTimeCuisine($el['OPENING_TIME'], $el['CLOSING_TIME']);
                }
            ?>
            <?if ($arParams['LIMIT']):?>
                <?if($arParams['LIMIT'] >= $arParams['SUM_CURRENT_BASKET']):?>
                    <a href="/personal/order/make/?day_week=<?=$arParams['DAY_WEEK']?>" id="basket_buy_button_<?=$cartId?>" class="basket-buy <?if($arParams['FINAL_TYME'] == 'Y' && $arParams['DAY_WEEK'] == $arParams['FACTIAL_DAY']):?>disabled_button<?endif?>">Купить</a>
                    <p class="basket_buy_button_warning disabled_warning">Вы не можете оформить заказ так как превышен Ваш дневной лимит!</p>
                    <?if($arParams['FINAL_TYME'] == 'Y' && $arParams['DAY_WEEK'] == $arParams['FACTIAL_DAY']):?>
                    <p class="basket_buy_button_warning">Время оформления заказа закончилось!</p>
                    <?endif?>
                <?else:?>
                    <a href="/personal/order/make/?day_week=<?=$arParams['DAY_WEEK']?>" id="basket_buy_button_<?=$cartId?>" class="basket-buy disabled_button">Купить</a>
                    <p class="basket_buy_button_warning">Вы не можете оформить заказ так как превышен Ваш дневной лимит!</p>
                <?endif?>
            <?else:?>
                <?if ($button):?>
                    <a href="/personal/order/make/?day_week=<?=$arParams['DAY_WEEK']?>" id="basket_buy_button_<?=$cartId?>" class="basket-buy">Купить</a>
                    <p class="basket_buy_button_warning disabled_warning">Вы не можете оформить заказ так как превышен Ваш дневной лимит!</p>
                <?endif?>
            <?endif?>
        </div>
        <div class="items" data-nums="<?=$arResult["NUM_PRODUCTS"]?>" style="display:none;"></div>
    </div>
<? } ?> 