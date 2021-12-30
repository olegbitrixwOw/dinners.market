<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if(!empty($arResult["STORES"]) && $arParams["MAIN_TITLE"] != ''):?>
	<h4><?=$arParams["MAIN_TITLE"]?></h4>
<?endif;?>
<div class="storage_items row" id="catalog_store_amount_div">
	<?if(!empty($arResult["STORES"])):?>
		<?foreach($arResult["STORES"] as $pid => $arProperty):?>
			<div class="storage_item col-md-6" style="display: <? echo ($arParams['SHOW_EMPTY_STORE'] == 'N' && isset($arProperty['REAL_AMOUNT']) && $arProperty['REAL_AMOUNT'] <= 0 ? 'none' : ''); ?>;">
				<?if (isset($arProperty["TITLE"])):?>
					<a href="<?=$arProperty["URL"]?>"><?=$arProperty["TITLE"]?></a>
				<?endif;?>
				<?if (isset($arProperty["IMAGE_ID"]) && !empty($arProperty["IMAGE_ID"])):?>
					<span class="schedule"><strong><?=GetMessage('S_IMAGE')?></strong> <?=CFile::ShowImage($arProperty["IMAGE_ID"], 200, 200, "border=0", "", true);?></span>
				<?endif;?>
				<?if (isset($arProperty["PHONE"])):?>
					<span class="tel"><strong><?=GetMessage('S_PHONE')?></strong> <?=$arProperty["PHONE"]?></span>
				<?endif;?>
				<?if (isset($arProperty["SCHEDULE"])):?>
					<span class="schedule"><strong><?=GetMessage('S_SCHEDULE')?></strong> <?=$arProperty["SCHEDULE"]?></span>
				<?endif;?>
				<?if (isset($arProperty["EMAIL"])):?>
					<span><strong><?=GetMessage('S_EMAIL')?></strong> <?=$arProperty["EMAIL"]?></span>
				<?endif;?>
				<?if (isset($arProperty["DESCRIPTION"])):?>
					<span><strong><?=GetMessage('S_DESCRIPTION')?></strong> <?=$arProperty["DESCRIPTION"]?></span>
				<?endif;?>
				<?if (isset($arProperty["COORDINATES"])):?>
					<span><strong><?=GetMessage('S_COORDINATES')?></strong> <?=$arProperty["COORDINATES"]["GPS_N"]?>, <?=$arProperty["COORDINATES"]["GPS_S"]?></span>
				<?endif;?>
				<span>
					<strong>
						<?if ($arParams['SHOW_GENERAL_STORE_INFORMATION'] == "Y") :?>
							<?=GetMessage('BALANCE')?>:
						<?else:?>
							<?=GetMessage('S_AMOUNT')?>
						<?endif;?>
					</strong>
					<i class="balance" id="<?=$arResult['JS']['ID']?>_<?=$arProperty['ID']?>"><?=$arProperty["AMOUNT"]?></i>
				</span>
				<?
				if (!empty($arProperty['USER_FIELDS']) && is_array($arProperty['USER_FIELDS']))
				{
					foreach ($arProperty['USER_FIELDS'] as $userField)
					{
						if (isset($userField['CONTENT']))
						{
							?><span><strong><?=$userField['TITLE']?></strong>: <?=$userField['CONTENT']?></span><?
						}
					}
				}
				?>
			</div>
		<?endforeach;?>
	<?endif;?>
</div>
<?if (isset($arResult["IS_SKU"]) && $arResult["IS_SKU"] == 1):?>
	<script type="text/javascript">
		var obStoreAmount = new JCCatalogStoreSKU(<? echo CUtil::PhpToJSObject($arResult['JS'], false, true, true); ?>);
	</script>
	<?
endif;?>