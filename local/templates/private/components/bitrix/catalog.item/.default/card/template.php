<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */
?>

<div class="img_box">
	<a class="product-item-image-wrapper" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$imgTitle?>" data-entity="image-wrapper">
		<span class="product-item-image-slider-slide-container slide" id="<?=$itemIds['PICT_SLIDER']?>"
		      style="display: <?=($showSlider ? '' : 'none')?>;"
		      data-slider-interval="<?=$arParams['SLIDER_INTERVAL']?>" data-slider-wrap="true">
			<?
			if ($showSlider)
			{
				foreach ($morePhoto as $key => $photo)
				{
					?>
					<span class="product-item-image-slide item <?=($key == 0 ? 'active' : '')?>"
					      style="background-image: url(<?=$photo['SRC']?>);">
					</span>
					<?
				}
			}
			?>
		</span>
		<span class="product-item-image-original" id="<?=$itemIds['PICT']?>"
		      style="background-image: url(<?=$item['PREVIEW_PICTURE']['SRC']?>); display: <?=($showSlider ? 'none' : '')?>;">
		</span>
		<?
		if ($item['SECOND_PICT'])
		{
			$bgImage = !empty($item['PREVIEW_PICTURE_SECOND']) ? $item['PREVIEW_PICTURE_SECOND']['SRC'] : $item['PREVIEW_PICTURE']['SRC'];
			?>
			<span class="product-item-image-alternative" id="<?=$itemIds['SECOND_PICT']?>"
			      style="background-image: url(<?=$bgImage?>); display: <?=($showSlider ? 'none' : '')?>;">
			</span>
			<?
		}

		if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y')
		{
			?>
			<div class="product-item-label-ring <?=$discountPositionClass?>" id="<?=$itemIds['DSC_PERC']?>"
			     style="display: <?=($price['PERCENT'] > 0 ? '' : 'none')?>;">
				<span><?=-$price['PERCENT']?>%</span>
			</div>
			<?
		}

		if ($item['LABEL'])
		{
			?>
			<div class="icon_box product-item-label-text <?=$labelPositionClass?>" id="<?=$itemIds['STICKER_ID']?>">
				<?
				if (!empty($item['LABEL_ARRAY_VALUE']))
				{
					foreach ($item['LABEL_ARRAY_VALUE'] as $code => $value)
					{
						?>
						<div class="<?=strtolower($code) . (!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' hidden-xs' : '')?>" title="<?=$value?>"></div>
						<?
					}
				}
				?>
			</div>
			<?
		}
		?>
		<div class="product-item-image-slider-control-container" id="<?=$itemIds['PICT_SLIDER']?>_indicator"
		     style="display: <?=($showSlider ? '' : 'none')?>;">
			<?
			if ($showSlider)
			{
				foreach ($morePhoto as $key => $photo)
				{
					?>
					<div class="product-item-image-slider-control<?=($key == 0 ? ' active' : '')?>" data-go-to="<?=$key?>"></div>
					<?
				}
			}
			?>
		</div>
		<?
		if ($arParams['SLIDER_PROGRESS'] === 'Y')
		{
			?>
			<div class="product-item-image-slider-progress-bar-container">
				<div class="product-item-image-slider-progress-bar" id="<?=$itemIds['PICT_SLIDER']?>_progress_bar" style="width: 0;"></div>
			</div>
			<?
		}
		?>
	</a>
	<div class="hover_over">
		<a href="javascript:;" data-width="810" data-fancybox="group" data-src="<?=$item["DETAIL_PAGE_URL"];?>?open_popup=Y" class="open_fancybox" rel="gallery">
			<?=Loc::getMessage("CT_BCI_TPL_MESS_FAST_VIEW");?>
		</a>
	</div>
</div>

<div class="item-info">
	<div class="bx_catalog_item_title name">
		<a href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$productTitle?>"><?=$productTitle?></a>
	</div>

	<?
	if ($item['LABEL'])
	{
		?>
		<div class="icon_box product-item-label-text">
			<?
			if (!empty($item['LABEL_ARRAY_VALUE']))
			{
				foreach ($item['LABEL_ARRAY_VALUE'] as $code => $value)
				{
					?>
					<div class="<?=strtolower($code)?>" title="<?=$value?>"></div>
					<?
				}
			}
			?>
		</div>
		<?
	}
	?>

	<div class="control-container">
		<div class="bx_catalog_item_price" data-entity="price-block">
			<div class="item_price">
				<?
				if ($arParams['SHOW_OLD_PRICE'] === 'Y')
				{
					?>
					<div class="old_price" id="<?=$itemIds['PRICE_OLD']?>" <?=($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '')?>>
						<?=$price['PRINT_RATIO_BASE_PRICE']?>
					</div>
					<?
				}
				?>
				<div class="price_box__actual-price" id="<?=$itemIds['PRICE']?>">
					<?
					if (!empty($price))
					{
						if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
						{
							echo Loc::getMessage(
								'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
								array(
									'#PRICE#' => $price['PRINT_RATIO_PRICE'],
									'#VALUE#' => $measureRatio,
									'#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
								)
							);
						}
						else
						{
							echo $price['PRINT_RATIO_PRICE'];
						}
					}
					?>
				</div>
			</div>
		</div>

		<div class="bx_catalog_item_controls align-items-center">
			<div class="bx_catalog_item_controls_blockone item_quantity inline" data-entity="quantity-block">
				<?
				if (!$haveOffers)
				{
					if ($actualItem['CAN_BUY'] && $arParams['USE_PRODUCT_QUANTITY'])
					{
						?>
						<a class="minus" id="<?=$itemIds['QUANTITY_DOWN']?>" href="javascript:void(0)" rel="nofollow">-</a>
						<input id="<?=$itemIds['QUANTITY']?>" type="text" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$measureRatio?>">
						<a class="plus" id="<?=$itemIds['QUANTITY_UP']?>" href="javascript:void(0)" rel="nofollow">+</a>
						<div class="product-item-amount-description-container">
							<span id="<?=$itemIds['QUANTITY_MEASURE']?>">
								<?=$actualItem['ITEM_MEASURE']['TITLE']?>
							</span>
							<span id="<?=$itemIds['PRICE_TOTAL']?>"></span>
						</div>
						<?
					}
				}
				elseif ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
				{
					if ($arParams['USE_PRODUCT_QUANTITY'])
					{
						?>
						<a class="minus" id="<?=$itemIds['QUANTITY_DOWN']?>" href="javascript:void(0)" rel="nofollow">-</a>
						<input id="<?=$itemIds['QUANTITY']?>" type="text" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$measureRatio?>">
						<a class="plus" id="<?=$itemIds['QUANTITY_UP']?>" href="javascript:void(0)" rel="nofollow">+</a>
						<div class="product-item-amount-description-container">
							<span id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$actualItem['ITEM_MEASURE']['TITLE']?></span>
							<span id="<?=$itemIds['PRICE_TOTAL']?>"></span>
						</div>
						<?
					}
				}
				?>
			</div>

			<div class="buy_box fr" data-entity="buttons-block">
				<?
				if (!$haveOffers)
				{
					if ($actualItem['CAN_BUY'])
					{
						?>
						<div id="<?=$itemIds['BASKET_ACTIONS']?>" class="basket_action_container">
							<a class="buy buy_button_a show_basket_popup inline" id="<?=$itemIds['BUY_LINK']?>" href="javascript:void(0)" rel="nofollow"></a>
						</div>
						<?
					}
					else
					{
						?>
						<div class="product-item-button-container">
							<?
							if ($showSubscribe)
							{
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.product.subscribe',
									'',
									array(
										'PRODUCT_ID' => $actualItem['ID'],
										'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
										'BUTTON_CLASS' => 'btn btn-default '.$buttonSizeClass,
										'DEFAULT_DISPLAY' => true,
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);
							}
							?>
							<div class="product-not-available__section" rel="nofollow">
								<?=$arParams['MESS_NOT_AVAILABLE']?>
							</div>
						</div>
						<?
					}
				}
				else
				{
					if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
					{
						?>
						<div class="product-item-button-container">
							<?
							if ($showSubscribe)
							{
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.product.subscribe',
									'',
									array(
										'PRODUCT_ID' => $item['ID'],
										'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
										'BUTTON_CLASS' => 'btn btn-default '.$buttonSizeClass,
										'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);
							}
							?>
							<a class="btn btn-link <?=$buttonSizeClass?>" id="<?=$itemIds['NOT_AVAILABLE_MESS']?>" href="javascript:void(0)" rel="nofollow"
								style="display: <?=($actualItem['CAN_BUY'] ? 'none' : '')?>;">
								<?=$arParams['MESS_NOT_AVAILABLE']?>
							</a>
							<div id="<?=$itemIds['BASKET_ACTIONS']?>" class="basket_action_container" style="display: <?=($actualItem['CAN_BUY'] ? '' : 'none')?>;">
								<a class="buy buy_button_a show_basket_popup inline" id="<?=$itemIds['BUY_LINK']?>" href="javascript:void(0)" rel="nofollow"></a>
							</div>
						</div>
						<?
					}
					else
					{
						?>
						<div class="product-item-button-container">
							<a class="btn btn-default <?=$buttonSizeClass?>" href="<?=$item['DETAIL_PAGE_URL']?>">
								<?=$arParams['MESS_BTN_DETAIL']?>
							</a>
						</div>
						<?
					}
				}
				?>
			</div>
		</div>

		<div class="clear"></div>
	</div>

	<div class="list-info-buttons">
		<?php if (count($item["DISPLAY_PROPERTIES"]) > 0) {?><button class="list-props-button"><?= GetMessage("SF_CHARACTS")?><i class="arr icons_fa"></i></button><?php } ?>
		<?php if (count($item["OFFERS"]) > 0) { ?><button class="list-offer-button"><?= GetMessage("SF_SKU_SELECT")?><i class="arr icons_fa"></i></button><?php } ?>
	</div>

	<div class="product-params<?if((isset($actualItem['DISPLAY_PROPERTIES']) && !empty($actualItem['DISPLAY_PROPERTIES'])) || count($item['OFFERS']) > 0):?> top-indent<?endif;?>">

		<?
		if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $haveOffers && !empty($item['OFFERS_PROP']))
		{
			?>
			<div class="bx_catalog_item_scu offers_item" id="<?=$itemIds['PROP_DIV']?>">
				<?
				foreach ($arParams['SKU_PROPS'] as $skuProperty)
				{
					$propertyId = $skuProperty['ID'];
					$skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
					if (!isset($item['SKU_TREE_VALUES'][$propertyId]))
						continue;
					?>
					<div class="offer_item" data-entity="sku-block">
						<div class="<?=($skuProperty['SHOW_MODE'] === 'PICT') ? 'bx_item_detail_scu' : 'bx_item_detail_size'?>" data-entity="sku-line-block">
							<div class="offer_name"><?=$skuProperty['NAME']?></div>
							<div class="bx_scu_scroller_container">
								<div class="<?=($skuProperty['SHOW_MODE'] === 'PICT') ? 'bx_scu' : 'bx_size'?>">
									<ul class="product-item-scu-item-list">
										<?
										foreach ($skuProperty['VALUES'] as $value)
										{
											if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
												continue;

											$value['NAME'] = htmlspecialcharsbx($value['NAME']);

											if ($skuProperty['SHOW_MODE'] === 'PICT')
											{
												?>
												<li title="<?=$value['NAME']?>" data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
													<i title="<?=$value['NAME']?>"></i>
													<span class="cnt">
														<span class="cnt_item" title="<?=$value['NAME']?>" style="background-image: url(<?=$value['PICT']['SRC']?>);"></span>
													</span>
												</li>
												<?
											}
											else
											{
												?>
												<li title="<?=$value['NAME']?>" data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
													<span class="cnt"><?=$value['NAME']?></span>
												</li>
												<?
											}
										}
										?>
									</ul>
									<div style="clear: both;"></div>
								</div>
							</div>
						</div>
					</div>
					<?
				}
				?>
			</div>
			<?
			foreach ($arParams['SKU_PROPS'] as $skuProperty)
			{
				if (!isset($item['OFFERS_PROP'][$skuProperty['CODE']]))
					continue;

				$skuProps[] = array(
					'ID' => $skuProperty['ID'],
					'SHOW_MODE' => $skuProperty['SHOW_MODE'],
					'VALUES' => $skuProperty['VALUES'],
					'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
				);
			}

			unset($skuProperty, $value);

			if ($item['OFFERS_PROPS_DISPLAY'])
			{
				foreach ($item['JS_OFFERS'] as $keyOffer => $jsOffer)
				{
					$strProps = '';

					if (!empty($jsOffer['DISPLAY_PROPERTIES']))
					{
						foreach ($jsOffer['DISPLAY_PROPERTIES'] as $displayProperty)
						{
							$strProps .= '<p><span class="prop_name">'.$displayProperty['NAME'].'</span><span class="prop_value">'
								.(is_array($displayProperty['VALUE'])
									? implode(' / ', $displayProperty['VALUE'])
									: $displayProperty['VALUE'])
								.'</span></p>';
						}
					}

					$item['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
				}
				unset($jsOffer, $strProps);
			}
		}

		if (!$haveOffers)
		{
			if (!empty($item['DISPLAY_PROPERTIES']))
			{
				?>
				<div class="bx_catalog_item_articul item_props main_preview_props" data-entity="props-block">
					<?
					foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty)
					{
						?>
						<p>
							<span class="prop_name"><?=$displayProperty['NAME']?></span>
							<span class="prop_value">
									<?=(is_array($displayProperty['DISPLAY_VALUE']) ? implode(' / ', $displayProperty['DISPLAY_VALUE']) : $displayProperty['DISPLAY_VALUE'])?>
								</span>
						</p>
						<?
					}
					?>
				</div>
				<?
			}

			if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES']))
			{
				?>
				<div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
					<?
					if (!empty($item['PRODUCT_PROPERTIES_FILL']))
					{
						foreach ($item['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
						{
							?>
							<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]"
							       value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
							<?
							unset($item['PRODUCT_PROPERTIES'][$propID]);
						}
					}

					if (!empty($item['PRODUCT_PROPERTIES']))
					{
						?>
						<table>
							<?
							foreach ($item['PRODUCT_PROPERTIES'] as $propID => $propInfo)
							{
								?>
								<tr>
									<td class="prop-title"><?=$item['PROPERTIES'][$propID]['NAME']?></td>
									<td class="prop-value">
										<?
										if (
											$item['PROPERTIES'][$propID]['PROPERTY_TYPE'] === 'L'
											&& $item['PROPERTIES'][$propID]['LIST_TYPE'] === 'C'
										)
										{
											foreach ($propInfo['VALUES'] as $valueID => $value)
											{
												?>
												<label>
													<? $checked = $valueID === $propInfo['SELECTED'] ? 'checked' : ''; ?>
													<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]"
													       value="<?=$valueID?>" <?=$checked?>>
													<?=$value?>
												</label>
												<br />
												<?
											}
										}
										else
										{
											?>
											<select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]">
												<?
												foreach ($propInfo['VALUES'] as $valueID => $value)
												{
													$selected = $valueID === $propInfo['SELECTED'] ? 'selected' : '';
													?>
													<option value="<?=$valueID?>" <?=$selected?>>
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
		}
		else
		{
			$showProductProps = !empty($item['DISPLAY_PROPERTIES']);
			$showOfferProps = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $item['OFFERS_PROPS_DISPLAY'];

			if ($showProductProps || $showOfferProps)
			{
				?>
				<div class="bx_catalog_item_articul item_props main_preview_props" data-entity="props-block">
					<?
					if ($showProductProps)
					{
						foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty)
						{
							?>
							<p>
								<span class="prop_name"><?=$displayProperty['NAME']?></span>
								<span class="prop_value">
										<?=(is_array($displayProperty['DISPLAY_VALUE']) ? implode(' / ', $displayProperty['DISPLAY_VALUE']) : $displayProperty['DISPLAY_VALUE'])?>
									</span>
							</p>
							<?
						}
					}

					if ($showOfferProps)
					{
						?>
						<div id="<?=$itemIds['DISPLAY_PROP_DIV']?>" style="display: none;"></div>
						<?
					}
					?>
				</div>
				<?
			}
		}
		?>


	</div>

	<?
	if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
	{
		if ($haveOffers)
		{
			if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
			{
				?>
				<div class="product-item-info-container product-item-hidden" id="<?=$itemIds['QUANTITY_LIMIT']?>"
				     style="display: none;" data-entity="quantity-limit-block">
					<div class="product-item-info-container-title">
						<?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
						<span class="product-item-quantity" data-entity="quantity-limit-value"></span>
					</div>
				</div>
				<?
			}
		}
		else
		{
			if (
				$measureRatio
				&& (float)$actualItem['CATALOG_QUANTITY'] > 0
				&& $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
				&& $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
			)
			{
				?>
				<div class="product-item-info-container product-item-hidden" id="<?=$itemIds['QUANTITY_LIMIT']?>">
					<div class="product-item-info-container-title">
						<?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
						<span class="product-item-quantity">
							<?
							if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
							{
								if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
								{
									echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
								}
								else
								{
									echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
								}
							}
							else
							{
								echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
							}
							?>
						</span>
					</div>
				</div>
				<?
			}
		}
	}
	?>

	<?
	if (
		$arParams['DISPLAY_COMPARE']
		&& (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
	)
	{
		?>
		<div class="product-item-compare-container">
			<div class="product-item-compare">
				<div class="checkbox">
					<label id="<?=$itemIds['COMPARE_LINK']?>">
						<input type="checkbox" data-entity="compare-checkbox">
						<span data-entity="compare-title"><?=$arParams['MESS_BTN_COMPARE']?></span>
					</label>
				</div>
			</div>
		</div>
		<?
	}
	?>
</div>
