<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
$this->setFrameMode(true);
$this->addExternalCss('/bitrix/css/main/bootstrap.css');

$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'ITEM' => array(
		'ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
		'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
		'JS_OFFERS' => $arResult['JS_OFFERS']
	)
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'ID' => $mainId,
	'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
	'STICKER_ID' => $mainId.'_sticker',
	'BIG_SLIDER_ID' => $mainId.'_big_slider',
	'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
	'SLIDER_CONT_ID' => $mainId.'_slider_cont',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $mainId.'_skudiv',
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
	'OFFER_GROUP' => $mainId.'_set_group_',
	'BASKET_PROP_DIV' => $mainId.'_basket_prop',
	'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	'TABS_ID' => $mainId.'_tabs',
	'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
	'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
	'TABS_PANEL_ID' => $mainId.'_tabs_panel',
	'AJAX_FILE'=>'/local/ajax/basket.php',
	'DAY_WEEK' => $arParams['DAY_WEEK']
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
	: $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
	: $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
	: $arResult['NAME'];

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers)
{
	$actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
		? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
		: reset($arResult['OFFERS']);
	$showSliderControls = false;

	foreach ($arResult['OFFERS'] as $offer)
	{
		if ($offer['MORE_PHOTO_COUNT'] > 1)
		{
			$showSliderControls = true;
			break;
		}
	}
}
else
{
	$actualItem = $arResult;
	$showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}
?>

<?if ($arParams['USE_VOTE_RATING'] === 'Y'):?>
	<div class="bx_item_detail_rating_wrapper">
		<?
		$APPLICATION->IncludeComponent(
			'bitrix:iblock.vote',
			'stars',
			array(
				'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
				'IBLOCK_ID' => $arParams['IBLOCK_ID'],
				'ELEMENT_ID' => $arResult['ID'],
				'ELEMENT_CODE' => '',
				'MAX_VOTE' => '5',
				'VOTE_NAMES' => array('1', '2', '3', '4', '5'),
				'SET_STATUS_404' => 'N',
				'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
				'CACHE_TYPE' => $arParams['CACHE_TYPE'],
				'CACHE_TIME' => $arParams['CACHE_TIME']
			),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
		?>
	</div>
<?endif;?>

<div class="bx_item_detail box padding good_box" id="<?=$itemIds['ID']?>" itemscope itemtype="http://schema.org/Product" data-product-id="<?=!empty($arResult['OFFERS']) ? $arResult['OFFER_ID_SELECTED'] : $arResult['ID']?>">
	<div class="row product-wrapper">
		<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 product-images-wrapper" id="main-slider">
			<div class="product-item-detail-slider-container" id="<?=$itemIds['BIG_SLIDER_ID']?>">
				<span class="product-item-detail-slider-close" data-entity="close-popup"></span>
				<div class="product-item-detail-slider-block
					<?=($arParams['IMAGE_RESOLUTION'] === '1by1' ? 'product-item-detail-slider-block-square' : '')?>" data-entity="images-slider-block">
					<span class="product-item-detail-slider-left" data-entity="slider-control-left" style="display: none;"></span>
					<span class="product-item-detail-slider-right" data-entity="slider-control-right" style="display: none;"></span>
					<div class="product-item-label-text icon_box <?=$labelPositionClass?>" id="<?=$itemIds['STICKER_ID']?>"
						<?=(!$arResult['LABEL'] ? 'style="display: none;"' : '' )?>>
						<?
						if ($arResult['LABEL'] && !empty($arResult['LABEL_ARRAY_VALUE']))
						{
							foreach ($arResult['LABEL_ARRAY_VALUE'] as $code => $value)
							{
								$labelPropMobileClass = !isset($arParams['LABEL_PROP_MOBILE'][$code]) ? ' hidden-xs' : '';
								?>
								<div class="<?=strtolower($code) . $labelPropMobileClass?>"></div>
								<?
							}
						}
						?>
					</div>
					<?
					if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y')
					{
						if ($haveOffers)
						{
							?>
							<div class="product-item-label-ring <?=$discountPositionClass?>" id="<?=$itemIds['DISCOUNT_PERCENT_ID']?>"
								style="display: none;">
							</div>
							<?
						}
						else
						{
							if ($price['DISCOUNT'] > 0)
							{
								?>
								<div class="product-item-label-ring <?=$discountPositionClass?>" id="<?=$itemIds['DISCOUNT_PERCENT_ID']?>"
									title="<?=-$price['PERCENT']?>%">
									<span><?=-$price['PERCENT']?>%</span>
								</div>
								<?
							}
						}
					}
					?>
					<div class="product-item-detail-slider-images-container" data-entity="images-container">
						<?
						if (!empty($actualItem['MORE_PHOTO']))
						{
							foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
							{
								?>
								<div class="product-item-detail-slider-image<?=($key == 0 ? ' active' : '')?>" data-entity="image" data-id="<?=$photo['ID']?>">
									<img src="<?=$photo['SRC']?>" alt="<?=$alt?>" title="<?=$title?>"<?=($key == 0 ? ' itemprop="image"' : '')?>>
								</div>
								<?
							}
						}

						if ($arParams['SLIDER_PROGRESS'] === 'Y')
						{
							?>
							<div class="product-item-detail-slider-progress-bar" data-entity="slider-progress-bar" style="width: 0;"></div>
							<?
						}
						?>
					</div>
				</div>
				<?
				if ($showSliderControls)
				{
					if ($haveOffers)
					{
						foreach ($arResult['OFFERS'] as $keyOffer => $offer)
						{
							if (!isset($offer['MORE_PHOTO_COUNT']) || $offer['MORE_PHOTO_COUNT'] <= 0)
								continue;

							$strVisible = $arResult['OFFERS_SELECTED'] == $keyOffer ? '' : 'none';
							?>
							<div class="product-item-detail-slider-controls-block" id="<?=$itemIds['SLIDER_CONT_OF_ID'].$offer['ID']?>" style="display: <?=$strVisible?>;">
								<?
								foreach ($offer['MORE_PHOTO'] as $keyPhoto => $photo)
								{
									?>
									<div class="product-item-detail-slider-controls-image<?=($keyPhoto == 0 ? ' active' : '')?>"
										data-entity="slider-control" data-value="<?=$offer['ID'].'_'.$photo['ID']?>">
										<img src="<?=$photo['SRC']?>">
									</div>
									<?
								}
								?>
							</div>
							<?
						}
					}
					else
					{
						?>
						<div class="product-item-detail-slider-controls-block" id="<?=$itemIds['SLIDER_CONT_ID']?>">
							<?
							if (!empty($actualItem['MORE_PHOTO']))
							{
								foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
								{
									?>
									<div class="product-item-detail-slider-controls-image<?=($key == 0 ? ' active' : '')?>"
										data-entity="slider-control" data-value="<?=$photo['ID']?>">
										<img src="<?=$photo['SRC']?>">
									</div>
									<?
								}
							}
							?>
						</div>
						<?
					}
				}
				?>
			</div>
		</div>
		<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 product-description-wrapper">
			<div class="product-price-block clearfix">
				<?if ($arParams['USE_PRODUCT_QUANTITY']):?>
					<div class="product-quantity-wrapper">
						<div class="detail_p_head"><?=Loc::getMessage('CT_BCE_QUANTITY')?></div>
						<div class="item_quantity">
							<div class="product-item-amount-field-container">
								<a class="minus" id="<?=$itemIds['QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
								<input id="<?=$itemIds['QUANTITY_ID']?>" type="tel" value="<?=$price['MIN_QUANTITY']?>">
								<a class="plus" id="<?=$itemIds['QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
							</div>
						</div>
						<div class="product-item-amount-description-container">
							<span id="<?=$itemIds['QUANTITY_MEASURE']?>">
								<?=$actualItem['ITEM_MEASURE']['TITLE']?>
							</span>
							<span id="<?=$itemIds['PRICE_TOTAL']?>"></span>
						</div>
					</div>

                <?endif;?>
                <div class="main_detail_price">
                    <?if ($arParams['SHOW_OLD_PRICE'] === 'Y'):?>
                        <div class="detail_p_head">
                            <?=Loc::getMessage('CT_BCE_CATALOG_RATIO_PRICE1')?>
                            <?if ($showDiscount):?>
                                <span class="economy_price"><?=Loc::getMessage('CT_BCE_CATALOG_ECONOMY_INFO2', array('#ECONOMY#' => $price['PRINT_RATIO_DISCOUNT']));?></span>
                            <?endif;?>
                        </div>
                    <?endif;?>

                    <div class="fl">
                        <?if ($arParams['SHOW_OLD_PRICE'] === 'Y'):?>
                            <span class="old_price" id="<?=$itemIds['OLD_PRICE_ID']?>" style="display: <?=($showDiscount ? '' : 'none')?>;">
                                <?=($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '')?>
                            </span>
                        <?endif;?>
                        <div class="price" id="<?=$itemIds['PRICE_ID']?>">
                            <?=$price['PRINT_RATIO_PRICE']?>
                        </div>
                        <div style="clear: both"></div>
                        <?
                        if ($arParams['USE_PRICE_COUNT'])
                        {
                            $showRanges = !$haveOffers && count($actualItem['ITEM_QUANTITY_RANGES']) > 1;
                            $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';
                            ?>
                            <div class="product-item-detail-info-container" <?=$showRanges ? '' : 'style="display: none;"'?> data-entity="price-ranges-block">
                                <dl class="product-item-detail-properties" data-entity="price-ranges-body">
                                    <?
                                    if ($showRanges)
                                    {
                                        foreach ($actualItem['ITEM_QUANTITY_RANGES'] as $range)
                                        {
                                            if ($range['HASH'] !== 'ZERO-INF')
                                            {
                                                $itemPrice = false;

                                                foreach ($arResult['ITEM_PRICES'] as $itemPrice)
                                                {
                                                    if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
                                                    {
                                                        break;
                                                    }
                                                }

                                                if ($itemPrice)
                                                {
                                                    ?>
                                                    <dt>
                                                        <?
                                                        echo Loc::getMessage(
                                                                'CT_BCE_CATALOG_RANGE_FROM',
                                                                array('#FROM#' => $range['SORT_FROM'].' '.$actualItem['ITEM_MEASURE']['TITLE'])
                                                            ).' ';

                                                        if (is_infinite($range['SORT_TO']))
                                                        {
                                                            echo Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                                                        }
                                                        else
                                                        {
                                                            echo Loc::getMessage(
                                                                'CT_BCE_CATALOG_RANGE_TO',
                                                                array('#TO#' => $range['SORT_TO'].' '.$actualItem['ITEM_MEASURE']['TITLE'])
                                                            );
                                                        }
                                                        ?>
                                                    </dt>
                                                    <dd><?=($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE'])?></dd>
                                                    <?
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </dl>
                            </div>
                            <?
                            unset($showRanges, $useRatio, $itemPrice, $range);
                        }
                        ?>
                    </div>

                    <div class="main-button-container" data-entity="main-button-container">
                        <div id="<?=$itemIds['BASKET_ACTIONS_ID']?>" style="display: <?=($actualItem['CAN_BUY'] ? '' : 'none')?>;"><?
                            if ($showAddBtn)
                            {
                                ?>
                                <a class="detail_buy_button inline buy_button_a <?=$showButtonClassName?>" id="<?=$itemIds['ADD_BASKET_LINK']?>" href="javascript:void(0);">
                                    <span class="icon"></span>
                                    <span class="text"><?=$arParams['MESS_BTN_ADD_TO_BASKET']?></span>
                                </a>
                                <?
                            }

                            if ($showBuyBtn)
                            {
                                ?>
                                <a class="detail_buy_button inline buy_button_a <?=$buyButtonClassName?>" id="<?=$itemIds['BUY_LINK']?>" href="javascript:void(0);">
                                    <span class="icon"></span>
                                    <span class="text"><?=$arParams['MESS_BTN_BUY']?></span>
                                </a>
                                <?
                            }
                            ?>

                            <a href="javascript:void(0);" class="buy_one_click_product_detail buy_one_click_product" data-fancybox="" data-src="#buyOneProductModal"><?=Loc::getMessage('CT_BCE_ONE_CLICK')?></a>
                        </div>
                        <div class="product-item-detail-info-container">
                            <div class="product-main-button">
                            <?
                            if ($showSubscribe)
                            {
                                $APPLICATION->IncludeComponent(
                                    'bitrix:catalog.product.subscribe',
                                    '',
                                    array(
                                        'PRODUCT_ID' => $arResult['ID'],
                                        'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                        'BUTTON_CLASS' => 'btn btn-default product-item-detail-buy-button',
                                        'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                    ),
                                    $component,
                                    array('HIDE_ICONS' => 'Y')
                                );
                            }
                            ?>
                            </div>
                            <div id="<?=$itemIds['NOT_AVAILABLE_MESS']?>" class="catalog-element-info-block" rel="nofollow" style="display: <?=(!$actualItem['CAN_BUY'] ? '' : 'none')?>;">
                                <?=$arParams['MESS_NOT_AVAILABLE']?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mess-show-max-quantity"></div>
			</div>
            <div class="product-mobile-block">
                <div class="product-mobile-block__title">
                    <h3>Ïðîñòàÿ ïèööà 4</h3>
                </div>
                <div class="product-mobile-block__wrap">
                    <div class="product-mobile-block__price">
                        <?if ($arParams['SHOW_OLD_PRICE'] === 'Y'):?>
                            <span class="old_price" id="<?=$itemIds['OLD_PRICE_ID']?>" style="display: <?=($showDiscount ? '' : 'none')?>;">
                                <?=($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '')?>
                            </span>
                        <?endif;?>
                        <div class="price" id="<?=$itemIds['PRICE_ID']?>">
                            <?=$price['PRINT_RATIO_PRICE']?>
                        </div>
                    </div>
                    <div class="product-mobile-block__btn">
                        <a class="detail_buy_button product-mobile-block__add inline buy_button_a <?=$showButtonClassName?>" id="" href="javascript:void(0);">
                        </a>
                        <div class="product-mobile-block__quantity">
                            <div class="product-quantity-wrapper">
                                <div class="item_quantity">
                                    <div class="product-item-amount-field-container">
                                        <a class="minus minus-mobile" id="<?=$itemIds['QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
                                        <input id="<?=$itemIds['QUANTITY_ID']?>" type="tel" value="<?=$price['MIN_QUANTITY']?>">
                                        <a class="plus" id="<?=$itemIds['QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="buy_one_click_product_detail buy_one_click_product" data-fancybox="" data-src="#buyOneProductModal"><?=Loc::getMessage('CT_BCE_ONE_CLICK')?></a>

                    </div>
                </div>
            </div>

			<div class="product-options-wrapper">
				<?
				if ($haveOffers && !empty($arResult['OFFERS_PROP']))
				{
					?>
					<div class="offers_item" id="<?=$itemIds['TREE_ID']?>">
						<?
						foreach ($arResult['SKU_PROPS'] as $skuProperty)
						{
							if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
								continue;

							$propertyId = $skuProperty['ID'];
							$skuProps[] = array(
								'ID' => $propertyId,
								'SHOW_MODE' => $skuProperty['SHOW_MODE'],
								'VALUES' => $skuProperty['VALUES'],
								'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
							);
							?>
							<div class="offer_item" data-entity="sku-line-block">
								<div class="offer_name"><?=htmlspecialcharsEx($skuProperty['NAME'])?></div>
								<ul class="product-item-scu-item-list">
									<?
									foreach ($skuProperty['VALUES'] as &$value)
									{
										$value['NAME'] = htmlspecialcharsbx($value['NAME']);

										if ($skuProperty['SHOW_MODE'] === 'PICT')
										{
											?>
											<li class="product-item-scu-item-color-container offer_sku color" title="<?=$value['NAME']?>"
											    data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
											    data-onevalue="<?=$value['ID']?>">
												<img src="<?=$value['PICT']['SRC']?>">
											</li>
											<?
										}
										else
										{
											?>
											<li class="product-item-scu-item-text-container offer_sku" title="<?=$value['NAME']?>"
											    data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
											    data-onevalue="<?=$value['ID']?>">
												<div class="product-item-scu-item-text"><?=$value['NAME']?></div>
											</li>
											<?
										}
									}
									?>
								</ul>
							</div>
							<?
						}
                        if (!empty($arResult['OFFERS']))
                        {
                            foreach ($arResult['OFFERS'] as $arOffer)
                            {
                                if (!empty($arOffer['PREVIEW_TEXT']))
                                {
                                    ?>
                                    <div class="detail_preview_text main_detail_preview_text" itemprop="description" style="display: block;" id="offer_preview_text_<?=$arOffer['ID']?>">
                                        <?=$arOffer['PREVIEW_TEXT']?>
                                    </div>
                                    <?
                                }
                            }
                        }
						?>
					</div>
					<?
				}



				if ($arParams['BRAND_USE'] === 'Y')
				{
					$APPLICATION->IncludeComponent(
						'bitrix:catalog.brandblock',
						'.default',
						array(
							'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
							'IBLOCK_ID' => $arParams['IBLOCK_ID'],
							'ELEMENT_ID' => $arResult['ID'],
							'ELEMENT_CODE' => '',
							'PROP_CODE' => $arParams['BRAND_PROP_CODE'],
							'CACHE_TYPE' => $arParams['CACHE_TYPE'],
							'CACHE_TIME' => $arParams['CACHE_TIME'],
							'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
							'WIDTH' => '',
							'HEIGHT' => ''
						),
						$component,
						array('HIDE_ICONS' => 'Y')
					);
				}
				?>
			</div>
		</div>
	</div>
	<div class="product-divider"></div>

    <?if (CGetfood::getOption("BLOCK_ABOUT") == 'true' || CGetfood::getOption("BLOCK_CHARACTERISTIC") == 'true' || CGetfood::getOption("NEAR_RESTORANS") == 'true' || CGetfood::getOption("BLOCK_COMMENTS") == 'true'){?>
        <div class="tabs_header">
            <?
            $is_active_tabs="active";
            $is_active_content="active";
            ?>
            <?if (CGetfood::getOption("BLOCK_ABOUT") == 'true' && $showDescription):?>
                <div class="tabs_head <?=$is_active_tabs?>">
                    <a id="description" href="#" data-href=".dt1" title="<?=$arParams['MESS_DESCRIPTION_TAB']?>">
                        <span class="icon_description icons_head"></span>
                        <span class="text"><?=$arParams['MESS_DESCRIPTION_TAB']?></span>
                    </a>
                </div>
                <?$is_active_tabs="";?>
            <?endif;?>
            <?if (CGetfood::getOption("BLOCK_CHARACTERISTIC") == 'true' && (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])):?>
                <div class="tabs_head <?=$is_active_tabs?>">
                    <a id="characters" href="#" data-href=".dt2" title="<?=$arParams['MESS_PROPERTIES_TAB']?>">
                        <span class="icon_item_params icons_head"></span>
                        <span class="text"><?=$arParams['MESS_PROPERTIES_TAB']?></span>
                    </a>
                </div>
                <?$is_active_tabs="";?>
            <?endif;?>
            <?if (CGetfood::getOption("NEAR_RESTORANS") == 'true' && $arParams["USE_STORE"] == "Y" && \Bitrix\Main\ModuleManager::isModuleInstalled("catalog")) { ?>
                <div class="tabs_head <?=$is_active_tabs?>">
                    <a id="restoran" href="#" data-href=".dt3" title="<?=Loc::getMessage("CT_BCE_CATALOG_STORE_TAB_TITLE");?>">
                        <span class="icon_ostatok icons_head"></span>
                        <span class="text"><?=Loc::getMessage("CT_BCE_CATALOG_STORE_TAB_TITLE");?></span>
                    </a>
                </div>
                <?$is_active_tabs="";?>
            <? } ?>
            <?if (CGetfood::getOption("BLOCK_COMMENTS") == 'true' && $arParams['USE_COMMENTS'] === 'Y'):?>
                <div class="tabs_head <?=$is_active_tabs?>">
                    <a id="comment" href="#" data-href=".dt4" title="<?=$arParams['MESS_COMMENTS_TAB']?>" onClick="showComment('0'); $(this).removeAttr('onClick');">
                        <span class="icon_comments icons_head"></span>
                        <span class="text"><?=$arParams['MESS_COMMENTS_TAB']?></span>
                    </a>
                </div>
                <?$is_active_tabs="";?>
            <?endif;?>
        </div>
        <div class="tabs_bodyes">
            <?if (CGetfood::getOption("BLOCK_ABOUT") == 'true' && $showDescription):?>
                <div class="tabs_body main_detail_text dt1 <?=$is_active_content?>">
                    <div class="main_detail_text">
                        <?
                        if (
                            $arResult['PREVIEW_TEXT'] != ''
                            && (
                                $arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S'
                                || ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == '')
                            )
                        )
                        {
                            echo $arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>';
                        }

                        if ($arResult['DETAIL_TEXT'] != '')
                        {
                            echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>'.$arResult['DETAIL_TEXT'].'</p>';
                        }
                        ?>
                    </div>
                </div>
                <?$is_active_content="";?>
            <?endif;?>

            <?if (CGetfood::getOption("BLOCK_CHARACTERISTIC") == 'true' &&  (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])):?>
                <div class="tabs_body main_detail_props dt2 <?=$is_active_content?>">
                    <div class="item_props">
                        <?
                        if (!empty($arResult['DISPLAY_PROPERTIES']))
                        {
                            foreach ($arResult['DISPLAY_PROPERTIES'] as $property)
                            {
                                ?>
                                <p>
                                    <span class="prop_name">
                                        <?=$property['NAME']?>
                                        <?if (!empty($property["HINT"])):?>
                                            <span class="quest">?<span class="tooltip"><?=$property["HINT"]?></span></span>
                                        <?endif;?>
                                    </span>
                                    <span class="prop_value"><?=(
                                        is_array($property['DISPLAY_VALUE'])
                                            ? implode(' / ', $property['DISPLAY_VALUE'])
                                            : $property['DISPLAY_VALUE']
                                        )?>
                                    </span>
                                </p>
                                <?
                            }
                            unset($property);
                        }

                        if ($arResult['SHOW_OFFERS_PROPS'])
                        {
                            ?>
                            <div class="product-item-detail-properties" id="<?=$itemIds['DISPLAY_PROP_DIV']?>"></div>
                            <?
                        }
                        ?>
                    </div>
                </div>
                <?$is_active_content="";?>
            <?endif;?>

            <? if (CGetfood::getOption("NEAR_RESTORANS") == 'true' && $arParams["USE_STORE"] == "Y" && \Bitrix\Main\ModuleManager::isModuleInstalled("catalog")):?>
                <div class="tabs_body dt3 <?=$is_active_content?>">
                    <div class="main_detail_quant">
                        <?$APPLICATION->IncludeComponent(
                            'bitrix:catalog.store.amount',
                            '.default',
                            array(
                                'ELEMENT_ID' => $arResult['ID'],
                                'STORE_PATH' => $arParams['STORE_PATH'],
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => '36000',
                                'MAIN_TITLE' => $arParams['STORE_MAIN_TITLE'],
                                'USE_MIN_AMOUNT' =>  $arParams['STORE_USE_MIN_AMOUNT'],
                                'MIN_AMOUNT' => $arParams['STORE_MIN_AMOUNT'],
                                'STORES' => $arParams['STORES'],
                                'SHOW_EMPTY_STORE' => $arParams['STORE_SHOW_EMPTY_STORE'],
                                'SHOW_GENERAL_STORE_INFORMATION' => $arParams['STORE_SHOW_GENERAL_STORE_INFORMATION'],
                                'USER_FIELDS' => $arParams['STORE_USER_FIELDS'],
                                'FIELDS' => $arParams['STORE_FIELDS']
                            ),
                            $component,
                            array('HIDE_ICONS' => 'Y')
                        );?>
                    </div>
                </div>
                <?$is_active_content="";?>
            <?endif;?>
            <? if (CGetfood::getOption("BLOCK_COMMENTS") == 'true' && "Y" == $arParams["USE_COMMENTS"]) { ?>
                <div class="tabs_body dt4 <?=$is_active_content?>">
                    <?//var_dump($arParams['PATH_TO_SMILE']);?>
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
                            "PATH_TO_SMILE" => '',
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
                <?$is_active_content="";?>
            <? } ?>
        </div>
    <? } ?>
	<div class="row">
		<div class="col-xs-12">
			<?
        	if ($haveOffers)
			{
				if ($arResult['OFFER_GROUP'])
				{
					foreach ($arResult['OFFER_GROUP_VALUES'] as $offerId)
					{
						?>
						<span id="<?=$itemIds['OFFER_GROUP'].$offerId?>" style="display: none;">
							<?
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.set.constructor',
								'.default',
								array(
									'IBLOCK_ID' => $arResult['OFFERS_IBLOCK'],
									'ELEMENT_ID' => $offerId,
									'PRICE_CODE' => $arParams['PRICE_CODE'],
									'BASKET_URL' => $arParams['BASKET_URL'],
									'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
									'CACHE_TYPE' => $arParams['CACHE_TYPE'],
									'CACHE_TIME' => $arParams['CACHE_TIME'],
									'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
									'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
									'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
									'CURRENCY_ID' => $arParams['CURRENCY_ID']
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
							?>
						</span>
						<?
					}
				}
			}
			else
			{
                $APPLICATION->IncludeComponent(
						'bitrix:catalog.set.constructor',
						'.default',
						array(
							'IBLOCK_ID' => $arParams['IBLOCK_ID'],
							'ELEMENT_ID' => $arResult['ID'],
							'PRICE_CODE' => $arParams['PRICE_CODE'],
							'BASKET_URL' => $arParams['BASKET_URL'],
							'CACHE_TYPE' => $arParams['CACHE_TYPE'],
							'CACHE_TIME' => $arParams['CACHE_TIME'],
							'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
							'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
							'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
							'CURRENCY_ID' => "RUB",/*$arParams['CURRENCY_ID']*/
						),
						$component,
						array('HIDE_ICONS' => 'Y')
					);

			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<?
			if ($arResult['CATALOG'] && $actualItem['CAN_BUY'] && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
			{
				$APPLICATION->IncludeComponent(
					'bitrix:sale.prediction.product.detail',
					'.default',
					array(
						'BUTTON_ID' => $showBuyBtn ? $itemIds['BUY_LINK'] : $itemIds['ADD_BASKET_LINK'],
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
			?>
		</div>
	</div>

	<meta itemprop="name" content="<?=$name?>" />
	<meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />
	<?
	if ($haveOffers)
	{
		foreach ($arResult['JS_OFFERS'] as $offer)
		{
			$currentOffersList = array();

			if (!empty($offer['TREE']) && is_array($offer['TREE']))
			{
				foreach ($offer['TREE'] as $propName => $skuId)
				{
					$propId = (int)substr($propName, 5);

					foreach ($skuProps as $prop)
					{
						if ($prop['ID'] == $propId)
						{
							foreach ($prop['VALUES'] as $propId => $propValue)
							{
								if ($propId == $skuId)
								{
									$currentOffersList[] = $propValue['NAME'];
									break;
								}
							}
						}
					}
				}
			}

			$offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
			?>
			<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="sku" content="<?=htmlspecialcharsbx(implode('/', $currentOffersList))?>" />
				<meta itemprop="price" content="<?=$offerPrice['RATIO_PRICE']?>" />
				<meta itemprop="priceCurrency" content="<?=$offerPrice['CURRENCY']?>" />
				<link itemprop="availability" href="http://schema.org/<?=($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
			</span>
			<?
		}

		unset($offerPrice, $currentOffersList);
	}
	else
	{
		?>
		<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?=$price['RATIO_PRICE']?>" />
			<meta itemprop="priceCurrency" content="<?=$price['CURRENCY']?>" />
			<link itemprop="availability" href="http://schema.org/<?=($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
		</span>
		<?
	}
	?>
</div>

<?
if (!isset($arParams['HIDE_SECTION_DESCRIPTION']) || $arParams['HIDE_SECTION_DESCRIPTION'] != 'Y')
{
	$this->SetViewTarget('section_description');
	if (!empty($arResult['SECTION_DETAIL_PICTURE']))
	{
		?><div class="box hd"><img src="<?=$arResult['SECTION_DETAIL_PICTURE']?>"></div><?
	}
	$this->EndViewTarget();
}
?>

<?
if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
{
	?>
	<?if(CGetfood::getOption("RECOMENDS_TO_BUY") == 'true'){?>
	<div data-entity="parent-container">
		<?
		if (!isset($arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE']) || $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y')
		{
			?>
			<div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
				<?=($arParams['GIFTS_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_GIFT_BLOCK_TITLE_DEFAULT'))?>
			</div>
			<?
		}

		CBitrixComponent::includeComponentClass('bitrix:sale.products.gift');
		$APPLICATION->IncludeComponent(
			'bitrix:sale.products.gift',
			'.default',
			array(
				'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
				'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],

				'PRODUCT_ROW_VARIANTS' => "",
				'PAGE_ELEMENT_COUNT' => 0,
				'DEFERRED_PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
					SaleProductsGiftComponent::predictRowVariants(
						$arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
						$arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT']
					)
				),
				'DEFERRED_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],

				'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
				'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
				'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
				'PRODUCT_DISPLAY_MODE' => 'Y',
				'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],
				'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
				'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
				'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

				'TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],

				'LABEL_PROP_'.$arParams['IBLOCK_ID'] => array(),
				'LABEL_PROP_MOBILE_'.$arParams['IBLOCK_ID'] => array(),
				'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],

				'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
				'MESS_BTN_BUY' => $arParams['~GIFTS_MESS_BTN_BUY'],
				'MESS_BTN_ADD_TO_BASKET' => $arParams['~GIFTS_MESS_BTN_BUY'],
				'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
				'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],

				'SHOW_PRODUCTS_'.$arParams['IBLOCK_ID'] => 'Y',
				'PROPERTY_CODE_'.$arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE'],
				'PROPERTY_CODE_MOBILE'.$arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE_MOBILE'],
				'PROPERTY_CODE_'.$arResult['OFFERS_IBLOCK'] => $arParams['OFFER_TREE_PROPS'],
				'OFFER_TREE_PROPS_'.$arResult['OFFERS_IBLOCK'] => $arParams['OFFER_TREE_PROPS'],
				'CART_PROPERTIES_'.$arResult['OFFERS_IBLOCK'] => $arParams['OFFERS_CART_PROPERTIES'],
				'ADDITIONAL_PICT_PROP_'.$arParams['IBLOCK_ID'] => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),
				'ADDITIONAL_PICT_PROP_'.$arResult['OFFERS_IBLOCK'] => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),

				'HIDE_NOT_AVAILABLE' => 'Y',
				'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
				'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
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
				'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
				'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
				'POTENTIAL_PRODUCT_TO_BUY' => array(
					'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
					'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
					'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
					'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
					'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

					'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
						? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID']
						: null,
					'SECTION' => array(
						'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
						'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
						'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
						'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
					),
				),

				'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
				'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
				'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
			),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
		?>
	</div>
	<?}?>
	<?
}

if ($arResult['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
{
	?>
	<div data-entity="parent-container">
		<?
		if (!isset($arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE']) || $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y')
		{
			?>
			<div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
				<?=($arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_GIFTS_MAIN_BLOCK_TITLE_DEFAULT'))?>
			</div>
			<?
		}

		$APPLICATION->IncludeComponent(
			'bitrix:sale.gift.main.products',
			'.default',
			array(
				'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
				'LINE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
				'HIDE_BLOCK_TITLE' => 'Y',
				'BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

				'OFFERS_FIELD_CODE' => $arParams['OFFERS_FIELD_CODE'],
				'OFFERS_PROPERTY_CODE' => $arParams['OFFERS_PROPERTY_CODE'],

				'AJAX_MODE' => $arParams['AJAX_MODE'],
				'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
				'IBLOCK_ID' => $arParams['IBLOCK_ID'],

				'ELEMENT_SORT_FIELD' => 'ID',
				'ELEMENT_SORT_ORDER' => 'DESC',
				//'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
				//'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],
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
				'HIDE_NOT_AVAILABLE' => 'Y',
				'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
				'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
				'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],

				'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
				'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
				'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

				'ADD_PICT_PROP' => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),
				'LABEL_PROP' => (isset($arParams['LABEL_PROP']) ? $arParams['LABEL_PROP'] : ''),
				'LABEL_PROP_MOBILE' => (isset($arParams['LABEL_PROP_MOBILE']) ? $arParams['LABEL_PROP_MOBILE'] : ''),
				'LABEL_PROP_POSITION' => (isset($arParams['LABEL_PROP_POSITION']) ? $arParams['LABEL_PROP_POSITION'] : ''),
				'OFFER_ADD_PICT_PROP' => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),
				'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : ''),
				'SHOW_DISCOUNT_PERCENT' => (isset($arParams['SHOW_DISCOUNT_PERCENT']) ? $arParams['SHOW_DISCOUNT_PERCENT'] : ''),
				'DISCOUNT_PERCENT_POSITION' => (isset($arParams['DISCOUNT_PERCENT_POSITION']) ? $arParams['DISCOUNT_PERCENT_POSITION'] : ''),
				'SHOW_OLD_PRICE' => (isset($arParams['SHOW_OLD_PRICE']) ? $arParams['SHOW_OLD_PRICE'] : ''),
				'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
				'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
				'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
				'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
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

				'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
				'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
				'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
			),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
		?>
	</div>
	<?
}

if ($haveOffers)
{
	$offerIds = array();
	$offerCodes = array();

	$useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

	foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer)
	{
		$offerIds[] = (int)$jsOffer['ID'];
		$offerCodes[] = $jsOffer['CODE'];

		$fullOffer = $arResult['OFFERS'][$ind];
		$measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

		$strAllProps = '';
		$strMainProps = '';
		$strPriceRangesRatio = '';
		$strPriceRanges = '';

		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			if (!empty($jsOffer['DISPLAY_PROPERTIES']))
			{
				foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property)
				{
					$current = '<span class="prop_name">'.$property['NAME'].'</span><span class="prop_value">'.(
						is_array($property['VALUE'])
							? implode(' / ', $property['VALUE'])
							: $property['VALUE']
						).'</span>';
					$strAllProps .= $current;

					if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']]))
					{
						$strMainProps .= $current;
					}
				}

				unset($current);
			}
		}

		if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1)
		{
			$strPriceRangesRatio = '('.Loc::getMessage(
					'CT_BCE_CATALOG_RATIO_PRICE',
					array('#RATIO#' => ($useRatio
							? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
							: '1'
						).' '.$measureName)
				).')';

			foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range)
			{
				if ($range['HASH'] !== 'ZERO-INF')
				{
					$itemPrice = false;

					foreach ($jsOffer['ITEM_PRICES'] as $itemPrice)
					{
						if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
						{
							break;
						}
					}

					if ($itemPrice)
					{
						$strPriceRanges .= '<dt>'.Loc::getMessage(
								'CT_BCE_CATALOG_RANGE_FROM',
								array('#FROM#' => $range['SORT_FROM'].' '.$measureName)
							).' ';

						if (is_infinite($range['SORT_TO']))
						{
							$strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
						}
						else
						{
							$strPriceRanges .= Loc::getMessage(
								'CT_BCE_CATALOG_RANGE_TO',
								array('#TO#' => $range['SORT_TO'].' '.$measureName)
							);
						}

						$strPriceRanges .= '</dt><dd>'.($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']).'</dd>';
					}
				}
			}

			unset($range, $itemPrice);
		}

		$jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
		$jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
		$jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
		$jsOffer['PRICE_RANGES_HTML'] = $strPriceRanges;
	}

	$templateData['OFFER_IDS'] = $offerIds;
	$templateData['OFFER_CODES'] = $offerCodes;
	unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio);

	$jsParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
			'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
			'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
			'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
			'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
			'USE_STICKERS' => true,
			'USE_SUBSCRIBE' => $showSubscribe,
			'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
			'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
			'ALT' => $alt,
			'TITLE' => $title,
			'MAGNIFIER_ZOOM_PERCENT' => 200,
			'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
				? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
				: null
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => $itemIds,
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'ACTIVE' => $arResult['ACTIVE'],
			'NAME' => $arResult['~NAME'],
			'CATEGORY' => $arResult['CATEGORY_PATH']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $skuProps
	);
}
else
{
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties)
	{
		?>
		<div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
			<?
			if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
			{
				foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo)
				{
					?>
					<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
					<?
					unset($arResult['PRODUCT_PROPERTIES'][$propId]);
				}
			}

			$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
			if (!$emptyProductProperties)
			{
				?>
				<table>
					<?
					foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo)
					{
						?>
						<tr>
							<td class="prop-title"><?=$arResult['PROPERTIES'][$propId]['NAME']?></td>
							<td class="prop-value">
								<?
								if (
									$arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === 'L'
									&& $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
								)
								{
									foreach ($propInfo['VALUES'] as $valueId => $value)
									{
										?>
										<label>
											<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]"
												value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"checked"' : '')?>>
											<?=$value?>
										</label>
										<br>
										<?
									}
								}
								else
								{
									?>
									<select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]">
										<?
										foreach ($propInfo['VALUES'] as $valueId => $value)
										{
											?>
											<option value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"selected"' : '')?>>
												<?=$value?>
											</option>
											<?
										}
										?>
									</select>
									<?
								}
								?>
							</td>
						</tr>
						<?
					}
					?>
				</table>
				<?
			}
			?>
		</div>
		<?
	}

	$jsParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
			'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
			'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
			'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
			'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
			'USE_STICKERS' => true,
			'USE_SUBSCRIBE' => $showSubscribe,
			'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
			'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
			'ALT' => $alt,
			'TITLE' => $title,
			'MAGNIFIER_ZOOM_PERCENT' => 200,
			'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
				? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
				: null 
		),
		'VISUAL' => $itemIds,
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'ACTIVE' => $arResult['ACTIVE'],
			'PICT' => reset($arResult['MORE_PHOTO']),
			'NAME' => $arResult['~NAME'],
			'SUBSCRIPTION' => true,
			'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
			'ITEM_PRICES' => $arResult['ITEM_PRICES'],
			'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
			'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
			'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
			'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
			'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
			'CATEGORY' => $arResult['CATEGORY_PATH']
		),
		'BASKET' => array(
			'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties,
			'BASKET_URL' => $arParams['BASKET_URL'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		)
	);
	unset($emptyProductProperties);
}

if ($arParams['DISPLAY_COMPARE'])
{
	$jsParams['COMPARE'] = array(
		'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
		'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
		'COMPARE_PATH' => $arParams['COMPARE_PATH']
	);
}
?>
<script>
	BX.message({
		ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		SITE_ID: '<?=SITE_ID?>'
	});

	var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);

	$('body').addClass('is-product-page');
</script>
<?
unset($actualItem, $itemIds, $jsParams);