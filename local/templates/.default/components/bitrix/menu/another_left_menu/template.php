<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (count($arResult) < 1) { return; } ?>
<ul class="uldepth_level_0"><?
	$previousLevel = 0;
	foreach($arResult as $arItem):
		if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):
			echo str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));
		endif;
		if ($arItem["IS_PARENT"]):
			?><li class="depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected active'; } ?> no-list-type"><span><a href="<?=$arItem["LINK"];?>" class="depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected'; } ?>"><?=$arItem["TEXT"];?><?if(!empty($arItem["PARAMS"]["picture_src"])) {?><span class="menu-icon" style="background-image: url(<?=$arItem["PARAMS"]["picture_src"]?>)"></span><?}?></a><span class="icon span_depth_level_<?=$arItem["DEPTH_LEVEL"];?>"></span></span>
				<ul class="uldepth_level_<?=$arItem["DEPTH_LEVEL"];?>"><?
		else:
			?><li class="depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected active'; } ?> no-list-type"><a href="<?=$arItem["LINK"];?>" class="depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected'; } ?>"><?=$arItem["TEXT"];?><?if(!empty($arItem["PARAMS"]["picture_src"])) {?><span class="menu-icon" style="background-image: url(<?=$arItem["PARAMS"]["picture_src"]?>)"></span><?}?></a></li><?
		endif;
		$previousLevel = $arItem["DEPTH_LEVEL"];
	endforeach;?>
	<? if ($previousLevel > 1) {
		echo str_repeat("</ul></li>", ($previousLevel-1) );
	} ?>
</ul>