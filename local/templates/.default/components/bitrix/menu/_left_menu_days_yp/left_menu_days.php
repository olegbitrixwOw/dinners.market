<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<?php


?>
<? if (count($arResult) < 1)
{
	return;
}

$current_day=get_day_of_week();
?>
<ul class="uldepth_level_0"><?
	$previousLevel = 0;
	foreach ($arResult

	as $arItem)
	{
	if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel)
	{
		echo str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));
	}

	$link = $arItem["LINK"];
	$day=substr($link,1);
	$link=Yp::bx_current_link_with_get_parameter('day_of_week',$day);

	// wip

	$is_active=$day==$current_day;
	// $is_active=strpos($_SERVER["REQUEST_URI"], $arItem["LINK"])!==false;

	if ($arItem["IS_PARENT"])
	{
	?>
    <li class="depth_level_<?= $arItem["DEPTH_LEVEL"]; ?>

        <?

	if ($arItem["SELECTED"])
	{
		echo ' selected active';
	} ?>"><span class="mobile_menu_button"><i></i>
                <div class="divdeph_level_<?= $arItem["DEPTH_LEVEL"]; ?>"><a href="<?= $link; ?>"
                                                                             class="<? if ($is_active): ?>active_item <? endif; ?>depth_level_<?= $arItem["DEPTH_LEVEL"]; ?><? if ($arItem["SELECTED"])
																			 {
																				 echo ' selected';
																			 } ?>"><?= $arItem["TEXT"]; ?></a></div>
                <span class="icon span_depth_level_<?= $arItem["DEPTH_LEVEL"]; ?>"></span>
                </span>
        <ul class="uldepth_level_<?= $arItem["DEPTH_LEVEL"]; ?>"><?
			}
			else
			{
				?>
            <li class="depth_level_<?= $arItem["DEPTH_LEVEL"]; ?><? if ($arItem["SELECTED"])
			{
				echo ' selected active';
			} ?>">
                <div class="divdeph_level_<?= $arItem["DEPTH_LEVEL"]; ?> no_child"><a href="<?= $link; ?>"
                                                                                      class="<? if ($is_active): ?>active_item <? endif; ?>depth_level_<?= $arItem["DEPTH_LEVEL"]; ?><? if ($arItem["SELECTED"])
																					  {
																						  echo ' selected';
																					  } ?>"><?= $arItem["TEXT"]; ?></a>
                </div></li><?
			}
			$previousLevel = $arItem["DEPTH_LEVEL"];
			} ?>
			<? if ($previousLevel > 1)
			{
				echo str_repeat("</ul></li>", ($previousLevel - 1));
			} ?>
        </ul>
