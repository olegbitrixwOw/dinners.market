<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
$this->addExternalCss($this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css');
CUtil::InitJSCore(array('fx'));
?>
<div class="bx-newsdetail">
	<div class="bx-newsdetail-block" id="<?echo $this->GetEditAreaId($arResult['ID'])?>">


		<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
			<h3 class="bx-newsdetail-title"><?=$arResult["NAME"]?></h3>
		<?endif;?>

		<?foreach($arResult["FIELDS"] as $code=>$value):?>

			<?//=$code?>
			<?if($code == "DATE_CREATE"):?>
				<div class="bx-newsdetail-author"><i class="fa fa-user"></i> <?=GetMessage("IBLOCK_FIELD_".$code)?>:
					<?=$value;?>
				</div>
			<?elseif($code == "CREATED_USER_NAME"):?>
				<div class="bx-newsdetail-author"><i class="fa fa-user"></i> <?=GetMessage("IBLOCK_FIELD_".$code)?>:
					<?=$value;?>
				</div>
			<?endif;?>
		<?endforeach;?>

		<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
			<div class="bx-newsdetail-date"><i class="fa fa-calendar-o"></i> <?echo $arResult["DISPLAY_ACTIVE_FROM"]?></div>
		<?endif?>
	
		<?if($arResult["DOCUMENTS"]):?>
			<p class="docunemts">Документы:</p>
			<?foreach($arResult["DOCUMENTS"] as $pid=>$file):?>

				<?if ($file['CONTENT_TYPE']== 'image/jpeg'):?>
					<div class="bx-newsdetail-date"><a href="<?=$file["SRC"]?>" target="_blank" ><?=$file["ORIGINAL_NAME"]?></a></div>
				<?elseif ($file['CONTENT_TYPE']== 'application/pdf'):?>
					<div class="bx-newsdetail-date"><a href="<?=$file["SRC"]?>" target="_blank" ><?=$file["ORIGINAL_NAME"]?></a></div>
				<?endif?>
			<?endforeach;?>
		<?endif?> 

		<?if($arParams['USER'] == 'admin'):?>
		<p><a href="/firm/documents/edit.php?edit=Y&CODE=<?=$arResult['ID'];?>">редактировать</a></p>
		<?endif?> 

		<?if($arResult["SECTION"]):?>
			<?foreach($arResult["SECTION"]["PATH"] as $key=>$url):?>
					<?if ($key == 0):?>
						<p><a href="<?='/firm/documents/'.$arResult['PARENT_SECTION']['CODE'].'/'?>">Возврат к списку документов организации</a></p>
					<?endif?>
			<?endforeach;?>
		<?endif?>
		
	</div>
</div>
<script type="text/javascript">
	BX.ready(function() {
		var slider = new JCNewsSlider('<?=CUtil::JSEscape($this->GetEditAreaId($arResult['ID']));?>', {
			imagesContainerClassName: 'bx-newsdetail-slider-container',
			leftArrowClassName: 'bx-newsdetail-slider-arrow-container-left',
			rightArrowClassName: 'bx-newsdetail-slider-arrow-container-right',
			controlContainerClassName: 'bx-newsdetail-slider-control'
		});
	});
</script>
