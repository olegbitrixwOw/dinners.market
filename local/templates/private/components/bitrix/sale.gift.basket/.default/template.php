<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$frame = $this->createFrame()->begin();

if (!empty($arResult['ITEMS']))
{
	$templateData = array(
		'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
		'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
	);

	$arSkuTemplate = array();
	if(is_array($arResult['SKU_PROPS']))
	{
		foreach ($arResult['SKU_PROPS'] as $iblockId => $skuProps)
		{
			$arSkuTemplate[$iblockId] = array();
			foreach ($skuProps as &$arProp)
			{
				ob_start();
				if ('TEXT' == $arProp['SHOW_MODE'])
				{
					if (5 < $arProp['VALUES_COUNT'])
					{
						$strClass = 'bx_item_detail_size full';
						$strWidth = ($arProp['VALUES_COUNT'] * 20) . '%';
						$strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
						$strSlideStyle = '';
					}
					else
					{
						$strClass = 'bx_item_detail_size';
						$strWidth = '100%';
						$strOneWidth = '20%';
						$strSlideStyle = 'display: none;';
					}
					?>
					<div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont">
						<div class="offer_name"><? echo htmlspecialcharsex($arProp['NAME']); ?></div>

						<div class="bx_size_scroller_container">
							<div class="bx_size">
								<ul class="product-item-scu-item-list" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;"><?
									foreach ($arProp['VALUES'] as $arOneValue)
									{
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
							<div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
							<div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
						</div>
					</div><?
				}
				elseif ('PICT' == $arProp['SHOW_MODE'])
				{
					if (5 < $arProp['VALUES_COUNT'])
					{
						$strClass = 'bx_item_detail_scu full';
						$strWidth = ($arProp['VALUES_COUNT'] * 20) . '%';
						$strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
						$strSlideStyle = '';
					}
					else
					{
						$strClass = 'bx_item_detail_scu';
						$strWidth = '100%';
						$strOneWidth = '20%';
						$strSlideStyle = 'display: none;';
					}
					?>
					<div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont">
						<span class="offer_name"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>

						<div class="bx_scu_scroller_container">
							<div class="bx_scu">
								<ul class="product-item-scu-item-list" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;"><?
									foreach ($arProp['VALUES'] as $arOneValue)
									{
										?>
										<li
											data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID'] ?>"
											data-onevalue="<? echo $arOneValue['ID']; ?>"
											style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>;">
											<span class="cnt">
												<i title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
												<span class="cnt_item"
														style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
														title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>">

												</span>
											</span>
										</li><?
									}
									?></ul>
							</div>
							<div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
							<div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
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
			CVP_MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CVP_TPL_MESS_BTN_BUY_GIFT')); ?>',
			CVP_MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CVP_TPL_MESS_BTN_ADD_TO_BASKET')); ?>',

			CVP_MESS_BTN_DETAIL: '<? echo ('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CVP_TPL_MESS_BTN_DETAIL')); ?>',

			CVP_MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CVP_TPL_MESS_BTN_DETAIL')); ?>',
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
	<div class="bx_item_list_you_looked_horizontal col<? echo $arParams['LINE_ELEMENT_COUNT']; ?> <? echo $templateData['TEMPLATE_CLASS']; ?>">
		<? if(empty($arParams['HIDE_BLOCK_TITLE']) || $arParams['HIDE_BLOCK_TITLE'] !== 'Y'){ ?><div class="bx_item_list_title"><? echo ($arParams['BLOCK_TITLE']? htmlspecialcharsbx($arParams['BLOCK_TITLE']) : GetMessage('SGB_TPL_BLOCK_TITLE_DEFAULT')) ?></div><? } ?>
		<div class="catalog_section">
			<div class="section clearfix bx_catalog_list_home bx_blue">
				<?
				$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
				$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
				$elementDeleteParams = array('CONFIRM' => GetMessage('CVP_TPL_ELEMENT_DELETE_CONFIRM'));
				foreach ($arResult['ITEMS'] as $key => $arItem)
				{
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

					?>
					<div class="item_element good_box bx_catalog_item" id="<? echo $strMainID; ?>">
						<div class="char-pop-up bx_catalog_item_container">
							<div class="img_box">
								<a id="<? echo $arItemIDs['PICT']; ?>" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="bx_catalog_item_images product-item-image-wrapper"<? if ($arParams['SHOW_IMAGE'] == "Y")
									{
										?> style="background-image: url('<? echo($arParams['SHOW_IMAGE'] == "Y" ? $arItem['PREVIEW_PICTURE']['SRC'] : ""); ?>')"<?
									} ?> title="<? echo $strTitle; ?>"><?
									if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
									{
										?>
										<div id="<? echo $arItemIDs['DSC_PERC']; ?>" class="bx_stick_disc right bottom" style="display:<? echo(0 < $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">
											-<? echo $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>%
										</div>
									<?
									}
									if ($arItem['LABEL'])
									{
										?><div class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>"></div><?
									}
									?>
								</a>
								<?
								if ($arItem['SECOND_PICT'])
								{
									?><a id="<? echo $arItemIDs['SECOND_PICT']; ?>" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="bx_catalog_item_images_double"<? if ($arParams['SHOW_IMAGE'] == "Y")
								{
									?> style="background-image: url('<? echo(
										!empty($arItem['PREVIEW_PICTURE_SECOND'])
										? $arItem['PREVIEW_PICTURE_SECOND']['SRC']
										: $arItem['PREVIEW_PICTURE']['SRC']
									); ?>')"<?
								} ?> title="<? echo $strTitle; ?>"><?
									if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
									{
										?>
										<div id="<? echo $arItemIDs['SECOND_DSC_PERC']; ?>" class="bx_stick_disc right bottom" style="display:<? echo(0 < $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">
											-<? echo $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>%
										</div>
									<?
									}
									if ($arItem['LABEL'])
									{
										?><div class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>"></div><?
									}
									?>
									</a><?
								}
								?>

								<div class="hover_over">
									<a href="javascript:;" data-width="810" data-fancybox="group" data-src="<?=$arItem["DETAIL_PAGE_URL"];?>?open_popup=Y" class="open_fancybox" rel="gallery">
										<?=GetMessage("CT_BCI_TPL_MESS_FAST_VIEW");?>
									</a>
								</div>
							</div>

							<div class="item-info">
								<? if ($arParams['SHOW_NAME'] == "Y")
								{
									?>
									<div class="bx_catalog_item_title name">
										<a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" title="<? echo $arItem['NAME']; ?>"><? echo $arItem['NAME']; ?></a>
									</div>
									<?
								}?>

								<?
								if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS'])) // Simple Product
								{
									?>

									<div class="control-container">
										<div class="bx_catalog_item_price">
											<?if (!empty($arItem['MIN_PRICE'])):?>
												<div class="item_price">
													<?
													if ('Y' == $arParams['SHOW_OLD_PRICE'] && $arItem['MIN_PRICE']['DISCOUNT_VALUE'] < $arItem['MIN_PRICE']['VALUE'])
													{
														?>
														<div class="old_price"><?=$arItem['MIN_PRICE']['PRINT_VALUE'];?></div>
														<?
													}
													?>
													<div id="<? echo $arItemIDs['PRICE']; ?>" class="price_box__actual-price">
														<?=$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'];?>
													</div>
												</div>
											<?endif;?>
										</div>

										<div class="bx_catalog_item_controls align-items-center"><?
											if ($arItem['CAN_BUY'])
											{
												if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
												{
													?>
													<div class="bx_catalog_item_controls_blockone item_quantity inline">
														<a id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" href="javascript:void(0)" class="minus" rel="nofollow">-</a>
														<input type="text" class="bx_col_input" id="<? echo $arItemIDs['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
														<a id="<? echo $arItemIDs['QUANTITY_UP']; ?>" href="javascript:void(0)" class="plus" rel="nofollow">+</a>
														<span class="product-item-amount-description-container"> id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"<? echo $arItem['CATALOG_MEASURE_NAME']; ?></span>
													</div>
												<?
												}
												?>
												<div class="buy_box">
													<a id="<? echo $arItemIDs['BUY_LINK']; ?>" class="buy" href="javascript:void(0)" rel="nofollow"></a>
												</div>
											<?
											}
											else
											{
												?>
												<div class="buy_box">
													<a class="bx_read_more_link"  href="<?=$arItem['DETAIL_PAGE_URL'];?>" rel="nofollow">
														<?	echo('' != $arParams['MESS_BTN_DETAIL'] ? $arParams['MESS_BTN_DETAIL'] : GetMessage('CVP_TPL_MESS_BTN_DETAIL')); ?>
													</a>
												</div><?
												if ('Y' == $arParams['PRODUCT_SUBSCRIPTION'] && 'Y' == $arItem['CATALOG_SUBSCRIPTION'])
												{
													?>
													<div class="bx_catalog_item_controls_blocktwo">
													<a id="<? echo $arItemIDs['SUBSCRIBE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><?
														echo('' != $arParams['MESS_BTN_SUBSCRIBE'] ? $arParams['MESS_BTN_SUBSCRIBE'] : GetMessage('CVP_TPL_MESS_BTN_SUBSCRIBE'));
														?>
													</a>
													</div><?
												}
											}
											?>
											<div style="clear: both;"></div>
										</div>
									</div>
									<?
									if (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']))
									{
									?>
										<div class="product-params top-indent">
											<div class="bx_catalog_item_articul item_props main_preview_props">
												<?
												foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
												{
													?>
													<span class="prop_name"><?=$arOneProp['NAME'];?></span>
													<span class="prop_value">
														<?=(is_array($arOneProp['DISPLAY_VALUE']) ? implode('/', $arOneProp['DISPLAY_VALUE']) : $arOneProp['DISPLAY_VALUE']);?>
													</span>
													<?
												}
												?>
											</div>
										</div>
									<?
									}


									$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
									if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
									{
									?>
										<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
											<?
											if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
											{
												foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
												{
													?>
													<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
													<?
													if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
														unset($arItem['PRODUCT_PROPERTIES'][$propID]);
												}
											}
											$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);

											if (!$emptyProductProperties)
											{

												?>
												<table>
													<?
													foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo)
													{
														?>
														<tr>
															<td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
															<td>
																<?
																if (
																	'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
																	&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
																)
																{
																	foreach ($propInfo['VALUES'] as $valueID => $value)
																	{
																		?><label><input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?></label><br><?
																	}
																}
																else
																{
																	?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
																	foreach ($propInfo['VALUES'] as $valueID => $value)
																	{
																		?>
																		<option value="<? echo $valueID; ?>" <? echo($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option><?
																	}
																	?></select><?
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
									$arJSParams = array(
										'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
										'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
										'SHOW_ADD_BASKET_BTN' => false,
										'SHOW_BUY_BTN' => true,
										'SHOW_ABSENT' => true,
										'PRODUCT' => array(
											'ID' => $arItem['ID'],
											'NAME' => $arItem['~NAME'],
											'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
											'CAN_BUY' => $arItem["CAN_BUY"],
											'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
											'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
											'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
											'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
											'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
											'ADD_URL' => $arItem['~ADD_URL'],
											'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL']
										),
										'BASKET' => array(
											'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
											'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
											'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
											'EMPTY_PROPS' => $emptyProductProperties
										),
										'VISUAL' => array(
											'ID' => $arItemIDs['ID'],
											'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT']),
											'QUANTITY_ID' => $arItemIDs['QUANTITY'],
											'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
											'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
											'PRICE_ID' => $arItemIDs['PRICE'],
											'BUY_ID' => $arItemIDs['BUY_LINK'],
											'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV']
										),
										'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
									);
									?>
									<script type="text/javascript">
										var <? echo $strObName; ?> = new JCSaleGiftBasket(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
									</script><?
								}
								else // Wth Sku
								{
								?>
									<div class="control-container">
										<div class="bx_catalog_item_price">
											<div class="item_price">
												<?
												if ('Y' == $arParams['SHOW_OLD_PRICE'] && $arItem['MIN_PRICE']['DISCOUNT_VALUE'] < $arItem['MIN_PRICE']['VALUE'] && !empty($arItem['MIN_PRICE']))
												{
													?>
													<div class="old_price"><?=$arItem['MIN_PRICE']['PRINT_VALUE'];?></div>
													<?
												}
												?>

												<div id="<? echo $arItemIDs['PRICE']; ?>" class="price_box__actual-price">
													<?
													if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS']) && !empty($arItem['MIN_PRICE']))
													{
														echo GetMessage(
															'CVP_TPL_MESS_PRICE_SIMPLE_MODE',
															array(
																'#PRICE#' => $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'],
																'#MEASURE#' => GetMessage(
																	'CVP_TPL_MESS_MEASURE_SIMPLE_MODE',
																	array(
																		'#VALUE#' => $arItem['MIN_PRICE']['CATALOG_MEASURE_RATIO'],
																		'#UNIT#' => $arItem['MIN_PRICE']['CATALOG_MEASURE_NAME']
																	)
																)
															)
														);
													}
													?>
												</div>
											</div>
										</div>


										<div class="bx_catalog_item_controls align-items-center">
											<?
											if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
											{
												?>
												<div class="bx_catalog_item_controls_blockone item_quantity inline">
													<a id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" href="javascript:void(0)" class="minus" rel="nofollow">-</a>
													<input type="text" id="<? echo $arItemIDs['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
													<a id="<? echo $arItemIDs['QUANTITY_UP']; ?>" href="javascript:void(0)" class="plus" rel="nofollow">+</a>
													<div class="product-item-amount-description-container" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"></div>
												</div>
												<?
											}
											?>
											<div class="buy_box">
												<a id="<? echo $arItemIDs['BUY_LINK']; ?>" class="buy" href="javascript:void(0)" rel="nofollow"></a>
											</div>
											<div style="clear: both;"></div>
										</div>
									</div>
									<div class="product-params top-indent">
										<?
										$boolShowOfferProps =  !!$arItem['OFFERS_PROPS_DISPLAY'];
										$boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));

										if (!empty($arItem['OFFERS']) && isset($arSkuTemplate[$arItem['IBLOCK_ID']]))
										{
											$arSkuProps = array();
											?>
											<div class="bx_catalog_item_scu offers_item" id="<? echo $arItemIDs['PROP_DIV']; ?>"><?
												foreach ($arSkuTemplate[$arItem['IBLOCK_ID']] as $code => $strTemplate)
												{
													if (!isset($arItem['OFFERS_PROP'][$code]))
														continue;
													echo '<div class="offer_item">', str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate), '</div>';
												}

												if (isset($arResult['SKU_PROPS'][$arItem['IBLOCK_ID']]))
												{
													foreach ($arResult['SKU_PROPS'][$arItem['IBLOCK_ID']] as $arOneProp)
													{
														if (!isset($arItem['OFFERS_PROP'][$arOneProp['CODE']]))
															continue;
														$arSkuProps[] = array(
															'ID' => $arOneProp['ID'],
															'SHOW_MODE' => $arOneProp['SHOW_MODE'],
															'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
														);
													}
												}
												foreach ($arItem['JS_OFFERS'] as &$arOneJs)
												{
													if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'])
														$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = '-' . $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] . '%';
												}

												?>
											</div>
											<?
											if ($arItem['OFFERS_PROPS_DISPLAY'])
											{
												foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer)
												{
													$strProps = '';
													if (!empty($arJSOffer['DISPLAY_PROPERTIES']))
													{
														foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp)
														{
															$strProps .= '<br>' . $arOneProp['NAME'] . ' <strong>' . (
																is_array($arOneProp['VALUE'])
																	? implode(' / ', $arOneProp['VALUE'])
																	: $arOneProp['VALUE']
																) . '</strong>';
														}
													}
													$arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
												}
											}
											$arJSParams = array(
												'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
												'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
												'SHOW_ADD_BASKET_BTN' => false,
												'SHOW_BUY_BTN' => true,
												'SHOW_ABSENT' => true,
												'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
												'SECOND_PICT' => ($arParams['SHOW_IMAGE'] == "Y" ? $arItem['SECOND_PICT'] : false),
												'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
												'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
												'DEFAULT_PICTURE' => array(
													'PICTURE' => $arItem['PRODUCT_PREVIEW'],
													'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
												),
												'VISUAL' => array(
													'ID' => $arItemIDs['ID'],
													'PICT_ID' => $arItemIDs['PICT'],
													'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
													'QUANTITY_ID' => $arItemIDs['QUANTITY'],
													'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
													'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
													'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
													'PRICE_ID' => $arItemIDs['PRICE'],
													'TREE_ID' => $arItemIDs['PROP_DIV'],
													'TREE_ITEM_ID' => $arItemIDs['PROP'],
													'BUY_ID' => $arItemIDs['BUY_LINK'],
													'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
													'DSC_PERC' => $arItemIDs['DSC_PERC'],
													'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
													'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
												),
												'BASKET' => array(
													'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
													'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE']
												),
												'PRODUCT' => array(
													'ID' => $arItem['ID'],
													'NAME' => $arItem['~NAME']
												),
												'OFFERS' => $arItem['JS_OFFERS'],
												'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
												'TREE_PROPS' => $arSkuProps,
												'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
											);
											?>
												<script type="text/javascript">
													var <? echo $strObName; ?> = new JCSaleGiftBasket(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
												</script>
											<?
										}

										if ($boolShowProductProps || $boolShowOfferProps)
										{
										?>
											<div class="bx_catalog_item_articul">
												<?
												if ($boolShowProductProps)
												{
													foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
													{
														?><br><? echo $arOneProp['NAME']; ?><strong> <?
														echo(
														is_array($arOneProp['DISPLAY_VALUE'])
															? implode(' / ', $arOneProp['DISPLAY_VALUE'])
															: $arOneProp['DISPLAY_VALUE']
														); ?></strong><?
													}
												}

												?>
												<span id="<? echo $arItemIDs['DISPLAY_PROP_DIV']; ?>" style="display: none;"></span>
											</div>
										<?
										}
										?>
									</div>
								<?
								}
								?>
							</div>
						</div>
					</div>
				<?
				}
				unset($elementDeleteParams, $elementDelete, $elementEdit);
				?>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
<?
}
?>
<?$frame->beginStub();?>
<?$frame->end();?>