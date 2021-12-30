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
<? if ($arResult["NUM_PRODUCTS"] > 0) { ?>
    <a id="small_basket" class="fr" href="<?=$arParams["PATH_TO_BASKET"];?>">
<? } else { ?>
    <span id="small_basket" class="fr">
<? } ?>
    <span class="mobile"><? if ($arResult["NUM_PRODUCTS"] > 0) { ?><span class="quant inline"><?=$arResult["NUM_PRODUCTS"];?></span><? } ?><span class="icon inline">0</span></span>
    <span class="desktop">
		<? if ($arResult["NUM_PRODUCTS"] > 0) { ?><span class="quant inline"><?=$arResult["NUM_PRODUCTS"];?></span><? } else { ?><span class="icon inline">0</span><? } ?>
        <span id="cart_heading_wrapper cart_heading_wrapper2" class="cart_heading_wrapper">
			<span class="text inline"><?=GetMessage("SF_SMALL_BASKET");?></span>
            <? if ($summ > 0) { ?><span class="summ inline"><?=str_replace(GetMessage("STUDIOFACT_RUB"), "<span class=\"rub black\">".GetMessage("STUDIOFACT_R")."</span>", CurrencyFormat($summ, "RUB"));?></span><? } else { ?><span class="summ inline"></span><? } ?>
            <span class="clear"></span>
		</span>
	</span>
<? if ($arResult["NUM_PRODUCTS"] > 0) { ?>
    </a>
<? } else { ?>
    </span>
<? } ?>

<? if (count($arResult['CATEGORIES']['READY']) > 0) { ?>
    <!-- <div class="small_basket_hover_block<? if ($_REQUEST["SMALL_BASKET_OPEN"] == "Y") { echo ' active'; } ?>"> -->
        <div class="small_basket_hover_block" style="display:block;">
        <div class="small_basket_overflow">
            <table class="small_basket_hover_table">
                <?foreach ($arResult['CATEGORIES']['READY'] as $arItem):?>
                    <tr class="good_box">
                    <td class="small_basket_hover_img">
                        <?
                        if (strlen($arItem["PICTURE_SRC"]) > 0)
                        {
	                        $basketImageSrc = $arItem["PICTURE_SRC"];
                        }
                        else
                        {
	                        $basketImageSrc = SITE_TEMPLATE_PATH.'/images/no-img.png';
                        }
                        ?>
	                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>" style="background-image: url('<?=$basketImageSrc?>')"></a>
                    </td>
                    <td class="small_basket_hover_name">
                        <div class="name"><? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0) { echo '<a href="'.$arItem["DETAIL_PAGE_URL"].'" title="'.$arItem["NAME"].'">'; } ?>
                            <? echo $arItem["NAME"]; ?>
                            <? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0) { echo '</a>'; } ?></div>
                        <div class="props">
                            <?
                            foreach ($arItem["PROPS"] as $arValue)
                            {
	                            echo '<span>' . $arValue["NAME"] . ':</span> ' . $arValue["VALUE"] . '<br />';
                            }
                            ?>
                        </div>
                        <div class="item_quantity small_basket_hover_quantity">
                            <a class="minus" href="javascript: void(0);">-</a><?
                            ?><input
                                    type="text"
                                    class="buy_button_a small-basket-quantity"
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
                            <?=$arItem["PRICE_FORMATED"]?>
                        </div>
                    </td>
                    <td class="small_basket_hover_delete"><a href="javascript:void(0);" class="small_basket_hover_delete_action" data-id="<?=$arItem["ID"];?>"></a></td>
                    </tr>
				<?endforeach;?>
            </table>
        </div>
        <div class="small_basket_hover_block__buttons clearfix">
            <a href="<?=$arParams["PATH_TO_BASKET"];?>" class="radius5 small_basket_hover_to_basket inline"><?=GetMessage("SF_SMALL_TO_BASKET");?></a>
            <a href="javascript: void(0);" data-fancybox data-src="#oneClickModal" class="radius5 small_basket_hover_buy inline"><?=GetMessage("SF_SMALL_BUY");?></a>
        </div>
    </div>
<? } ?>