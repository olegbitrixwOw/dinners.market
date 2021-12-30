<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

	<?if(strlen($arResult["ERROR_MESSAGE"])):?>
		<?=ShowError($arResult["ERROR_MESSAGE"]);?>
	<?else:?>	
	<?if($arParams["SHOW_ORDER_BASKET"]=='Y'):?>
		<!-- <h3><?//=GetMessage('SPOD_ORDER_BASKET')?></h3>  -->
		<table class="bx_order_list_table_order" style="width: 100%;">
		
			<tbody>
				<?//echo "<pre>".print_r($arParams['CUSTOM_SELECT_PROPS'], true).print_R($arResult["BASKET"], true)."</pre>"?>
				<?
				foreach($arResult["BASKET"] as $prod):
					?><tr><?
					
					$hasLink = !empty($prod["DETAIL_PAGE_URL"]);
					$actuallyHasProps = is_array($prod["PROPS"]) && !empty($prod["PROPS"]);
					
					foreach ($arParams["CUSTOM_SELECT_PROPS"] as $headerId):
						
						?><td class="custom"><?
						
						if($headerId == "NAME"):
							
							if($hasLink):
								?><a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank" style="font-size: 16px;"><?
							endif;
							?><?=$prod["NAME"]?><?
							if($hasLink):
								?></a><?
							endif;
							
						elseif($headerId == "PICTURE"):
							
							if($hasLink):
								?><a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank"><?
							endif;
							if($prod['PICTURE']['SRC']):
								?><img src="<?=$prod['PICTURE']['SRC']?>" width="<?=$prod['PICTURE']['WIDTH']?>" height="<?=$prod['PICTURE']['HEIGHT']?>" alt="<?=$prod['NAME']?>" /><?
							endif;
							if($hasLink):
								?></a><?
							endif;
							
						elseif($headerId == "PROPS" && $arResult['HAS_PROPS'] && $actuallyHasProps):
							
							?>
							<table cellspacing="0" class="bx_ol_sku_prop">
								<?foreach($prod["PROPS"] as $prop):?>
									<tr>
										<td><nobr><?=htmlspecialcharsbx($prop["NAME"])?>:</nobr></td>
										<td style="padding-left: 10px !important"><b><?=htmlspecialcharsbx($prop["VALUE"])?></b></td>
									</tr>
								<?endforeach?>
							</table>
							<?

						elseif($headerId == "QUANTITY"):
						
							?>
							<?=$prod["QUANTITY"]?>
							<?if(strlen($prod['MEASURE_TEXT'])):?>
								<?=$prod['MEASURE_TEXT']?>
							<?else:?>
								<?=GetMessage('SPOD_DEFAULT_MEASURE')?>
							<?endif?>
							<?
							
						else:
							$headerId = strtoupper($headerId);
							echo $prod[(strpos($headerId, 'PROPERTY_')===0 ? $headerId."_VALUE" : $headerId)];
						endif;
						
						?></td><?
						
					endforeach;
					
					?></tr><?
					
				endforeach;
				?>
			</tbody>
		</table>

		<?endif?>

		<?if($arParams["SHOW_ORDER_SUM"]=='Y'):?>
		<table class="bx_ordercart_order_sum">
			<tbody>

				<? ///// WEIGHT ?>
				<?if(floatval($arResult["ORDER_WEIGHT"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_TOTAL_WEIGHT')?>:</td>
						<td class="custom_t2"><?=$arResult['ORDER_WEIGHT_FORMATED']?></td>
					</tr>
				<?endif?>

				<? ///// PRICE SUM ?>
				<!-- <tr>
					<td class="custom_t1"><?=GetMessage('SPOD_PRODUCT_SUM')?>:</td>
					<td class="custom_t2"><?=$arResult['PRODUCT_SUM_FORMATED']?></td>
				</tr> -->

				<? ///// DELIVERY PRICE: print even equals 2 zero ?>
				<?if(strlen($arResult["PRICE_DELIVERY_FORMATED"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_DELIVERY')?>:</td>
						<td class="custom_t2"><?=$arResult["PRICE_DELIVERY_FORMATED"]?></td>
					</tr>
				<?endif?>

				<? ///// TAXES DETAIL ?>
				<?foreach($arResult["TAX_LIST"] as $tax):?>
					<!-- <tr>
						<td class="custom_t1"><?//=$tax["TAX_NAME"]?>:</td>
						<td class="custom_t2"><?//=$tax["VALUE_MONEY_FORMATED"]?></td>
					</tr>	 -->
				<?endforeach?>

				<? ///// TAX SUM ?>
				<?if(floatval($arResult["TAX_VALUE"])):?>
					<!-- <tr>
						<td class="custom_t1"><?//=GetMessage('SPOD_TAX')?>:</td>
						<td class="custom_t2"><?=$arResult["TAX_VALUE_FORMATED"]?></td>
					</tr> -->
				<?endif?>

				<? ///// DISCOUNT ?>
				<?if(floatval($arResult["DISCOUNT_VALUE"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_DISCOUNT')?>:</td>
						<td class="custom_t2"><?=$arResult["DISCOUNT_VALUE_FORMATED"]?></td>
					</tr>
				<?endif?>

				<tr>
					<td class="custom_t1 fwb"><?=GetMessage('SPOD_SUMMARY')?>:</td>
					<td class="custom_t2 fwb"><?=$arResult["PRICE_FORMATED"]?></td>
				</tr>
			</tbody>
		</table>
		<?endif?>

		<?if($arParams["SHOW_ORDER_BASE"]=='Y' || $arParams["SHOW_ORDER_USER"]=='Y' || $arParams["SHOW_ORDER_PARAMS"]=='Y' || $arParams["SHOW_ORDER_BUYER"]=='Y' || $arParams["SHOW_ORDER_DELIVERY"]=='Y' || $arParams["SHOW_ORDER_PAYMENT"]=='Y'):?>
		<table class="bx_order_list_table" style="margin:20px 0; width: 100%;">
			<thead>
				<tr>
					<td colspan="2">
						<?=GetMessage('SPOD_ORDER')?> <?=GetMessage('SPOD_NUM_SIGN')?><?=$arResult["ACCOUNT_NUMBER"]?>
						<?if(strlen($arResult["DATE_INSERT_FORMATED"])):?>
							<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_INSERT_FORMATED"]?>
						<?endif?>
					</td>
				</tr>
			</thead>
			<tbody>
			<?if($arParams["SHOW_ORDER_BASE"]=='Y'):?>
				<tr>
					<td>
						<?=GetMessage('SPOD_ORDER_STATUS')?>:
					</td>
					<td>
						<?=htmlspecialcharsbx($arResult["STATUS"]["NAME"])?>
						<?if(strlen($arResult["DATE_STATUS_FORMATED"])):?>
							(<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_STATUS_FORMATED"]?>)
						<?endif?>
					</td>
				</tr>
				<tr>
					<td>
						<?=GetMessage('SPOD_ORDER_PRICE')?>:
					</td>
					<td>
						<?=$arResult["PRICE_FORMATED"]?>
						<?if(floatval($arResult["SUM_PAID"])):?>
							(<?=GetMessage('SPOD_ALREADY_PAID')?>:&nbsp;<?=$arResult["SUM_PAID_FORMATED"]?>)
						<?endif?>
					</td>
				</tr>
				<?
				if (!empty($arResult["SUM_REST"]))
				{
					?>
					<tr>
						<td>
							<?=GetMessage('SPOD_ORDER_SUM_REST')?>:
						</td>
						<td>
							<?=$arResult["SUM_REST_FORMATED"]?>
						</td>
					</tr>
					<?
				}
				?>
				

			<?endif?>


			<?if($arParams["SHOW_ORDER_PARAMS"]=='Y'):?>
				<tr>
					<td colspan="2"><?=GetMessage('SPOD_ORDER_PROPERTIES')?></td>
				</tr>
				<tr>
					<td><?=GetMessage('SPOD_ORDER_PERS_TYPE')?>:</td>
					<td><?=htmlspecialcharsbx($arResult["PERSON_TYPE"]["NAME"])?></td>
				</tr>
			<?endif?>
			
			<?if($arParams["SHOW_ORDER_BUYER"]=='Y'):?>
				<?//foreach($arResult["ORDER_PROPS"] as $prop):?>

					
				<?//endforeach?>
			<?endif?>

			<?if($arParams["SHOW_ORDER_PAYMENT"]=='Y'):?>
				<tr>
					<td colspan="2"><?=GetMessage("SPOD_ORDER_PAYMENT")?></td>
				</tr>
				<tr><td><br></td><td></td></tr>
				<?
				foreach ($arResult["PAYMENT"] as $payment)
				{
					$titleParams = [
						"#ACCOUNT_NUMBER#" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
						"#DATE_BILL#" => $payment['DATE_BILL_FORMATTED'],
					];
					?>
					<tr>
						<td colspan="2"><?=GetMessage("SPOD_ORDER_PAYMENT_TITLE", $titleParams)?></td>
					</tr>
					<tr>
						<td><?=GetMessage('SPOD_PAY_SYSTEM')?>:</td>
						<td>
							<?if(intval($payment["PAY_SYSTEM_ID"])):?>
								<?=htmlspecialcharsbx($payment["PAY_SYSTEM_NAME"])?>
							<?else:?>
								<?=GetMessage("SPOD_NONE")?>
							<?endif?>
						</td>
					</tr>
					<tr>
						<td><?=GetMessage('SPOD_ORDER_PAYED')?>:</td>
						<td>
							<?if($payment["PAID"] == "Y"):?>
								<?=GetMessage('SPOD_YES')?>
								<?if(strlen($payment["DATE_PAID_FORMATTED"])):?>
									(<?=GetMessage('SPOD_FROM')?> <?=$payment["DATE_PAID_FORMATTED"]?>)
								<?endif?>
							<?else:?>
								<?=GetMessage('SPOD_NO')?>
								<?if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
									&nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("SPOD_REPEAT_PAY")?></a>]
								<?endif?>
							<?endif?>
						</td>
					</tr>
					<tr><td><br></td><td></td></tr>
					<?
				}
				?>

				<?// $arResult["SHIPMENT"] // удалил строки 200-270?>

			<?endif?>
			</tbody>
		</table>
			
			<?//if($arParams["SHOW_ORDER_BASKET"]=='Y'):?>
			<!-- 	<h3><?//=GetMessage('SPOD_ORDER_BASKET')?></h3>  -->
			<?//endif?>
		<?endif?>	
		
		
	<?endif?>
