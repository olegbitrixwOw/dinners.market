<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<?
if (!empty($arResult)):?>
	<ul>
	<?
	$previousLevel = 0;
	foreach($arResult as $arItem):?>
		<?
		if($arParams['CUISINE']){
			if (strpos($arItem['LINK'], 'news')){			
				$arItem['LINK'] = $arItem['LINK'].$arParams['CUISINE'].'/';		
			}

			if (strpos($arItem['LINK'], 'contacts')){			
				$arItem['LINK'] = $arItem['LINK'].'?cuisine='.$arParams['CUISINE'];		
			}
		}else{
			if (strpos($arItem['LINK'], 'news')){			
				$arItem['LINK'] = $arItem['LINK'].'shop-news/';		
			}
		}	
		?>
		<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
			<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
		<?endif?>
		<?if ($arItem["IS_PARENT"]):?>
			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li class="inline">
					<div class="dropdown__menu-title">
						<a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"];?>"><?=$arItem["TEXT"]?></a>
						<span class="dropdown__menu-symbol"></span>
					</div>
					<ul class="dropdown__menu-list">
			<?else:?>
				<li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><a href="<?=$arItem["LINK"]?>" class="parent"><?=$arItem["TEXT"]?></a>
				<ul>
			<?endif?>
		<?else:?>
			<?if($arItem["PERMISSION"] > "D"):?>
				<?if ($arItem["DEPTH_LEVEL"] == 1):?>
					<li class="inline">
						<a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>"><?=$arItem["TEXT"]?></a>
					</li>
				<?else:?>
					<li class="dropdown__menu-item"><a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"];?>"><?=$arItem["TEXT"]?></a></li>
				<?endif?>
			<?else:?>
				<?if ($arItem["DEPTH_LEVEL"] == 1):?>
					<li><a href="" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
				<?else:?>
					<li><a href="" class="denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
				<?endif?>
			<?endif?>
		<?endif?>
		<?$previousLevel = $arItem["DEPTH_LEVEL"];?>
	<?endforeach?>

	<?if ($previousLevel > 1)://close last item tags?>
		<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
	<?endif?>

	</ul>
<?endif?>
<?//die();?>